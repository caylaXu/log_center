<?php

namespace LogSystem\Storage;

/**
 * Interface StorageInterface
 * @author Leo Yang <leoyang@motouch.cn>
 */
interface StorageInterface
{

    /**
     * @param int    $system_id
     * @param int    $time
     * @param int    $level
     * @param string $type
     * @param string $message
     * @param string $context
     * @return bool
     */
    public function put($system_id, $time, $level, $type, $message, $context);

    /**
     * @param int $system_id
     * @param int $id
     * @return array
     */
    public function fetch($system_id, $id);

    /**
     * @param int    $system_id
     * @param int    $time_start
     * @param int    $time_end
     * @param int    $level
     * @param int    $type
     * @param string $message
     * @param string $context
     * @param int    $limit
     * @param int    $offset
     * @return array
     */
    public function find($system_id, $time_start, $time_end, $level, $type, $message, $context, $limit, $offset);

    /**
     * @param int    $system_id
     * @param int    $time_start
     * @param int    $time_end
     * @param int    $level
     * @param int    $type
     * @param string $message
     * @param string $context
     * @return int
     */
    public function count($system_id, $time_start, $time_end, $level, $type, $message, $context);
}