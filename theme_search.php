<?php
if(isset($_GET["query"]))
{
 $connect = new PDO("mysql:host=localhost; dbname=test", "root", "");

 $query = "
 SELECT theme FROM projects  
 WHERE theme like '%".lcfirst($_GET["query"])."%' OR theme like '%".ucfirst($_GET["query"])."%'
 ";

 $statement = $connect->prepare($query);

 $statement->execute();


 $data = array();
 while($row = $statement->fetch(PDO::FETCH_ASSOC))
 {
    $data[] = $row['theme'];
 }
}

echo json_encode(array_unique($data));

?>