define([
    'jquery',
    'underscore',
    'backbone',
    'bootstrap',
    'bootstrap_modal',
    '../collections/posts/posts',
    '../views/posts/app',
    '../models/post',
], function($, _, Backbone, Bootstrap, BootstrapModal ,CollectionsPosts, AppPosts, ModelPost){

    var AppRouter = Backbone.Router.extend({
        filters:[],
        views : {},
        posts : null,
        config : null,
        post_id: null,
        action: null,
        initialize : function(posts, config){

            this.posts = posts || this.posts;
            this.config = config;
            this.posts.setConfig([this.config.node.protocol, this.config.node.host, this.config.node.port, this.config.getCookie(this.config.key)]);
            //console.log(this.config.key, this.config.getCookie(this.config.key));
            var self = this;

            $(document).on('submit','.postsForm form',function(e){
                console.log(e);

                var modal_body = $('#myModal .modal-body'), modal_header = $('#myModal .modal-header h3');
                $('.btn.btn-primary.save').css('display', '');
                $('.btn.btn-primary.remove').css('display', 'none');

                var message = $('.postsForm form textarea').val();

                self.action = "save";

                if(message){
                    modal_header.html("save");
                    modal_body.html(message);
                    $("#myModal").modal({ // wire up the actual modal functionality and show the dialog
                        "backdrop" : "static",
                        "keyboard" : true,
                        "show" : true // ensure the modal is shown immediately
                    });
                }

                e.stopPropagation();
                e.preventDefault();
                //self.navigate("posts", {trigger: true, replace: true} );

            });

            $(document).on('click','.action.btn',function(e){
                var action = $(this).data('action');
                var post_id = $(this).data('id');
                var post = self.posts.where({post_id : post_id});
                var message = post[0].get('message');
                var modal_body = $('#myModal .modal-body'), modal_header = $('#myModal .modal-header h3');
                var btn_save = $('.btn.btn-primary.save');
                var btn_remove = $('.btn.btn-primary.remove');
                btn_save.css('display', 'none');
                btn_remove.css('display', 'none');
                modal_header.html('');
                modal_body.html('');
                self.post_id = post_id;
                self.action = action;
                switch(action){
                    case 'view' :
                        $url = self.config.node.protocol+'://'+self.config.node.host+':'+self.config.node.port + '/api/'+self.config.getCookie(self.config.key)+'/posts/'+self.post_id+'.html';
                        $.ajax({
                            dataType: "html",
                            url: $url,
                            success: function(data){
                                modal_body.html(data);
                            },
                            error: function(e){
                                console.log(e);
                            }
                        });
                        modal_header.html(action);
                        modal_body.html(message);
                        break;
                    case 'edit' :
                        btn_save.css('display', '');
                        var input = document.createElement('TEXTAREA');
                        input.setAttribute('name', 'post');
                        input.style.width = '520px';
                        input.style.height = '100px';
                        input.value = message;
                        modal_header.html(action);
                        modal_body.html(input);
                        break;
                    case 'delete' :
                        btn_remove.css('display', '');
                        modal_header.html(action);
                        modal_body.html(message);
                        break;
                }

                $("#myModal").modal({ // wire up the actual modal functionality and show the dialog
                    "backdrop" : "static",
                    "keyboard" : true,
                    "show" : true // ensure the modal is shown immediately
                });

                e.stopPropagation();
                e.preventDefault();
                //self.navigate("posts", {trigger: true, replace: true} );

            });


            $(document).on('click', '.btn.btn-primary.save', function(e){

                switch(self.action){
                    case 'save':

                        var message = $('.postsForm form textarea').val();
                        var post = new ModelPost({'message' : message});

                        post.url = self.config.node.protocol+'://'+self.config.node.host+':'+self.config.node.port + '/api/'+self.config.getCookie(self.config.key)+'/posts';
                        post.save().done(function(res){
                            console.log('ok_save', res);
                            self.posts.fetch().done(function(res){
                                self.showPosts();
                            }).error(function(e){
                                console.log(e);
                            });

                        }).error(function(e){
                            console.log(e);
                        });
                        break;
                    case 'edit':
                        if(self.post_id){
                            var message = $('#myModal .modal-body textarea').val();
                            var post = self.posts.where({post_id : self.post_id});
                            post[0].set({'message' : message});
                            post[0].save().done(function(res){
                                console.log('ok_edit', res);
                                self.showPosts();

                            }).error(function(e){
                                console.log(e);
                            });
                        }
                        break;
                }

                $("#myModal").modal('hide');
                $(".modal-backdrop.fade.in").remove();
                self.action = self.post_id = null;

                self.showPosts();

            });

            $(document).on('click', '.btn.btn-primary.remove', function(e){

                if(self.action == 'delete' && self.post_id){

                    var post = self.posts.where({post_id : self.post_id});

                    post[0].destroy().done(function(res){
                        self.showPosts();
                    }).error(function(e){
                        console.log('error', e);
                    });
                }

                $("#myModal").modal('hide');
                $(".modal-backdrop.fade.in").remove();
                self.action = self.post_id = null;

            });

            Backbone.history.start({pushState: true, root: "/"});

        },
        routes: {
            '' : 'showPosts',
            'posts' : 'showPosts',
            'posts/:id' : 'showPost'
        },
        showPosts : function(){
            if(!this.views['AppPosts']) {
                var posts = this.posts;
                this.views['AppPosts'] = new AppPosts({mode :'show', posts : posts, user_id : this.config.getCookie("user_id")});
            } else {
                this.views['AppPosts'].setPosts(posts);
                this.views['AppPosts'].setMode('show');
                this.views['AppPosts'].render();
            }
        },
        showPost : function(id){
            console.log('showPost');
        },
        getConfig : function (){
            return this.config;
        },
        loadConfig : function(){
                this.config = require('../config.js');
        }
    });

    var initialize = function(posts, config){
        return new AppRouter(posts, config);
    };

    return {
        initialize: initialize
    };
});
