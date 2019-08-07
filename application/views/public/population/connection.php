<?php

    //database details
    $dbHost     = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName     = 'new_redes_admin';
    
    //create connection and select DB
    $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    mysqli_set_charset($db, "utf8");
    if($db->connect_error){
        die("Unable to connect database: " . $db->connect_error);
    }
?>