<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('my-rabbit', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('task_queue',
						false,
						true, // durable
						false,
						false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) {
  echo " [x] Received ", $msg->body, "\n";
  sleep(substr_count($msg->body, '.'));
  echo " [x] Done", "\n";
  $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

// don't dispatch a new message to a worker until it has processed and acknowledged the previous one
$channel->basic_qos(null, 1, null);
$channel->basic_consume('task_queue',
						'',
						false,
						false,
						false, // false => when task done, ack back to MQ
						false,
						$callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
