define([
    'jquery',
    'underscore',
    'backbone',
    './posts',
    'text!/resources/templates/posts/index.html',
], function($, _, Backbone, ViewPosts, TemplatePosts){

    var AppPosts = Backbone.View.extend({

        el: $('#appContent'),
        template : _.template(TemplatePosts),
        view : {},
        courses : null,
        filters : null,
        content : null,
        mode : null,
        user_id : null,
        events : {
        },
        initialize : function(params){

            this.mode = params.mode || this.mode;
            this.posts = params.posts || this.posts;
            this.user_id = params.user_id || null;

            var self = this;

            if(self.mode === 'show')
                self.render();

        },
        setMode : function(mode) {
            this.mode = mode;
        },
        getMode: function() {
            return this.mode;
        },
        setPosts: function(posts) {
            this.posts = posts || this.posts;
        },
        getPosts: function() {
            return this.posts;
        },
        renderPostsList : function(){
            if(!this.view['ViewPosts'])
                this.view['ViewPosts'] = new ViewPosts({posts : this.posts, user_id : this.user_id});

            this.$(".content .courseWrap .container").html(this.view['ViewPosts'].render().el);
        },
        render : function(){
            if(this.mode === 'show') {

                this.$el.html(this.template());

                this.renderPostsList();

            }

        }

    });

    return AppPosts;
});