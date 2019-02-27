<?php
require_once '../func.php';
$current_page=getCurrentPage();

function addCategory(){
  if(empty($_POST['name'])||empty($_POST['slug'])){
    $GLOBALS['err']='添加失败，分类名或别名为空';
    exit();
  }
  $name=$_POST['name'];
  $slug=$_POST['slug'];
  $addCate_sql="insert into categories values (null, '{$slug}', '{$name}');";
  updateOneData($addCate_sql);
  $GLOBALS['err']='添加成功';
}

function editCategory(){
  // $id_arr=explode('=',$_SERVER['QUERY_STRING']);
  // $id=$id_arr[2];
  global $current_category;
  $id=$_GET['id'];
  $current_category=findOneData('select * from categories where id = ' .$id);
  if(empty($_POST['name'])||empty($_POST['slug'])){
    $GLOBALS['err']='修改失败，分类名或别名为空';
    exit();
  }

  if($current_category['name']===$_POST['name']&&$current_category['slug']===$_POST['slug']){
    //没有修改
    $GLOBALS['err']='没有发生修改';
    exit();
  }
  $name=$_POST['name'];
  $slug=$_POST['slug'];
  $editCate_sql="update categories set slug = '{$slug}', name = '{$name}' where id = {$id}";
  updateOneData($editCate_sql);
  $GLOBALS['err']='修改成功';
  header("Location:/admin/categories.php");
}

if(isset($_GET['id'])){
  //编辑主线
  $current_category=findOneData("select * from categories where id='{$_GET['id']}'");
  if($_SERVER['REQUEST_METHOD']==='POST'){
    editCategory();
  }
}else{
  //添加主线
  if($_SERVER['REQUEST_METHOD']==='POST'){
    addCategory();
  }
}



$cates=findAllData('select * from categories');
if(empty($cate)){
  $cate=[];
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <?php if(isset($GLOBALS['err'])):?>
      <!-- 有错误信息时展示 -->
        <div class="alert alert-danger">
          <strong>information！</strong><?php echo $GLOBALS['err'];?>
        </div>
      <?php endif ?>
      <div class="row">
        <div class="col-md-4">
          <form action="<?php echo $_SERVER['PHP_SELF'];?><?php echo isset($current_category)?"?id='{$current_category['id']}'":'';?>" method='POST'>
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称" value="<?php echo isset($current_category)?$current_category['name']:'';?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo isset($current_category)?$current_category['slug']:'';?>">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="/admin/category-delete.php" style="display: none" id='btnDel'>批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($cates as $item):?>
              <tr>
                  <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id'];?>"></td>
                  <td><?php echo $item['name']?></td>
                  <td><?php echo $item['slug']?></td>
                  <td class="text-center">
                    <a href="/admin/categories.php?id=<?php echo $item['id'];?>" class="btn btn-info btn-xs">编辑</a>
                    <a href="/admin/category-delete.php?id=<?php echo $item['id'];?>" class="btn btn-danger btn-xs">删除</a>
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

  <script>
  $(function($){
    var $allCheckbox=$('tbody input');
    var $allDel=$('#btnDel');
    var $delArr=[];
    $allCheckbox.on('change',function(){
      var id=$(this).data('id');
      //如果选中了，就放到数组中，否则，取出数组
      if($(this).prop('checked')){
        $delArr.includes(id)||$delArr.push(id);
      }else{
        $delArr.splice($delArr.indexOf(id),1);
      }
      console.log($delArr);
      $delArr.length?$allDel.attr('style','display:block'):$allDel.attr('style','display:none');
      $allDel.prop('search',"?id="+$delArr);
    })

    //全选或全不选
    var $headCheckBox=$('thead input');
    //当状态发生改变时
    $headCheckBox.on('change',function(){
      //获取当前选中状态
      var checked=$headCheckBox.prop('checked');
      $allCheckbox.prop('checked',checked).trigger('change');
    })
  })
  </script>
</body>
</html>
