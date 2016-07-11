require('dotenv').load();

var server = require('http').Server();
var io = require('socket.io')(server);
var Redis = require('ioredis');
var redis = new Redis(process.env.REDIS_PORT, process.env.REDIS_HOST);

redis.subscribe('orders');

var connections = [];

server.listen(process.env.NODEJS_LISTEN_PORT, function() {
  if (!/^win/.test(process.platform)) {
    process.setgid(process.env.NODEJS_GID);
    process.setuid(process.env.NODEJS_UID);
  }
});

io.sockets.on('connection', function(socket) {

  socket.once('disconnect', function() {
    connections.splice(connections.indexOf(socket), 1);
    socket.disconnect();
    console.log("Disconnected: %s sockets remaining.", connections.length);
  });

  connections.push(socket);
  console.log("Connected: %s sockets connected.", connections.length);

  redis.on('message', function(channel, message) {
    io.emit('orders:new_order');
  });
});

console.log("WebSockets server is running at 'http://127.0.0.1:3000'");
