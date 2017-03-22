<?php


/**
 * Class Logger
 * @author Leo Yang <leoyang@motouch.cn>
 */
class Logger
{

    /**
     * Detailed debug information
     */
    const DEBUG = 1;

    /**
     * Interesting events
     *
     * Examples: User logs in, SQL logs.
     */
    const INFO = 2;

    /**
     * Uncommon events
     */
    const NOTICE = 3;

    /**
     * Exceptional occurrences that are not errors
     *
     * Examples: Use of deprecated APIs, poor use of an API,
     * undesirable things that are not necessarily wrong.
     */
    const WARNING = 4;

    /**
     * Runtime errors
     */
    const ERROR = 5;

    /**
     * Critical conditions
     *
     * Example: Application component unavailable, unexpected exception.
     */
    const CRITICAL = 6;

    /**
     * Action must be taken immediately
     *
     * Example: Entire website down, database unavailable, etc.
     * This should trigger the SMS alerts and wake you up.
     */
    const ALERT = 7;

    /**
     * Urgent alert.
     */
    const EMERGENCY = 8;


    static $ERROR_MAP = array(
        E_ERROR             => Logger::ERROR,
        E_RECOVERABLE_ERROR => Logger::NOTICE,
        E_WARNING           => Logger::WARNING,
        E_PARSE             => Logger::ERROR,
        E_NOTICE            => Logger::NOTICE,
        E_STRICT            => Logger::WARNING,
        E_DEPRECATED        => Logger::NOTICE,
        E_USER_ERROR        => Logger::ERROR,
        E_USER_WARNING      => Logger::WARNING,
        E_USER_NOTICE       => Logger::NOTICE,
        E_USER_DEPRECATED   => Logger::NOTICE,
    );


    /**
     * @var resource
     */
    private $socket;
    private $host;
    private $port;
    private $system_name;

    public function __construct($host, $port, $system_name)
    {
        $this->socket      = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        $this->host        = $host;
        $this->port        = $port;
        $this->system_name = $system_name;
    }

    public function __destruct()
    {
        socket_close($this->socket);
    }


    public function write($level, $type, $message, array $context = array())
    {
        $log = array(
            'SystemName' => $this->system_name,
            'Time'       => time(),
            'Level'      => $level,
            'Type'       => $type,
            'Message'    => $message,
            'Context'    => json_encode($context, JSON_UNESCAPED_UNICODE),
        );

        $log = json_encode($log);
        $len = strlen($log);
        socket_sendto($this->socket, $log, $len, 0, $this->host, $this->port);
    }

    public function errorHandle($errno, $message, $errfile, $errline, $request)
    {
        $level = $this->getErrorLevel($errno);

        $context = array(
            'File' => $errfile,
            'Line' => $errline,
            'Request' => $request,
        );

        $this->write($level, 'Runtime', $message, $context);
    }

    public function getErrorLevel($phpLevel)
    {
        if (!isset(Logger::$ERROR_MAP[$phpLevel])) {
            throw new ErrorException("错误级别不存在");
        }

        return Logger::$ERROR_MAP[$phpLevel];
    }

}