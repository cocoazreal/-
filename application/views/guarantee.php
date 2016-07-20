<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>霸霸后台管理</title>
    <link href="../../static/css/bootstrap.min.css" rel="stylesheet">
      <!-- <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script> -->
      <style>
      h4{
        text-align: center;
        background-color: #315982;
        color: #fff;
        margin:0;
        height: 50px;
        line-height: 50px;
        letter-spacing: 5px;
      }
        #addEvent{
          margin-top: 20px;
          padding: 0 20px;
        }
        #preview{
          width: 226px;
          height: 226px;
          background-size: 100%;
          background-repeat: no-repeat;
          background-color: #bdbdbd;
        }
      </style>
  </head>
  <body>
    <h4>添加保障</h4>
    <div id="addEvent">
      <form class="form-horizontal">
        <div class="form-group">
          <label for="title" class="col-sm-2 control-label">项目</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="title" placeholder="保障项目名称">
          </div>
        </div>
        <div class="form-group">
          <label for="content" class="col-sm-2 control-label">说明</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="content" placeholder="保障项目说明">
          </div>
        </div>
        <div class="form-group">
          <label for="price" class="col-sm-2 control-label">价格</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="price" placeholder="单位：麻麻币">
          </div>
        </div>
        <div class="form-group">
          <label for="image" class="col-sm-2 control-label">图片</label>
          <div class="col-sm-9">
             <input type="file" id="image" onchange="previewImg();">
             <input type="hidden">
             <div id="preview"></div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-9">
            <input type="button" class="btn btn-default" value="提交" id="submitEvent">
          </div>
        </div>
      </form>
    </div>
   

    <script src="../../static/js/jquery-2.0.0.min.js"></script>
    <script src="../../static/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      function previewImg(){
        var img = $('#image')[0];
        // var  blob = URL.createObjectURL(img.files[0]);
        // var url = getPath(img);
        var url = window.URL.createObjectURL(document.getElementById('image').files[0])
        $('#preview').css('background-image','url("'+url+'")');
      }

      $(document).on('click','#submitEvent',function(){
        $.ajax({
          url:'http://115.28.73.97/guarantee_back/getguarmaxid',
          type:'GET',
          dataType:'text',
          success:function(data){
            uploadData(data);
          }
        })
      })

      function uploadData(id){
        var eve = {};
        eve.title = $('#title').val();
        eve.price = $('#price').val();
        eve.content = $('#content').val();
        $.ajax({
          url:'http://115.28.73.97/guarantee_back/insertguar',
          data:eve,
          dataType:'json',
          type:'POST',
          success:function(data){
            // alert('success');
            uploadImg(id);
          },
          error:function(xhr,type,errorThrown){
            console.log(type);
          }

        })
      }

      function uploadImg(id){
        var form = new FormData();
        form.append('upload',document.getElementById('image').files[0])
        form.append('uploadId',id);
        $.ajax({
          url:'http://115.28.73.97/guarantee_back/getguarimg',
          data:form,
          type:'POST',
          processData:false,
          contentType:false,
          success:function(data){
              alert("提交成功，id：" + data);
          }
        })

      }

      function getPath(obj){ 
          if (obj) { 
              if (navigator.userAgent.indexOf("MSIE")>0) { 
                  obj.select(); 
                  // IE下取得图片的本地路径 
                  return document.selection.createRange().text; 
                   
              } else if(isFirefox=navigator.userAgent.indexOf("Firefox")>0) { 
                      if (obj.files) { 
                          // Firefox下取得的是图片的数据 
                          return obj.files.item(0).getAsDataURL(); 
                      } 
                      return obj.value; 
              } 
              return obj.value; 
          } 
      } 
    </script>
  </body>
</html>