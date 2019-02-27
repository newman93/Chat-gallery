var createMessage;
var selected_user;
var username;
var  scrollMessages;
$( document ).ready(function() {
 selected_user = "";
 username = "";
 
 scrollMessages = function() {
  if ($("#message").length > 0) {
   $("#message").scrollTop($("#message")[0].scrollHeight);
  }
 }
 
 function resetSelectedUser() {
  selected_user = "";
  $(document).find(".active_user").removeClass("active_user");
 }

 function loadContacts() {
		$.ajax({
			type: 'POST',
			url: 'ajax/load-contacts.php',
			datatype: "json",
			success: function (data) { 
				data = JSON.parse(data);
				$("#my-users").empty();	
				if (!('empty' in data[0])) {
           for (var i = 0; i < data.length; ++i) {
            $("#my-users").append('\
							<div class="user '+data[i]['to_read']+'" username='+data[i]['username']+'>\
								<div class="user-avatar">\
									<img src="'+data[i]['avatar']+'" class="rounded-circle border-2">\
									<div class="status '+data[i]['active']+'">\
									</div>\
								</div>\
								<div  class="user-name">\
									<div class="user-description1">\
										<span>'+data[i]['name']+' '+data[i]['surname']+'</span>\
									</div>\
								</div>\
							</div>');
					}
				} 
			}
		});
	}
	
	
	function checkInvitations() {
		$.ajax({
			type: 'POST',
			url: 'ajax/waiting-invitations.php',
			datatype: "json",
			success: function (data) { 
				data = JSON.parse(data);		
				if (!('empty' in data[0])) {
					$("#add").removeClass("btn-default");
					$("#add").addClass("btn-primary");
				}
			}
		});
	}
	
	loadContacts();
	checkInvitations();
	scrollMessages();
  
  createMessage = function (author, name, surname, time, date, message) {
    return (author == "guest" || author == "sender")? 
                       `<div class="main-message">
                         <div class="message-user `+author+`">
                           <span class="message-user-name">`+name+` `+surname+`</span>
                           <span class="message-user-date"><small>`+time+` `+date+`</small></span>
                         </div>
                         <div class="message `+author+`-message">
                           <span>`+message+`</span>
                         </div>
                       </div>`:
                       "";
  };
  
	$(document).on("submit", "#search-contact", function (event) {
		event.preventDefault();
		var user = $('#search-contact input[name="user"]').val().trim();
		var split_user = user.split(" ");
		
		if (split_user.length > 2 || user == "") {
			bootbox.alert('Niepoprawny format danych!<br /> Wpisz: <br /> Imię Nazwisko lub Nazwisko Imię lub Imię lub Nazwisko');

		} else {
			$.ajax({
				type: 'POST',
				data: {
					user: user,
				},
				url: 'ajax/search-contact.php',
				datatype: "json",
				success: function (data) { 
					data = JSON.parse(data);
					
					$("#my-users").empty();	
					
					if (!('empty' in data[0])) {
						for (var i = 0; i < data.length; ++i) {							
							$("#my-users").append('\
								<div class="user" username="'+data[i]['username']+'">\
									<div class="user-avatar">\
										<img src="'+data[i]['avatar']+'" class="rounded-circle border-2">\
										<div class="status '+data[i]['active']+'">\
									</div>\
									</div>\
									<div  class="user-name">\
										<div class="user-description1">\
											<span>'+data[i]['name']+' '+data[i]['surname']+'</span>\
										</div>\
									</div>\
								</div>');
						}
					} 
				}
			});
		}
	});
	
	/*
	$(window).resize(function() {
		if ($(window).width() > 767) {
			$("#messages").css("display", "block");
			$("#users").css("display", "block");
		} else {
			$("#users").css("display", "block");
			$("#messages").css("display", "none");
		}
	}); */
	
	$(document).on("click touch", "#show-contacts", function () {
		$("#search-contact input").val("");
		loadContacts();
	});
	
	$(document).on("click touch", "#users-content img", function () {
		window.location.href = "index.php";
	});
	
	$(document).on("click touch", "#back-users", function () {
		resetSelectedUser();
    $("#messages").css("display", "none");
		$("#users").css("display", "block");
	});
	
	$(document).on("click touch", "#settings", function () {
	 resetSelectedUser();
   if ($("#messages").css("display") == "none") {
			$("#users").css("display", "none");
			$("#messages").css("display", "block");		
		}
		
		$("#messages").empty();
		$("#messages").append(' \
			<div id="edit-settings-label">\
				<div id="edit-settings-label-button">\
					<button class="btn btn-back" type="button" id="back-users"><i class="fas fa-arrow-left" aria-hidden="true"></i></button>\
				</div>\
				<div  id="edit-settings-label-name">\
					<div id="edit-settings-description">\
						<span>Edytuj</span>\
					</div>\
				</div>\
				<div class="line">\
				</div>\
			</div>\
			<div id="edit-settings">\
				<div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">\
					<form  method="POST" action="" id="logout">\
						<button class="btn btn-lg btn-primary btn-block" type="submit">Wyloguj się</button>\
					</form>\
					<h5>Zmień avatar</h6>\
					<form  method="POST" action="" enctype="multipart/form-data" id="change-avatar">\
						<div class="form-group center-avatar">\
						</div>\
						<div class="form-group">\
							<input type="file" name="avatar-file" id="avatar-file"  accept="image/x-png,image/gif,image/jpeg" required>\
							<small id="avatar-hint" class="form-text text-muted">Preferowany rozmiar to 100x100</small>\
						</div>\
						<button class="btn btn-lg btn-primary btn-block" type="submit">Zapisz</button>\
					</form>\
					<h5>Zmień imię i nazwisko</h5>\
					<form  method="POST" action="" id="change-name">\
						<div class="form-group">\
							<div class="input-group">\
								<div class="input-group-prepend">\
									<div class="input-group-text">\
										<i class="fas fa-user-tag"></i>\
									</div>\
								</div>\
								<input type="name" id="name" name="name" class="form-control" placeholder="Imię" required>\
							</div>\
						</div>\
						<div class="form-group">\
							<div class="input-group">\
								<div class="input-group-prepend">\
									<div class="input-group-text">\
										<i class="fas fa-user-tag"></i>\
									</div>\
								</div>\
								<input type="surname" id="surname" name="surname" class="form-control" placeholder="Nazwisko" required>\
							</div>\
						</div>\
						<button class="btn btn-lg btn-primary btn-block" type="submit">Zapisz</button>\
					</form>\
					<h5>Zmień e-mail</h5>\
					<form  method="POST" action="" id="change-email">\
						<div class="form-group">\
							<div class="input-group">\
								<div class="input-group-prepend">\
									<div class="input-group-text">\
										<i class="fas fa-envelope"></i>\
									</div>\
								</div>\
								<input type="email" id="email" name="email" class="form-control" placeholder="E-mail" required>\
							</div>\
						</div>\
						<button class="btn btn-lg btn-primary btn-block" type="submit">Zapisz</button>\
					</form>\
					<h5>Zmień hasło</h5>\
					<form  method="POST" action="" id="change-password">\
						<div class="form-group">\
							<div class="input-group">\
								<div class="input-group-prepend">\
									<div class="input-group-text">\
										<i class="fas fa-unlock"></i>\
									</div>\
								</div>\
								<input type="password" id="password" name="password" class="form-control" placeholder="Hasło" required>\
							</div>\
						</div>\
						<div class="form-group">\
							<div class="input-group">\
								<div class="input-group-prepend">\
									<div class="input-group-text">\
										<i class="fas fa-unlock"></i>\
									</div>\
								</div>\
								<input type="password" id="password2" name="password2" class="form-control" placeholder="Powtórz hasło" required>\
							</div>\
						</div>\
						<button class="btn btn-lg btn-primary btn-block" type="submit">Zapisz</button>\
					</form>\
				</div>\
			<div>');
	});
	
	 function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$(".center-avatar").empty();
				$(".center-avatar").append('<img id="avatar" src="'+e.target.result+'" class="rounded-circle border-2">');
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$(document).on("change", "#avatar-file", function() {
		readURL(this);
	});
	
	$(document).on("submit", "#logout", function (event) {
		event.preventDefault();
		var self = this;
				
		$.ajax({
			type: 'POST',
			url: 'ajax/logout.php',
			datatype: "text",
			success: function (data) {
				bootbox.alert({
					message: "Zostałeś wylogowany!",
					callback: function () {
						self.submit();
					}
				})				
			}
		});
	});

	$(document).on("submit", "#change-avatar", function (event) {
		event.preventDefault();
		var self = this;
		
		$.ajax({
			type: 'POST',
			url: 'ajax/change-avatar.php',
			data: new FormData(this),
			contentType: false,
    	    processData:false,
			success: function (data) {
				bootbox.alert({
					message: "Avatar został zmieniony!",
					callback: function () {
						self.submit();
					}
				})			
			}
		});
	});

	$(document).on("submit", "#change-name", function (event) {
		event.preventDefault();
  
		var self = this;
		var name = $('input[name="name"]').val();
    var surname = $('input[name="surname"]').val();
		var error = false;
		var message = "";
		
		if (/\s/.test(name)) {
			error = true;
			message += "Podane imię nie może zawierac spacji!<br/>"
		}
		
		if (/\s/.test(surname)) {
			error = true;
			message += "Podane nazwisko nie może zawierać spacji! Dla nazwisk dwuczłonowych użyj '-'!<br/>";
		}
		
		message = message.slice(0,-5);
		
		if (error == false) {
			$.ajax({
				type: 'POST',
				url: 'ajax/change-name.php',
				data: new FormData(this),
				contentType: false,
				processData: false,
				success: function (data) {
					bootbox.alert({
						message: "Pomyslnie zmieniono imię i nazwisko!",
						callback: function () {
							self.submit();
						}
					})			
				}
			});
		} else {
			bootbox.alert(message);	
		} 
	});

	$(document).on("submit", "#change-email", function (event) {
		event.preventDefault();
		var self = this;
		var form = new FormData(this);
		$.ajax({
			type: 'POST',
			url: 'ajax/validate-email.php',
			data: form,
			contentType: false,
    	    processData: false,
			dataType: "text",
			success: function (data) {
				if (data == "0") {
					bootbox.alert("Taki adres e-mail istnije już w bazie!");
				} else {
					$.ajax({
						type: 'POST',
						url: 'ajax/change-email.php',
						data: form,
						contentType: false,
						processData: false,
						success: function (data) {
							bootbox.alert({
								message: "Adres e-mail został zmieniony!",
								callback: function () {
									self.submit();
								}
							})			
						}
					});	
				}			
			}
		});
	});	
	
	$(document).on("submit", "#change-password", function (event) {
		event.preventDefault();
		var self = this;
		var password = $('input[name="password"]').val();
		var password2 = $('input[name="password2"]').val();
		var message = "";
		var error = false;
		
		if (/\s/.test(password)) {
			error = true;
			message += "Podabne hasło nie może zawierać spacji!<br/>";		
		}
		
		if (password != password2) {
			error = true;
			message += "Podane hasła nie są takie same!<br/>";	
		}
		
		message = message.slice(0,-5);
		
		if (error == false) {
			$.ajax({
				type: 'POST',
				url: 'ajax/change-password.php',
				data: new FormData(this),
				contentType: false,
				processData:false,
				success: function (data) {
					bootbox.alert({
						message: "Hasło zostało zmienione!",
						callback: function () {
							self.submit();
						}
					})			
				}
			});
		} else {
			bootbox.alert(message);	
		}
	});
	
	$(document).on("click touch", "#add", function () {
		resetSelectedUser();
   if ($("#messages").css("display") == "none") {
			$("#users").css("display", "none");
			$("#messages").css("display", "block");		
		}
		
		$("#messages").empty();
		$("#messages").append('\
			<div id="add-user-label">\
				<div id="add-user-label-button">\
					<button class="btn btn-back" type="button" id="back-users"><i class="fas fa-arrow-left" aria-hidden="true"></i></button>\
				</div>\
				<div  id="add-user-label-name">\
					<div id="add-user-description">\
						<span>Dodaj kontakt</span>\
					</div>\
				</div>\
				<div class="line">\
				</div>\
			</div>\
			<div id="add-user">\
				<div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">\
					<form method="POST" action="" id="search-user">\
						<div class="input-group">\
							<input type="text" class="form-control" name="user" placeholder="Szukaj" required>\
							<div class="input-group-btn" id="search">\
								<button class="btn btn-primary" type="submit">\
									<i class="fas fa-search"></i>\
								</button>\
							</div>\
						</div>\
					</form>\
					<div id="found-users">\
					</div>\
					<div id="sent-invitations">\
					</div>\
					<div id="waiting-invitations">\
					</div>\
				</div>\
			</div>\
		');
		
		$.ajax({
			type: 'POST',
			url: 'ajax/sent-invitations.php',
			datatype: "json",
			success: function (data) {
				data = JSON.parse(data);		
				if (!('empty' in data[0])) {
					$("#sent-invitations").append("<h6>Wysłane zaproszenia</h6>");
					for (var i = 0; i < data.length; ++i) {
						$("#sent-invitations").append('\
							<div class="contact">\
								<div class="user-avatar">\
									<img src="'+data[i]['avatar']+'" class="rounded-circle border-2">\
								</div>\
								<div  class="user-name">\
									<div class="user-description1">\
										<span>'+data[i]['name']+' '+data[i]['surname']+'</span>\
									</div>\
								</div>\
							</div>');
					}
				} 
			}
		});
		
		$.ajax({
			type: 'POST',
			url: 'ajax/waiting-invitations.php',
			datatype: "json",
			success: function (data) { 
				data = JSON.parse(data);		
				$("#waiting-invitations").empty();
				if (!('empty' in data[0])) {
					$("#waiting-invitations").append("<h6>Czekające na akceptację</h6>");
					for (var i = 0; i < data.length; ++i) {
						$("#waiting-invitations").append('\
							<div class="contact">\
								<div class="user-avatar">\
									<img src="'+data[i]['avatar']+'" class="rounded-circle border-2">\
								</div>\
								<div  class="user-name L-2">\
									<div class="user-description1">\
										<span>'+data[i]['name']+' '+data[i]['surname']+'</span>\
									</div>\
								</div>\
								<div class="invitation-buttons">\
									<button class="btn btn-primary add-contact" type="button" username="'+data[i]['username']+'"><i class="fas fa-user-plus" aria-hidden="true"></i></button>\
								</div>\
								<div class="invitation-buttons">\
									<button class="btn btn-danger cancel-contact" type="button" username="'+data[i]['username']+'"><i class="fas fa-user-minus" aria-hidden="true"></i></button>\
								</div>\
							</div>');
					}
				} 
			}
		});
	});
  
  $(document).on("click touch", ".add-contact", function() {
   var contact = $(this).attr('username');
   var parent = $(this).parent();
   
   $.ajax({
				type: 'POST',
				data: {
					contact: contact,
				},
				url: 'ajax/add-contact.php',
				datatype: "json",
				success: function (data) { 
         if (parent.prev('.ivitation-buttons').length !== 0) {
          parent.prev('.invitation-buttons').remove();
         } else {
          parent.next('.invitation-buttons').remove();
         }
         parent.empty();
         parent.append('<button class="btn btn-secondary added-contact" type="button"><i class="fas fa-user-check" aria-hidden="true"></i></button>');
         if ($('.add-contact').length < 1) {
          $("#add").removeClass("btn-primary");
					$("#add").addClass("btn-default");
         }
         loadContacts();
        }
       });  
  });
	
  $(document).on("click touch", ".cancel-contact", function() {
   var contact = $(this).attr('username');
   var parent = $(this).parent();
   
   $.ajax({
				type: 'POST',
				data: {
					contact: contact,
				},
				url: 'ajax/cancel-contact.php',
				datatype: "json",
				success: function (data) { 
         if (parent.prev('.invitation-buttons').length !== 0) {
          console.log('lol');
          parent.prev('.invitation-buttons').remove();
         } else {
          parent.next('.invitation-buttons').remove();
         }
         parent.empty();
         parent.append('<button class="btn btn-secondary canceled-contact" type="button"><i class="fas fa-user-slash" aria-hidden="true"></i></button>');
        
         if ($('.add-contact').length < 1) {
          $("#add").removeClass("btn-primary");
					$("#add").addClass("btn-default");
         }
        }
       });  
  });
  
	$(document).on("submit", "#search-user", function (event) {
		event.preventDefault();
		var user = $('#search-user input[name="user"]').val().trim();
		var split_user = user.split(" ");
		
		if (split_user.length > 2 || user == "") {
			bootbox.alert('Niepoprawny format danych!<br /> Wpisz: <br /> Imię Nazwisko lub Nazwisko Imię lub Imię lub Nazwisko');

		} else {
			$.ajax({
				type: 'POST',
				data: {
					user: user,
				},
				url: 'ajax/search-user.php',
				datatype: "json",
				success: function (data) { 
					data = JSON.parse(data);
					
					$("#found-users").empty();	
					
					if (!('empty' in data[0])) {
						for (var i = 0; i < data.length; ++i) {
							var contact = "";
							var invitation = "";
							var add = "";
							
							if (data[i]['contact'] != null) {
								contact =   '<div class="invitation-buttons">\
												<button class="btn btn-secondary added-contact" type="button"><i class="fas fa-users" aria-hidden="true"></i></button>\
											</div>';	
							}
							
							if (data[i]['invitation'] != null) {
								invitation = 	'<div class="invitation-buttons">\
													<button class="btn btn-secondary invited-contact" type="button"><i class="fas fa-user-check" aria-hidden="true"></i></button>\
												</div>';	
							}
							
							if (data[i]['contact'] == null && data[i]['invitation'] == null) {
								add = 	'<div class="invitation-buttons">\
											<button class="btn btn-primary invite" type="button" username="'+data[i]['username']+'"><i class="fas fa-user-plus" aria-hidden="true"></i></button>\
										</div>';
							}
							
							$("#found-users").append('\
								<div class="contact">\
									<div class="user-avatar">\
										<img src="'+data[i]['avatar']+'" class="rounded-circle border-2">\
									</div>\
									<div  class="user-name L-1">\
										<div class="user-description1">\
											<span>'+data[i]['name']+' '+data[i]['surname']+'</span>\
										</div>\
									</div>' + contact + invitation + add +'\
								</div>');
						}
					} else {
           $("#found-users").append("Brak wyników...");
          } 
				}
			});
		}
	});
  
  $(document).on("click touch",".invite", function() {
   var contact = $(this).attr("username");
   var parent = $(this).parent();
   $.ajax({
				type: 'POST',
				data: {
					contact: contact,
				},
				url: 'ajax/send-invitation.php',
				datatype: "json",
				success: function (data) { 
         parent.empty();
         parent.append('<button class="btn btn-secondary invited-contact" type="button"><i class="fas fa-user-check" aria-hidden="true"></i></button>');
        }
       });     
  });
  
  $(document).on("click touch", ".user", function() {
   var username1 = $(this).attr('username');
   var element = $(this);
   $("#messages").empty();
   $.ajax({
				type: 'POST',
				data: {
					username: username1,
				},
				url: 'ajax/load-messages.php',
				datatype: "json",
				success: function (data1) { 
         var data = JSON.parse(data1);
         var messages = `<div id="user-label">
                         <div id="user-label-button">
                           <button class="btn btn-back" type="button" id="back-users"><i class="fas fa-arrow-left" aria-hidden="true"></i></button>
                         </div>
                         <div id="user-label-avatar">
                           <img src="`+data['user']['avatar']+`" class="rounded-circle border-2">
                         </div>
                         <div  id="user-label-name">
                           <div id="user-label-description">
                             <span>`+data['user']['name']+` `+data['user']['surname']+`</span>
                           </div>
                         </div>
                         <div class="line">
                         </div>
                       </div>
                       <div id="message">`;
        if (!('empty' in data['messages'][0])) {
         for(var value in data['messages']) {
          var author = "";
          if (data['messages'][value]['author'] == 1) {
           author = "sender";
          } else {
           author = "guest";
          }
           messages += createMessage(author,data['messages'][value]['from_name'], data['messages'][value]['from_surname'], data['messages'][value]['time'], data['messages'][value]['date'], data['messages'][value]['message']);
         }
        }
         messages += `</div>
                     <div id="input">
                      <div class="line">
                      </div>
                      <form action="" method="POST" id="send-message">
                        <div id="text-message">
                          <textarea type="text" class="form-control" name="message" placeholder="Wpisz wiadomość..."></textarea>
                        </div>
                        <div id="message-button">		
                          <button class="btn btn-send" type="submit" username="`+data['user']['username']+`">
                            <i class="fas fa-arrow-right"></i>
                          </button>
                        </div>
                      </form>
                    </div>`;
         $("#messages").append(messages);
         scrollMessages();
         element.removeClass('to_read');
         resetSelectedUser();
         selected_user = username1;
         element.addClass('active_user');
         if ($("#messages").css("display") == "none") {
           $("#users").css("display", "none");
           $("#messages").css("display", "block");		
         }
        }
       }); 
  });
  
  $(document).on("submit", "#send-message", function(e) {
   e.preventDefault();
   var message = $("textarea[name='message']").val().trim();
   var username1 = $('.btn-send').attr('username');
   
   if (message != "") {
    $.ajax({
				type: 'POST',
				data: {
					username: username1,
          message: message
				},
				url: 'ajax/send-message.php',
				datatype: "json",
				success: function (data1) { 
         var data = JSON.parse(data1);
         
         $("#message").append(createMessage("sender", data['name'], data['surname'], data['time'], data['date'], message));
         scrollMessages();
         $("textarea[name='message']").val("");
         send({type: 'message', toUsername: username1, name: data['name'], surname: data['surname'], username: username, time: data['time'], date: data['date'], message: message});
        }
   });
  }
 }); 
});