<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;

// Create AMQPConnection with connection details
$connection = new AMQPConnection('datdb.cphbusiness.dk', 5672, 'student', 'cph');

// Create channel with connection
$channel = $connection->channel();

// Declare the queue
$channel->queue_declare('rh11_hello', false, false, false, false);
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

// Callback function to receive message
$callback = function($msg) {
    echo " [x] Received ", $msg->body, "\n";
};

$channel->basic_consume('rh11_hello', '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

?>