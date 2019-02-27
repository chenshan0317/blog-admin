<?php
require_once '../func.php';
$current_page=getCurrentPage();


//文章状态转变
function convertStatus($status){
  $statusArr=array(
    'published' => '已发布',
    'drafted' => '草稿',
    'trashed' => '回收站'
  );
  return isset($statusArr[$status])?$statusArr[$status]:'未知';
}
//时间转换
function convertTimestamp($time){
  $stamp=strtotime($time);
  return date('Y年m月d日<b\r> H:i:s',$stamp);
}

$where="1=1";
$search="";
//搜索制定分类
if (isset($_GET['category']) && $_GET['category'] !== 'all') {
  $where .= ' and posts.category_id = ' . $_GET['category'];
  $search .= '&category=' . $_GET['category'];
}
//搜索指定状态
if(isset($_GET['status'])&&$_GET['status']!=='all'){
  $where.=" and posts.status='{$_GET['status']}'";
  $search.='&status='.$_GET['status'];
}
//每页展示20条数据
$size=20;

//当前页 由 url中获取
$current_p=isset($_GET['p'])?(int)$_GET['p']:1;
$countSql="select count(1) as count from posts 
inner join categories on posts.category_id=categories.id
inner join users on posts.user_id=users.id
where {$where}";
//总数据条数
$count=findOneData($countSql)['count'];

$begin=$current_p-1;
$end=$current_p+1;
if($begin<1){
  $begin=1;
  $end=$begin+2;
  $current_p=1;
}
if($current_p+1>ceil($count/$size)){
  $end=ceil($count/$size);
  $begin=$end-2;
  $current_p=$end;
}
$offset=($current_p-1)*$size;
if($_SERVER['REQUEST_METHOD']==='GET'){
  
  if(isset($_GET['posts'])&&$_GET['posts']=='del'){
    $delSql="delete from posts where id={$_GET['id']}";
    updateOneData($delSql);
    header("Location:/admin/posts.php");
  }
  
}

$sql="select posts.id as id,posts.title,users.nickname as user,categories.name as category,posts.created,posts.status from posts 
inner join categories on posts.category_id=categories.id
inner join users on posts.user_id=users.id
where {$where}
limit {$offset},{$size}";

$allData=findAllData($sql);
if(empty($allData)){
  $allData=[];
}
$cates=findAllData('select * from categories');
// //取得url中的当前p,实现上一页，下一页功能
// $current_p=getKeyValue()['p']==''?1:(int)getKeyValue()['p'];

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
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
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline" method='GET' action='<?php echo $_SERVER['PHP_SELF'];?>'>
          <select name="category" class="form-control input-sm">
            <option value="all">所有分类</option>
            <?php foreach($cates as $item):?>
            <option value="<?php echo $item['id']; ?>"<?php echo isset($_GET['category']) && $_GET['category'] == $item['id'] ? ' selected' : '' ?>><?php echo $item['name'];?></option>
            <?php endforeach?>
          </select>
          <select name="status" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option value="drafted"<?php echo isset($_GET['status'])&&$_GET['status']==='drafted'?' selected':'';?>>草稿</option>
            <option value="published"<?php echo isset($_GET['status'])&&$_GET['status']==='published'?' selected':'';?>>已发布</option>
            <option value="trashed"<?php echo isset($_GET['status'])&&$_GET['status']==='trashed'?' selected':'';?>>回收站</option>

          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
        <?php if($current_p>2):?>
          <li><a href="?p=<?php echo ($current_p-1).$search;?>">上一页</a></li>
        <?php else:?>
          <li><a href="">上一页</a></li>
        <?php endif?>
        
        <?php for($i=$begin;$i<=$end;$i++):?>
            <li <?php echo $i === $current_p ? ' class="active"' : '' ?>><a href="?p=<?php echo $i . $search; ?>"><?php echo $i; ?></a></li>
        <?php endfor?>
        <?php if($current_p+1<$end):?>
          <li><a href="?p=<?php echo ($current_p+1).$search;?>">下一页</a></li>
        <?php else:?>
        <li><a href="">下一页</a></li>
        <?php endif?>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($allData as $item):?>
          <tr>
              <td class="text-center"><input type="checkbox"></td>
              <td><?php echo $item['title'];?></td>
              <td><?php echo $item['user'];?></td>
              <td><?php echo $item['category'];?></td>
              <td class="text-center"><?php echo convertTimestamp($item['created']);?></td>
              <td class="text-center"><?php echo convertStatus($item['status']);?></td>
              <td class="text-center">
                <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
                <a href="?posts=del&id=<?php echo $item['id'];?>" class="btn btn-danger btn-xs">删除</a>
              </td>
            </tr>
        <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php include './inc/sidebar.php';?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
