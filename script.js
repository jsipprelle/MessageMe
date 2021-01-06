var message_count = 0;
var other = "";
var current_user = "";

function isLoggedIn()
{
	return false;
}

function displayLoginPage()
{
	var messageDiv = document.getElementById("messageDiv");
	messageDiv.style.display = "none";
	
	var mainDiv = document.getElementById("loginDiv");
	mainDiv.style.display = "block";
}

function displayConversationPage()
{
	var mainDiv = document.getElementById("loginDiv");
	mainDiv.style.display = "none";
	
	var messageDiv = document.getElementById("messageDiv");
	messageDiv.style.display = "block";
}

function sendMessage()
{
	var messageText = document.getElementById("messageText").value;
	if(messageText == "")
	{
		return;
	}
	
	document.getElementById("messageText").value = "";
	
	var xhttp = new XMLHttpRequest();
	xhttp.open("POST", "send_message.php", true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	var send_request = "other=" + other + "&messageText=" + messageText;
	xhttp.send(send_request);
}

function newConversation()
{
	var newUser = window.prompt("Who would you like to message?", "");
	
	if(newUser == "")
	{
		return;
	}
	
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200)
        {
			alert(this.responseText);
            if(this.responseText != "true")
            {
                return;
            }

            listConversation(newUser, "");
			loadConversation(newUser);
        }
    };
    xhttp.open("POST", "validate_user.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.send("user=" + newUser);
}

function loadConversation(sender)
{
	other = sender;
	message_count = 0;
	
	document.getElementById("currentMessage").style = "display: none;";
	
	var messageList = document.getElementById("currentConversation");
	messageList.innerHTML = "";
	
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
	{
        if (this.readyState == 4 && this.status == 200)
		{
			if(this.responseText == "false")
			{
				//return;
			}
			
			messageList.innerHTML = "<h2 style=\"text-align: center;\">Current user: " + current_user + "</h2>";
			
			messageList.innerHTML += "<h2 style=\"text-align: center;\">Messages with " + sender + "</h2>";
			
			var response = this.responseText;
			var json = JSON.parse(response);
			var number_of_new_messages = json.length;
			
			for(var i = 0; i < number_of_new_messages; i++)
			{
				var new_message = json[i];
				var new_message_content = new_message[0];
				
				if(new_message[1] == "R")
				{
					var old_html = document.getElementById("currentConversation").innerHTML;
					var new_html = "<p class=\"left\">" + new_message_content + "</p>";
					
					document.getElementById("currentConversation").innerHTML = old_html + new_html;
				}
				else
				{
					var old_html = document.getElementById("currentConversation").innerHTML;
					var new_html = "<p class=\"right\">" + new_message_content + "</p>";
					
					document.getElementById("currentConversation").innerHTML = old_html + new_html;
				}
				message_count++;
			}
			document.getElementById("sendMessagePanel").style = "display: inline-block;";
			document.getElementById("currentMessage").style = "display: initial;";
		}
	};
  xhttp.open("POST", "open_conversation.php", true);
  xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhttp.send("otherUser=" + sender + "&loadedMessageCount=0");
}

function listConversation(sender, latestMessage)
{
	var messageList = document.getElementById("messageList");
	
	var newDiv =  "<div class=\"conversation\" onclick=\"loadConversation('" + sender + "');\">";
	newDiv += "<div class=\"sender\">" + sender + "</div>";
	newDiv += "<div class=\"newestMessage\">" + latestMessage + "</div>";
	newDiv += "</div>";
	
	messageList.innerHTML += newDiv;
}

function loadConversationPage()
{
	displayConversationPage();
	
	var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
	{
    	if (this.readyState == 4 && this.status == 200)
		{
			if(this.responseText == "false")
			{
				return;
			}
			
			var conversations = JSON.parse(this.responseText);
			
			var i;
			for(i = 0; i < conversations.length; i++)
			{
				var conversation = conversations[i];
				listConversation(conversation, "");
			}
		}
	};
  xhttp.open("POST", "messages_from_user.php", true);
  xhttp.send();
}

function checkForNewMessages()
{
	if(other == "")
	{
		return;
	}
	var messageList = document.getElementById("currentConversation");
	
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            if(this.responseText == "false")
            {
                return;
            }

            var response = this.responseText
            var json = JSON.parse(response);
            var number_of_new_messages = json.length;
			
            for(var i = 0; i < number_of_new_messages; i++)
            {
                var new_message = json[i];
                var new_message_content = new_message[0];
				
                if(new_message[1] == "R")
                {
                    var old_html = document.getElementById("currentConversation").innerHTML;
                    var new_html = "<p class=\"left\">" + new_message_content + "</p>";
					
                    document.getElementById("currentConversation").innerHTML = old_html + new_html;
                }
                else
                {
                    var old_html = document.getElementById("currentConversation").innerHTML;
                    var new_html = "<p class=\"right\">" + new_message_content + "</p>";
					
                    document.getElementById("currentConversation").innerHTML = old_html + new_html;
                }
                message_count++;
            }
        }
    };
    xhttp.open("POST", "open_conversation.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.send("otherUser=" + other + "&loadedMessageCount=" + message_count);
}

function mainEntry()
{
	var loggedIn = isLoggedIn();
	if(!loggedIn)
	{
		displayLoginPage();
	}
	else
	{
		loadConversationPage();
	}
	setInterval(checkForNewMessages, 500);
}

function validateUsername(usernameText)
{
	return usernameText != "";
}

function validatePassword(passwordText)
{
	return passwordText != "";
}

function displayMessage(message)
{
	document.getElementById("errorMessage").innerHTML = message;
	document.getElementById("errorMessage").style.display = "block";
}

function hideMessage()
{
	document.getElementById("errorMessage").style.display = "none";
}

function validateInput()
{
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;
	if(!validateUsername(username))
	{
		displayMessage("Invalid username");
		return false;
	}
	
	if(!validatePassword(password))
	{
		displayMessage("Invalid password.");
		return false;
	}
	
	hideMessage();
	return true;
}

function create()
{
	if(!validateInput())
	{
		return;
	}
	
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;
	
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
	{
        if (this.readyState == 4 && this.status == 200)
		{
			if(this.responseText != "true")
			{
				displayMessage("Combination rejected by server: " + this.responseText);
				return;
			}
			displayMessage("User created.");
		}
	};
  xhttp.open("POST", "create_new_user.php", true);
  xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhttp.send("username=" + username + "&password=" + password);
}

function login()
{
	if(!validateInput())
	{
		return;
	}
	
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;
	
	var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
	{
        if (this.readyState == 4 && this.status == 200)
		{
			if(this.responseText != "true")
			{
				displayMessage("Login failed: " + this.responseText);
				return;
			}
			
			current_user = username;
			
			loadConversationPage();
		}
	};
  xhttp.open("POST", "login_existing_user.php", true);
  xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhttp.send("username=" + username + "&password=" + password);
}
