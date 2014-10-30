/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 10.09.14
 * Time: 9:58
 * To change this template use File | Settings | File Templates.
 */

require.config({waitSeconds : 30});

require(['./config'], function(config){

    require(['./app/app'], function(App){
        App.initialize();
    });

});