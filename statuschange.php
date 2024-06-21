<?php

if(isset($_GET["status"]))
{
 $connect = new PDO("mysql:host=localhost; dbname=test", "root", "");
 $id=$_GET["id"];
if($_GET["status"] == 'active'):
  $status = 'inactive';
else:
  $status = 'active';
endif;
 $data = [
  'status' => $status,
  'id' => $id,
];
$sql = "UPDATE projects SET status='".$status."' WHERE id='".$id."'";
$stmt= $connect->prepare($sql);
$res=$stmt->execute();
if($_GET["page"] == 'inact'):
  header("Location: inactive_projects.php"); 
else:
  header("Location: textauto.php"); 
endif;
}
?>