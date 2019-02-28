<?php
//接收客户端传递过来的数据，并返回所有的评论数据
require_once '../../func.php';
$current_p=empty($_GET['current_p'])?1:intval($_GET['current_p']);
$size=1;
$offset=($current_p-1)*$size;
$sql=sprintf('select comments.*,posts.id as title from comments 
    inner join users on comments.author=users.nickname 
    inner join posts on posts.id=comments.post_id
    order by comments.created desc
    limit %d,%d',$offset,$size);
$countSql="select count(1) as count from comments 
inner join users on comments.author=users.nickname 
inner join posts on posts.id=comments.post_id";
$allComments=findAllData($sql);
$allCount=findOneData($countSql)['count'];
$count=(int)ceil($allCount/$size);
//因为网络传输只能是字符串
//所以要将数据转换成字符串（序列化）
$comments=json_encode(array(
    "count"=>$count,
    "allComments"=>$allComments
));
header('Content-Type:application/json');
echo $comments;
?>