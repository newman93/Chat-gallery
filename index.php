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
        <link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/fontawesome/all.min.css">
        
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
		<script src="js/bootbox.min.js"></script>
        <script src="js/script.js"></script>
    </head>
    <body>
        <?php 
            if (isset($_SESSION['active']) && $_SESSION['active'] == 1) {
        ?>
        <div class="container-fluid">
			<div id="content" class="row">
				<div id="users" class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
					<div id="users-content">
						<div id="user" class="row">
								<div>
									<img src="<?php echo $_SESSION['avatar']?>" class="rounded-circle border-3">
								</div>
								<div class="user-description">
									<span><?php echo $_SESSION['username'] ?></span>
								</div>
								<div class="user-description">
									<span><?php echo $_SESSION['name']." ".$_SESSION['surname'] ?></span>
								</div>
						</div>
						<div id="serachbox">
							<form method="POST" action="" id="search-contact">
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Szukaj" name="user" required>
									<div class="input-group-btn" id="search">
										<button class="btn btn-default" type="submit">
											<i class="fas fa-search"></i>
										</button>
									</div>
								</div>
							</form>
						</div>
						<div id="my-users"><!--
							<div class="user">
								<div class="user-avatar">
									<img src="img/avatars/avatar.png" class="rounded-circle border-3">
									<div class="status online">
									</div>
								</div>
								<div  class="user-name">
									<div class="user-description1">
										<span>Adrian Kowalski</span>
									</div>
								</div>
							</div>
							<div class="user">
								<div class="user-avatar">
									<img src="img/avatars/avatar.png" class="rounded-circle border-3">
									<div class="status online">
									</div>
								</div>
								<div  class="user-name">
									<div class="user-description1">
										<span>Adrian Kowalski</span>
									</div>
								</div>
							</div>
							<div class="user">
								<div class="user-avatar">
									<img src="img/avatars/avatar.png" class="rounded-circle border-3">
									<div class="status online">
									</div>
								</div>
								<div  class="user-name">
									<div class="user-description1">
										<span>Adrian Kowalski</span>
									</div>
								</div>
							</div>
							<div class="user">
								<div class="user-avatar">
									<img src="img/avatars/avatar.png" class="rounded-circle border-3">
									<div class="status online">
									</div>
								</div>
								<div  class="user-name">
									<div class="user-description1">
										<span>Adrian Kowalski</span>
									</div>
								</div>
							</div>
							<div class="user">
								<div class="user-avatar">
									<img src="img/avatars/avatar.png" class="rounded-circle border-3">
									<div class="status online">
									</div>
								</div>
								<div  class="user-name">
									<div class="user-description1">
										<span>Adrian Kowalski</span>
									</div>
								</div>
							</div>
							<div class="user">
								<div class="user-avatar">
									<img src="img/avatars/avatar.png" class="rounded-circle border-3">
									<div class="status online">
									</div>
								</div>
								<div  class="user-name">
									<div class="user-description1">
										<span>Adrian Kowalski</span>
									</div>
								</div>
							</div>
							<div class="user">
								<div class="user-avatar">
									<img src="img/avatars/avatar.png" class="rounded-circle border-3">
									<div class="status online">
									</div>
								</div>
								<div  class="user-name">
									<div class="user-description1">
										<span>Adrian Kowalski</span>
									</div>
								</div>
							</div>
							<div class="user">
								<div class="user-avatar">
									<img src="img/avatars/avatar.png" class="rounded-circle border-3">
									<div class="status online">
									</div>
								</div>
								<div  class="user-name">
									<div class="user-description1">
										<span>Adrian Kowalski</span>
									</div>
								</div>
							</div>
							<div class="user">
								<div class="user-avatar">
									<img src="img/avatars/avatar.png" class="rounded-circle border-3">
									<div class="status online">
									</div>
								</div>
								<div  class="user-name">
									<div class="user-description1">
										<span>Adrian Kowalski</span>
									</div>
								</div>
							</div>
							<div class="user">
								<div class="user-avatar">
									<img src="img/avatars/avatar.png" class="rounded-circle border-3">
									<div class="status online">
									</div>
								</div>
								<div  class="user-name">
									<div class="user-description1">
										<span>Adrian Kowalski</span>
									</div>
								</div>
							</div>-->
						</div>
					</div> 
					<div id="users-menu">
						<div class="line">
						</div>
						<div class="user-buttons">
							<button class="btn btn-default" type="button" id="add"><i class="fas fa-plus" aria-hidden="true"></i></button>
						</div>
						<div class="user-buttons">
							<button class="btn btn-default" type="button" id="show-contacts"><i class="fas fa-users" aria-hidden="true"></i></button>
						</div>
						<div class="user-buttons">
							<button class="btn btn-default" id="settings" type="button"><i class="fas fa-address-card" aria-hidden="true"></i></button>
						</div>
					</div>
				</div>
				
				<div id="messages" class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 p-0 m-0">
					<div id="user-label">
						<div id="user-label-button">
							<button class="btn btn-back" type="button" id="back-users"><i class="fas fa-arrow-left" aria-hidden="true"></i></button>
						</div>
						<div id="user-label-avatar">
							<img src="img/avatars/avatar.png" class="rounded-circle border-2">
						</div>
						<div  id="user-label-name">
							<div id="user-label-description">
								<span>Adrian Kowalski</span>
							</div>
						</div>
						<div class="line">
						</div>
					</div>
					<div id="message">
						<div class="main-message">
							<div class="message-user sender">
								<span class="message-user-name">Adrian Kowalski</span>
								<span class="message-user-date"><small>09.10.2018</small></span>
 							</div>
							<div class="message sender-message">
								<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
							</div>
						</div>
						<div class="main-message">
							<div class="message-user guest">
								<span class="message-user-date"><small>09.10.2018</small></span>
								<span class="message-user-name">Adrian Kowalski</span>
							</div>
							<div class="message guest-message">
								<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
							</div>
						</div>
						<div class="main-message">
							<div class="message-user sender">
								<span class="message-user-name">Adrian Kowalski</span>
								<span class="message-user-date"><small>09.10.2018</small></span>
 							</div>
							<div class="message sender-message">
								<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
							</div>
						</div>
						<div class="main-message">
							<div class="message-user guest">
								<span class="message-user-date"><small>09.10.2018</small></span>
								<span class="message-user-name">Adrian Kowalski</span>
							</div>
							<div class="message guest-message">
								<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
							</div>
						</div>
						<div class="main-message">
							<div class="message-user sender">
								<span class="message-user-name">Adrian Kowalski</span>
								<span class="message-user-date"><small>09.10.2018</small></span>
 							</div>
							<div class="message sender-message">
								<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
							</div>
						</div>
						<div class="main-message">
							<div class="message-user guest">
								<span class="message-user-date"><small>09.10.2018</small></span>
								<span class="message-user-name">Adrian Kowalski</span>
							</div>
							<div class="message guest-message">
								<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
							</div>
						</div>
					</div>
					<div id="input">
						<div class="line">
						</div>
						<form>
							<div id="text-message">
								<textarea type="text" class="form-control" placeholder="Wpisz wiadomość..."></textarea>
							</div>
							<div id="message-button">		
								<button class="btn btn-send" type="submit">
									<i class="fas fa-arrow-right"></i>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
        </div>    
        
        <?php    
            } else {
                header('Location: login.php');
            }
       ?>
    </body>       
</html>