<?php
require_once '../../config.php';
    function getImage($sql){
        if(!$_GET['email']) return;
        $conn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if(!$conn){
            exit('数据库连接失败');
        }
        $query=mysqli_query($conn,$sql);
        $row=mysqli_fetch_assoc($query);
        mysqli_free_result($query);
        mysqli_close($conn);
        echo $row['avatar'];
    }

    $email=$_GET['email'];
    $sql="select * from users where email='{$email}' limit 1";
    getImage($sql);
?>