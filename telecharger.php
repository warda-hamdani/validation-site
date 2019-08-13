<?php
session_start()
if(isset($_SESSION['id'])){
	$id_user=$_SESSION['id'];

include "functions.php";
	
$newXML=get_xml_user($id_user);

header('Content-type: text/xml ; charset=UTF-8;');
header('Content-Disposition: attachment; filename="instances.xml"');

echo $newXML->asXML();
exit();

}
else{
	header('Location:connexion.php');
}
?>