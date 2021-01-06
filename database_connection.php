<?php

function create_db_connection()
{
	$servername = "localhost";
	$database_login_username = "login_db_admin";
	$database_login_password = "8Ogw6oUmV62MdGy0";
	$database_name = "login";

	$connection = mysqli_connect($servername, $database_login_username, $database_login_password, $database_name);
	
	return $connection;
}

function user_exists($connection, $username)
{
	$escaped_username = mysqli_real_escape_string($connection, $username);
	$sql_username_query = "SELECT username FROM users WHERE username=\"" . $escaped_username . "\"";
	$result = $connection->query($sql_username_query);
	
	if ($result && $result->num_rows != 0)
	{
		return true;
	}
	return false;
}

?>