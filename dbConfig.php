<?php

$conn=mysqli_connect("192.168.100.20:3306","testuser","qwerty","users");

$conn1=mysqli_connect("192.168.100.10::3306","testuser","qwerty","users");

if(!$conn or !$conn1)

{

die("Connection failed: " . mysqli_connect_error());

}

?>
