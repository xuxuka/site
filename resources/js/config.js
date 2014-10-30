/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 10.09.14
 * Time: 9:58
 * To change this template use File | Settings | File Templates.
 */
require.config({
    baseUrl: '/resources/js/lib/',
    paths: {
        jquery: 'jquery',
        underscore: 'underscore',
        backbone: 'backbone',
        backboneLocalStorage: 'backbone.localStorage',
        json2: 'json2',
        text: 'text',
        bootstrap: 'bootstrap',
        bootstrap_modal: 'bootstrap-modal',
        app: '../app'
    },
    shim : {
        backbone : {
           deps : ['jquery', 'underscore']
        },
        json2 : {
            deps : ['jquery', 'backbone'],
            exports : 'json2'
        },
        'bootstrap': {
            deps: ['jquery']
        },
        'bootstrap_modal': {
            deps: ['jquery']
        }
    },
    waitSeconds : 30
});