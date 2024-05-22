<?php

//fetch.php;



function array_search_partial($arr, $keyword) {
    $result = [];
    foreach($arr as $string) {
        if (strpos($string, $keyword) !== FALSE)
            $result[] = $string;
        }
        return $result;   
}
if(isset($_GET["query"]))
{
 $connect = new PDO("mysql:host=localhost; dbname=test", "root", "");

 $query = "
 SELECT client FROM projects  
 WHERE client like '%".lcfirst($_GET["query"])."%' OR client like '%".ucfirst($_GET["query"])."%'
 ";

 $statement = $connect->prepare($query);

 $statement->execute();


 $data = array();
 while($row = $statement->fetch(PDO::FETCH_ASSOC))
 {
    $data[] = $row['client'];
 }
}

echo json_encode(array_unique($data));

?>