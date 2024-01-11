<?php
header('Content-Type: application/json'); // Set the content type to JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); 

if ($_SERVER['REQUEST_METHOD']=="POST"){
    require_once '../config/database.php';
    include '../class/functions.php';
    $user -> signup();
}

?>