exports.list = function(req, res, user_id, format){

  req.getConnection(function(err, connection){

      if(user_id){
          var query = 'SELECT * FROM tbl_posts AS tp LEFT JOIN tbl_users AS tu ON tp.user_id = tu.user_id';

          connection.query(query, function(err,rows) {

              if(err)
                  console.log("Error Selecting : %s ",err );

              switch (format) {
                  case 'html' :
                      res.writeHead(200, {
                          "Content-Type": "application/html; charset=utf\-8",
                          "Cache-Control": "no-cache",
                          "Access-Control-Allow-Origin": '*',
                          "Connection": "keep-alive"
                      });
                      res.end(getHtml(rows));
                      //res.render('posts',{page_title:"posts",data:rows});
                      break;
                  default : {
                      res.writeHead(200, {
                          "Content-Type": "application/json; charset=utf\-8",
                          "Cache-Control": "no-cache",
                          "Access-Control-Allow-Origin": '*', //this.config.node.AllowOrigin,
                          "Connection": "keep-alive"
                      });
                      var json = JSON.stringify(rows);
                      res.end(json);
                  }
              }
          });
      }
       
  });
  
};
exports.view = function(req, res, user_id, post_id, format){

    req.getConnection(function(err,connection){

        if(user_id && post_id){

            var query = 'SELECT * FROM tbl_posts AS tp LEFT JOIN tbl_users AS tu ON tp.user_id = tu.user_id WHERE tp.post_id = ' +  post_id + " LIMIT 1";

            connection.query(query, function(err,rows) {
                if(err)
                    console.log("Error Selecting : %s ",err );

                switch (format) {
                    case 'html':
                        res.writeHead(200, {
                            "Content-Type": "application/html; charset=utf\-8",
                            "Cache-Control": "no-cache",
                            "Access-Control-Allow-Origin": '*',
                            "Connection": "keep-alive"
                        });
                        res.end(getHtml(rows));
                        break;
                    default : {
                        res.writeHead(200, {
                            "Content-Type": "application/json",
                            //"Content-Type": "text/event-stream; charset=utf\-8",
                            "Cache-Control": "no-cache",
                            "Access-Control-Allow-Origin": '*', //this.config.node.AllowOrigin,
                            "Connection": "keep-alive"
                        });
                        var json = JSON.stringify(rows);
                        res.end(json);
                    }

                }

            });
        }

    });

};
exports.create = function(req, res, user_id, format){

    req.getConnection(function(err,connection){

        var message = connection.escape(req.body.message).replace(/^\'+|\'+$/gm, '');

        if(user_id && message){

            var post  = {message: message, created_at : 'UNIX_TIMESTAMP(NOW())', user_id: user_id};

            connection.query('INSERT INTO tbl_posts SET ?', post, function(err, result) {

                if(err)
                    console.log("Error Selecting : %s ",err );

                switch (format) {
                    case 'html' :
                        res.writeHead(200, {
                            "Content-Type": "application/html; charset=utf\-8",
                            "Cache-Control": "no-cache",
                            "Access-Control-Allow-Origin": '*',
                            "Connection": "keep-alive"
                        });
                        res.end("Create success");
                        break;
                    default : {
                        res.writeHead(200, {
                            "Content-Type": "application/json; charset=utf\-8",
                            "Cache-Control": "no-cache",
                            "Access-Control-Allow-Origin": '*', //this.config.node.AllowOrigin,
                            "Connection": "keep-alive"
                        });
                        var json = JSON.stringify({});
                        res.end(json);
                    }
                }

            });
        }

    });

};

exports.delete = function(req, res, user_id, post_id, format){

    req.getConnection(function (err, connection) {

        if(user_id && post_id){

            connection.query("DELETE FROM tbl_posts WHERE post_id = ? AND user_id = ? LIMIT 1",[post_id, user_id], function(err, rows){

                if(err)
                    console.log("Error deleting : %s ",err );

                switch (format) {
                    case 'html' :
                        res.writeHead(200, {
                            "Content-Type": "application/html; charset=utf\-8",
                            "Cache-Control": "no-cache",
                            "Access-Control-Allow-Origin": '*',
                            "Connection": "keep-alive"
                        });
                        res.end("<p>Delete success</p>");
                        break;
                    default : {
                        res.writeHead(200, {
                            "Content-Type": "application/json; charset=utf\-8",
                            "Cache-Control": "no-cache",
                            "Access-Control-Allow-Origin": '*', //this.config.node.AllowOrigin,
                            "Connection": "keep-alive"
                        });
                        var json = JSON.stringify({});
                        res.end(json);
                    }
                }

            });
        }
    });

};

exports.update = function(req, res, user_id, post_id, format){

  req.getConnection(function(err, connection){

      var message = connection.escape(req.body.message).replace(/^\'+|\'+$/gm, '');

      if(user_id && post_id && message){

          connection.query('UPDATE tbl_posts SET message = ? WHERE post_id = ? AND user_id = ?',[message, post_id, user_id],function(err,rows) {

              if(err)
                  console.log("Error Selecting : %s ", err);

              switch (format) {
                  case 'html' :
                      res.writeHead(200, {
                          "Content-Type": "application/html; charset=utf\-8",
                          "Cache-Control": "no-cache",
                          "Access-Control-Allow-Origin": '*',
                          "Connection": "keep-alive"
                      });
                      res.end("<p>Update success</p>");
                      break;
                  default : {
                      res.writeHead(200, {
                          "Content-Type": "application/json; charset=utf\-8",
                          "Cache-Control": "no-cache",
                          "Access-Control-Allow-Origin": '*', //this.config.node.AllowOrigin,
                          "Connection": "keep-alive"
                      });
                      var json = JSON.stringify({});
                      res.end(json);
                  }
              }

          });
      }
                 
  });

};

function getHtml(rows){

    var html = '';

    rows.forEach(function(row){
        html += '<div class="post" style="width:530px;">';
        html += '<div class="info">';
        html += '<b>Author:</b><span>'+row.username+'</span><br>';
        html += '<b>Date:</b><span>'+row.created_at+'</span>';
        html += '</div>';
        html += '<div class="message">';
        html += '<span>'+row.message+'</span>';
        html += '</div>';
        html += '<div style="clear:both;"></div>';
        html += '</div>';
    });

    return html;
}