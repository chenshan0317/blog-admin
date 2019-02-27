<?php
session_start();
require_once '../func.php';
function login(){
  echo $_POST['email'];
  if(empty($_POST['email'])||empty($_POST['password'])){
    $GLOBALS['err']='邮箱或者密码为空';
    return;
  }
  $email=$_POST['email'];
  $password=$_POST['password'];
  $sql_login="select * from users where email='{$email}' limit 1";
  $user=findOneData($sql_login);
  $sql_pass=$user['password'];
  if($password!=$sql_pass){
    $GLOBALS['err']='邮箱或者密码错误';
    return;
  }else{
    header('Location:/admin/index.php');
    $_SESSION['user']=$user;
  }
}
if($_SERVER['REQUEST_METHOD']==='POST'){
  login();
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap" method='POST' autocomplete='on' action='<?php echo $_SERVER['PHP_SELF'];?>'>
      <img class="avatar" id='avatar' src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if(isset($GLOBALS['err'])):?>
        <div class="alert alert-danger">
          <strong>错误！</strong> <?php echo $GLOBALS['err'];?>
        </div>
      <?php endif?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" type="text" name='email' class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" type="password" name='password' class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block">登 录</button>
    </form>
  </div>
  <script src='/static/assets/vendors/jquery/jquery.min.js'></script>
  <script>
    $(function($){
      var reg=/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]+$/;
      $('#email').on('blur',function(){
        var value=$(this).val();
        if(!reg||!reg.test(value)) return;
        $.get('/admin/api/avatar.php',{email:value},function(res){
            $('#avatar').attr('src',res);
        })
      })
    })
  </script>
</body>

</html>
