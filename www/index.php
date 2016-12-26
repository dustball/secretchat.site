<?php

if (!$_SERVER['HTTPS']) {
  header('Location: https://secretchat.site/');
  exit;
}

?><!doctype html>
<html lang="en">
<head>
  <title>secretchat.site - private online chat</title>
  <meta charset="utf-8">
  <link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    html, body {font-family: 'Droid Sans', sans-serif; }
    .container {margin:3em auto; max-width:440px;}
  </style>
</head>
<body>

  <div class="container">
  
    <h1>secretchat.site</h1>
    
    <p>Private online chat - web &amp; mobile friendly. Free.</p>
    
    <p>All messages encrypted with 128-bit AES encryption.  Everything is realtime and messages are not stored on server. Powered by Socket.IO and node.js. Open Source.</p>
    
    <br>
  
    <table cellspacing=5>
      <tr><td align=right>Secret Room Name: </td><td>&nbsp;</td><td><nobr><input type=text style="width:250px" name="room" placeholder="(at least 8 chars)" id="room" maxlength=150 class="ui-corner-all ui-widget" value=""> &nbsp;<span id="len">&nbsp;</span></nobr></td></tr>
      <tr><td align=right>&nbsp;</td><td>&nbsp;</td><td><button  style="margin-top:.5em" onclick="goroom()" class="ui-button ui-corner-all ui-widget">Go to Secret Room</button> &nbsp;<span id="len2">&nbsp;</span></td></tr>
    </table>
  
  <p><br><br><br>
  
  <p style="cursor:pointer" onclick="$('#react').show('slow');">▼ <i>What do you think of this site?</i></p>
  
  <br>
  
  <div id="react" class="getsocial gs-reaction-button" style="display:none"></div>
  
  </div>

  <p>
  <br>
  <center>
    <a class="github-button" href="https://github.com/dustball/secretchat.site/fork" data-style="mega" aria-label="Fork dustball/secretchat.site on GitHub">Fork</a>
    <br>
    <br>
    <a href="https://github.com/dustball/secretchat.site">github.com/dustball/secretchat.site</a>
    <br>
    <br>Copyright &copy; 2017 secretchat.site
    <br>
  </center>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/sha1.js"></script>
<script>

function goroom() {
  var passphrase = $("#room").val().toLowerCase();
  if (passphrase.length<8) {
    alert("Please enter a room 8 chars or more.");
    ga('send', 'error_short_roomname');
    return; 
  }
  var hash = CryptoJS.SHA1(passphrase);
  
  window.location.href = '/room/' + hash;
}
</script>
<script>
  // production version will not have google analytics
  
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-12748037-19', 'auto');
  ga('send', 'pageview');

</script>
<script type="text/javascript">
(function() { var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true; po.src = '//api.at.getsocial.io/widget/v1/gs_async.js?id=d6ca81'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s); })();
</script>
<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>
