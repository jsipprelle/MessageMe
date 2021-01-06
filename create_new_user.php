<?php

if(!isset($_POST["username"]) || !isset($_POST["password"]))
{
	die("false");
}

include "database_connection.php";

$username = $_POST["username"];
$password = $_POST["password"];

create_new_user($username, $password);

function create_new_user($username, $password)
{
	$connection = create_db_connection();

	if (!$connection)
	{
		die("false");
	}
	
	if(user_exists($connection, $username))
	{
		die("false");
	}

	$escaped_password = mysqli_real_escape_string($connection, $password);
	$pw_hash = password_hash($escaped_password, PASSWORD_BCRYPT);
	
	$sql_query = "INSERT INTO users (username, salted_password_hash) VALUES ('" . $username . "', '" . $pw_hash . "')";
	
	if (!mysqli_query($connection, $sql_query))
	{
		die("false");
	}
	
	die("true");
}

?>



