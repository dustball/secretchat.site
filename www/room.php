<?php

$salt = $_REQUEST['salt'];
$room = $_REQUEST['room'];

if (strlen($room)!=40 || strlen($salt)!=16) {  
  http_response_code(404);
  print "404 Not Found";
  exit;
}

if (!$_SERVER['HTTPS']) {
  header('Location: https://secretchat.site/room/'.urlencode($room));
  exit;
}

?><!doctype html>
<html>
  <head>
    <title>secretchat.site room</title>
    <style>
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body,td { font: 14px Helvetica, Arial;}
      #sendform { background: #ccc; padding: 8px; position: absolute; bottom: 0; width: 100%; }
      #sendform input { border: 0; padding: 10px; width: 83%; margin-right: .5%; border-radius:4px }
      #sendform button { color:white; width: 15%; background: #F48401; border: none; padding: 10px; border-radius:4px }
      #messages { list-style-type: none; margin: 0; padding: 0; overflow-x:scroll; height:100%; width:100%;padding-bottom:3em }
      #messages li { padding: 5px 10px; }
      #messages li:nth-child(odd) { background: #eee; }
      #container,body,html {height:100% !important}

      #container {width:800px; margin:0 3px}
      @media (max-width: 799px) {
        #container {width:634px;}
      }        
      @media (max-width: 639px) {
        #container {width:494px;}
      }        
      @media (max-width: 399px) {
        #container {width:314px; border:none !important}
      }

    </style>
    <meta charset="utf-8">
    <meta http-equiv="content-language" content="en">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" integrity="sha384-12hbHS5VUYVLOm/mmt5zrO3NnhEuXiIwdj3TMACB//xJmJi1lS9lIS89Hwp4E972" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/sha256.js" integrity="sha384-qZaSe/P6nv2mOz9Nu4R/FYkRKUKCp6jqy5bzVdNJ7/HA/26eOigjvRhgmKpXiKow" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://rawgit.com/ricmoo/aes-js/master/index.js" integrity="sha384-M/c10E44f5lGATHNjTNLhT7QZJZGEZOAE1xwgGtJaoZSVX6ME7BjU9yv2gUo4i5N" crossorigin="anonymous"></script>

  
    <meta name="robots" content="noindex,noarchive">
    <meta name="viewport" content="width=device-width, initial-scale=1">

  </head>
  <body>
    <div style=" margin:0 auto; border:1px dotted #ccc;  position:relative" id="container">
    <div id="login" style="z-index:2; position:absolute; left:0; right:0; top:0; bottom:0; background:white">
    <br><br>
    <h2 style="margin-left:12px">🔒 secretchat.site - join room</h2>
    <br>
    <form onsubmit="return login();">
    <table cellspacing=5 width=100% align=center>
      <tr><td align=right>Password: </td><td>&nbsp;</td><td><nobr><input type=password style="width:170px" name="room" placeholder="(secret room name)" id="room" maxlength=150 class="ui-corner-all ui-widget" value=""> &nbsp;<span id="len">&nbsp;</span></nobr></td></tr>
      <tr><td align=right>Handle: </td><td>&nbsp;</td><td><nobr><input type=text style="width:170px" name="handle" placeholder="(your name)" id="handle" maxlength=150 class="ui-corner-all ui-widget" value=""> &nbsp;<span id="len">&nbsp;</span></nobr></td></tr>
      <tr><td align=right>&nbsp;</td><td>&nbsp;</td><td><button  style="margin-top:.5em" onclick="return login()" class="ui-button ui-corner-all ui-widget">Join Room</button> &nbsp;<span id="len2">&nbsp;</span></td></tr>
    </table>
    </form>

    <br><br>
    <p style="margin:1em">Share this room via URL:<br><br><input id="shareurl" class="ui-corner-all ui-widget" type="text" value="https://secretchat.site/room/<?php echo htmlspecialchars($salt); ?>/<?php echo htmlspecialchars($room); ?>" style="width:50%; color:#666"><br><br><button class="ui-button ui-corner-all ui-widget" onclick='copyToClipboard(document.getElementById("shareurl"));'>Copy to clipboard</button></p>
    
    </div>
    <div id="connected" style="position:absolute;right:3px;top:5px"></div>
    
      <ul id="messages"></ul>
      <form id=sendform action="">
        <input id="m" autocomplete="off" /> <button>Send</button>
      </form>
    
    </div>
    
    <script src="https://cdn.socket.io/socket.io-1.2.0.js" integrity="sha384-oGza066+khylclVXEZ3hxALnzokmv86IwCZG1nl05HZmD+9apNXFYb0ovTNiVe4P" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.11.1.js" integrity="sha384-wciR07FV6RBcI+YEVsZy/bInxpyn0uefUp6Yi9R5r46Qv/yk/osR5nzY31koh9Uq" crossorigin="anonymous"></script>
    <script>
      var socket = io('https://securesocket.io:8443/');

      $('form').submit(function(){
        send_message();
        return false;
      });
      
      socket.on('secret_message', function(msg){
        ivt = msg.message.substr(0,16);
        bytes = msg.message.substr(16);
        // console.log("incoming ivt: %s",ivt);
        // console.log("bytes: %s",bytes);        
        decrypt(ivt,bytes);
      });
      
      socket.emit('subscribe', "<?php echo htmlspecialchars($room); ?>");
      
      function send_message() {
        encrypt();
        ga('send', 'pageview', 'send_message');        
      }
      
      $('#messages').append($('<li>').text("Encryption connected with 128 8-bit TLS."));      
      $('#messages').append($('<li>').text("Welcome to secretchat.site. This page is mobile-friendly."));
      $('#messages').append($('<li>').text("Note: Messages are NOT stored on server.  Closing this window will clear all record of this conversation."));
      $('#messages').append($('<li>').text("Note: You may invite others using the URL of this page. They will need to know the room name (password)."));
      
      setInterval("update_timer()",1000);
      
      function update_timer() {
        var onl = navigator.onLine && socket && socket.connected;
        if (onl!=window.lastonl) {
          if (onl) {
            $('#messages').append($('<li>').text('You are now ONLINE.'));
          } else {
            $('#messages').append($('<li>').text('You have been disconnected. You will miss any messages while offline.'));
          }            
        }
        window.lastonl = onl;
        if (onl) {
          $("#connected").html("<div style='display:inline-block; background:#3ba770; border-radius: 8px; width:12px; height:12px; margin-right:6px'><div>");    
        } else {
          $("#connected").html("<div style='display:inline-block; background:#a52311; border-radius: 8px; width:12px; height:12px; margin-right:6px'><div>");    
        }  
      }    
      
      function login() {
        if (window.passphrase) {
          return false;
        }
        var passphrase = $("#room").val().toLowerCase().trim();
        if (passphrase.length<8) {
          alert("Please enter a room 8 chars or more.");
          ga('send', 'error_short_roomname');
          return false;
        }
        var handle = $("#handle").val();
        if (handle.length<1) {
          alert("Please enter a handle.");
          ga('send', 'error_short_handle');
          return false;
        }
        var salt = "<?php echo htmlspecialchars($salt); ?>";
        var hash = CryptoJS.SHA256(salt + passphrase).toString().substr(10,40);
        if (hash!="<?php echo htmlspecialchars($room); ?>") {
          alert("The password must match the secret room name.");
          return false; 
        }
        window.passphrase = passphrase;
        window.handle = handle;
        $('#login').hide("slow");
        
        $('#m').val(handle+" has entered the room.")
        encrypt(true);

        $('#m').focus(); 
        return false;
      }
      
       
      function encrypt(ex_handle) {
        var plaintext;
        
        if (ex_handle) {
          plaintext = $('#m').val();
        } else {
          plaintext = window.handle + ': ' + $('#m').val();          
        }
        var passphrase = window.passphrase;
        if (plaintext.length<1) {
          return; 
        }
        if (plaintext.length>2000) {
          $.alert("Length of "+plaintext.length+" exceeds 2000 character limit.", "Message Too Large");
          return; 
        }
        passphrase = passphrase.substr(0,32);
        var padding = Array(33).join('x');
        passphrase = pad(padding,passphrase,true);
        var text = plaintext;
        if (text.length<64) {
          padding = Array(64).join(' ');
          var text = pad(padding,plaintext,false);
        }
        
        // From https://github.com/ricmoo/aes-js
        var ivt = ""+make_iv();
        var key = aesjs.util.convertStringToBytes(passphrase);
        var iv = aesjs.util.convertStringToBytes(ivt);
        var textBytes = aesjs.util.convertStringToBytes(text);
        var aesOfb = new aesjs.ModeOfOperation.ofb(key, iv);
        var encryptedBytes = aesOfb.encrypt(textBytes);   
        encrypted = JSON.stringify(encryptedBytes); 
      
        // console.log("outgoing ivt: %s",ivt);
        // console.log("encrypted: %o",encrypted);
      
        socket.emit('secret_message', "<?php echo htmlspecialchars($room); ?>",  ivt + encrypted );
        $('#m').val('');
          
      }
      
      function decrypt(ivt,encrypted) {
        if (!window.passphrase) {
          return false;
        }        
        var passphrase = window.passphrase;
        passphrase = passphrase.substr(0,32);
        var padding = Array(33).join('x');
        passphrase = pad(padding,passphrase,true);
        var key = aesjs.util.convertStringToBytes(passphrase);
        var iv = aesjs.util.convertStringToBytes(ivt);
        var aesOfb = new aesjs.ModeOfOperation.ofb(key, iv);                    
        var bytes = JSON.parse(encrypted);
        var decryptedBytes = aesOfb.decrypt(bytes);
        var result = aesjs.util.convertBytesToString(decryptedBytes);
        var result = result.substr(0,result.length-1);
        $('#messages').append($('<li>').text(result));
        $("#messages").scrollTop($("#messages")[0].scrollHeight);
      }                  
      
      function pad(pad, str, padLeft) {
        if (typeof str === 'undefined') 
          return pad;
        if (padLeft) {
          return (pad + str).slice(-pad.length);
        } else {
          return (str + pad).substring(0, pad.length);
        }
      }
      
      function make_iv() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for( var i=0; i < 16; i++ )
          text += possible.charAt(window.crypto.getRandomValues(new Uint32Array(1))[0] % possible.length);
        return text;
      }   

      function copyToClipboard(elem) {
      	  // create hidden text element, if it doesn't already exist
          var targetId = "_hiddenCopyText_";
          var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
          var origSelectionStart, origSelectionEnd;
          if (isInput) {
              // can just use the original source element for the selection and copy
              target = elem;
              origSelectionStart = elem.selectionStart;
              origSelectionEnd = elem.selectionEnd;
          } else {
              // must use a temporary form element for the selection and copy
              target = document.getElementById(targetId);
              if (!target) {
                  var target = document.createElement("textarea");
                  target.style.position = "absolute";
                  target.style.left = "-9999px";
                  target.style.top = "0";
                  target.id = targetId;
                  document.body.appendChild(target);
              }
              target.textContent = elem.textContent;
          }
          // select the content
          var currentFocus = document.activeElement;
          target.focus();
          target.setSelectionRange(0, target.value.length);
          
          // copy the selection
          var succeed;
          try {
          	  succeed = document.execCommand("copy");
          } catch(e) {
              succeed = false;
          }
          // restore original focus
          if (currentFocus && typeof currentFocus.focus === "function") {
              currentFocus.focus();
          }
          
          if (isInput) {
              // restore prior selection
              elem.setSelectionRange(origSelectionStart, origSelectionEnd);
          } else {
              // clear temporary content
              target.textContent = "";
          }
          return succeed;
      }

      $(function(){
          $("#room").focus();
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
