<?php
session_start();
if(isset($_SESSION['id'])){
$id_user=$_SESSION['id'];
/**
 * 
 */

include 'functions.php';
 	
  	
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
  		<!--<a href="#news">home</a>-->
  		<a href="historic.php?id=<?php

  			echo $_SESSION['id'];
  			?>">historic</a>
  			<a href="logout.php">logout</a>
  		<!--<a href="plus.php">Plus</a>-->
  		<div dir='rtl'>
  			<?php

  			echo $_SESSION['user_name'];
  			?>
  		</div>
	</div>
	<section id="body">

		
		<div id="user_download">
			<a href="manual.pdf" target="_blank"><img src="images/manual.png"><span>Manual</span></a>
		</div>

	
		
	<!--<div id="nb_synset">
		<div>
			<form  action="index4.php" method="post" onsubmit="return checkINPUT()" name="nb_form">
				<label>Enter the number of synsets: </label><input type="text" name="number" id="input_nb">
				<input type="submit" name="ok" value="Ok">
			</form>
		</div>-->
	</div>
	<!--<script type="text/javascript">
		function checkINPUT()
			{
			    var x=document.forms["nb_form"]["number"].value;
			    var regex=/^[0-9]+$/;
			    if (!x.match(regex))
			    {
			        alert("the input must contain numbers only");
			        return false;
			    }
			}
	</script>-->
		<?php
		 	
		 		$nb_s=200;
		 		$synsets=getSynsets($nb_s,$id_user);
		 		

		 	

 	 
		?>

	<section>

		
		
		<div class="flexslider1">

			  <ul class="slides">
			  	
			  	<?php
																	
						foreach($synsets as $synset)
						{
							//$synset=$synsets[$j];
							$englishver=$synset->engVersion;
							$arabicver=$synset->arabVersion;							 
				?>
				
			    <li id="par<?php echo $synset['idSynset']?>" class="par">
			    	<form class="synsetValid">
			    	
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
						     		 		 		echo"<tr><td> ".$def['value']." </td></tr>";
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
			     		 								echo "<tr><td>".$word['value']."</td><td><input type='radio' name='".$synset['idSynset']."[w-$i]' value='true'></td><td><input type='radio' name='".$synset['idSynset']."[w-$i]' value='false'></td></tr>";
			     		 								$i++;
			     		 							}
						     		 		        

						     		 			}
						     		 			if(isset($arabicver->examples) && count($arabicver->examples->children())>0)
						     		 			{
						     		 				echo "<tr class='tr_title'><td>Examples:</td><td></td><td></td></tr>";
						     		 				$i=0;
						     		 				foreach ($arabicver->examples->children() as $ex) 
						     		 				{

						     		 					echo "<tr><td>".$ex['value']."</td><td><input type='radio' name='".$synset['idSynset']."[ex-$i]' value='true'></td><td><input type='radio' name='".$synset['idSynset']."[ex-$i]' value='false'></td></tr>";
						     		 					$i++;
						     		 					
						     		 				}
						     		 			}
						     		 			if(isset($arabicver->vocalisations) && count($arabicver->vocalisations->children())>0)
						     		 			{
						     		 		
						     		 				echo "<tr class='tr_title'><td>Vocalisations:</td></tr>";
						     		 				$i=0;
						     		 				foreach ($arabicver->vocalisations->children() as $voca)
						     		 				{
						     		 					echo "<tr><td>".$voca['value']."</td><td><input type='radio' name='".$synset['idSynset']."[voca-$i]' value='true'></td><td><input type='radio' name='".$synset['idSynset']."[voca-$i]' value='false'></td></tr>";
						     		 					$i++;
						     		 				}
						     		 			}
						     		 		?>
			     		 				</table>
			     		 			</td>
			     		 		</tr>
			     		 		
			     		 	</table>
			     		 </div>
			     		 
			      <div class="user_submit"><div><button id='prev<?php echo $synset['idSynset']?>' class='prev'>&#10094; Previous</button><input type="submit" name="valider" value="Validate" class="validate_user"> 
			      <input type="reset" value="Reset" class="reset_user"><button id='nextt<?php echo $synset['idSynset']?>' class='next'>Next &#10095;</button></div></div>
			    	</form>
			    	<script type="text/javascript">
			    		$('#nextt<?php echo $synset['idSynset']?>').click(function(e){
						    var $current = $('#par<?php echo $synset['idSynset']?>');
						    
						    e.preventDefault();
						    $current.next().addClass("active");
						    $current.removeClass("active");
						    
						});
			    	</script>
			    	<script type="text/javascript">
			    		$('#prev<?php echo $synset['idSynset']?>').click(function(e){
						    var $current = $('#par<?php echo $synset['idSynset']?>');
						    
						    e.preventDefault();
						    $current.prev().addClass("active");
						    $current.removeClass("active");
						    //$current.next().css( "display", "block" );
						    //$current.hide();
						});
			    	</script>
			    </li>
			    <?php
					}
			    ?>

			  </ul>
			  
		</div>
				
		

	</section>
	</section>

<style type="text/css">

	#nb_synset{
	position: relative;
	margin: 15%;
	margin-left: 20%;

}

</style>


	<script type="text/javascript">
		$(".slides li:first-child").addClass("active");
	</script>
	<!--<script type="text/javascript">
		$(function(){
    		$('.flexslider').flexslider();
  			});
	</script>-->
	<script>
      $(function () {
        $('.synsetValid').on('submit', function (event) {
        	
		event.preventDefault();

          $.ajax({
            type: 'POST',
            url: 'ValiderModifySynset2.php',
            data: $(this).serialize(),
            
            success: function (response) {
            	            	
                alert(response);
               
            }
          });

        });
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