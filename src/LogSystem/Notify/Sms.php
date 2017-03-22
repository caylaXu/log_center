<?php
namespace LogSystem\Notify;
use MessageCenter;
/**
 * Class Sms
 * @author  Leo Yang <leoyang@motouch.cn>
 */
class Sms extends AbstructRateController implements NotifyInterface
{

    const API_URL = 'http://sdk2.entinfo.cn:8060/webservice.asmx/mdSmsSend_u';

    /**
     * @var string
     */
    private $sn;

    /**
     * @var string
     */
    private $pwd;

    /**
     * @var array
     */
    private $mobiles = array();

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @param string $sn
     * @param string $pwd
     * @param array  $mobiles
     */
    public function __construct($sn, $pwd, array $mobiles = array())
    {
        $this->sn       = $sn;
        $this->pwd      = $pwd;
        $this->mobiles  = $mobiles;
        $this->lastSend = time();
    }

    public function send($log)
    {
        echo "发短信啦";
        $ac = $this->checkRate();

        if ($ac === false) {
            return;
        }
        $content = $this->renderBody($ac);
        $mobiles = array_keys($this->mobiles);
        foreach($mobiles as $k=>$v)
        {
            $this->send_sms($content,array($v));
        }

//        $mobile  = implode(',', $this->mobiles);
//        $content = $this->renderBody($ac);
//        $this->_send($mobile, $content);

        if ($this->debug) {
            echo "短信已发送\n";
            echo $content, "\n";
        }
    }

    /**
     * @param int $ac
     * @return string
     */
    private function renderBody($ac)
    {
        return sprintf(
            "系统告警,　%s - %s 期间, 共有 %s 个告警信息, 请登录日志系统查看",
            date('Y-m-d H:i:s', $this->getLastAction()),
            date('Y-m-d H:i:s'),
            $ac
        );
    }


    /**
     * @param string $mobile
     * @param string $content
     */
    private function _send($mobile, $content)
    {
        if (empty($mobile)) {
            return;
        }

        $post = array(
            'sn'      => $this->sn,
            'pwd'     => strtoupper(md5($this->sn . $this->pwd)),
            'mobile'  => $mobile,
            'content' => urlencode($content),
            'ext'     => '',
            'rrid'    => '',
            'stime'   => ''
        );

        $options = array(
            CURLOPT_POST           => 1,
            CURLOPT_HEADER         => 0,
            CURLOPT_URL            => self::API_URL,
            CURLOPT_FRESH_CONNECT  => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE   => 1,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_POSTFIELDS     => http_build_query($post)
        );
        $ch      = curl_init();
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);

        if ($this->debug) {
            echo sprintf("短信接口返回数据: %s \n", $result);
        }

        curl_close($ch);
    }

    /**
     * @return array
     */
    public function getMobiles()
    {
        return $this->mobiles;
    }

    /**
     * @param array $mobiles
     */
    public function setMobiles($mobiles)
    {
        $this->mobiles = $mobiles;
    }

    /**
     * @param number $mobile
     */
    public function addMobile($mobile)
    {
        $this->mobiles[] = $mobile;
    }

    /**
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        $this->debug = (bool)$debug;
    }

    /**
     * @param int $second
     */
    public function setRate($second)
    {
        $this->rate = $second;
    }


    static function send_sms($content, $mobiles)
    {
        require_once dirname(dirname(dirname(__DIR__))).'/vendor/messagecenter/MessageCenter.php';
        $MessageCenter = MessageCenter::getInstance();
        $msg           = $content;
        $res           = $MessageCenter->sendSms($msg, $mobiles);
        return $res;
    }
}