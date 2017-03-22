<?php

namespace LogSystem\Queue;

/**
 * Interface QueueInterface
 * @author Leo Yang <leoyang@motouch.cn>
 */
interface QueueInterface
{
    /**
     * @param string $message
     * @return string
     */
    public function push($message);

    /**
     * @return string
     */
    public function pop();


}