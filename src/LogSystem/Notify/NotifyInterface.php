<?php
namespace LogSystem\Notify;

/**
 * Interface NotifyInterface
 * @author Leo Yang <leoyang@motouch.cn>
 */
interface NotifyInterface
{
    /**
     * @param array $log
     */
    public function send($log);

    /**
     * @param bool $debug
     */
    public function setDebug($debug);


    /**
     * @param int $second
     */
    public function setRate($second);

}