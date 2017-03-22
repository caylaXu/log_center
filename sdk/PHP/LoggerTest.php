<?php


$logger = new Logger('127.0.0.1', 8080, 'fee.motouch.com.cn');

$logger->write(Logger::WARNING, 'Runtime', 'test', array('我还是当一个安静的美男子算了'));

