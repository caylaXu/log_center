<?php
namespace LogSystem\Notify;


use LogSystem\LogSystem;

use Swift_Mailer,
    Swift_Message,
    Swift_SmtpTransport;

/**
 * Class SmtpEmail
 * @package LogSystem\Notify
 * @author  Leo Yang <leoyang@motouch.cn>
 */
class SmtpEmail extends AbstructRateController implements NotifyInterface
{

    /**
     * @var SmtpEmail
     */
    protected static $instance;

    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var Swift_Message
     */
    protected $message;

    /**
     * @var string
     */
    private $smtp_host;

    /**
     * @var int
     */
    private $smtp_port;

    /**
     * @var string
     */
    private $smtp_user;

    /**
     * @var string
     */
    private $smtp_password;

    /**
     * @var array
     */
    private $addresses;


    private $debug = false;


    /**
     * @param string $smtp_host
     * @param int    $smtp_port
     * @param string $smtp_user
     * @param string $smtp_password
     */
    public function __construct($smtp_host, $smtp_port, $smtp_user, $smtp_password)
    {
        $this->smtp_host     = $smtp_host;
        $this->smtp_port     = $smtp_port;
        $this->smtp_user     = $smtp_user;
        $this->smtp_password = $smtp_password;

        $this->mailer = $this->createSmtpMailer($smtp_host, $smtp_port, $smtp_user, $smtp_password);
    }


    /**
     * @param array $log
     * @param int   $ac
     * @return string
     */
    protected function renderBody($log, $ac)
    {
        $time        = date('Y-m-d H:i:s', $log['Time']);
        $current     = date('Y-m-d H:i:s');
        $level       = LogSystem::getLevelString($log['Level']);
        $context     = $log['Context'];
        $system_name = $log['SystemName'];
        $src_ip      = isset($log['SrcIp']) ? $log['SrcIp'] : '';

        $lastAction = date('Y-m-d H:i:s', $this->getLastAction());

        $body = <<<BODY
<pre>
在 {$lastAction} - {$current} 期间共有　{$ac} 个告警

最新告警信息为

系统 　　: {$system_name}
时间 　　: {$time}
错误等级 : {$level}
来源IP  : {$src_ip}
Message : {$log['Message']}
Context :
{$context}
</pre>
BODY;

        return $body;
    }

    /**
     * @param array $log
     */
    public function send($log)
    {
        $ac = $this->checkRate();
        if ($ac === false) {
            return;
        }

        $body    = $this->renderBody($log, $ac);
        $this->sendMail($body);

//        $body    = $this->renderBody($log, $ac);
//        $message = new Swift_Message("系统告警", $body, 'text/html', 'utf-8');
//        $message->setFrom([$this->smtp_user => 'motouch log']);
//        $message->setTo($this->getAddresses());
//
//        try {
//            $this->mailer->send($message);
//        } catch (\Exception $e) {
//            if ($this->debug) {
//                echo sprintf('发送邮件失败: %s', $e->getMessage());
//            }
//        }
//
//        if ($this->debug) {
//            echo "邮件已发送\n";
//            echo $message->getBody(), "\n";
//        }

    }

    /**
     * @param $from
     * @param $name
     * @return Swift_Message
     * @internal param $address
     * @internal param $body
     */
    public static function createMessage($from, $name)
    {
        $message = Swift_Message::newInstance();
        $message->setFrom([$from => $name]);
        $message->setSubject("系统告警");

        return $message;
    }


    /**
     * @param $smtp_host
     * @param $smtp_port
     * @param $smtp_user
     * @param $smtp_password
     * @return Swift_Mailer
     */
    public function createSmtpMailer($smtp_host, $smtp_port, $smtp_user, $smtp_password)
    {
        $transport = Swift_SmtpTransport::newInstance($smtp_host, $smtp_port);
        $transport->setUsername($smtp_user);
        $transport->setPassword($smtp_password);
        $mailer = Swift_Mailer::newInstance($transport);

        return $mailer;
    }

    /**
     * @param $address
     * @param $name
     */
    public function addAddress($address, $name)
    {
        $this->addresses[$address] = $name;
    }

    /**
     * @return array
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param array $addresses
     */
    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;
    }

    /**
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    function sendMail($body)
    {
        $transport = Swift_SmtpTransport::newInstance($this->smtp_host,$this->smtp_port);
        $transport->setUsername($this->smtp_user);
        $transport->setPassword($this->smtp_password);
        $mailer = Swift_Mailer::newInstance($transport);
        $message = Swift_Message::newInstance();
        $message->setFrom([$this->smtp_user => 'motouch log']);
        $message->setTo($this->addresses);
        $message->setSubject("系统告警");
        $message->setBody($body, 'text/html', 'utf-8'); //'text/plain'
        try{
            $mailer->send($message);
            echo "发送成功";
        }
        catch (\Exception $e){
            echo 'There was a problem communicating with SMTP: ' . $e->getMessage();
        }
    }
}