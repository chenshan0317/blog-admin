<?php
require_once '../func.php';
if(empty($_GET['id'])){
    exit();
}
$deleCate_sql="delete from comments where id in ({$_GET['id']})";
$rows=updateOneData($deleCate_sql);
header('Content-Type:application/json');
echo json_encode($row>0);
?>