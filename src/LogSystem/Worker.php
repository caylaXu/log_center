<?php

namespace LogSystem;

use LogSystem\Notify\NotifyInterface;
use LogSystem\Queue\QueueInterface;
use LogSystem\Storage\StorageInterface;

/**
 * Class Worker
 * @author Leo Yang <leoyang@motouch.cn>
 */
class Worker
{

    /**
     * @var QueueInterface
     */
    private $queue;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var NotifyInterface[]
     */
    private $notifys;

    /**
     * @var int
     */
    private $notifyLevel = LogSystem::NOTICE;

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @param QueueInterface   $queue
     * @param StorageInterface $storage
     * @param array            $notifys
     */
    public function __construct(QueueInterface $queue, StorageInterface $storage, array $notifys = array())
    {
        $this->queue   = $queue;
        $this->storage = $storage;
        $this->notifys = $notifys;
        ksort($this->notifys);
    }


    public function run($debug = false)
    {
        $this->debug = $debug;
        while (true) {
            $message = $this->queue->pop();
            if ($this->debug) {
                echo $message, "\n";
            }
            $this->handle($message);
        }
    }


    public function handle($message)
    {
        $log = json_decode($message, true);

        if (empty($log)) {
            return;
        }

        if (
            !isset($log['SystemName']) ||
            !isset($log['Time']) ||
            !isset($log['Level']) ||
            !isset($log['Type']) ||
            !isset($log['Message']) ||
            !isset($log['Context'])
        ) {
            return;
        }

        $system_id = LogSystem::getSystemIdByName($log['SystemName']);

        //无法识别的系统
        if ($system_id == false) {
            return;
        }

        $types = LogSystem::getTypes();
        $types_upper = strtoupper($log['Type']);
        if (isset($types[$types_upper])) {
            $type = $types[$types_upper];
        } else {
            $type = is_numeric($log['Type']) ? $log['Type'] : 0;
        }

        $this->storage->put($system_id, $log['Time'], $log['Level'], $type, $log['Message'], $log['Context']);

        //IP过滤
        $ip_pass = array('117.136.81.156','117.136.81.221','117.136.81.184','218.58.192.2','183.38.129.138','113.110.156.10','183.38.134.183','119.139.189.78','119.136.47.38');
        $src_ip = isset($log['SrcIp']) ? $log['SrcIp'] : '';
        if ($src_ip && in_array($src_ip, $ip_pass)) {
            return;
        }

        foreach ($this->notifys as $level => $notify) {
            if ($log['Level'] >= $level) {
                $log['Context'] = print_r($log['Context'], true);
                $notify->send($log, $system_id);
            }
        }
    }


    /**
     * @param $notify
     */
    public function addNotify(NotifyInterface $notify)
    {
        $this->notifys[] = $notify;
        ksort($this->notifys);
    }

    /**
     * @param $log
     * @param $system_id
     */
    public function sendNotify($log, $system_id)
    {
        foreach ($this->notifys as $notify) {
            $notify->send($log, $system_id);
        }
    }

    /**
     * @return int
     */
    public function getNotifyLevel()
    {
        return $this->notifyLevel;
    }

    /**
     * @param int $notifyLevel
     */
    public function setNotifyLevel($notifyLevel)
    {
        $this->notifyLevel = $notifyLevel;

    }

    /**
     * @return boolean
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @param boolean $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

}