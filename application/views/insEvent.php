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
          width: 595px;
          height: 226px;
          background-size: 100%;
          background-repeat: no-repeat;
          background-color: #bdbdbd;
        }
      </style>
  </head>
  <body>
    <h4>新增事件</h4>
    <div id="addEvent">
      <form class="form-horizontal">
        <div class="form-group">
          <label for="type" class="col-sm-2 control-label">事件类型</label>
          <div class="col-sm-9">
            <select class="form-control" id="eventType">
              <option value="sports">体育</option>
              <option value="finance">财经</option>
              <option value="entertainment">娱乐</option>
              <option value="livelihood">民生</option>
              <option value="science">科技</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="recommend" class="col-sm-2 control-label">是否推荐</label>
          <div class="col-sm-9">
            <label class="radio-inline">
              <input type="checkbox" name="recommend" id="recommendyes" value="1">推荐
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="preTitle" class="col-sm-2 control-label">预览标题</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="preTitle" placeholder="预览标题">
          </div>
        </div>
        <div class="form-group">
          <label for="title" class="col-sm-2 control-label">标题</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="title" placeholder="详情标题">
          </div>
        </div>
         <div class="form-group">
          <label for="detail" class="col-sm-2 control-label">事件详情</label>
          <div class="col-sm-9">
             <textarea class="form-control" rows="3" id="detail"></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="tradeBasis" class="col-sm-2 control-label">清算依据</label>
          <div class="col-sm-9">
            <textarea class="form-control" rows="3" id="tradeBasis"></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="date" class="col-sm-2 control-label">截止时间</label>
          <div class="col-sm-3" style="margin-bottom:10px">
            <input type="date" class="form-control" id="date" placeholder="">
          </div>
          <div class="col-sm-3">
            <input type="time" class="form-control" id="time" placeholder="">
          </div>
        </div>
        <div class="form-group">
          <label for="image" class="col-sm-2 control-label">事件图片</label>
          <div class="col-sm-9">
             <input type="file" id="image" name="image" onchange="previewImg();">
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
    <script src="../../static/myJs/insEvent.js"></script>
  </body>
</html>