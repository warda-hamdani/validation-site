<?php
	 try
	{
	  $bdd = new PDO('mysql:host=localhost;dbname=sitevalidation;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));
	}
	catch (Exception $e)
	{
	        die('Erreur : ' . $e->getMessage());
	}

	if (isset($_POST["register"]))
	{
		if(!empty($_POST['email']) && !empty($_POST['user_name']) && !empty($_POST['pwd']) && !empty($_POST['pwd2']))
		{
			$email = htmlspecialchars($_POST['email']);
			$user_name = htmlspecialchars($_POST['user_name']);
			$password1 = sha1($_POST['pwd']);
			$password2 = sha1($_POST['pwd2']);
			if(filter_var($email,FILTER_VALIDATE_EMAIL)){
				if($password1 != $password2){
					$error_pwd = 'the two passwords are not edentical ';
				}
				else{
						$membre = $bdd->prepare("INSERT into user (email,user_name,psw) values (?,?,?)");
						$membre->execute(array($email,$user_name,$password1));
						if(isset($_POST['tel'])){
							$tel = htmlspecialchars($_POST['tel']);
							$update = $bdd->prepare("UPDATE user set phone_num=? WHERE email=?");
							$update->execute(array($tel,$email));
						}
						if(isset($_POST['university'])){
							$univer = htmlspecialchars($_POST['university']);
							$update = $bdd->prepare("UPDATE user set university=? WHERE email=?");
							$update->execute(array($univer,$email));


						}
						$error = 'your account was successfuly created';

					}

			}
			else{
				$error_email='wrong mail';
			}

		}
		else{
			$error='fill the required inputs';
			
		}
	}
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Registration</title>
		<script src="js/jquery-3.3.1.min.js" ></script>	
		<link rel="stylesheet" href="css/stylesheet2.css">
		<meta charset="utf-8">		
	</head>
	<body id="register-body">
		<section id='registration'>

			<div id='content-register'>
				
				<form action='' method="POST">
					<table>
						<tr><td><label for='user_name'>User name*</label></td><td><input type="text" name="user_name"></td></tr> 

						<tr><td><label for="email">Email*</label></td><td><input type="email" name="email"></td></tr>

						<tr><td><label for='university'>University</label></td><td><input type="text" name="university"></td></tr>

						<tr><td><label for="tel">Phone number</label></td><td><input type="tel" name="tel"></td></tr>						
						<tr><td><label for="pwd">Password*</label></td><td><input type="password" name="pwd"></td></tr>
						<tr><td><label for="pwd">Confirm assword*</label></td><td><input type="password" name="pwd2"></td></tr>
						<?php
							if(isset($error_pwd)){
								echo '<tr><td></td><td><font color="red">'.$error_pwd."</font></td></tr>";
									}
						?>
						<tr><td></td><td><input type="submit" name="register" value="Register"></td></tr>
					</table>
				</form>
				<a href="connexion.php">Connexion</a>
				
				<?php
				if(isset($error)){
					echo '<font color="red">'.$error."</font>";
				}
				?>
			
			</div>
		</section>
		
	</body>
</html>