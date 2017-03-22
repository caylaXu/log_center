<?php
namespace LogSystem\Storage;

use PDO;

/**
 * Class Mysql
 * @author  Leo Yang <leoyang@motouch.cn>
 */
class Mysql extends PDO implements StorageInterface
{
    /**
     * @param int $system_id
     * @return string
     */
    public function getTableNameBySystemId($system_id)
    {
        return sprintf('log_%s', $system_id);
    }

    /**
     * @param string $tablename
     */
    public function  createTable($tablename)
    {
        $sql = <<<SQL
        CREATE TABLE {$tablename} (
          Id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
          Time bigint(20) NOT NULL COMMENT '日志产生时间',
          Level tinyint(4) NOT NULL COMMENT 'DEBUG = 1，INFO = 2，NOTICE = 3，WARNING = 4，ERROR = 5，CRITICAL = 6，ALERT = 7，EMERGENCY = 8',
          Type char(20) NOT NULL COMMENT '日志类型',
          Message text NOT NULL COMMENT '日志信息',
          Context text NOT NULL COMMENT '附加信息',
          PRIMARY KEY (Id)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SQL;
        $this->exec($sql);
    }

    /**
     * @param int    $system_id
     * @param int    $time
     * @param int    $level
     * @param string $type
     * @param string $message
     * @param string $context
     * @return bool
     */
    public function put($system_id, $time, $level, $type, $message, $context)
    {
        $tablename = $this->getTableNameBySystemId($system_id);
        $sql       = sprintf("INSERT INTO %s (Time,Level,Type,Message,Context)VALUES(?, ?, ?, ?, ?)", $tablename);
        $stmt      = $this->prepare($sql);

        $input_parameters = array($time, $level, $type, $message, $context);
        if (!$stmt->execute($input_parameters)) {
            $this->createTable($tablename);
            $stmt->execute($input_parameters);
        }
        return true;
    }

    /**
     * @param int $system_id
     * @param int $id
     * @return array
     */
    public function fetch($system_id, $id)
    {
        $tablename = $this->getTableNameBySystemId($system_id);
        $sql       = "SELECT * FROM {$tablename} WHERE Id={$id}";
        $query     = $this->query($sql);
        $array     = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

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
    public function find($system_id, $time_start, $time_end, $level, $type, $message, $context, $limit, $offset)
    {
        $tablename = $this->getTableNameBySystemId($system_id);

        $sql   = "SELECT * FROM {$tablename}
                 WHERE Level >= {$level}
                 AND Type = '{$type}'
                 AND (Time BETWEEN {$time_start} AND {$time_end})
                 LIMIT {$limit} OFFSET {$offset}";
        $query = $this->query($sql);
        $array = array();
        if ($query) {
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return $array;
    }

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
    public function count($system_id, $time_start, $time_end, $level, $type, $message, $context)
    {
        $tablename = $this->getTableNameBySystemId($system_id);

        $sql = "SELECT COUNT(*) AS count FROM {$tablename} WHERE (Time BETWEEN ? AND ?) AND Level >= ? AND Type = ?";

        $stmt  = $this->prepare($sql);
        $query = $stmt->execute(array($time_start, $time_end, $level, $type));

        $count = 0;
        if ($query) {
            $count = $stmt->fetchColumn(0);
        }

        return $count;
    }
}
//end of the file