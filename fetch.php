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
 SELECT tag FROM projects  
 WHERE tag like '%".lcfirst($_GET["query"])."%' OR tag like '%".ucfirst($_GET["query"])."%' 
 ";


 $statement = $connect->prepare($query);

 $statement->execute();


$r_data = [];
$f_data = [];
$l_data = [];
 while($row = $statement->fetch(PDO::FETCH_ASSOC))
 {
    $tags_arr = explode(',',$row["tag"]);
    $data = array_search_partial($tags_arr, lcfirst($_GET["query"]));
    $udata = array_search_partial($tags_arr, ucfirst($_GET["query"]));
    if(!empty($data)):
    $f_data[] = $data;
    endif;
    if(!empty($udata)):
    $l_data[] = $udata;
    endif;
 }
}


if(!empty($l_data) && !empty($f_data)):
    $r_data = array_merge($f_data,$l_data);
elseif(!empty($l_data) && empty($f_data)):
    $r_data = $l_data;
elseif(empty($l_data) && !empty($f_data)):
    $r_data = $f_data;
endif;

$data = array();
foreach ($r_data as $r){
  $data = array_unique(array_merge($data, $r));
}

echo json_encode($data);

?>