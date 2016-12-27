var fs = require('fs');
var https = require('https');

var app = require('express')();

var privateKey = fs.readFileSync('domain.key');
var certificate = fs.readFileSync('ssl.crt');
var ca = fs.readFileSync('intermediate.crt');

var server = https.createServer({key:privateKey,cert:certificate,ca:ca}, app);

server.listen(1443);

var io = require('socket.io').listen(server);

io.sockets.on('connection', function(socket){
	
  	console.log("Connection");

	socket.on('subscribe', function(room) {
    		socket.join(room);
	});

	socket.on('secret_message', function(room,msg){
		io.sockets.to(''+room).emit('secret_message', {
        	message: msg
    	});		
		
});

app.listen(180, function () {});
