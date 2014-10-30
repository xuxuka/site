define([], function(){
    var Config = {
        "enable" : true,
        "prefix": "notify",
        "timerInterval": 5000,
        "host" : "192.168.0.95",
        "protocol" : "http",
        "key" : "PHPSESSID",
        "node": {
            "protocol" : "http",
            "host": "192.168.0.95",
            "port": 3000,
            "AllowOrigin": "*",
            "listen": "0.0.0.0",
            "retry": 10000
        },
        "redis": {
            "protocol" : "http",
            "host": "192.168.0.95",
            "port": 6379,
            "db": 0,
            "keyLifeTime": 300
        },
        getCookie: function(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1);
                if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
            }
            return "";
        }
    };
    return Config;
});