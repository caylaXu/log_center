<?php

namespace LogSystem\Queue;

/**
 * Class Redis
 * @author  Leo Yang <leoyang@motouch.cn>
 */
class Redis extends \Redis implements QueueInterface
{

    const QUEUE_KEY = 'log_system_queue';

    /**
     * @param string $message
     * @return string
     */
    public function push($message)
    {
        return $this->lPush(self::QUEUE_KEY, $message);
    }

    /**
     * @return string
     */
    public function pop()
    {
        while (true) {
            $message = $this->rPop(self::QUEUE_KEY);
            if ($message === false) {
                sleep(1);
                continue;
            }
            return $message;
        }

        return false;
    }
}