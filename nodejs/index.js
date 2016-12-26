var fs = require('fs');
var https = require('https');

var app = require('express')();

var privateKey = fs.readFileSync('/root/securesocket.io/domain.key');
var certificate = fs.readFileSync('/root/securesocket.io/ssl.crt');
var ca = fs.readFileSync('/root/securesocket.io/int.crt');

var server = https.createServer({key:privateKey,cert:certificate,ca:ca}, app);

server.listen(1443);

var io = require('socket.io').listen(server);

app.get('/secret_message', function(req, res){
	var url = require('url');
	var url_parts = url.parse(req.url, true);
	var query = url_parts.query;
	var room = req.query.room;
	var message = req.query.message

    	io.sockets.to(""+room).emit('secret_message', {
        	message: message
    	});

    	console.log('secret_message', room, message);

	res.end()
	return

});


io.sockets.on('connection', function(socket){
  console.log("Connection");

	socket.on('subscribe', function(room) {
    		console.log('Joining Room ', room);
    		socket.join(room);
	});
	
});

app.listen(180, function () {});
