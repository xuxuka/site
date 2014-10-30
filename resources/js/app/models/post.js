/**
 * Created with JetBrains PhpStorm.
 * User: root
 * Date: 10.09.14
 * Time: 12:38
 * To change this template use File | Settings | File Templates.
 */
define(['backbone'], function(){
    var Post = Backbone.Model.extend({
        idAttribute : 'post_id',
        initialize : function(){
            return;
        }
    });
    return Post;
});
