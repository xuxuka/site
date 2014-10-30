define([
    'jquery',
    'underscore',
    'backbone',
    '../posts/app',
    'text!/resources/templates/posts/post.html',
], function($, _, Backbone, ViewPost, TemplatePost){

    var Post =  Backbone.View.extend({
        className : 'post fastTransition',
        template : _.template(TemplatePost),
        user_id : null,
        events : {},
        initialize : function(params){
            this.model = params.model || null;
            this.user_id = params.user_id || null;
            this.model.on('change', this.render, this);
            return this;
        },
        render : function(){
            this.$el.html(this.template({data : this.model.toJSON(), user_id : this.user_id}));
            return this;
        }
    });

    return Post;

});
