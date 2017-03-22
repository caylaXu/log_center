<?php
namespace LogSystem;

use Exception;
use LogSystem\Queue\QueueInterface;

/**
 * Class QueueServer
 * @author Leo Yang <leoyang@motouch.cn>
 */
class QueueServer
{

    /**
     * @var string
     */
    private $ip;

    /**
     * @var int
     */
    private $port;

    /**
     * @var QueueInterface
     */
    private $queue;

    /**
     * @param string $ip
     * @param int $port
     * @param QueueInterface $queue
     */
    public function __construct($ip, $port, QueueInterface $queue)
    {
        $this->ip    = $ip;
        $this->port  = $port;
        $this->queue = $queue;
    }

    /**
     * @param bool $debug
     * @throws Exception
     */
    public function listen($debug = false)
    {
        // 创建udp socket
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if ($socket == false) {
            throw new Exception(socket_strerror(socket_last_error($socket)));
        }

        echo "创建socket成功", "\n";

        // 绑定端口
        $bind = socket_bind($socket, $this->ip, $this->port);
        if ($bind == false) {
            throw new Exception(socket_strerror(socket_last_error($socket)));
        }

        echo "绑定端口成功,开始监听", "\n";

        while (true) {
            //$message = socket_read($socket, 8196);
            $size = socket_recvfrom($socket, $message, 8196, 0, $ip_address, $port);
            if ($debug) {
                echo "Received [$message] ($size bytes) from IP $ip_address Port $port\n";
            }
            $message = json_decode($message, true);
            if (!$message) continue;
            $message['SrcIp'] = $ip_address;
            $message = json_encode($message);
            $this->send_log($message);
            $res = $this->queue->push($message);
            if ($debug) {
                var_dump($res);
                echo $message , "\n";
            }
        }
        socket_close($socket);
    }

    /**
     * @function 接收记录
     * @User: CaylaXu
     * @param $res
     */
    public function send_log($res)
    {
        $file_name = date("Y-m-d") . '_received_log.txt';
        $path = '../logs/' . $file_name;
        //失败数据写入文件
        $handle = fopen($path, "a+");
        if (is_array($res))
        {
            $log = json_encode($res);
            $log = date("Y-m-d H:i:s").$log;
        }
        else
        {
            $log = date("Y-m-d H:i:s").$res;
        }
        fwrite($handle, $log . PHP_EOL);
        fclose($handle);
    }
}