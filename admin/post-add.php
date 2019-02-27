<?php
require_once '../func.php';
$current_page=getCurrentPage();
$allCate=findAllData('select * from categories');
function addPost(){
  $avatar = $_FILES['feature'];
  // $avatar => array(5) {
  //   ["name"]=>
  //   string(11) "icon-02.png"
  //   ["type"]=>
  //   string(9) "image/png"
  //   ["tmp_name"]=>
  //   string(27) "C:\Windows\Temp\php1138.tmp"
  //   ["error"]=>
  //   int(0)
  //   ["size"]=>
  //   int(4398)
  // }
  if ($avatar['error'] !== UPLOAD_ERR_OK) {
    // 服务端没有接收到上传的文件
    $GLOBALS['message'] = '上传失败';
    return;
  }

  // 接收到了文件
  // 将文件从临时目录移动到网站范围之内
  $source = $avatar['tmp_name']; // 源文件在哪
  // => 'C:\Windows\Temp\php1138.tmp'
  $target = '/static/uploads/'.time(). $avatar['name']; // 目标放在哪
  // => './uploads/icon-02.png'
  // 移动的目标路径中文件夹一定是一个已经存在的目录
  $moved = move_uploaded_file($source."/".$avatar['name'], $target);

  // if (!$moved) {
  //   $GLOBALS['message'] = '上传失败';
  //   return;
  // }

  // 移动成功（上传整个过程OK）
  if(empty($_POST['title'])||empty($_POST['category'])||empty($_POST['content'])||empty($_POST['slug'])||empty($_POST['category'])||empty($_POST['created'])||empty($_POST['status'])){
    $GLOBALS['err']='不能为空';
  }
  var_dump($_POST['slug']);
  var_dump($_FILES['feature']);
  $slug=$_POST['slug'];
  $title=$_POST['title'];
  // $feature=$_POST['feature'];
  $created=$_POST['created'];
  $content=$_POST['content'];
  $views=0;
  $likes=0;
  $status=$_POST['status'];
  $user_id=getSessionUser()['id'];
  $category_id=$_POST['category'];
  $addPost_sql="insert into posts values (null,'{$slug}','{$title}','{$target}','{$created}','{$content}','{$views}','{$likes}','{$status}','{$user_id}','{$category_id}');";
  updateOneData($addPost_sql);
}
if($_SERVER['REQUEST_METHOD']==='POST'){
  addPost();
}

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
  <?php include './inc/navbar.php';?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form class="row" action="<?php echo $_SERVER['PHP_SELF']?>" method='POST' enctype="multipart/form-data">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <!-- <textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容"></textarea> -->
            <script id="container" name="content" type="text/plain"></script>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" style="display: none">
            <input id="feature" class="form-control" name="feature" type="file">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
            <?php foreach($allCate as $item):?>
              <option value="<?php echo $item['id'];?>"><?php echo $item['name'];?></option>
            <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted">草稿</option>
              <option value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php include './inc/sidebar.php';?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/ueditor-1.4.3.3-php-uf8/ueditor.config.js"></script>
  <script src="/static/assets/vendors/ueditor-1.4.3.3-php-uf8/ueditor.all.js"></script>
  <script type="text/javascript">
        var ue = UE.getEditor('container',{
          initialFrameHeight: 250,
          autoHeight: false
        });
  </script>
  <script>NProgress.done()</script>
</body>
</html>
