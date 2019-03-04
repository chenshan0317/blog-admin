# blog-admin
/**
服务端思路：
1.接收客户端传过来的文件
    if(empty($_FILES['avatar])){
        exit('文件上传失败');
    }
    $avatar=$_FILES['avatar'];
    if($avatar['error']!==UPLOAD_ERR_OK){
        exit('文件上传失败');
    }
2.检验文件大小格式
    $file_type=array("gif","jpeg","jpg","png");
    if(!in_array($avatar['type'])){
        exit("文件格式不对");
    }
    if($avatar['size']>1024*200){
        exit("文件大小不对");
    }
3.把文件放到指定位置,即存储文件
    $ext=pathinfo($avatar['name'],PATHINFO_EXTENSION);
    $target='../../static/uploads/img-'.uniqid().'.'.$ext;
    if(!move_uploaded_file($avatar['tmp_name'],$target)){
        exit('上传失败');
    }

    echo substr($target,5);//服务端返回保存文件的地址
*/

/**
客户端逻辑：
1.当检测到input状态发生变化时，获取选中的文件
2.利用ajax和formData把文件传到服务端，再拿到服务端返回的文件保存路径，再将文件路径放到上传按钮的src属性上
 $('#avatar').on('change',function(){
    var $img=$(this);
    //当文件选中状态发生变化，会执行onchange这个事件处理函数
    //判断是否选中了文件
    var files=$(this).prop('files');
    if(!files.length) return;
    //拿到上传的文件
    var file=files[0];

    //FormData是html5新增成员，配合ajax使用，用于客户端与服务端二进制数据传递
    var data=new FormData();
    data.append('avatar',file);//提交的键和值

    var xhr=new XMLHttpRequest();
    xhr.open('POST','/admin/api/upload.php');
    xhr.send(data);

    xhr.onload=function(){
      // console.log(this.responseText);
      $img.siblings('img').attr('src',this.reponseText);//到时候提交的时候是提交的是图片路径到服务端
      $img.siblings('input').val(this.responseText);//在html中加一个隐藏的input标签，让表单提交时，提交的是图片的路径
    }
  })

*/

