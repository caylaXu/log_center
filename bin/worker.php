<?php

include dirname(__DIR__) . "/config.php";

register_shutdown_function(function () {
    @unlink(Config::WORKER_PID);
});

file_put_contents(Config::WORKER_PID, posix_getpid());

$worker = new \LogSystem\Worker(DI::Queue(), DI::Storage(), DI::Notifys());
$worker->run(Config::DEBUG);
