var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');
var port = 8008;
server.listen(port,'205.134.254.135');

io.on('connection', function (socket) {
 
  console.log("connected");
  var redisClient = redis.createClient();
  redisClient.subscribe('message');
 
  redisClient.on("message", function(channel, data) {
    socket.emit(channel, data);
  });
 
  socket.on('disconnect', function() {
    redisClient.quit();
  });
 
});