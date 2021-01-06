<?php
if(!isset($_POST['username']))
{
	die("Invalid username");
}

if(!isset($_POST['password']))
{
	die("Invalid password");
}

$username = $_POST['username'];
$password = $_POST['password'];



?>