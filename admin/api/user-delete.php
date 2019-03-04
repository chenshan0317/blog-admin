<?php
require_once '../../func.php';
if(empty($_GET['id'])){
    exit();
}
$deleCate_sql="delete from users where id in ({$_GET['id']})";
$rows=updateOneData($deleCate_sql);
header('Location: /admin/users.php');
?>