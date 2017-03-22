<?php
use LogSystem\Notify\NotifyInterface;
use LogSystem\Queue\QueueInterface;
use LogSystem\Storage\StorageInterface;

/**
 * Class DI
 * @author Leo Yang <leoyang@motouch.cn>
 */
class DI
{

    /**
     * @var array
     */
    private static $container = array();

    /**
     * 不同队列容器的redis的单例
     * @return QueueInterface
     */
    public static function Queue()
    {
        return DI::RedisQueue();
    }

    /**
     * @return StorageInterface
     */
    public static function Storage()
    {
        return DI::MysqlStorage();
    }

    /**
     * @return NotifyInterface[]
     */
    public static function Notifys()
    {
        return array(
            \LogSystem\LogSystem::NOTICE => DI::SmtpEmailNotify(),
            \LogSystem\LogSystem::ERROR  => DI::SmsNotify(),
        );
    }

    /**
     * @return \LogSystem\Notify\SmtpEmail
     */
    public static function SmtpEmailNotify()
    {
        $key = 'smtp_email_notify';
        if (!isset(self::$container[$key])) {
            $smtpEmail = new \LogSystem\Notify\SmtpEmail(Config::SMTP_HOST, Config::SMTP_PORT, Config::SMTP_USER, Config::SMTP_PASS);
            $smtpEmail->setAddresses(Config::$NOTIFY_MAILS);
            $smtpEmail->setDebug(Config::DEBUG);

            self::$container[$key] = $smtpEmail;
        }

        return self::$container[$key];
    }

    /**
     * @return \LogSystem\Notify\Sms
     */
    public static function SmsNotify()
    {
        $key = 'sms_notify';
        if (!isset(self::$container[$key])) {
            $sms = new \LogSystem\Notify\Sms(Config::SMS_SN, Config::SMS_PWD, Config::$MOBILES);
            $sms->setDebug(Config::DEBUG);
            self::$container[$key] = $sms;
        }

        return self::$container[$key];
    }

    /**
     * 不同队列key的连接redis单例
     * @return \LogSystem\Queue\Redis
     */
    protected static function RedisQueue()
    {
        $key = 'redis_queue';
        if (!isset(self::$container[$key])) {
            $redis = new \LogSystem\Queue\Redis();
            $redis->connect(Config::REDIS_HOST, Config::REDIS_PORT);
            $redis->auth(Config::REDIS_PASS);
            self::$container[$key] = $redis;
        }

        return self::$container[$key];
    }

    /**
     * @return \LogSystem\Storage\Mysql
     */
    protected static function MysqlStorage()
    {
        $key = 'mysql_storage';

        if (!isset(self::$container[$key])) {
            $dsn   = sprintf('mysql:host=%s;dbname=%s;charset=%s', Config::MYSQL_HOST, Config::MYSQL_DBNAME, Config::MYSQL_CHARSET);
            $mysql = new \LogSystem\Storage\Mysql($dsn, Config::MYSQL_USER, Config::MYSQL_PASS);

            self::$container[$key] = $mysql;
        }

        return self::$container[$key];
    }

}