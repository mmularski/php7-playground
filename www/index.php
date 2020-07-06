<?php
require_once __DIR__ . '/vendor/autoload.php';

use RabbitMq\FirstTutorial\Consumer;
use RabbitMq\FirstTutorial\Publisher;

$publisher = new Publisher();

for ($i = 0; $i < 5; $i++) {
    $publisher->process();
}

$consumer = new Consumer();
$consumer->start();



