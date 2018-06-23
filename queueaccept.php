<?php

    require_once('functions.php');

    $user=session_check();

    if (!isset($user['id'])) {

        header("location: index.php");

        exit;

    }

?>

Akceptuj posty
