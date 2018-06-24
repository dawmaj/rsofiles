<?php



require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;



$connection = new AMQPStreamConnection('192.168.100.10', 5672, 'admin', 'admin');

$channel = $connection->channel();



$result = ($channel->basic_get('task_queue', true, null)->body);


echo " [*] Waiting for messages. To exit press CTRL+C\n";



$callback = function ($result) {

    echo ' [x] Received ', $result->body, "\n";

    sleep(substr_count($result->body, '.'));

    echo " [x] Done\n";

    $result->delivery_info['channel']->basic_ack($result->delivery_info['delivery_tag']);

};



$channel->basic_qos(null, 1, null);

$channel->basic_consume('task_queue', '', false, false, false, false, $callback);



while (count($channel->callbacks)) {

    $channel->wait();

}



$channel->close();

$connection->close();
