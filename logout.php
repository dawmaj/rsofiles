<?php
   require_once('functions.php');

    $token = $_COOKIE['MYSID'];

    $user  = array(

        'id' => NULL,

        'username' => "VisitorLoggedOut",

	'role' => NULL

    );

    redis_set_json($token, $user, "0");

    header("location: index.php");

    exit;


?>
