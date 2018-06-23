<?php
   require_once('functions.php');

    $token = "MYSID:".$_COOKIE['MYSID'];

    $user  = array(

        'id' => NULL,

        'username' => "Visitor",

	'role' => NULL

    );

    redis_set_json($token, $user, "0");

     header("location: index.php");

    exit;
?>
