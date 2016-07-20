<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>霸霸后台管理</title>
    <link href="../../static/bootstrap.min.css" rel="stylesheet">
    <link href="../../static/index.css" rel="stylesheet">
    <script type="text/javascript" src="../../static/juicer.js"></script>
    

  </head>
  <body>
    <h4><i class="slidebtn"></i>事件管理</h4>
 <ul class="nav nav-pills nav-stacked slidebar">
  <li role="presentation" class="event-manage"><a href="#">事件管理</a></li>
  <li role="presentation" class="user-manage"><a href="#">用户管理</a></li>
  <li role="presentation" class="point-manage"><a href="#">观点管理</a></li>
  <li role="presentation" ><a href="http://115.28.73.97/event_back/insertEvent" target="blank">添加事件</a></li>
  <li role="presentation" ><a href="http://115.28.73.97/guarantee_back/" target="blank">添加保障</a></li>
</ul>

<table class="content events-content">
  <thead>
    <tr class="title">
      <th class="eventId item">ID</th><th class="eventType item">
      <select name="eventType" id="eventType">
        <option value="">全部</option>
        <option value="sports">体育</option>
        <option value="finance">财经</option>
        <option value="entertainment">娱乐</option>
        <option value="livehood">民生</option>
        <option value="science">科技</option>
      </select></th><th class="recommend item">
      <select name="recommend" id="recommend">
        <option value="">全部</option>
        <option value="1">推荐</option>
        <option value="0">未推荐</option>
      </select></th><th class="preTitle item">
      预览标题</th><th class="mainTitle item">
      正文标题</th><th class="mainContent item">
      事件内容</th><th class="deadline item">
      截止日期</th><th class="isAlive item">
      <select name="isAlive" id="isAlive">
        <option value="">全部</option>
        <option value="1">有效</option>
        <option value="0">失效</option>
      </select></th><th class="dependence item">
      清算依据</th><th class="actions item">
      操作</th>
    </tr>
  </thead>
    <tbody>
    </tbody>
</table>

<table class="content users-content">
  <thead>
    <tr class="title">
      <th class="userId item">ID</th><th class="username item">
      用户名</th><th class="password item">
      密码</th><th class="phonenumber item">
      手机号</th><th class="mamadou item">
      可用麻麻豆</th><th class="playdou item">
      赠送麻麻豆</th><th class="toId item">
      关注的人ID</th><th class="fromId item">
      粉丝的ID</th><th class="toEvent item">
      关注的事件</th><th class="actions item">
      操作</th>
    </tr>
  </thead>
    <tbody>
     
    </tbody>
</table>

<table class="content points-content">
  <thead>
    <tr class="title">
      <th class="pointId item">ID</th><th class="pointUserId item">
      用户ID</th><th class="pointEventId item">
      事件ID</th><td class="pointEventTitle item">
      事件标题</td><th class="pointTitle item">
      观点标题</th><th class="pointContent item">
      内容</th><th class="pointTime item">
      发布时间</th><th class="actions item">
      操作</th>
    </tr>
  </thead>
    <tbody>
     
    </tbody>
</table>

<nav align="center">
  <ul class="pagination">
   
  </ul>
</nav>

<div class="backup"></div>
<div id="windupbar">
  <h5>请选择结果</h5>
  <button type="button" id="lookup" class="btn btn-success">看好</button>
  <button type="button" id="lookdown" class="btn btn-danger">不看好</button>
</div>

<div class="backup2"></div>
<div id="editdoubar">
  <h5>moumou</h5>
  <div class="form-group">
    <label for="useablemoney">可用麻麻豆</label>
    <input type="number" class="form-control" id="useablemoney">
  </div>
  <div class="form-group">
    <label for="playmoney">赠送麻麻豆</label>
    <input type="number" class="form-control" id="playmoney">
  </div>
  <button type="button" id="editconfirm" class="btn btn-primary">确认</button>
</div>

<div class="backup3"></div>
<div id="addPointbar">
  <h5>在事件“”下添加观点在事件“”下添加观点在事件“</h5>
  <input type="text" class="form-control" placeholder="标题" id="pointTitle">
  <textarea class="form-control" rows="11" resize="none" placeholder="内容" id="pointContent"></textarea>
  <button type="button" id="addconfirm" class="btn btn-primary">确认</button>
</div>

<div class="backup4"></div>
<div id="editEventbar">
   <div id="addEvent">
      <form class="form-horizontal">
        <div class="form-group">
          <label for="type" class="col-sm-2 control-label">事件类型</label>
          <div class="col-sm-9">
            <select class="form-control" id="eventType2">
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
            <input type="text" class="form-control" id="preTitle2" placeholder="预览标题">
          </div>
        </div>
        <div class="form-group">
          <label for="title" class="col-sm-2 control-label">标题</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="title2" placeholder="详情标题">
          </div>
        </div>
         <div class="form-group">
          <label for="detail" class="col-sm-2 control-label">事件详情</label>
          <div class="col-sm-9">
             <textarea class="form-control" rows="3" id="detail2"></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="tradeBasis" class="col-sm-2 control-label">清算依据</label>
          <div class="col-sm-9">
            <textarea class="form-control" rows="3" id="tradeBasis2"></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="date" class="col-sm-2 control-label">截止时间</label>
          <div class="col-sm-4" style="margin-bottom:10px">
            <input type="date" class="form-control" id="date2" placeholder="">
          </div>
          <div class="col-sm-4">
            <input type="time" class="form-control" id="time2" placeholder="">
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
</div>

   <script type="text/template" id="event-template">
   {@each eve as it}
   <tr>
        <td class="eventId item">${it.id}</td>
        <td class="eventType item">${it.eventType}</td>
        {@if it.recommend=='1'}
          <td class="recommend item">推荐</td>
          {@else}
          <td class="recommend item">未推荐</td>
        {@/if}
        <td class="preTitle item">${it.preTitle}</td>
        <td class="mainTitle item">${it.title}</td>
        <td class="mainContent item">${it.detail}</td>
        <td class="deadline item">${it.datetime}</td>
        {@if it.isValid=='1'}
          <td class="isAlive item">有效</td>
          {@else}
          <td class="isAlive item">失效</td>
          {@/if}
        <td class="dependence item">${it.tradeBasis}</td>
        <td class="actions item">
        {@if it.isCalcu=='0'}
          <button type="button" class="btn btn-success windup">结算</button>
          <button type="button" class="btn btn-danger delete">删除</button>
          <hr/>
          <button type="button" class="btn btn-primary editEvent">修改</button>
          <button type="button" class="btn btn-success addPoint">添加</button>
          {@else}
          <button type="button" class="btn btn-success windup" disabled="disabled">已结算</button>
        {@/if}
        </td>
      </tr>
   {@/each}
   </script>
<script type="text/template" id="user-template">
{@each user as it}
  <tr>
      <td class="userId item">${it.userId}</td><td class="username item">
      ${it.account}</td><td class="password item">
      ${it.password}</td><td class="phonenumber item">
      ${it.phone}</td><td class="mamadou item">
      ${it.useableMoney}</td><td class="playdou item">
      ${it.playMoney}</td><td class="toId item">
      ${it.likePeople}</td><td class="fromId item">
      ${it.likedPeople}</td><td class="toEvent item">
      ${it.likeEvent}</td><td class="actions item">
      <button type="button" class="btn btn-success editdou">修改</button>
      <button type="button" class="btn btn-danger deleteuser">删除</button>
      </td>
    </tr>
{@/each}
</script>
<script type="text/template" id="point-template">
{@each user as it}
 <tr>
      <td class="pointId item">${it.id}</td><td class="pointUserId item">
      ${it.userId}</td><td class="pointEventId item">
      ${it.eventId}</td><td class="pointEventTitle item">
      ${it.eventTitle}</td><td class="pointTitle item">
      ${it.title}</td><td class="pointContent item">
      ${it.content}</td><td class="pointTime item">
      ${it.publishedTime}</td><td class="actions item">
      <button type="button" class="btn btn-danger deletepoint">删除</button></td>
    </tr>
{@/each}
</script>
    <script src="../../static/jquery-2.0.0.min.js"></script>
    <script src="../../static/bootstrap.min.js"></script>
    <script src="../../static/jquery.bootpag.js"></script>
    <script src="../../static/uploadevent.js"></script>
    <script src="../../static/index.js"></script>
  </body>
</html>