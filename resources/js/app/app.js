define([
    '../app/routers/router',
    './collections/posts/posts',
    './config'
], function(Router, CollectionPosts, Config){

    var Application = {
        Models : {},
        Collections : {},
        Views : {},
        Routers : {}
    };

    var initialize = function(){

        var config = Config;

        var Posts = new CollectionPosts();

        Posts.setConfig([config.node.protocol, config.node.host, config.node.port, config.getCookie(config.key)]);

        Posts.fetch().done(function(){

            Posts.setMode('success');

            Application.Routers.Router = Router.initialize(Posts, config);

            //window.app = Application;

        }).error(function(e){

            Posts.setMode('error');
        });

    };

    return {
        initialize: initialize
    };

});
