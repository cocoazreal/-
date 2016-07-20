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
            <h4>所有用户</h4>
        </header>
        <!--内容显示区域-->
        <div class="table-responsive">
           <?php
                echo $this->table->generate($user);
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
            var userId = self.parentNode.parentNode.children[0].innerHTML;
            $.ajax({
                url:'http://115.28.73.97/user_back/deleteuser',
                data: {'userId':userId},
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