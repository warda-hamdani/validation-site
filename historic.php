<?php
session_start();
if(isset($_SESSION['id'])){
$id_user=$_SESSION['id'];
include 'functions.php';
//retreive the synsets of historic of user to show them in the interface
$synsets=getSynsHisto($id_user);


?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Historic</title>
		<script src="js/jquery-3.3.1.min.js" ></script>	
		<link rel="stylesheet" href="css/stylesheet2.css">
	</head>
	<body>
		<div class="navbar">
  		<a href="index4.php?id=<?php

  			echo $_SESSION['id'];
  			?>">Synsets validation</a>
  		<!--<a href="#news">home</a>-->
  		<label>historic</label>
  		<a href="logout.php">logout</a>
  		<!--<a href="plus.php">Plus</a>-->
	</div>
	<section id="body" >
		<div id="user_download">
			<a href="telecharger.php" ><img src="images/download.png"><span>Download XML file</span></a>
		</div>
		
		<div class="container">
				<table id="table-container">
			  	
			  	<?php
			  		if(count($synsets)==0)echo "<p>No historic</p>";
																	
						foreach($synsets as $synset)
						{
							
							$englishver=$synset->engVersion;
							$arabicver=$synset->arabVersion;							 
				?>
				<script type="text/javascript">
					$(function () {
			        $(<?php echo "'#synset".$synset['idSynset']."'";?>).on('submit', function (event) {
			        	//$(this).parent('.par').remove();

					event.preventDefault();// using this page stop being refreshing 

			          $.ajax({
			            type: 'POST',
			            url: 'ValiderModifySynset2.php',
			            data: $(this).serialize(),
			            
			            success: function (response) {
			            	console.log(response);
			            	
			                alert(response);
			               // $(this).hide();
			            }
			          });

			        });
			      });
			    </script>
				
			    <tr><td class="tdi">
			    		<form id=<?php echo "'synset".$synset['idSynset']."'";?>>
			     		 <div class="slide">
			     		 	<table class="en-ar-table">
			     		 		<tr>
			     		 			<th>English</th><th>Arabic</th>
			     		 		</tr>
			     		 		<tr>
			     		 			<td class="td1">
			     		 				<table class="english_section">
			     		 					<?php
			     		 						echo "<tr><td><span class='id_synset'>Id synset:</span> ".$synset['idSynset']."</td></tr>";
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
			     		 				<table class="arabic_section">
			     		 					<?php
			     		 						if(isset($arabicver->words) && count($arabicver->words->children())>0)
			     		 						{
			     		 							echo "<tr class='tr_title'><td>Synonyms:</td><td>true</td><td>false</td></tr>";
			     		 							$i=0;
			     		 							foreach ($arabicver->words->children() as $word) {
			     		 								echo "<tr><td>".$word['value']."</td><td>";
			     		 								$t=false;
			     		 								foreach ($word->validations->children() as $validation) {
			     		 									
			     		 									if($validation['id']==$id_user)
			     		 										if($validation['value']=="true"){echo "<input type='radio' name='".$synset['idSynset']."[w-$i]' value='true' checked></td><td><input type='radio' name='".$synset['idSynset']."[w-$i]' value='false'></td></tr>";$t=true;break;}
			     		 										else{ echo "<input type='radio' name='".$synset['idSynset']."[w-$i]' value='true' ></td><td><input type='radio' name='".$synset['idSynset']."[w-$i]' value='false' checked></td></tr>";$t=true;break;}
			     		 									
			     		 								}
			     		 								if($t==false)echo "<input type='radio' name='".$synset['idSynset']."[w-$i]' value='true' ></td><td><input type='radio' name='".$synset['idSynset']."[w-$i]' value='false'></td></tr>";
			     		 								
			     		 								$i++;
			     		 							}
						     		 		        

						     		 			}
						     		 			if(isset($arabicver->examples) && count($arabicver->examples->children())>0)
						     		 			{
						     		 				echo "<tr class='tr_title'><td>Examples:</td><td></td><td></td></tr>";
						     		 				$i=0;
						     		 				foreach ($arabicver->examples->children() as $ex) 
						     		 				{

						     		 					echo "<tr><td>".$ex['value']."</td>";
						     		 					$t=false;
						     		 					foreach ($ex->validations->children() as $validation) {
						     		 						if($validation['id']==$id_user)
			     		 										if($validation['value']=="true"){
			     		 											echo "<td><input type='radio' name='".$synset['idSynset']."[ex-$i]' value='true' checked></td><td><input type='radio' name='".$synset['idSynset']."[ex-$i]' value='false'></td></tr>";
			     		 											$t=true;break;

			     		 										}else{
			     		 											echo "<td><input type='radio' name='".$synset['idSynset']."[ex-$i]' value='true'></td><td><input type='radio' name='".$synset['idSynset']."[ex-$i]' value='false' checked></td></tr>";
			     		 											$t=true;break;
			     		 										}
						     		 						
						     		 					}
						     		 					if($t==false)echo "<td><input type='radio' name='".$synset['idSynset']."[ex-$i]' value='true'></td><td><input type='radio' name='".$synset['idSynset']."[ex-$i]' value='false'></td></tr>";
						     		 					$i++;
						     		 					
						     		 				}
						     		 			}
						     		 			if(isset($arabicver->vocalisations) && count($arabicver->vocalisations->children())>0)
						     		 			{
						     		 		
						     		 				echo "<tr class='tr_title'><td>Vocalisations:</td></tr>";
						     		 				$i=0;
						     		 				foreach ($arabicver->vocalisations->children() as $voca)
						     		 				{	echo "<tr><td>".$voca['value']."</td>";
						     		 					$t=false;
						     		 					foreach ($voca->validations->children() as $validation) {
						     		 						
						     		 						
						     		 						if($validation['id']==$id_user)
			     		 										if($validation['value']=="true"){$t=true;
			     		 											echo "<td><input type='radio' name='".$synset['idSynset']."[voca-$i]' value='true' checked></td><td><input type='radio' name='".$synset['idSynset']."[voca-$i]' value='false'></td></tr>";break;
			     		 										}else{
			     		 											echo "<td><input type='radio' name='".$synset['idSynset']."[voca-$i]' value='true'></td><td><input type='radio' name='".$synset['idSynset']."[voca-$i]' value='false' checked></td></tr>";$t=true;break;

			     		 										}
						     		 					}
						     		 					
						     		 					
						     		 					if($t==false){echo "<td><input type='radio' name='".$synset['idSynset']."[voca-$i]' value='true'></td><td><input type='radio' name='".$synset['idSynset']."[voca-$i]' value='false'></td></tr>";}
						     		 					$i++;
						     		 				}
						     		 			}
						     		 		?>
			     		 				</table>
			     		 			</td>
			     		 		</tr>
			     		 		
			     		 	</table>
			     		 </div>
			     		 
			      	<?php if($synset['state']=="invalid") echo "<input type='submit' name='modify' value='Modify' class='validate'>" ;
			      	else echo "<div class='msg'><p>this synset has been validated</p></div>";
			      	?>
			    	</form>
			    </td></tr>
			    <?php
					}
			    ?>

			  </table>
			  
		</div>
				
	</section>
	<script type="text/javascript">
		$(function(){
    		$('.flexslider').flexslider();
  			});
	</script>
	
	
		
	</body>
</html>
<?php
}
else{
	header('Location:connexion.php');
}
?>