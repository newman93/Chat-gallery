<?php 
    if (session_status() === PHP_SESSION_NONE){session_start();}
?>
<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Chat</title>
        <link rel="stylesheet" href="css/bootstrap-reboot.min.css">
        <link rel="stylesheet" href="css/bootstrap-grid.min.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/login-register.css">
		<link rel="stylesheet" href="css/fontawesome/all.min.css">
        
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootbox.min.js"></script>
        <script>
            $(document).ready(function() {
                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#avatar').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }
                
                $("#avatar-file").change(function() {
                    readURL(this);
                });
                
                $('#register-form').submit(function(event) {
                    event.preventDefault();
                    var username = $('input[name="username"]').val();
					var password = $('input[name="password"]').val();
					var password2 = $('input[name="password2"]').val();
                    var email = $('input[name="email"]').val();
					var name = $('input[name="name"]').val();
                    var surname = $('input[name="surname"]').val();
					var error = false;
					var message = "";
					
					name = name.slice(0,1).toUpperCase() + name.slice(1).toLowerCase();
					name = surname.slice(0,1).toUpperCase() + surname.slice(1).toLowerCase();
					
					if (/\s/.test(username)) {
						error = true;
						message += "Błędny login! Nie może zawierać spacji!<br/>";
					}
					
					if (/\s/.test(password)) {
						error = true;
						message += "Podabne hasło nie może zawierać spacji!<br/>";		
					}
					
					if (password != password2) {
						error = true;
						message += "Podane hasła nie są takie same!<br/>";	
					}
                    
					if (/\s/.test(name)) {
						error = true;
						message += "Podane imię nie może zawierac spacji!<br/>"
					}
					
					if (/\s/.test(surname)) {
						error = true;
						message += "Podane nazwisko nie może zawierać spacji! Dla nazwisk dwuczłonowych użyj '-'!<br/>";
					}
					
					message = message.slice(0,-5);
					
                    var self = this;
					
					if (error == false) {
						$.ajax({
							type: 'POST',
							data: {
								username: username,
								email: email
							},
							url: 'ajax/checkuser.php',
							datatype: "text",
							success: function (data) { 
								if (data == "0") {
									 bootbox.alert('Istnieje już użytkownik o takim loginie lub adresie e-mail!');
								} else {
									$('#register-form').append('<input type="hidden" name="register" value="register" />');
									self.submit();
								}
							}
						});
					} else {
						bootbox.alert(message);
					}
                });
            });
        </script>
    </head>
    <body class="text-center">
          <?php
			require_once("php-image-resize-master/lib/ImageResize.php");
			use \Gumlet\ImageResize;
            if (isset($_POST['register'])) {
                require_once("database_connection.php");
				
                $sql ="INSERT INTO users (username, password, e_mail, name, surname, avatar, function, active) VALUES(:username, :password, :email, :name, :surname, :avatar, 1, 1)";
                $query = $conn->prepare($sql); 
                $query->bindParam(':username', $_POST['username']);
                $password = md5($_POST['password']);
                $query->bindParam(':password', $password);
                $query->bindParam(':email', $_POST['email']);
                $query->bindParam(':name', $_POST['name']);
                $query->bindParam(':surname', $_POST['surname']);
                if (!isset($_FILES['avatar-file']) || $_FILES['avatar-file']['error'] == UPLOAD_ERR_NO_FILE) {
                    mkdir("img/avatars/".$_POST['username']);
				   $query->bindValue(':avatar', "img/avatars/avatar.png", PDO::PARAM_STR);
                } else {
                   mkdir("img/avatars/".$_POST['username']);
                   $file =  "img/avatars/".$_POST['username']."/".$_FILES["avatar-file"]["name"];
				   $image = new ImageResize($_FILES['avatar-file']['tmp_name']);
				   $image->resize(100, 100);
				   $image->save($file);
                   $query->bindValue(':avatar', $file, PDO::PARAM_STR);
                }
                $query->execute();
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['function'] = 1;
                $_SESSION['active'] = 1;
                header("Location: index.php");
            }
        ?>
        <form id="register-form" class="form-login-register" method="POST" action=""  enctype="multipart/form-data">
            <img id="avatar" src="img/avatars/avatar.png" class="rounded-circle border-3">
            <h1 class="h3 mb-3 font-weight-normal">Chat</h1>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text">
							<i class="fas fa-user"></i>
						</div>				
					</div>	
					<input type="text" id="username" name="username" class="form-control" placeholder="Login" required autofocus>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text">
							<i class="fas fa-unlock"></i>
						</div>				
					</div>	
					<input type="password" id="password" name="password" class="form-control" placeholder="Hasło" required>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text">
							<i class="fas fa-unlock"></i>
						</div>				
					</div>	
					<input type="password" id="password2" name="password2" class="form-control" placeholder="Powtórz hasło" required>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text">
							<i class="fas fa-envelope"></i>
						</div>				
					</div>	
					<input type="email" id="email" name="email" class="form-control" placeholder="E-mail" required>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text">
							<i class="fas fa-user-tag"></i>
						</div>				
					</div>	
					<input type="name" id="name" name="name" class="form-control" placeholder="Imię" required>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text">
							<i class="fas fa-user-tag"></i>
						</div>				
					</div>	
					<input type="surname" id="surname" name="surname" class="form-control" placeholder="Nazwisko" required>
				</div>	
			</div>
			<div class="form-group">
				<label for="avatar-file">Avatar</label>
				<input type="file" name="avatar-file" id="avatar-file"  accept="image/x-png,image/gif,image/jpeg">
				<small id="avatar-hint" class="form-text text-muted">Preferowany rozmiar to 100x100</small><br />
            </div>
			<button class="btn btn-lg btn-primary btn-block" type="submit" name="register">Rejestracja</button>
        </form>
    </body>       
</html>
