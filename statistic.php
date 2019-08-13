<?php
session_start();
if(isset($_SESSION['id'])){
	$id_user=$_SESSION['id'];
include 'functions.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Statistics</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="js/jquery-3.3.1.min.js" ></script>
		<script src="js/chart.js" ></script>
		
		<!--<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>-->
		<link rel="stylesheet" href="css/stylesheet2.css">
	</head>
	<body>
		<div class="navbar">
  			<a href="admin.php">Synsets validation</a>
  			<label>statistics</label>
  			<a href="logout.php">logout</a>
  		
		</div>
		<section id="body">
			<div id="stat">
			<h1>Statistics show the number of synsets for each category</h1>
			<canvas id="myChart" ></canvas>
			</div>
			<?php
				$table=stat1();
				$nbValid=count($table[0]);
				$nbInvalid=count($table[1]);
				$nbDropped=count($table[2]);

			?>
			<div>

				
				<div id="buttons">
			
					
					<a href="#invalid_synsets" class="button" id="show_invalid">Show invalid synsets</a>
					<a href="#valid_synsets" class="button" id="show_valid">Show valid synsets</a>
					<a href="#dropped_synsets" class="button" id="show_dropped">Show dropped synsets</a>

				
				</div>
				<script type="text/javascript">
					
					$("#show_valid").click(function(){
						$(this).css('background-color',"#d1c0c0");
						$("#show_invalid").css('background-color',"rgba(54, 162, 235, 0.8)");
						$("#show_dropped").css('background-color',"rgba(54, 162, 235, 0.8)");
						$("#valid_synsets").show();
						$("#invalid_synsets").hide();
						$("#dropped_synsets").hide();
					});
					$("#show_invalid").click(function(){
						$(this).css('background-color',"#d1c0c0");
						$("#show_valid").css('background-color',"rgba(54, 162, 235, 0.8)");
						$("#show_dropped").css('background-color',"rgba(54, 162, 235, 0.8)");
						$("#valid_synsets").hide();
						$("#invalid_synsets").show();
						$("#dropped_synsets").hide();
					});
					$("#show_dropped").click(function(){
						$(this).css('background-color',"#d1c0c0");
						$("#show_invalid").css('background-color',"rgba(54, 162, 235, 0.8)");
						$("#show_valid").css('background-color',"rgba(54, 162, 235, 0.8)");
						$("#valid_synsets").hide();
						$("#invalid_synsets").hide();
						$("#dropped_synsets").show();
					});
				</script>
				
				<div id="invalid_synsets">
					<table>
					<?php 
					if(count($table[1])==0)echo "<p>No synset in this categorie</p>";
						foreach ($table[1] as $instance) {
							$englishver=$instance->engVersion;
							$arabicver=$instance->arabVersion;

							?>

							<tr>
								<td class="tdi">
									<table class="en-ar-table">
					     		 		<tr>
					     		 			<th>English</th><th>Arabic</th>
					     		 		</tr>
					     		 		<tr>
					     		 			<td class="td1">
					     		 				<table class="english_section2">
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
					     		 				<table class="arabic_section3">
					     		 					
					     		 					<?php
			     		 						if(isset($arabicver->words) && count($arabicver->words->children())>0)
			     		 						{
			     		 							echo "<tr class='tr_title'><td>Synonyms:</td></tr>";
			     		 							
			     		 							foreach ($arabicver->words->children() as $word) {
			     		 								echo "<tr><td>".$word['value']."</td></tr>";
			     		 								     		 								
			     		 								}
						     		 		        

						     		 			}
						     		 			if(isset($arabicver->examples) && count($arabicver->examples->children())>0)
						     		 			{
						     		 				echo "<tr class='tr_title'><td>Examples:</td></tr>";
						     		 				
						     		 				foreach ($arabicver->examples->children() as $ex) 
						     		 				{

						     		 					echo "<tr><td>".$ex['value']."</td></tr>";
						     		 					
						     		 					
						     		 				}
						     		 			}
						     		 			if(isset($arabicver->vocalisations) && count($arabicver->vocalisations->children())>0)
						     		 			{
						     		 		
						     		 				echo "<tr class='tr_title'><td>Vocalisations:</td></tr>";
						     		 				$i=0;
						     		 				foreach ($arabicver->vocalisations->children() as $voca)
						     		 				{	echo "<tr><td>".$voca['value']."</td></tr>";
						     		 					
						     		 				}
						     		 			}
						     		 		?>
						     		 			</table>
						     		 		</td>
						     		 		</tr>
					     		 			
					     		 		
					     		 	</table>
								</td>
							</tr>
					<?php }
					 ?>
					 </table>
				</div>
				<div id="valid_synsets">
					<table>
					<?php 
						if(count($table[0])==0)echo "<p>No synset in this categorie</p>";
						foreach ($table[0] as $instance) {
							$englishver=$instance->engVersion;
							$arabicver=$instance->arabVersion;

							?>

							<tr>
								<td class="tdi" >
									<table class="en-ar-table">
					     		 		<tr>
					     		 			<th>English</th><th>Arabic</th>
					     		 		</tr>
					     		 		<tr>
					     		 			<td class="td1">
					     		 				<table class="english_section2">
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
					     		 				
					     		 				<table class="arabic_section3">
					     		 					<?php
						     		 						if(isset($arabicver->words) && count($arabicver->words->children())>0)
						     		 						{
						     		 							echo "<tr class='tr_title'><td>Synonyms:</td></tr>";
						     		 							
						     		 							foreach ($arabicver->words->children() as $word) {
						     		 								$found=false;
						     		 								foreach ($word->validations->children() as $validation) {
						     		 									if(isset($validation['admin_id']) && $validation['value']=="true"){$found=true;break;}
						     		 								}
						     		 								

						     		 								if($found==true)echo "<tr><td><img src='images/true.png'>".$word['value']."</td></tr>";
						     		 												else echo "<tr><td><img src='images/false.png'>".$word['value']."</td></tr>";
						     		 								     		 								
						     		 								}
									     		 		        

									     		 			}
									     		 			if(isset($arabicver->examples) && count($arabicver->examples->children())>0)
									     		 			{
									     		 				echo "<tr class='tr_title'><td>Examples:</td></tr>";
									     		 				
									     		 				foreach ($arabicver->examples->children() as $ex) 
									     		 				{
									     		 					$found=false;
						     		 								foreach ($ex->validations->children() as $validation) {
						     		 									if(isset($validation['admin_id']) && $validation['value']=="true"){$found=true;break;}
						     		 								}
						     		 								

						     		 								if($found==true)echo "<tr><td><img src='images/true.png'>".$ex['value']."</td></tr>";
						     		 												else echo "<tr><td><img src='images/false.png'>".$ex['value']."</td></tr>";
									     		 					
									     		 					
									     		 				}
									     		 			}
									     		 			if(isset($arabicver->vocalisations) && count($arabicver->vocalisations->children())>0)
									     		 			{
									     		 		
									     		 				echo "<tr class='tr_title'><td>Vocalisations:</td></tr>";
									     		 				$i=0;
									     		 				foreach ($arabicver->vocalisations->children() as $voca)
									     		 				{	
									     		 					$found=false;
						     		 								foreach ($voca->validations->children() as $validation) {
						     		 									if(isset($validation['admin_id']) && $validation['value']){$found=true;break;}
						     		 								}
						     		 								

						     		 								if($found==true)echo "<tr><td><img src='images/true.png'>".$voca['value']."</td></tr>";
						     		 												else echo "<tr><td><img src='images/false.png'>".$voca['value']."</td></tr>";
									     		 					
									     		 				}
									     		 			}
									     		 		?>
					     		 					
					     		 				</table>
					     		 			
					     		 			
								</td>
							</tr>
						</table>
					</td>
				</tr>
					<?php }
					 ?>
					 </table>
					
				</div>
				<div id="dropped_synsets">
					<table>
					<?php 
						if(count($table[2])==0)echo "<p>No synset in this categorie</p>";
						foreach ($table[2] as $instance) {
							$englishver=$instance->engVersion;
							$arabicver=$instance->arabVersion;

							?>

							<tr>
								<td class="tdi">
									<table class="en-ar-table">
					     		 		<tr>
					     		 			<th>English</th><th>Arabic</th>
					     		 		</tr>
					     		 		<tr>
					     		 			<td class="td1">
					     		 				<table class="english_section2">
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
					     		 				<table class="arabic_section3">
					     		 					<?php
			     		 						if(isset($arabicver->words) && count($arabicver->words->children())>0)
			     		 						{
			     		 							echo "<tr class='tr_title'><td>Synonyms:</td></tr>";
			     		 							
			     		 							foreach ($arabicver->words->children() as $word) {
			     		 								echo "<tr><td>".$word['value']."</td><tr>";
			     		 								     		 								
			     		 								}
						     		 		        

						     		 			}
						     		 			if(isset($arabicver->examples) && count($arabicver->examples->children())>0)
						     		 			{
						     		 				echo "<tr class='tr_title'><td>Examples:</td></tr>";
						     		 				
						     		 				foreach ($arabicver->examples->children() as $ex) 
						     		 				{

						     		 					echo "<tr><td>".$ex['value']."</td></tr>";
						     		 					
						     		 					
						     		 				}
						     		 			}
						     		 			if(isset($arabicver->vocalisations) && count($arabicver->vocalisations->children())>0)
						     		 			{
						     		 		
						     		 				echo "<tr class='tr_title'><td>Vocalisations:</td></tr>";
						     		 				$i=0;
						     		 				foreach ($arabicver->vocalisations->children() as $voca)
						     		 				{	echo "<tr><td>".$voca['value']."</td></tr>";
						     		 					
						     		 				}
						     		 			}
						     		 		?>
					     		 				</table>
					     		 			</td>
					     		 		</tr>
					     		 	</table>
								</td>
							</tr>
					<?php }
					 ?>
					 </table>
					
				</div>
			</div>
		
		</section>
		<script>
			var canvas = document.getElementsByTagName('canvas')[0];
			canvas.style.width = "500px";
			canvas.style.height = "150px";
			var ctx = document.getElementById('myChart').getContext('2d');
			var myChart = new Chart(ctx, {
			    type: 'pie',
			    data: {
			        labels: [<?php echo "'Invalid $nbInvalid'"; ?>,<?php echo "'Valid $nbValid'"; ?>,  <?php echo "'Dropped $nbDropped'"; ?>],
			        datasets: [{
			            label: 'number of synsets',
			            data: [<?php echo $nbInvalid ?>,<?php echo $nbValid ?> ,  <?php echo $nbDropped ?>],
			            backgroundColor: [
			                'rgba(255, 159, 64, 0.2)',
			                'rgba(54, 162, 235, 0.2)',			                
			                'rgba(255, 99, 132, 0.2)'
			            ],
			            borderColor: [
			                'rgba(255, 159, 64, 1)',
			                'rgba(54, 162, 235, 1)',			                
			                'rgba(255, 99, 132, 1)'
			            ],
			            borderWidth: 1
			        }]
			    },
			    
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