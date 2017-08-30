<!DOCTYPE html>
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

<!--Parte di PHP--->
<?php
	require_once 'core/init.php';

	/*---Validazione dei dati---
	---------------------------- */
	if(Input::exists()){

		if(Token::check(Input::get('token'))){


		$validate = new Validation();
		$validation = $validate->check($_POST, array(
			'username' => array(
				'required' => true,
				'min' => 2,
				'max' => 20,
				'unique' => 'users'
				),
			'password' => array(
				'required' => true,
				'min' => 6
				),
			'password_again' => array(
				'required' => true,
				'matches' => 'password'
				),
			'email' => array(
				'required' => true,
				'min' => 6,
				'max' => 45,
				'unique' => 'users'
				),
			'name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50
				),
			'surname' => array(
				'required' => true,
				'min' => 2,
				'max' => 50
				)
		));


			if($validation->passed()){

				$user = new User();

				$salt = Hash::salt(16);

					try{

								$user->create(array(
								'username' => Input::get('username'),
								'password' => Hash::make(Input::get('password'), $salt),
								'salt' => $salt,
								'email' => Input::get('email'),
								'name' => Input::get('name'),
								'surname' => Input::get('surname'),
								'joined' => date('Y-m-d H:i:s'),
								'group' => 1

							));



						Session::flash('success','You registered successfully!');
							Redirect::to('index.php');


					}
					catch(Exception $e){
						die($e->getMessage());
					}
			}
			else{
				foreach($validation->getErrors() as $error)
					echo $error . "<br>";
			}
		}
	}
?>

<div class="container-fluid">

  <div class="container">
   <div class="row"><!--Row dell'Header-->
	<header>
		<div class="col-md-6 col-md-offset-3">
		  <h1 class="text-center">Registrati</h1>
		</div><!--col-md-6-offset-3-->
	</header>
   </div><!--row-->
   <hr>
  </div><!--container-->

   <div class="container"><!--container della form-->
   <div class="row "><!--Row Della Form-->
	   <div class="col-md-6 col-md-offset-3">
		   <section>

				<!--Una form di registrazone-->
				<form action="" method="post">

					  <div class="col-md-6"><!--prima colonna della form-->

						  <!--Un campo  username-->
						  <div class="form-group field">
							<label for="Enter Username">Username</label>
							<input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?php echo escape(Input::get('username'));?>" autocomplete="off">
						  </div>

						  <!--Un campo  password-->
						  <div class="form-group field">
							<label for="password">Password</label>
							<input type="password" class="form-control" name="password" id="password" placeholder="Password">
						  </div>

						  <!--Un campo  passwordAgain-->
						  <div class="form-group field">
							<label for="password_again">Enter Your Password Again</label>
							<input type="password" class="form-control" name="password_again" id="password_again" placeholder="Enter Your Password Again">
						  </div>

				 	  </div><!--col-md-6-->

					  <div class="col-md-6"><!--seconda colonna della form-->

						  <!--Un campo  email-->
						  <div class="form-group field">
							<label for="email">Email address</label>
								<div class="input-group">
								<span class="input-group-addon" id="basic-addon1">@</span>
								<input type="email" class="form-control" name="email" id="email" value="<?php echo escape(Input::get('email'));?>" placeholder="Enter Email">
							   </div>
						  </div>

						  <!--Un campo  name-->
						  <div class="form-group field">
							<label for="name">Name</label>
							<input type="text" class="form-control" name="name" id="name" value="<?php echo escape(Input::get('name'));?>" placeholder="Enter Your Name">
						  </div>

						  <!--Un campo  surname-->
						  <div class="form-group field">
							<label for="surname">Surname</label>
							<input type="text" class="form-control" name="surname" id="surname" value="<?php echo escape(Input::get('surname'));?>" placeholder="Enter Your Surname">
						  </div>

					  </div><!--col-mid-6-->

				      	  <!--Un testo di obbligatorietà-->
						  <div class="col-md-offset-3 col-md-6 text-center">
							  <small class=" text-muted">*All fields are required</small>
						  </div>

						  <!--Un bottone-->
						  <div class="col-md-offset-3 col-md-6">

							<input type="hidden" name="token" value="<?php echo Token::generate();?>" >
							<button type="submit" class="btn btn-block btn-primary" value="Register">Submit</button>
						  </div>

			 	</form>
		  </section>
  		</div><!--col-md-6-offset-3-->
	</div><!--row-->
   </div><!--container-->

</div><!--container-fluid-->



<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery-1.11.3.min.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.js"></script>
</body>
</html>
