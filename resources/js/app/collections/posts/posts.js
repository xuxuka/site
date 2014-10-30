define([
    'jquery',
    'underscore',
    'backbone',
    'backboneLocalStorage',
    '../../models/post',
], function($, _, Backbone, BackboneLocalStorage ,ModelPost){

    var Posts =  Backbone.Collection.extend({
        model: ModelPost,
        mode: null,
        config : ['', '', '', ''],
        url: function() {
	        return this.config[0]+'://'+this.config[1]+':'+this.config[2] + '/api/'+this.config[3]+'/posts';
        },
        initialize: function() {
            this.sortVar = 'created_at';
        },
        comparator: function(model){
            return model.get(this.sortVar);
        },
        parse: function(response) {
            return response;
        },
        setMode: function(mode) {
            this.mode = mode;
        },
        getMode: function() {
            return this.mode;
        },
        setConfig: function(config) {
            this.config = config;
        },
        getConfig: function() {
            return this.config;
        }
    });


    return Posts;
});