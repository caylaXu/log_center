<?php

include __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/DI.php';

class Config
{
    const DEBUG = false;

    // MySQL
    const MYSQL_HOST    = 'localhost';
    const MYSQL_PORT    = 3306;
    const MYSQL_USER    = 'studycarlog';
    const MYSQL_PASS    = 'LV7c%UEZ';
    const MYSQL_DBNAME  = 'StudyCarLog';
    const MYSQL_CHARSET = 'utf8';

    // Redis
    const REDIS_HOST = 'localhost';
    const REDIS_PORT = 6379;
    const REDIS_PASS = 'motouch@2015';

    // Queue
    const QUEUE_IP   = '0.0.0.0';
    const QUEUE_PORT = 8080;

    // Smtp Mail
    const SMTP_HOST = 'smtp.exmail.qq.com';
    const SMTP_PORT = 25;
    const SMTP_USER = 'log@motouch.cn';
    const SMTP_PASS = 'Dianxia@2016';

    // SMS
    const SMS_SN  = 'SDK-NSF-010-00046';
    const SMS_PWD = '0@1f3e@4';

    // Pid
    const WORKER_PID = '/var/run/motouch_log_worker.pid';
    const QUEUE_PID  = '/var/run/motouch_log_queue.pid';

    // 邮件发送
    static $NOTIFY_MAILS = array(
        //'flowerzhou@motouch.cn'    => 'flowerzhou',
        //'allenzhao@motouch.cn'     => 'allenzhao',
        //'martinchen@motouch.cn'    => 'martinchen',
        //'mythyang@motouch.cn'      => 'mythyang',
        //'fetionhe@motouch.cn'      => 'fetionhe',
        //'williamcai@motouch.cn'    => 'williamcai',
        //'sunnysun@motouch.cn'      => 'sunnysun',
        //'grommzhu@motouch.cn'      => 'grommzhu',
        //'nasuslin@motouch.cn'      => 'nasuslin',
        //'shirleyyu@motouch.cn'     => 'shirleyyu',
        //'willwu@motouch.cn'        => 'willwu',
        //'jentelyli@motouch.cn'     => 'jentelyli',
        //'ericyuan@motouch.cn'      => 'ericyuan',
        //'waitorchen@motouch.cn'    => 'waitorchen',
        //'hueylai@motouch.cn'       => 'hueylai',
        //'mickeywei@motouch.cn'     => 'mickeywei',
        //'peterpan@motouch.cn'      => 'peterpan',
        //'zippozeng@motouch.cn'     => 'zippozeng',
        //'darrenzhang@motouch.cn'   => 'darrenzhang',
        //'cindychen@motouch.cn'     => 'cindychen',
        //'stephanzeng@motouch.cn'   => 'stephanzeng',
        //'caylaxu@motouch.cn'       => 'caylaxu'
        'develop@motouch.cn'         => 'develop'
    );

    // 手机号
    static $MOBILES = array(
        '13020272609'    => 'flowerzhou',
        '13058100501'     => 'allenzhao',
        '18042411751'    => 'martinchen',
        '18823209179'      => 'mythyang',
        '18027228858'      => 'fetionhe',
        '13006378537'    => 'williamcai',
        '18200712718'      => 'sunnysun',
        '13570819926'      => 'grommzhu',
        '13798990388'      => 'nasuslin',
        '18576478421'     => 'shirleyyu',
        '15899863436'        => 'willwu',
        '15118073589'     => 'jentelyli',
        '15919774440'      => 'ericyuan',
        '15606072001'    => 'waitorchen',
        '15279110584'       => 'hueylai',
        '15875587525'     => 'mickeywei',
        '15986328380'      => 'peterpan',
        '13691891024'     => 'zippozeng',
        '13924649130'   => 'darrenzhang',
        '13699764993'     => 'cindychen',
        '18123675314'   => 'stephanzeng',
        '13823125047'       => 'caylaxu'
    );
}