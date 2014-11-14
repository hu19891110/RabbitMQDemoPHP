<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Create AMQPConnection with connection details
$connection = new AMQPConnection('datdb.cphbusiness.dk', 5672, 'student', 'cph');

// Create channel with connection
$channel = $connection->channel();

// Declare the queue
$channel->queue_declare('rh11_hello', false, false, false, false);

// Create new AMQPMessage
$msg = new AMQPMessage('Hello, World!');

// Publish message
$channel->basic_publish($msg, '', 'rh11_hello');
echo " [x] Sent 'Hello World!'\n";

// Close the channel and connection
$channel->close();
$connection->close();
?>