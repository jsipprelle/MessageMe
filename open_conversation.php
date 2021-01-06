<?php
session_start();

if(!isset($_SESSION['username']))
{
	die("false");
}

include "database_connection.php";

$username = $_SESSION["username"];
$otherUser = $_POST["otherUser"];
$loadedMessageCount = $_POST["loadedMessageCount"];

open_conversation($username, $otherUser, $loadedMessageCount);

function open_conversation($username, $otherUser, $loadedMessageCount)
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
	
	if(!user_exists($connection, $otherUser))
	{
		die("false");
	}
	
	$our_username_escaped = mysqli_real_escape_string($connection, $username);
	$other_user_escaped = mysqli_real_escape_string($connection, $otherUser);
	
	$sql_query = "SELECT * FROM messages WHERE (sender=\"" . $our_username_escaped . "\" AND receiver=\"" . $other_user_escaped . "\") " .
"OR (sender=\"" . $other_user_escaped . "\" AND receiver=\"" . $our_username_escaped . "\")";
	
	$result = mysqli_query($connection, $sql_query);
	if(!$result)
	{
		die("false");
	}
	
	$number_of_messages_already_loaded = intval($loadedMessageCount);
	$number_of_total_messages = $result->num_rows;
	
	if($number_of_messages_already_loaded > $number_of_total_messages)
	{
        die("false");
	}

	if($number_of_messages_already_loaded == $number_of_total_messages)
	{
		//No update needed
		die("false");
	}

	mysqli_data_seek($result, $number_of_messages_already_loaded);

	$array_stack = array();
	while ($row = mysqli_fetch_assoc($result))
	{
		$message = $row['message'];
		$message_sender = $row['sender'];
	
		if($message_sender == $username)
		{
			$array = array($message, "S");
			array_push($array_stack, $array);
		}
		else
		{
			$array = array($message, "R");
			array_push($array_stack, $array);
		}
	}
	$json_final = json_encode($array_stack);
	echo $json_final;
}
?>
