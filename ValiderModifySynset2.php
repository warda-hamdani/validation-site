<?php
session_start();
if(isset($_SESSION['id'])){
	$id_user=$_SESSION['id'];	
		$xml=simplexml_load_file("instances.xml") or die("Failed to load xml file");
		foreach ($_POST as $name_instance => $tab1) {
			$found=false;
			foreach ($xml->children() as $instance) 
			{  
				if($instance['idSynset']==$name_instance && $instance['state']=="invalid")
				{ $found=true;
					
						/*foreach ($child->children() as $child2) {
							$t=false;
							foreach ($child2->validations->children() as $validation) {
								if($validation['id']==$id_user)$t=true;$validation['value']=
							}

						}*/
						foreach ($tab1 as $tag_name => $value) {
							$a=explode("-", $tag_name);
							
							$t=false;
								if(strpos($a[0],"w")===0)
									{
										foreach ($instance->arabVersion->words->word[intval($a[1])]->validations->children() as $validation) 
										{
											if($validation['id']==$id_user){$t=true;$validation['value']=$value;}
										}
										if($t==false)$child=$instance->arabVersion->words->word[intval($a[1])]->validations->addChild("validation");
									}
								else if(strpos($a[0],"ex")===0)
									{	
										
										foreach ($instance->arabVersion->examples->example[intval($a[1])]->validations->children() as $validation) {
											if($validation['id']==$id_user){$t=true;$validation['value']=$value;}
										}
										if($t==false){$child=$instance->arabVersion->examples->example[intval($a[1])]->validations->addChild("validation");}
									}	

								else if(strpos($a[0],"voca")===0)
									{
										foreach ($instance->arabVersion->vocalisations->vocalisation[intval($a[1])]->validations->children() as $validation) {
											if($validation['id']==$id_user){$t=true;$validation['value']=$value;}
										}
										if($t==false)$child=$instance->arabVersion->vocalisations->vocalisation[intval($a[1])]->validations->addChild("validation");
									}
								if($t==false){$child->addAttribute("id",$id_user);
										$child->addAttribute("value",$value);
										if(!isset($instance['checked']))$instance->addAttribute("checked","true");
									}
						}
												
					
				}
				if($found==true){echo json_encode("success");break;}
			}

		}
		$xml->asXML("instances.xml");
}
		
		



else{
	header('Location:connexion.php');
}
?>