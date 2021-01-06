<?php

if(!isset($_POST["user"]))
{
    die("false");
}

include "database_connection.php";

$user = $_POST["user"];

$connection = create_db_connection();

if(user_exists($connection, $user))
{
	die("true");
}

die("false");

?>