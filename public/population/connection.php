<?php

    //database details
    $dbHost     = '50.62.209.11:3306';
    $dbUsername = 'dev_user_rs';
    $dbPassword = 'redsaludRS@2018';
    $dbName     = 'db_devrs';
    
    //create connection and select DB
    $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    mysqli_set_charset($db, "utf8");
    if($db->connect_error){
        die("Unable to connect database: " . $db->connect_error);
    }
?>