<?php
if(isset($_GET["query"]))
{
 $connect = new PDO("mysql:host=localhost; dbname=test", "root", "");

 $query = "
 SELECT industry FROM projects  
 WHERE industry like '%".lcfirst($_GET["query"])."%' OR industry like '%".ucfirst($_GET["query"])."%'
 ";

 $statement = $connect->prepare($query);

 $statement->execute();


 $data = array();
 while($row = $statement->fetch(PDO::FETCH_ASSOC))
 {
    $data[] = $row['industry'];
 }
}

echo json_encode(array_unique($data));

?>