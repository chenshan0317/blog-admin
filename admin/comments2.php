<?php 
require_once '../func.php';
$current_page=getCurrentPage();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <style>
      #loading{
        position:fixed;
        display:none;
        align-items:center;
        justify-content:center;
        left:0;
        top:0;
        bottom:0;
        right:0;
        background-color:rgba(0,0,0,.7);
        z-index:999;
      }
      .flip-txt-loading {
        font: 26px Monospace;
        letter-spacing: 5px;
        color: #AF3F3F;
      }

      .flip-txt-loading > span {
        animation: flip-txt  2s infinite;
        display: inline-block;
        transform-origin: 50% 50% -10px;
        transform-style: preserve-3d;
      }

      .flip-txt-loading > span:nth-child(1) {
        -webkit-animation-delay: 0.10s;
                animation-delay: 0.10s;
      }

      .flip-txt-loading > span:nth-child(2) {
        -webkit-animation-delay: 0.20s;
                animation-delay: 0.20s;
      }

      .flip-txt-loading > span:nth-child(3) {
        -webkit-animation-delay: 0.30s;
                animation-delay: 0.30s;
      }

      .flip-txt-loading > span:nth-child(4) {
        -webkit-animation-delay: 0.40s;
                animation-delay: 0.40s;
      }

      .flip-txt-loading > span:nth-child(5) {
        -webkit-animation-delay: 0.50s;
                animation-delay: 0.50s;
      }

      .flip-txt-loading > span:nth-child(6) {
        -webkit-animation-delay: 0.60s;
                animation-delay: 0.60s;
      }

      .flip-txt-loading > span:nth-child(7) {
        -webkit-animation-delay: 0.70s;
                animation-delay: 0.70s;
      }

      @keyframes flip-txt  {
        to {
          -webkit-transform: rotateX(1turn);
                  transform: rotateX(1turn);
        }
      }
  
  </style>
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
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none">
          <button class="btn btn-info btn-sm">批量批准</button>
          <button class="btn btn-warning btn-sm">批量拒绝</button>
          <button class="btn btn-danger btn-sm">批量删除</button>
        </div>
        <ul class="pagination pagination-sm pull-right" id="pagination-demo"></ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>作者</th>
            <th>评论</th>
            <th>评论在</th>
            <th>提交于</th>
            <th>状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody id='content'>
        <script type="text/x-art-template" id="tmpl" >
     
        {{each comments as item i}}
          <tr {{if item.status=='approved'}} class='success'
           {{else item.status=='rejected'}} class='danger'
           {{/if}}
          data-id={{item.id}}>
                <td class="text-center"><input type="checkbox"></td>
                <td>{{item.author}}</td>
                <td>{{item.content}}</td>
                <td>{{item.title}}</td>
                <td>{{item.created}}</td>
                <td>{{item.status}}</td>
                <td class="text-center" style="width:150px;">
                  <a href="post-add.html" class="btn btn-info btn-xs">批准</a>
                  <a href="post-add.html" class="btn btn-warning btn-xs">驳回</a>
                  <a href="javascript:;" class="btn btn-danger btn-xs btn-delete">删除</a>
                </td>
            </tr>
        {{/each}}
        </script>
          
          
         
        </tbody>
      </table>
    </div>
  </div>

  <?php include './inc/sidebar.php';?>


<div class="flip-txt-loading" id='loading'>
  <span>L</span><span>o</span><span>a</span><span>d</span><span>i</span><span>n</span><span>g</span>
</div>
  
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/twbs-pagination/jquery.twbsPagination.js"></script>
  <script src="/static/assets/vendors/art-template/template-web.js"></script>
  <script src="/static/assets/vendors/jsrender/jsrender.js"></script>
  <script>NProgress.done()</script>
  <script>
  // nprogress
  $(document)
    .ajaxStart(function () {
      NProgress.start();
      $('#loading').css('display','flex');
    })
    .ajaxStop(function () {
      NProgress.done();
      $('#loading').css('display','none');
})

function loadPageData(page){
    $.get('/admin/api/comments.php',{current_p:page},function(res){
      console.log(33)
      var html = template('tmpl',{comments:res});
      console.log(html)
      $('tbody').html(html);
      
        
    })
}

  $.get('/admin/api/comments.php',{},function(res){
 
        $('#pagination-demo').twbsPagination({
              totalPages: 100,
              visiblePages: 7,
              // initiateStartPageClick:false,
              //第一次初始化时就会出发
              onPageClick: function (event, p) {
                console.log(p);
                  loadPageData(p);
                  // current_p=page;
              }
          });
  })

  // var current_p=1
  // function loadPageData(page){
  //   $.getJSON('/admin/api/comments.php',{current_p:page},function(res){
       
  //     //  $('#pagination-demo').twbsPagination('destroy')

  //      $('#pagination-demo').twbsPagination({
  //           totalPages: 100,
  //           visiblePages: 7,
  //           initiateStartPageClick:false,
  //           //第一次初始化时就会出发
  //           onPageClick: function (event, p) {
  //             console.log(p);
  //               loadPageData(p);
  //               // current_p=page;
  //           }
  //       });
  //      var html1=$('#tmpl').render({comments:res});
  //      console.log(html1)
  //      $('#content').text(html1);
  //     //  $('tbody').empty().append(html1);
  //     })
        
  // }

  // loadPageData(1);
  // $('#pagination-demo').twbsPagination({
  //           totalPages: 100,
  //           visiblePages: 7,
  //           // initiateStartPageClick:false,
  //           //第一次初始化时就会出发
  //           onPageClick: function (event, p) {
  //             console.log(p);
  //               loadPageData(p);
  //               // current_p=page;
  //           }
  //       });


  
  </script>
</body>
</html>
