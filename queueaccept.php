<?php



require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;


function getMsg() {

$connection = new AMQPStreamConnection('192.168.100.10', 5672, 'admin', 'admin');

$channel = $connection->channel();



$channel->queue_declare('posts', false, true, false, false);

$message = $channel->basic_get('task_queue');

$callback = function ($msg) {

    echo ' [x] Received ', $msg->body, "\n";

    sleep(substr_count($msg->body, '.'));

    echo " [x] Done\n";

    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

};



$channel->basic_qos(null, 1, null);

$channel->basic_consume('task_queue', '', false, false, false, false, $callback);



$channel->close();

$connection->close();

}




?>


<br><input type="button" id="add" value="ACCEPT POST"></br>
<br><input type="button" id="del" value="DELETE POST"></br>
<br><input type="button" onclick="getMsg()" id="get" value="GET POST"></br>
