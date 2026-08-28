<?php

session_start();

if(isset($_SESSION['id_user'])){
    header("location: dashboard.php");
    exit;
} else{
    header("location: login.php");
    exit;
}