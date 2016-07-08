require('dotenv').load();

var server = require('http').Server();
var io = require('socket.io')(server);
var Redis = require('ioredis');
var redis = new Redis(process.env.REDIS_PORT, process.env.REDIS_HOST);

redis.subscribe('orders');

redis.on('message', function(channel, message) {
  console.log(channel + ':newOrder');
  io.emit(channel + ':newOrder', 'A new order has been placed.');
});

server.listen(process.env.NODEJS_LISTEN_PORT, process.env.NODEJS_LISTEN_IP, function() {
  if (!/^win/.test(process.platform)) {
    process.setgid(process.env.NODEJS_GID);
    process.setuid(process.env.NODEJS_UID);
  }
});
