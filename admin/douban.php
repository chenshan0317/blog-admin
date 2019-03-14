<?php
require_once '../func.php';
getSessionUser();
$current_page=getCurrentPage();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include './inc/navbar.php'; ?>
    <ul class="movieTitle">
    </ul>
  </div>

  <?php include './inc/sidebar.php';?>
  <script>
 function foo(res){
  resultArr=res.subjects;
  var html='';
  resultArr.forEach(function(value,i){
    html+='<li>'+value.title+'</li>';
  })
  $('ul.movieTitle').html(html);
 }
 </script>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="https://api.douban.com/v2/movie/in_theaters?callback=foo"></script>
  <script>NProgress.done()</script>
 
</body>
</html>
