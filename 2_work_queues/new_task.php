<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Create AMQPConnection to RabbitMQ as guest
$connection = new AMQPConnection('datdb.cphbusiness.dk', 5672, 'guest', 'guest');

// Create channel with connection
$channel = $connection->channel();

// Declare the queue with message durability
$channel->queue_declare('rh11_task_queue', false, true, false, false);

// Implode program arguments by white space
$data = implode(' ', array_slice($argv, 1));

// Use "Hello, World!" if empty
if (empty($data))
    $data = "Hello World!";

// Create new AMQPMessage, make text persistent
$msg = new AMQPMessage($data, array('delivery_mode' => 2));

// Publish to the default exchange
$channel->basic_publish($msg, '', 'rh11_task_queue');

echo " [x] Sent ", $data, "\n";

// Close channel and connection
$channel->close();
$connection->close();
?>