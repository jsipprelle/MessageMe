<!doctype html>
<html>

  <head>
    <meta charset="utf-8">
    <title>MessageMe</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
  </head>

  <body>
    <div id="loginDiv">
        <header><h1 class="msg">Message</h1><h1 class="me">Me</h1><br></header>
        <form action="/login.php" method="post">
	      <input type="text" class="txt" name="username" id="username" value=""><br>
	      <input type="password" class="txt" name="password" id="password" value=""><br>
		  <div id="errorMessage"></div>
		  <div id="buttonsDiv">
	        <input type="button" class="submitbutton" value="New" onclick="create();">
	        <input type="button" class="submitbutton" value="Login" onclick="login();" style="position: absolute; left: 55%;">
	      </div>
        </form>
	  </div>
	<div id="messageDiv">
	  <div id="messageList">
		  <div class="newConversation" onClick="newConversation();">Create new conversation</div>
      </div>
	  <div id="currentMessage">
		<div id="currentConversation"></div>
		<div id="sendMessagePanel" style="display: none;">
		  <input type="text" name="messageText" id="messageText">
		  <input type="button" onclick="sendMessage()" id="messageSendButton" value="Send">
		</div>
	  </div>
	</div>
	<script>
	mainEntry();
	</script>
  </body>
</html>
