<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>霸霸后台管理</title>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../static/css/bootstrap.min.css">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
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
        #form{
            margin-top: 10px;
        }
        .footer{
            position: relative;
            left: 50%;
            top: 50px;
        }
    </style>
</head>
<body>
    <div class="main">
        <header>
            <h4>所有事件</h4>
        </header>
        <!--表单区域-->
        <div class="container" id="form">
            <form class="form-horizontal" action="http://115.28.73.97/event_back/getAllEvent" method="post">
                <div class="form-group col-lg-4">
                    <label for="startTime" class="control-label col-lg-4">起始时间</label>
                    <div class="col-lg-8">
                        <input type="datetime-local" class="form-control" name="startTime" id="startTime" >
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <label for="endTime" class="control-label col-lg-4">截止时间</label>
                    <div class="col-lg-8">
                        <input type="datetime-local" class="form-control" name="endTime" id="endTime" >
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="col-lg-1 btn btn-info">提交</button>
                </div>
            </form>
        </div>
        <!--内容显示区域-->
        <div class="table-responsive">
           <?php
                echo $this->table->generate($event);
           ?>
        </div>
        <!--页脚分页区-->
        <div class="footer">
            <?php
                echo $this->pagination->create_links();
            ?>
        </div>
    </div>
    <script type="text/javascript">
        (function () {
//       var buttons = document.querySelectorAll('button');
            $(document).on('click','button',function () {
                var self = this;
                var eventId = self.parentNode.parentNode.children[0].innerHTML;
                $.ajax({
                    url:'http://115.28.73.97/event_back/deleteEvent',
                    data: {'eventId':eventId},
                    type:'post',
                    datatype:'json',
                    success:function (data) {
                        alert(data+'delete success!');
                        $(self.parentNode.parentNode).remove();
                    }
                })
            })

        })()
    </script>
</body>
</html>