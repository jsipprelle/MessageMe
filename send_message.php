<?php

session_start();

if(!isset($_SESSION['username']))
{
	die("false");
}

$user_from = $_SESSION['username'];

if(!isset($_POST['other']))
{
	die("false");
}

$user_to = $_POST['other'];

if(!isset($_POST['messageText']))
{
	die("false");
}

$message_text = $_POST['messageText'];

include "database_connection.php";

send_message($user_from, $user_to, $message_text);

function send_message($user_from, $user_to, $message_text)
{
	$connection = create_db_connection();

	if (!$connection)
	{
		die("false");
	}
	
	if(!user_exists($connection, $user_from))
	{
		die("false");
	}
	
	if(!user_exists($connection, $user_to))
	{
		die("false");
	}
	
	$escaped_sender = mysqli_real_escape_string($connection, $user_from);
	$escaped_user_to_message = mysqli_real_escape_string($connection, $user_to);
	$escaped_message = mysqli_real_escape_string($connection, $message_text);

	$out_values = "\"" . $escaped_message . "\", \"" . $escaped_sender . "\", \"" . $escaped_user_to_message . "\"";
	$sql_username_query = "INSERT INTO messages (message, sender, receiver) VALUES (" . $out_values . ")";

	$out_message_result = $connection->query($sql_username_query);
	if (!$out_message_result)
	{
		die("false");
	}

	die("true");
}

?>
