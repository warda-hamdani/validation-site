<?php 

function tested($instance,$id_user){
  $t=false;
  foreach ($instance->arabVersion->children() as $child3) //child3 is words examples or voca
    {
      foreach ($child3 as $child4) //child4 is word or ex or voca
      { if($t==true)break;
        foreach ($child4->validations->children() as $validation) 
        { 
          if($t==true)break;
          
          if($validation['id']==$id_user){
            //echo $instance['idSynset'];
            $t=true;
            break;
          }
        }
      }

         if($t==true){break;}
       }
       return $t;
}
function getSynsets($nb,$id)
{
	$xml=simplexml_load_file("instances.xml") or die ("Failed to load xml file");
	$add=0;//number we add so we extract $nb+$add  synsets and we random $nb synsets
  $limite=5;//number of person checked the synsets
	$n=1;
	
	$synsets=array();

	foreach($xml->children() as $instance) 
 	{	 		
 		if($n>($nb+$add)) break;
 		
 		if($instance['state']=="invalid")
 		{	/* ===============================> code to test the links  if level !=0*/
      if($instance['level']!='0'){$link_valid=false;
      foreach ($instance->links->children() as $link) {
          if(isset($link['state'])  && $link['state']=="valid")$link_valid=true;
        }}
      
      if( $instance['level']=='0' || (isset($link_valid) && $link_valid==true) ){
        $tested=tested($instance,$id);
      }
  			
 			//==============================>we will add here the test if synset not treated already we put it in the array
 			if(isset($tested) && $tested==false)
 			{ $users=array();
        foreach ($instance->arabVersion->words->children() as $word) 
            foreach ($word->validations->children() as $validation) {
              
              if(!in_array($validation['id'],$users))$users[]=$validation['id'];
            }
             
     		if(count($users)<$limite)
          {$n++;
     				$synsets[]=$instance;}
      }
  			
  	}
  		
}
    //=================> we random $nb synsets from $nb+$add synsets
    $synsets2=array();
    for($i=0;$i<$nb;$i++){
      $a=rand(0,count($synsets)-1);
      $synsets2[]=$synsets[$a];
      unset($synsets[$a]);
      $synsets=array_merge($synsets);

    }
  	return $synsets2;
}

function getSynsHisto($id_user){
  $xml=simplexml_load_file("instances.xml") or die ("Failed to load xml file");
  $synsets=array();

  foreach ($xml as $instance) {
       $t=tested($instance,$id_user);
       if($t==true)$synsets[]=$instance;     

  }
  return $synsets;
}

function stat1(){
  $xml=simplexml_load_file("instances.xml");
  $Dropped=array();$Valid=array();$Invalid=array();
  foreach ($xml as $instance) {
    switch ($instance['state']) {
      case 'valid':$Valid[]=$instance;break;
      case 'invalid':$Invalid[]=$instance;break;
      case 'dropped':$Dropped[]=$instance;break;
    }
  }
  return array($Valid,$Invalid,$Dropped);
}

function get_xml_user($id_user){
  $newXML = new SimpleXMLElement("<instances></instances>");
  $xml=simplexml_load_file("instances.xml") or die ("Failed to load xml file");

  foreach ($xml as $instance) {
    if(tested($instance,$id_user)==true){
      $child1=$newXML->addChild("instance");
    
      foreach ($instance->attributes() as $a => $b) 
        
        if($a!="checked")$child1->addAttribute($a,$b);
      
      foreach ($instance as $enarlink) {
        if($enarlink->getName()!="links"){
          $child2=$child1->addChild($enarlink->getName());
        foreach ($enarlink->attributes() as $a => $b) 
          $child2->addAttribute($a,$b);
        
        foreach ($enarlink as $wevs) {//examples words defs vocas
          $child3=$child2->addChild($wevs->getName());

          foreach ($wevs as $wev) {
            $child4=$child3->addChild($wev->getName());
            foreach ($wev->attributes() as $k => $v) 
              $child4->addAttribute($k,$v);
            
            if(isset($wev->validations)){$child5=$child4->addChild('validations');
            foreach ($wev->validations->children() as $v) 
            if(isset($v['id']) && $v['id']==$id_user){
              $child6=$child5->addChild('validation');
              $child6->addAttribute('id',$v['id']);
              $child6->addAttribute('value',$v['value']);
              }
            
              }
          }}
      }   
      }}}
  return $newXML;
}
?>