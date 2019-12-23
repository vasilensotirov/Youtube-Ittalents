<?php

require_once 'config.php';
function getPDO() {
    try{
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );
        $db = new PDO('mysql:host='.DB_HOST. ":" . DB_PORT . ';dbname='.DB_NAME , DB_USER, DB_PASS, $options);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }
    catch (PDOException $e){
        echo "Oops...something went wrong: " . $e->getMessage();
    }
}