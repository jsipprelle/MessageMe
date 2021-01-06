<?php

session_start();

if(!isset($_POST["username"]) || !isset($_POST["password"]))
{
	die("false");
}

$username = $_POST["username"];
$password = $_POST["password"];

include "database_connection.php";

login_existing_user($username, $password);

function login_existing_user($username, $password)
{
	$connection = create_db_connection();
	
	if (!$connection)
	{
		die("false");
	}
	
	if(!user_exists($connection, $username))
	{
		die("false");
	}
	
	$escaped_username = mysqli_real_escape_string($connection, $username);
	
	$sql_pw_hash_query = "SELECT salted_password_hash FROM users WHERE username=\"" . $escaped_username . "\"";
	$pw_hash_result = $connection->query($sql_pw_hash_query);
	
	if (!$pw_hash_result || $pw_hash_result->num_rows != 1)
	{
		die("false");
	}
	
	$row = mysqli_fetch_assoc($pw_hash_result);
	$pw_hash = $row["salted_password_hash"];
	
	$escaped_password = mysqli_real_escape_string($connection, $password);
	
	if(password_verify($escaped_password, $pw_hash))
	{
		$_SESSION['username'] = $username;
		
		if(isset($_SESSION['username']))
		{
			die("true");
		}
	}
	else
	{
		die("false");
	}
}

?>



