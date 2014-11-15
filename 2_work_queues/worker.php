<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;

// Create AMQPConnection to RabbitMQ as guest
$connection = new AMQPConnection('datdb.cphbusiness.dk', 5672, 'guest', 'guest');

// Create channel with connection
$channel = $connection->channel();

// Declare the queue with message durability
$channel->queue_declare('rh11_task_queue', false, true, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

// Callback function to receive message
$callback = function($msg){
    echo " [x] Received ", $msg->body, "\n";
    sleep(substr_count($msg->body, '.'));
    echo " [x] Done", "\n";
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

// Fair dispatch.  Do not give more than one message to a worker at a time.
$channel->basic_qos(null, 1, null);

$channel->basic_consume('rh11_task_queue', '', false, false, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

// Close the channel and connection
$channel->close();
$connection->close();
?>