<?php
require_once '../func.php';
getSessionUser();
?>
<div class="aside">
    <div class="profile">
      <img class="avatar" src="/static/uploads/avatar.jpg">
      <h3 class="name">布头儿</h3>
    </div>
    <ul class="nav">
      <li class=<?php echo $current_page==='index.php'?' active':"";?>>
        <a href="/admin/index.php"><i class="fa fa-dashboard"></i>仪表盘</a>
      </li>
      <li>
        <?php $arr=Array('posts.php','post-add.php','categories.php');?>
        <a href="#menu-posts" class="<?php echo in_array($current_page,$arr)?'':'collapsed';?>" data-toggle="collapse">
          <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-posts" class="collapse<?php echo in_array($current_page,$arr)?' in':'';?>">
          <li class=<?php echo $current_page==='posts.php'?' active':"";?>><a href="/admin/posts.php">所有文章</a></li>
          <li class=<?php echo $current_page==='post-add.php'?' active':"";?>><a href="/admin/post-add.php">写文章</a></li>
          <li class=<?php echo $current_page==='categories.php'?' active':"";?>><a href="/admin/categories.php">分类目录</a></li>
        </ul>
      </li>
      <li class=<?php echo $current_page==='comments.php'?' active':"";?>>
        <a href="/admin/comments.php"><i class="fa fa-comments"></i>评论</a>
      </li>
      <li class=<?php echo $current_page==='users.php'?' active':"";?>>
        <a href="/admin/users.php"><i class="fa fa-users"></i>用户</a>
      </li>
      <li>
        <?php $arr1=Array('nav-menus.php','slides.php','settings.php');?>
        <a href="#menu-settings" class="<?php echo in_array($current_page,$arr)?'':'collapsed';?>" data-toggle="collapse">
          <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-settings" class="collapse <?php echo in_array($current_page,$arr1)?' in':'';?>">
          <li class=<?php echo $current_page==='nav-menus.php'?' active':"";?>><a href="/admin/nav-menus.php">导航菜单</a></li>
          <li class=<?php echo $current_page==='slides.php'?' active':"";?>><a href="/admin/slides.php">图片轮播</a></li>
          <li class=<?php echo $current_page==='settings.php'?' active':"";?>><a href="/admin/settings.php">网站设置</a></li>
        </ul>
      </li>
      <li class=<?php echo $current_page==='douban.php'?' active':"";?>>
        <a href="/admin/douban.php"><i class="fa fa-users"></i>豆瓣</a>
      </li>
    </ul>
  </div>