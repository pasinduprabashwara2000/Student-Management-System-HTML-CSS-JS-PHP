<?php
$host = "localhost";
$username = "root";
$password = "";
$db_name = "student_db";

$conn = new mysqli($host,$username,$password,$db_name);

if($conn->connect_error){
    die("Database Connection Failed : ".$conn->connect_error);
}


