<?php
if(isset($_POST['importSubmit'])){
    
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    $connect = new PDO("mysql:host=localhost; dbname=test", "root", "");
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
                $name   = $line[1];
                $link  = $line[2];
                $client  = $line[3];
                $tag = $line[4];
                $status = $line[5];
                $is_feature = $line[6];
                $industry = $line[7];
                $theme = $line[8];
                $plugins = $line[9];
                $payment_gateways = $line[10];
                $other_comments = $line[11];
                
                // Check whether member already exists in the database with the same email
                $query = "SELECT id FROM projects WHERE link = '".$line[2]."'";
                //$prevResult = $db->query($prevQuery);
                $prevResult = $connect->prepare($query);
                $prevResult->execute();
                $row = $prevResult->fetch(PDO::FETCH_ASSOC);
                if(!empty($row)){
                    // Update member data in the database
                    $e_query = "UPDATE projects SET name = '".$name."', client = '".$client."', tag = '".$tag."', status = '".$status."',
                                is_feature = '".$is_feature."', industry = '".$industry."', theme = '".$theme."' , plugins = '".$plugins."'
                                , payment_gateways = '".$payment_gateways."', other_comments = '".$other_comments."' WHERE link = '".$link."'";
                }else{
                    // Insert member data in the database
                    $e_query = "INSERT INTO projects (name, link, client, tag, status, is_feature,industry,theme,plugins,payment_gateways,other_comments) VALUES 
                    ('".$name."', '".$link."', '".$client."', '".$tag."','".$status."','".$is_feature."','".$industry."','".$theme."','".$plugins."', '".$payment_gateways."', '".$other_comments."')";
                }
                $result = $connect->prepare($e_query);
                $result->execute();
            }
            // Close opened CSV file
            fclose($csvFile);
            
            header("Location: textauto.php?success=Data imported successfully");

            exit();
        }else{
            header("Location: textauto.php?error=Something Went Wrong");

            exit();
        }
    }else{
        header("Location: textauto.php?error=Invalid file");

            exit();
    }
}
?>