<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bootstrap - Prebuilt Layout</title>

<!-- Bootstrap -->
<link href="css/bootstrap.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
  <?php
	
	require_once('core/init.php');
	
	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			
			$validate = new Validation();
			$validation = $validate->check($_POST, array(
			'username' => array('required' => true),
			'password' => array('required' => true)));
			
			if($validation->passed()){
				$user = new User();
				$login = $user->login(Input::get('username'), Input::get('password'));
				
				if($login){
					echo 'Success';
				}//login fallito
				else{
					echo "<p>Sorry, Logging in failed</p>";
				}
			} 
			//validazione fallita
			else{
				foreach($validation->getErrors() as $error){
					echo $error . '<br>';
				}
			}
		}
	}
	
	?>
 
  <div class="container-fluid">

	  <div class="container">
	   <div class="row"><!--Row dell'Header-->
		<header>
			<div class="col-md-6 col-md-offset-3">
			  <h1 class="text-center">Accedi</h1>
			</div><!--col-md-6-offset-3-->
		</header>
	   </div><!--row-->
	   <hr>
	  </div><!--container-->

	   <div class="container"><!--container della form-->
		   <div class="row "><!--Row Della Form-->
			   <div class="col-md-6 col-md-offset-3">
				   <section>

						<!--Una form di login-->
						<form action="" method="post">

						  <div class="form-group">
							<label for="Username">Username</label>
							<input type="text" class="form-control" name="username" id="username" placeholder="Enter Username" autocomplete="off">
						  </div>

						  <div class="form-group">
							<label for="Password">Password</label>
							<input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" autocomplete="off">
						  </div>

						  <div class="form-check">
							<label class="form-check-label">
							  <input type="checkbox" class="form-check-input">
							  Remember me
							</label>
						  </div>

							<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

							<button type="submit" class="btn btn-block btn-primary" value="login">Log In</button>

						</form>
				  </section>
				</div><!--col-md-6-offset-3-->
			</div><!--row-->
		</div><!--container-->

	</div><!--container-fluid-->
</body>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery-1.11.3.min.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.js"></script>
</body>
</html>

