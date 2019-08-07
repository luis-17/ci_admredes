<?php

    //database details
    $dbHost     = 'rdsinstancemysql.czvvckkesgis.us-east-2.rds.amazonaws.com:3306';
    $dbUsername = 'rsaws2019';
    $dbPassword = 'hcarsAcces2019$';
    $dbName     = 'new_redes_admin';
    
    //create connection and select DB
    $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    mysqli_set_charset($db, "utf8");
    if($db->connect_error){
        die("Unable to connect database: " . $db->connect_error);
    }
?>