<?php
require_once '../func.php';
if(empty($_GET['id'])){
    exit();
}
$deleCate_sql="delete from categories where id in ({$_GET['id']})";
updateOneData($deleCate_sql);
header('Location: /admin/categories.php');
?>