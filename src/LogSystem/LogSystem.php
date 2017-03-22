<?php
namespace LogSystem;

/**
 * Class LogSystem
 * @author  Leo Yang <leoyang@motouch.cn>
 */
class LogSystem
{
	//------------------------------------ 报错的层级 level -------------------------------------//
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

	//-------------------------------------- 错误类型分类 ----------------------------------------//
    /**
     * error type : runtime
     */
    const RUNTIME = 1;

    /**
     * error type : api
     */
    const API = 2;

    /**
     * error type : sql
     */
    const SQL = 3;

    /**
     * error type : android
     */
    const ANDROID = 4;

    /**
     * error type : ios
     */
    const IOS = 5;


    static $LEVELS = array(
        'DEBUG'     => self::DEBUG,
        'INFO'      => self::INFO,
        'NOTICE'    => self::NOTICE,
        'WARNING'   => self::WARNING,
        'ERROR'     => self::ERROR,
        'CRITICAL'  => self::CRITICAL,
        'ALERT'     => self::ALERT,
        'EMERGENCY' => self::EMERGENCY,
    );

    static $TYPES = array(
        'RUNTIME' => self::RUNTIME,
        'API'     => self::API,
        'SQL'     => self::SQL,
        'ANDROID' => self::ANDROID,
        'IOS' => self::IOS
    );

    public static function getLevels()
    {
        return self::$LEVELS;
    }

    /**
     * @param int $level
     * @return string
     */
    public static function getLevelString($level)
    {
        return array_search($level, self::$LEVELS);
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
        return self::$TYPES;
    }

    public static function getSystemString($system_id)
    {

    }

    /**
     * @var array
     */
    static $AllowSystem = array(
        'www.llj365.com'         => '1',      //邻里间
        'fee.llj365.com'         => '2',      //计费系统
        'c.llj365.com'           => '3',      //呼叫中心
        'shop.llj365.com'        => '4',      //商城
        'lifeservice.llj365.com' => '5',      //生活服务
        //'fee.gdog.com.cn'        => '6',    //计费gdog
        //'feev1.motouch.com'      => '7',    //flower测试
        //'motouch.me'             => '8',    //sunny测试
        'xzl.llj365.com'         => '9',      //邻里间写字楼
	    'studycar.me'		=> '11',
        //'studycar.gdog.com.cn'   =>'20',    //91恋车预发布
        'studycar.motouch.cn'   =>'21',       //91恋车教练端和学员端
        //'my.driving.com'   =>'22',          //caylatest
        'mstudycar.motouch.cn' => '23',       //91恋车驾校端
        'datacenter.91lianche.com.cn' => '24',//数据中心
    );


    /**
     * 根据名称获取系统id
     * @param int $system_id
     * @return bool|int|string
     */
    public static function existsSystemId($system_id)
    {
        return array_search($system_id, LogSystem::$AllowSystem);
    }

    public static function getSystemIdByName($system_name)
    {
        return isset(LogSystem::$AllowSystem[$system_name]) ? LogSystem::$AllowSystem[$system_name] : false;

    }
}