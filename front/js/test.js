// import superagent from "superagent";

// superagent.get('/test/ajaxTest')
//         .set('Accept', 'application/json')
//         .end((err, res) => {

//                 var userList = [];
//                 console.log(res);
              
//                 $.each( res.body, ( key, val ) => {
//                     userList.push("<div>" + val.user_name + "</div>");

//                 });
              
//                 $("#test").html(userList.join(''));
//         });


$.getJSON( "/test/ajaxTest", function( data ) {

  var userList = [];
  
  $.each( data, function( key, val ) {

      console.log(val);
      userList.push("<div>" + val.user_name + "</div>");

  });
  
  $("#test").html(userList.join(''));
});