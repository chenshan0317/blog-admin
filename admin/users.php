<?php 
require_once '../func.php';
$current_page=getCurrentPage();
function showAllUsers(){
  $sql="select * from users";
  $user_arr=findAllData($sql);
  return $user_arr;
}
function addOneUser(){
  if(empty($_POST['email'])||empty($_POST['slug'])||empty($_POST['nickname'])||empty($_POST['password'])){
    $GLOBALS['err']="输入项有空置，重新输入";
    return;
  }
  $email=$_POST['email'];
  $slug=$_POST['slug'];
  $nickname=$_POST['nickname'];
  $password=$_POST['password'];
  $sql="insert into users values (null,'{$email}','{$slug}','{$nickname}','{$password}',null,null,'activated')";
  return updateOneData($sql);
}

function editUser(){
  // $id_arr=explode('=',$_SERVER['QUERY_STRING']);
  // $id=$id_arr[2];
  global $current_use;
  $id=$_GET['id'];
  $current_use=findOneData('select * from users where id = ' .$id);
  if(empty($_POST['email'])||empty($_POST['slug'])||empty($_POST['nickname'])||empty($_POST['password'])){
    $GLOBALS['err']='修改失败，某一项值为空';
    exit();
  }

  if($current_use['email']===$_POST['email']&&$current_use['password']===$_POST['password']&&$current_use['nickname']===$_POST['nickname']&&$current_use['slug']===$_POST['slug']){
    //没有修改
    $GLOBALS['err']='没有发生修改';
    return;
  }
  $email=$_POST['email'];
  $slug=$_POST['slug'];
  $nickname=$_POST['nickname'];
  $password=$_POST['password'];
  $editUser_sql="update users set slug = '{$slug}', email = '{$email}',nickname='{$nickname}',password='{$password}' where id = {$id}";
  updateOneData($editUser_sql);
  $GLOBALS['err']='修改成功';
  header("Location:/admin/users.php");
}

if(isset($_GET['id'])){
  //编辑主线
  $current_use=findOneData("select * from users where id='{$_GET['id']}'");
  if($_SERVER['REQUEST_METHOD']==='POST'){
    editUser();
  }
}else{
  //添加主线
  if($_SERVER['REQUEST_METHOD']=="POST"){
    $result=addOneUser();
    if($result==-1){
      $GLOBALS['err']="添加失败";
      return;
    }
}
}


$users=showAllUsers();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Users &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <nav class="navbar">
      <button class="btn btn-default navbar-btn fa fa-bars"></button>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="profile.html"><i class="fa fa-user"></i>个人中心</a></li>
        <li><a href="login.html"><i class="fa fa-sign-out"></i>退出</a></li>
      </ul>
    </nav>
    <div class="container-fluid">
      <div class="page-title">
        <h1>用户</h1>
      </div>
      <!-- 有错误信息时展示 -->
       <?php if(isset($GLOBALS['err'])):?>
      <!-- 有错误信息时展示 -->
        <div class="alert alert-danger">
          <strong>information！</strong><?php echo $GLOBALS['err'];?>
        </div>
      <?php endif ?>
      <div class="row">
        <div class="col-md-4">
          <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?><?php echo isset($current_use)?"?id='{$current_use['id']}'":'';?>">
            <h2>添加新用户</h2>
            <div class="form-group">
              <label for="email">邮箱</label>
              <input id="email" class="form-control" name="email" type="email" placeholder="邮箱" value="<?php echo isset($current_use)?$current_use['email']:'';?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo isset($current_use)?$current_use['slug']:'';?>">
              <p class="help-block">https://zce.me/author/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <label for="nickname">昵称</label>
              <input id="nickname" class="form-control" name="nickname" type="text" placeholder="昵称" value="<?php echo isset($current_use)?$current_use['nickname']:'';?>">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input id="password" class="form-control" name="password" type="text" placeholder="密码" value="<?php echo isset($current_use)?$current_use['password']:'';?>">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
               <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th class="text-center" width="80">头像</th>
                <th>邮箱</th>
                <th>别名</th>
                <th>昵称</th>
                <th>状态</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($users as $item):?>
              <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td class="text-center"><img class="avatar" src="<?php echo $item['avatar'];?>"></td>
                <td><?php echo $item['email'];?></td>
                <td><?php echo $item['slug'];?></td>
                <td><?php echo $item['nickname'];?></td>
                <td><?php echo $item['status'];?></td>
                <td class="text-center">
                  <a href="?id=<?php echo $item['id'];?>" class="btn btn-default btn-xs">编辑</a>
                  <a href="/admin/api/user-delete.php?id=<?php echo $item['id'];?>" class="btn btn-danger btn-xs btn-delete">删除</a>
                </td>
              </tr>
            <?php endforeach?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php include './inc/sidebar.php';?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
