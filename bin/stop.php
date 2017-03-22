<?php
include dirname(__DIR__) . "/config.php";

$queue_pid = file_get_contents(Config::QUEUE_PID);
$worker_pid = file_get_contents(Config::WORKER_PID);

exec("kill {$queue_pid}");
exec("kill {$worker_pid}");