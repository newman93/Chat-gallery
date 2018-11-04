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
               $(document).on("click", "#register", function() {
                   window.location.href = "register.php";
               }); 
            });
        </script>
    </head>
    <body class="text-center">
         <?php 
            require_once("database_connection.php");
            
            if ((isset($_POST['username'])) && (isset($_POST['password']))) {
                $sql ="SELECT * FROM users WHERE username = :username AND password = :password ";
                $query = $conn->prepare($sql); 
                $query->bindParam(':username', $_POST['username']);;
                $password = md5($_POST['password']);
                $query->bindParam(':password', $password);
                $query->execute();  
                $row_count = $query->rowCount();
                $row = $query->fetch();
                if ($row_count > 0) {
                    $_SESSION['username'] = $_POST['username'];
					$_SESSION['name'] = $row['name'];
					$_SESSION['surname'] = $row['surname'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['function'] = $row['function']; 
                    $_SESSION['active'] = 1; 
					$_SESSION['avatar'] = $row['avatar'];
					
					$sql1 = "UPDATE users SET active = 1 WHERE username = :username";
					$query1 = $conn->prepare($sql1); 
					$query1->bindParam(':username', $_SESSION['username']);
					$query1->execute();
					
                    header("Location: index.php"); 
                } else {
                    echo "<script type='text/javascript'>$(document).ready(function() { bootbox.alert('Podano niepoprawny login lub hasło!'); });</script>";
                }
            }
        ?>
        <form class="form-login-register" method="POST" action="">
			<img id="logo" src="img/logo.png" class="rounded-circle border-3">
            <h1 class="h3 mb-3 font-weight-normal">Chat</h1>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text">
							<i class="fas fa-user"></i>
						</div>				
					</div>	
					<input type="text" name="username" id="username" class="form-control" placeholder="Login" pattern=".*\S+.*" required autofocus>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text">
							<i class="fas fa-unlock"></i>
						</div>				
					</div>	
					<input type="password" name="password" id="password" class="form-control" placeholder="Hasło" pattern=".*\S+.*" required>
				</div>
			</div>
			<button class="btn btn-lg btn-primary btn-block" type="submit" id="login" name="login">Logowanie</button>
            <button class="btn btn-lg btn-primary btn-block" type="button" id="register" name="register">Rejestracja</button>
        </form> 
    </body>       
</html>
