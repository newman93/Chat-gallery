var send;
$( document ).ready(function() {
 var conn = new WebSocket('ws://localhost:8080');
 conn.onopen = function(e) {
     console.log("Connection established!");
     changeStatus(1);
     getWSContacts("login");
 };

 conn.onmessage = function(e) {
    var data = JSON.parse(e.data);

    switch(data.type) { 
       case "login": 
          handleLogin(data.state); 
          break; 
     /*   
       case "offer": 
          handleOffer(data.fromUsername); 
          break; 
       case "accepted":
          handleAccepted(data.fromUsername);
          break;
       case "answer": 
          handleAnswer(data.fromUsername, data.message); 
          break; 
       case "leave": 
          handleLeave(data.fromUsername); 
          break;
      */
     case "new-login":
          handleNewLogin(data.username);
          break
     case "user-leave":
          //console.log("leave");
          //handleUserLeave(data.username);
          getWSContacts('leave', data.username);
          break;
     case "message":
          console.log("lol");
          handleMessage(data.message, data.time, data.date, data.fromUsername, data.fromName, data.fromSurname);
          break;
    } 
 };
 
 function handleNewLogin(username) {
  var element = $('.user[username="'+username+'"]').children('.user-avatar').children('.status');
  var status = element.attr('class').split(' ')[1];
  if (status != 'online') {
   element.removeClass(status);
   element.addClass('online');
  }
 }
 
 function handleUserLeave(username) {
  var element = $('.user[username="'+username+'"]').children('.user-avatar').children('.status');
  var status = element.attr('class').split(' ')[1];
  if (status != 'offline') {
   element.removeClass(status);
   element.addClass('offline');
  }
 }
 
 function handleMessage(message, time, date, username, name, surname) {
  if (selected_user == username) {
    $("#message").append(createMessage('guest', name, surname, time, date, message));
    scrollMessages();
    updateMessagesStatus(username);
  } else {
   var element = $('.user[username="'+username+'"]');
   var status = element.attr('class').length;
   if (status != 1) {
    element.addClass('to_read');
   }
  }
 }
  
 send = function (message) { 
    conn.send(JSON.stringify(message)); 
 };


 function handleLogin(state) {
  console.log(state);
 }
 
 /*
 function handleOffer(fromUsername) { 
    send({type : 'accepted', toUsername: fromUsername, fromUsername: 'host'});
 };

 function handleAccepted(fromUsername) { 
    console.log("accepeted: "+fromUsername);
 };

 //when we got an answer from a remote user 
 function handleAnswer(fromUsername, answer) { 
    console.log('Message: '+answer+ " from: "+fromUsername); 
 };


 function handleLeave(username) { 
   console.log("leave: " + username);
 };
*/

 window.onbeforeunload = function() {
    // getWSContacts("leave");
     conn.onclose = function () {
      changeStatus(0);
      conn.close();
     }; 
 };
 
 function getWSContacts(type,user) {
  $.ajax({
       type: 'POST',
       url: 'ajax/get-contacts.php', 
       datatype: "json",
       success: function (data1) { 
        	if (type == 'login') {
           send({type: type, username: username, users: data1});
          } else {
           var data = JSON.parse(data1);
           if (!('empty' in data[0])) {
            for(var i = 0; data.length > i; ++i) {
             if (data[i]['username'] == user) {
              handleUserLeave(user);
              break;
             } 
            }
           }
          } 
       }
   });
 };

 function changeStatus(st) {
   $.ajax({
       type: 'POST',
       url: 'ajax/change-status.php',
       data: {status : st}, 
       datatype: "json",
       success: function (data) { 
       }
   });
 }
 
 function updateMessagesStatus(username) {
  $.ajax({
       type: 'POST',
       url: 'ajax/update-messages-status.php',
       data: {username : username}, 
       datatype: "json",
       success: function (data) { 
       }
   });
 }
});



