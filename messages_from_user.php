<?php
session_start();

if(!isset($_SESSION['username']))
{
	die("false");
}

include "database_connection.php";

$conversations = get_open_conversations();

function get_open_conversations()
{
	$connection = create_db_connection();

	if (!$connection)
	{
		die("false");
	}
	
	$username = $_SESSION['username'];
	
	if(!user_exists($connection, $username))
	{
		die("false");
	}
	
	$sql_query = "SELECT * FROM messages WHERE sender = '" . $username . "' OR receiver = '" . $username . "'";
	$result = mysqli_query($connection, $sql_query);
	
	if(!$result)
	{
		die("false");
	}

	$conversations = [];
	while($row = $result->fetch_row())
	{
		//$message = $row[0];
		$sender = $row[1];
		$receiver = $row[2];
		
		if($sender == $username)
		{
			$other = $receiver;
		}
		else
		{
			$other = $sender;
		}
		
		if(!in_array($other, $conversations))
		{
			array_push($conversations, $other);
		}
	}
	$conversations_json = json_encode($conversations);
	echo $conversations_json;
}
	
?>
