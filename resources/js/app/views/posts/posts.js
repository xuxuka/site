define([
    'jquery',
    'underscore',
    'backbone',
    './post',
], function($, _, Backbone, ViewPost){

    var Posts = Backbone.View.extend({
        className : 'posts',
        posts : null,
        user_id : null,
        initialize : function(params){
            this.posts = params.posts || null;
            this.user_id = params.user_id || null;
            return this;
        },
        sortEnabled : false,
        render : function(){
            var html = [];

            var filteredCollectionsPost = this.posts.models;

            var self = this;

            _.each(filteredCollectionsPost, function(entity){
                var viewPost = new ViewPost({model : entity, user_id : self.user_id});
                html.push(viewPost.render().el);
            });

            if(!html.length)
                html = '<div style="text-align:center;font-weight:bold;font-size:18px;"></div>';

            $(this.el).html(html);

            return this;
        }
    });

    return Posts;
});
