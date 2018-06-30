<?php
   require_once('functions.php');
   //our cookie MYSID
    $token = "MYSID:".$_COOKIE['MYSID'];
   //clear user array
    $user  = array(

        'id' => NULL,

        'username' => "Visitor",

	'role' => NULL

    );
	//forever as Visitor
    redis_set_json($token, $user, "0");
	//return to index
     header("location: index.php");

    exit;
?>
