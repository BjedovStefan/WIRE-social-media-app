<?php

ob_start(); //turns on buffering
session_start();

$timezone = date_default_timezone_set("Europe/Belgrade");

$con = mysqli_connect("localhost","root", "", "social");

if(mysqli_connect_errno()){
  echo "Failed to connect: " . mysqli_connect_errno();
}

?>
