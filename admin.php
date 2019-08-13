<?php
session_start();
if(isset($_SESSION['id']) and isset($_SESSION['admin'])){
 try
	{
	  $bdd = new PDO('mysql:host=localhost;dbname=sitevalidation;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));
	}
	catch (Exception $e)
	{
	        die('Erreur : ' . $e->getMessage());
	}
$id_user=$_SESSION['id'];
		if(isset($_POST['drop']))
		{
			$xml=simplexml_load_file("instances.xml") or die("Failed to load xml file");
			foreach ($xml as $instance) {
				if($instance['idSynset']==$_POST['hidden']){
					$instance['state']="dropped";
					break;}
			}
			/*foreach ($xml as $instance) 
			{
				if(isset($instance->links) && count($instance->links->children())==1)
					{$links=$instance->links->children();
					if( $links[0]['idSynset']==$_POST['hidden'] )$instance['state']="dropped";}

			}*/
			$xml->asXML("instances.xml");
		}else if(isset($_POST['validate'])){
			$xml=simplexml_load_file("instances.xml");
			foreach ($xml as $instance) {
				if($instance['idSynset']==$_POST['hidden']){
					$instance['state']="valid";
					
					foreach ($_POST as $key => $value) {
						if($key!="validate" && $key!="hidden"){
							$a=explode("-", $key);
							if(strpos($a[0], "w")===0){
								$child=$instance->arabVersion->words->word[intval($a[1])]->validations->addChild("validation");}

								else if(strpos($a[0],"ex")===0){
									$child=$instance->arabVersion->examples->example[intval($a[1])]->validations->addChild("validation");}

									else if(strpos($a[0], "voca")===0){
										$child=$instance->arabVersion->vocalisations->vocalisation[intval($a[1])]->validations->addChild("validation");}
							if(isset($child)){$child->addAttribute("admin_id",$id_user);
										$child->addAttribute("value",$value);}
						}
						
					}


					break;
				}
			}
			foreach ($xml as $instance) {
				foreach ($instance->links->children() as $link) {
					if($link['idSynset']==$_POST['hidden'] )
						if(isset($link['state'])) $link['state']="valid";
							else $link->addAttribute('state',"valid");
					
				}
			}
			$xml->asXML("instances.xml");
		}


?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Synsets validation</title>
		<script src="js/jquery-3.3.1.min.js" ></script>
		<link rel="stylesheet" href="css/stylesheet2.css">
	</head>
	<body>
		<div class="navbar">
  			<label>Synsets validation</label>
  			<a href="statistic.php">statistics</a>
  			<a href="logout.php">logout</a>
  		
		</div>
		<section id="body" >
			<div id="display_download_xml">
				<a href="" onclick="javascript:void window.open('instances.xml','1553465678084','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;"><img src="images/display.png"><span>Display XML File</span></a>
				<a href="instances.xml" download><img src="images/download.png"><span>Download XML file</span></a>
			</div>
			<div class="container">
				<table id="table-container">
						
			  
			  	<?php
			  		$xml=simplexml_load_file("instances.xml") or die("Failed to load xml file");
			  		$find=false;
			  		
			  		foreach ($xml->children() as $instance) {
			  			
			  			if($instance['state']=="invalid" && isset($instance['checked'])){
			  				$find=true;
			  				$englishver=$instance->engVersion;
							$arabicver=$instance->arabVersion;
			  	?>	<tr ><td class="tdi">
			  		<form action="" method="post">
			  		<table class="en-ar-section">
			  		<tr><th>English</th><th>Arabic</th></tr>
			  		<tr>
			  			<td class="td1">
			     		 				<table class="english_section">
			     		 					<?php 
			     		 						echo "<tr><td><span class='id_synset'>Id synset:</span> ".$instance['idSynset']."</td></tr>";
			     		 						if(count($englishver->words->children())>0)
			     		 								echo "<tr class='tr_title'><td>Synonyms:</td></tr>";
						     		 			foreach ($englishver->words->children() as  $word) {						     		 							     		 		
						     		 					$a=explode("_", $word['value'])	;
						     		 					$w=join(" ",$a);		     		 		
						     		 					echo"<tr><td> ".$w." </td></tr>";
						     		        	}
						     		         	if(count($englishver->definitions->children())>0)
						     		 				echo "<tr class='tr_title'><td>Definitions:</td></tr>";
						     		 		 	foreach ($englishver->definitions->children() as  $def) 
						     		 		 	{						     		 							     		 		
						     		 		 		echo"<tr><td>".$def['value']."</td></tr>";
						     		        	} 
						     		        ?>
						     		 		
			     		 				</table>
			     		</td>
			     		<td class="td2">
			     			<table class="arabic_section2">
			     				<?php
			     		 						if(isset($arabicver->words) && count($arabicver->words->children())>0)
			     		 						{
			     		 							echo "<tr class='tr_title'><td>Synonyms:</td><td>true</td><td>drop</td><td></td></tr>";
			     		 							$i=0;
			     		 							foreach ($arabicver->words->children() as $word) {
			     		 								$nbTrue=0;$nbFalse=0;
			     		 								$idsTrue="";$idsFalse="";
			     		 								foreach ($word->validations->children() as $validation) {
			     		 									if($validation['value']=="true"){$nbTrue++;
			     		 										$id = $validation['id'];
			     		 										 $user = $bdd->prepare("SELECT user_name From user where id=?");
			     		 										 $user->execute(array($id));
			     		 										 $user_info = $user->fetch();
			     		 										 $idsTrue.=$user_info['user_name'].", ";}
			     		 									else {$nbFalse++;
			     		 										$id = $validation['id'];
			     		 										 $user = $bdd->prepare("SELECT user_name From user where id=?");
			     		 										 $user->execute(array($id));
			     		 										 $user_info = $user->fetch();
			     		 										 $idsFalse.=$user_info['user_name'].", ";}
			     		 								}
			     		 								$idsTrue=substr($idsTrue, 0,-2);
			     		 								$idsFalse=substr($idsFalse, 0,-2);
			     		 								$message2="";
			     		 								$message="#false: $nbFalse";
			     		 								if($nbFalse>0)$message2.="users validate by false: $idsFalse.";
			     		 								$message.="<br>#true: $nbTrue";
			     		 								if($nbTrue>0)$message2.="<br>users validate by true: $idsTrue.";
			     		 								echo "<tr class='atdi'><td >".$word['value']."</td>";
			     		 								if($nbTrue>$nbFalse)echo "<td><input type='radio' name='w-$i' value='true' checked></td>";
			     		 									else echo "<td><input type='radio' name='w-$i' value='true'></td>";
			     		 								if($nbFalse>$nbTrue)echo "<td><input type='radio' name='w-$i' value='drop' checked></td>";
			     		 									else echo "<td><input type='radio' name='w-$i' value='drop'></td>";
			     		 								echo "<td class='msg_stat'>$message";
			     		 								if($nbFalse!=0 ||$nbTrue!=0) echo "<div id='".$instance['idSynset']."-w-$i'>details</div>";
			     		 								echo "</td></tr>";
			     		 								if($nbFalse!=0 ||$nbTrue!=0){echo "<div id='".$instance['idSynset']."-w-$i-TF' class='hiddenInfo'>$message2</div>";
			     		 							?>
			     		 								<script type="text/javascript">
															$(document).ready(function(){
															  $(<?php echo "'#".$instance['idSynset']."-w-$i'";?>).click(function(){
															    $(<?php echo "'#".$instance['idSynset']."-w-$i-TF'";?>).toggle('slow');														    
															  });
															  $(<?php echo "'#".$instance['idSynset']."-w-$i-TF'";?>).click(function(){
															    $(this).hide('slow');														    
															  });

															 
															  });
														</script>

			     		 							<?php
			     		 						}
			     		 								
			     		 								$i++;
			     		 							}
						     		 		        

						     		 			}
						     		 			if(isset($arabicver->examples) && count($arabicver->examples->children())>0)
			     		 						{
			     		 							echo "<tr class='tr_title'><td>Examples:</td></tr>";
			     		 							$i=0;
			     		 							foreach ($arabicver->examples->children() as $ex) {
			     		 								$nbTrue=0;$nbFalse=0;
			     		 								$idsTrue="";$idsFalse="";
			     		 								foreach ($ex->validations->children() as $validation) {
			     		 									if($validation['value']=="true")
			     		 										{$nbTrue++;
			     		 										 $id = $validation['id'];
			     		 										 $user = $bdd->prepare("SELECT user_name From user where id=?");
			     		 										 $user->execute(array($id));
			     		 										 $user_info = $user->fetch();

			     		 										 $idsTrue.=$user_info['user_name'].", ";}
			     		 									else {$nbFalse++;
			     		 										  $id = $validation['id'];
			     		 										  $user = $bdd->prepare("SELECT user_name From user where id=?");
			     		 										  $user->execute(array($id));
			     		 										  $user_info = $user->fetch();
			     		 										  $idsFalse.=$user_info['user_name'].", ";}
			     		 								}
			     		 								$idsTrue=substr($idsTrue, 0,-2);
			     		 								$idsFalse=substr($idsFalse, 0,-2);
			     		 								$message="#false: $nbFalse.";
			     		 								$message2="";
			     		 								if($nbFalse>0)$message2.="users validate by false: $idsFalse.";
			     		 								$message.="<br>#true: $nbTrue.";
			     		 								if($nbTrue>0)$message2.="<br>users validate by true: $idsTrue.";
			     		 								echo "<tr id='".$instance['idSynset']."-ex-$i-r' class='atdi'><td >".$ex['value']."</td>";
			     		 								if($nbTrue>$nbFalse)echo"<td><input type='radio' name='ex-$i' value='true' checked></td>";
			     		 									else echo"<td><input type='radio' name='ex-$i' value='true'></td>";
			     		 								if($nbFalse>$nbTrue)echo"<td><input type='radio' name='ex-$i' value='drop' checked></td>";
			     		 									else echo"<td><input type='radio' name='ex-$i' value='drop'></td>";
			     		 								echo "<td class='msg_stat'>$message";
			     		 								if($nbFalse!=0 ||$nbTrue!=0)echo"<div id='".$instance['idSynset']."-ex-$i'>details</div>";
			     		 								echo "</td></tr>";
			     		 								if($nbFalse!=0 ||$nbTrue!=0){echo "<div id='".$instance['idSynset']."-ex-$i-TF' class='hiddenInfo'>$message2</div>";
			     		 								
			     		 								
			     		 								
			     		 								?>
			     		 								
			     		 								<script type="text/javascript">
															$(document).ready(function(){
															  $(<?php echo "'#".$instance['idSynset']."-ex-$i'";?>).click(function(){
															    $(<?php echo "'#".$instance['idSynset']."-ex-$i-TF'";?>).toggle('slow');														    
															  });
															  $(<?php echo "'#".$instance['idSynset']."-ex-$i-TF'";?>).click(function(){
															    $(this).hide('slow');														    
															  });

															 
															  });
														</script>
			     		 								<?php
			     		 									}
			     		 								
			     		 								$i++;
			     		 							}
						     		 		        

						     		 			}
						     		 			if(isset($arabicver->vocalisations) && count($arabicver->vocalisations->children())>0)
			     		 						{
			     		 							echo "<tr class='tr_title'><td>Vocalisations:</td></tr>";
			     		 							$i=0;
			     		 							foreach ($arabicver->vocalisations->children() as $voca) {
			     		 								$nbTrue=0;$nbFalse=0;
			     		 								$idsTrue="";$idsFalse="";
			     		 								foreach ($voca->validations->children() as $validation) {
			     		 									if($validation['value']=="true"){$nbTrue++;
			     		 										$id = $validation['id'];
			     		 										 $user = $bdd->prepare("SELECT user_name From user where id=?");
			     		 										 $user->execute(array($id));
			     		 										 $user_info = $user->fetch();
			     		 										$idsTrue.=$user_info['user_name'].", ";}
			     		 									else {$nbFalse++;
			     		 										$id = $validation['id'];
			     		 										 $user = $bdd->prepare("SELECT user_name From user where id=?");
			     		 										 $user->execute(array($id));
			     		 										 $user_info = $user->fetch();
			     		 										$idsFalse.=$user_info['user_name'].", ";}
			     		 								}
			     		 								$idsTrue=substr($idsTrue, 0,-2);
			     		 								$idsFalse=substr($idsFalse, 0,-2);
			     		 								$message="#false: $nbFalse";
			     		 								$message2="";
			     		 								if($nbFalse>0)$message2.="users validate by False: $idsFalse.";
			     		 								$message.="<br>#true: $nbTrue";
			     		 								if($nbTrue>0)$message2.="<br>users validate by true: $idsTrue.";
			     		 								echo "<tr id='".$instance['idSynset']."-voca-$i-r' class='atdi'><td >".$voca['value']."</td>";
			     		 								if($nbTrue>$nbFalse) echo "<td><input type='radio' name='voca-$i' value='true' checked ></td>";
			     		 								else echo "<td><input type='radio' name='voca-$i' value='true' ></td>";
			     		 								if($nbFalse>$nbTrue)echo "<td><input type='radio' name='voca-$i' value='drop' checked></td>";
			     		 								else echo "<td><input type='radio' name='voca-$i' value='drop'></td>";

			     		 								echo "<td class='msg_stat'>$message";
			     		 								if($nbFalse!=0 ||$nbTrue!=0)echo"<div id='".$instance['idSynset']."-voca-$i'>details</div>";
			     		 								echo "</td></tr>";
			     		 								if($nbFalse!=0 ||$nbTrue!=0){echo "<div id='".$instance['idSynset']."-voca-$i-TF' class='hiddenInfo'>$message2</div>";
			     		 								?>
			     		 								<script type="text/javascript">
															$(document).ready(function(){
															  $(<?php echo "'#".$instance['idSynset']."-voca-$i'";?>).click(function(){
															    $(<?php echo "'#".$instance['idSynset']."-voca-$i-TF'";?>).toggle('slow');														    
															  });
															  $(<?php echo "'#".$instance['idSynset']."-voca-$i-TF'";?>).click(function(){
															    $(this).hide('slow');														    
															  });

															 
															  });
														</script>

			     		 							<?php
			     		 						}
			     		 								
			     		 								$i++;
			     		 							}
						     		 		        

						     		 			}
						     	?>
			     				
			     			</table>
			     		</td>
			  		
			  		</tr>
			  		
			  		</table>
			  		<input type="hidden" name="hidden" value='<?php echo $instance['idSynset'];?>'>
			  		<div id="buttons_area"><div><input type="submit" name="validate" value="Validate" class="admin_validate"><input type="submit" name="drop" value='Drop' class="admin_drop"><input type="reset" name="reset" value="Reset" class="reset_admin">
			  		</div></div>
			  	</form></td></tr>
			  	<?php

			  			}
			  			
			  		}
			  		if($find==false)echo "<p>no more synset checked by users</p>";
			  	?>
			  	</table>
			</div>
		</section>
	
	</body>
</html>
<?php
}
else{
	header('location:connexion.php');
}
?>