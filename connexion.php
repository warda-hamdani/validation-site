
<?php
session_start();
	  try
	{
	  $bdd = new PDO('mysql:host=localhost;dbname=sitevalidation;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));
	}
	catch (Exception $e)
	{
	        die('Erreur : ' . $e->getMessage());
	}
	if(isset($_POST['connexion'])){
		$email = htmlspecialchars($_POST['email']);
		$pwd = sha1($_POST['pwd']);
		if(!empty($email) and !empty($pwd)){
			$user = $bdd->prepare('SELECT * from user where email=? and psw=?');
			$user->execute(array($email,$pwd));
			$count = $user->rowCount();
			if($count ==1){
				$user_info = $user->fetch();
				$_SESSION['email']=$user_info['email'];
				$_SESSION['id'] = $user_info['id'];
				$_SESSION['user_name'] = $user_info['user_name'];
				if($user_info['status']=='admin')
				{	$_SESSION['admin'] = 'yes';
					header("Location: admin.php?id=".$_SESSION['id']);
				}
				else
				    header("Location: index4.php?id=".$_SESSION['id']);
			}
			else{
				$error = "email or password are wrong";
			}
		}
	}
?>
<!doctype>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Connexion</title>
		<script src="js/jquery-3.3.1.min.js" ></script>	
		<link rel="stylesheet" href="css/stylesheet2.css">
		<meta charset="utf-8">		
	</head>
	<body>
		<section id='connexion_section'>
			
			<div id='content'>
				<form action='' method="POST">
					<table>
						<tr><td><input type="email" name="email" placeholder="Enter your email"></td></tr>
						<tr><td><input type="password" name="pwd" placeholder="Enter password"></td></tr>
						<tr><td><input type="submit" name="connexion" value='Connexion'></td></tr>
					</table>
					<?php
					if(isset($error)){
						echo '<font color="red" >'.$error.'</font>';
					}
					?>
				</form>
				<a href="inscription.php">inscription</a>

			</div>
		</section>
		
	</body>
</html>