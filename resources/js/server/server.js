var express = require('express');
var app = express.createServer();
var api = require('./lib/api');
var connection  = require('express-myconnection');
var mysql = require('mysql');
var redis = require("redis");
var bodyParser = require('body-parser');
var settings = require('./config.json');
var redisClient = redis.createClient(
        settings.redis.port,
        settings.redis.host,
        {no_ready_check: true});

if (settings.redis.password){
    client.auth(settings.redis.password, function() {
        console.log('Redis client connected');
    });
}

var allowCrossDomain = function(req, res, next) {
    res.header('Access-Control-Allow-Origin', '*');
    res.header('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS');
    res.header('Access-Control-Allow-Headers', 'Content-Type, Authorization, Content-Length, X-Requested-With');

    // intercept OPTIONS method
    if ('OPTIONS' == req.method) {
        res.send(200);
    }
    else {
        next();
    }
};

app.use(express.methodOverride());

app.use(allowCrossDomain);

app.use(express.cookieParser());
app.use(express.session({secret: 'foul5f793iqbmgb9ei5tv2a3k7'}));

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({extended: true}));

app.use(
    connection(mysql,{
        host: settings.db.host,
        user: settings.db.user,
        password : settings.db.password,
        port : settings.db.port, //port mysql
        database:settings.db.database,
        connectTimeout: settings.db.connectTimeout
    },'request')
);

app.listen(settings.node.port, function(){
    console.log('listening on *: ' + settings.node.port + ' Port : ');
});

// List
app.get('/api/:hash/posts', function(req, res) {
    redisClient.get('session:php:id:' + req.params.hash, function(err, result){
        if(err)
            console.log(err);
        var user_id = parseInt(result);
        var format = 'json';
        api.list(req, res, user_id, format);
    });
});
app.get('/api/:hash/posts.:format', function(req, res) {
    redisClient.get('session:php:id:' + req.params.hash, function(err, result){
        if(err)
            console.log(err);
        var user_id = parseInt(result);
        api.list(req, res, user_id, req.params.format);
    });
});

// Create
app.post('/api/:hash/posts',  function(req, res) {
    redisClient.get('session:php:id:' + req.params.hash, function(err, result){
        if(err)
            console.log(err);
        var user_id = parseInt(result);
        var format = 'json';
        api.create(req, res, user_id, format);
    });
});
app.post('/api/:hash/posts.:format',  function(req, res) {
    redisClient.get('session:php:id:' + req.params.hash, function(err, result){
        if(err)
            console.log(err);
        var user_id = parseInt(result);
        api.create(req, res, user_id, req.params.format);
    });
});

// View
app.get('/api/:hash/posts/:id',  function(req, res) {
    redisClient.get('session:php:id:' + req.params.hash, function(err, result){
        if(err)
            console.log(err);
        var user_id = parseInt(result);
        var param =  getFormat(req.params.id);
        api.view(req, res, user_id, parseInt(param[0]), param[1]);
    });
});

// Update
app.put('/api/:hash/posts/:id', function(req, res) {
    redisClient.get('session:php:id:' + req.params.hash, function(err, result){
        if(err)
            console.log(err);
        var user_id = parseInt(result);
        var param =  getFormat(req.params.id);
        api.update(req, res, user_id, parseInt(param[0]), param[1]);
    });
});

// Delete
app.del('/api/:hash/posts/:id', function(req, res) {
    redisClient.get('session:php:id:' + req.params.hash, function(err, result){
        if(err)
            console.log(err);
        var user_id = parseInt(result);
        var param =  getFormat(req.params.id);
        api.delete(req, res, user_id, parseInt(param[0]), param[1]);
    });
});

function getFormat(param){

    var position = param.indexOf(".");

    if(position == -1)
        return [param, "json"];

    return [param.slice(0, position), param.slice(position+1, param.length)];

}