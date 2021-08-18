<?php

//Config file
require_once './config/db_function.php';

//Database Object
$db = new DB_Functions();

// Get Products from Database

$products = $db->getProducts();