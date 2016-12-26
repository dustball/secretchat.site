<?php

$room = $_REQUEST['room'];

if (strlen($room)!=40) {
  
  print "404 Not Found";
  exit;
}

?><!doctype html>
<html>
  <head>
    <title>secretchat.site room</title>
    <style>
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body { font: 14px Helvetica, Arial;}
      form { background: #000; padding: 3px; position: absolute; bottom: 0; width: 100%; }
      form input { border: 0; padding: 10px; width: 84%; margin-right: .5%; border-radius:4px }
      form button { width: 13%; background: rgb(130, 224, 255); border: none; padding: 10px; border-radius:4px }
      #messages { list-style-type: none; margin: 0; padding: 0; overflow-x:scroll; height:100%; width:100%;padding-bottom:3em }
      #messages li { padding: 5px 10px; }
      #messages li:nth-child(odd) { background: #eee; }
      #container,body,html {height:100% !important}
    </style>
    <meta charset="utf-8">
    <meta http-equiv="content-language" content="en">
    <meta name="robots" content="noindex,noarchive">
    <meta name="viewport" content="width=device-width, initial-scale=1">

  </head>
  <body>
    <div style="width:400px; margin:0 auto; border:1px dotted #ccc;  position:relative" id="container">
    
      <ul id="messages"></ul>
      <form action="">
        <input id="m" autocomplete="off" /> <button>Send</button>
      </form>
    
    </div>
    
    <script src="https://cdn.socket.io/socket.io-1.2.0.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.1.js"></script>
    <script>
      var socket = io('https://securesocket.io:1443/');
      
      $('form').submit(function(){
        send_message();
        return false;
      });
      
      socket.on('secret_message', function(msg){
        $('#messages').append($('<li>').text(msg.message));
        $("#messages").scrollTop($("#messages")[0].scrollHeight);
      });
      
      socket.emit('subscribe', "<?php echo htmlspecialchars($room); ?>");
      
      function send_message() {
        $.get( "/send", { 
            room: "<?php echo htmlspecialchars($room); ?>",
            message: $('#m').val()
        }).done(function( json ) {
          $('#m').val('');
        }).fail(function( jqxhr, textStatus, error ) {
            var err = textStatus + ", " + error;
            console.log( "Request Failed: " + err );      
        });  
        ga('send', 'pageview', 'send_message');
      }
      
      $('#messages').append($('<li>').text("Welcome to secretchat.site. This page is mobile-friendly."));
      $('#messages').append($('<li>').text("Encryption connected: 128 8-bit TLS"));      
      $('#messages').append($('<li>').text("Welcome. Messages are NOT stored on server.  Close this window to clear all record of this conversation."));
      $('#messages').append($('<li>').text("Note: you may invite others using the URL of this page."));
      
      
      $.get( "/send", { 
            room: "<?php echo htmlspecialchars($room); ?>",
            message: "A participant has entered the room."
        }).done(function( json ) {
          $('#m').val('');
        }).fail(function( jqxhr, textStatus, error ) {
            var err = textStatus + ", " + error;
            console.log( "Request Failed: " + err );      
        });       
    </script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    
      ga('create', 'UA-12748037-19', 'auto');
      ga('send', 'pageview');
    
    </script>    
  </body>
</html>