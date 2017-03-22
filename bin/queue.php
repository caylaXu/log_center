<?php

include dirname(__DIR__) . "/config.php";

register_shutdown_function(function () {
    @unlink(Config::QUEUE_PID);
});

file_put_contents(Config::QUEUE_PID, posix_getpid());

$queueServer = new \LogSystem\QueueServer(Config::QUEUE_IP, Config::QUEUE_PORT, DI::Queue());
$queueServer->listen(Config::DEBUG);
