<?php

class DB_Functions{
    function __construct(){
        require_once 'db_connect.php';
        $db = new DB_Connect();
        $this->conn = $db->connect();
    }

    function  __destruct(){
        // TODO: Implement __destruct() method.
    }


    /*
     * Database Functions
    */

    /*
     * Get products functions
     * Return product object
     * Return Null if no product exist
     */

    function getProducts(){
        $result = $this->conn->query("SELECT * FROM product");
        $products = array();
        while($item = $result->fetch_assoc())
            $products[] = $item;
        if(!empty($products != 0)){
            return $products; 
        }else{
            return NULL;
        }
    }

    
}

?>