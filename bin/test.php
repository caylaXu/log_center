<?php


include dirname(__DIR__) . '/config.php';


//$m = new \LogSystem\Notify\SmtpEmail('smtp.exmail.qq.com', 25, 'leoyang@motouch.cn', 'xz19931007');
//
//
//$m->addAddress('897798676@qq.com', 'leoyang');
//
//$m->send(array(
//    'SystemName' => 'fee.ddd',
//    'Time'       => time(),
//    'Level'      => "1",
//    'Type'       => "Runtime",
//    'Message'    => "hhh",
//    'Context'    => json_encode(array(
//        "aaa" => "bbb"
//    )),
//));


//$s = new \LogSystem\Notify\Sms('SDK-NSF-010-00046', '0@1f3e@4', array('13361163805', '18575690045'));
//
//$s->send(array());
//
//
require_once dirname(__DIR__) .'/vendor/swiftmailer/swiftmailer/lib/swift_required.php';
function sendMail(){
    /*
    $transport = Swift_SmtpTransport::newInstance('smtp.163.com', 25);
    $transport->setUsername('username@163.com');
    $transport->setPassword('password');
    $transport = Swift_SendmailTransport::newInstance('/usr/sbin/exim -bs');
    $transport = Swift_MailTransport::newInstance();
    */
    $transport = Swift_SmtpTransport::newInstance('smtp.exmail.qq.com', 25);
    $transport->setUsername('log@motouch.cn');
    $transport->setPassword('***');
    $mailer = Swift_Mailer::newInstance($transport);
    $message = Swift_Message::newInstance();
    $message->setFrom(array('log@motouch.cn' => 'name'));
    $message->setTo(array('306538517@qq.com' => 'Mr.Right'));
    $message->setSubject("This is a subject");
    $message->setBody('Here is the message', 'text/html', 'utf-8'); //'text/plain'
    try{
        $mailer->send($message);
        echo "发送成功";
    }
    catch (\Exception $e){
        echo 'There was a problem communicating with SMTP: ' . $e->getMessage();
    }
}
?>