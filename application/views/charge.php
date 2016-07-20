<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>充值</title>
    <script src="../../static/js/veryless.js"></script>
    <link href="../../static/css/mui.css" rel="stylesheet"/>
    <style type="text/css">
    html{
    	/*background-color: #fff;*/
    }
	#header{
	width: 100%;
	height: 3.5rem;
	background-color: #FFF;
	padding-top: 1.25rem;
}
	h1.mui-title{
		height: 2.25rem;
		line-height: 2.25rem;
		font-size: 0.9rem;
	}
	#header a{
		font-size: 1.6rem;
		height: 2.25rem;
		line-height: 1.75rem;
		width: 2rem;
		color: #000;
	}
	.mui-content .mui-scroll-wrapper{
		top:  3.5rem;
	}
	#number{
	margin-top: 1rem;
	-webkit-box-shadow: 0px 1px 1px 1px #D8D8D8;
}
#number li{
	position: relative;
	width: 100%;
	height: 7rem;
	margin-left: 0.5rem;
}
#number .price{
	border-bottom: 2px solid #D8D8D8;
}
#number li p{
	height: 7rem;
	line-height: 6.6rem;
	font-size: 0.8rem;
	color: #000;
}
#number li .mui-numbox{
    display: inline-block;
    overflow: hidden;
    vertical-align: middle;
	position: absolute;
	top:2.3rem;
	left: 5rem;
	width: 10.2rem;
	height: 2rem;
}
#number li .mui-numbox input{
	position: absolute;
	margin: 0 2.2rem;
	width: 5.67rem;
	height: 2rem;
	border-radius: 0.2rem;
	font-size: 0.8rem;
}
#number li .mui-numbox button{
	position: absolute;
	width: 2rem;
	height: 2rem;
	background-color: #9A9A9A;
	border-radius: 0.2rem;
	color: #fff;
	font-size: 1.2rem;
	line-height: 1.2rem;
	padding: 0rem;
	z-index: 1;
}
#number .mui-numbox-plus{
	right: 0;
}
#number .note{
	position: absolute;
	right: 1rem;
	bottom: 0.5rem;
	font-size: 0.7rem;
	color:#A4A4A4;
}
#waite{
	position: relative;
	width: 100%;
	height: 3.75rem;
	margin-top: 1rem;
	background-color: #fff;
	-webkit-box-shadow: 0px 1px 1px 1px #D8D8D8;
}
#surePay{
	position: absolute;
	bottom: 0.9rem;
	left:5.4rem;
	width: 5.2rem;
	height: 1.7rem;
	background-color: #315982;
	color: #fff;
	border-radius: 0.2rem;
	font-size: 0.7rem;
}
    </style>
</head>
<body>
	<header id="header" class="mui-bar mui-bar-nav">
		<!-- <a class="mui-action-back mui-icon mui-icon-arrowleft mui-pull-left"></a> -->
		<h1 class="mui-title">充值</h1>
	</header>
	<div class="mui-content">
		<div class="mui-scroll-wrapper">
			<div class="mui-scroll">
				<ul id="number" class="mui-table-view">
					<li class="price">
						<p>充值金额(元)</p>
						<!-- <div class="numbox" data-numbox-step='1' data-numbox-min='1'>
							<button class="mui-btn numbox-minus" type="button">－</button>
							<input id="money" type="number" step="1"/>
							<button class="mui-btn numbox-plus" type="button">＋</button>
						</div> -->
						<div class="mui-numbox" data-numbox-step='1' data-numbox-min='1' data-numbox-max='1000'>
  <button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
  <input class="mui-input-numbox" type="number" placeholder=""/>
  <button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
</div>
<div class="note">1元=10麻麻豆</div>
					</li>
				</ul>
				<div id="waite">
					<button id="surePay">确认支付</button>
				</div>
			</div>
		</div>
	</div>
</body>
    <script src="../../static/js/mui.js"></script>
    <script src="../../static/js/app.js"></script>
    <script type="text/javascript">
    (function(win,$){
    	var state = app.getState();

    	$.init({
			swipeBack: true
		});
		$('.mui-scroll-wrapper').scroll({
			deceleration: 0.0003
		});
		
		$.plusReady(function(){
			  var offset = 45;
			  if(plus.navigator.isImmersedStatusbar()){
			      offset = plus.navigator.getStatusbarHeight() + 10;
			  }
			  mui('body')[0].style.paddingTop = offset + 'px';
			  mui('header')[0].style.paddingTop = offset + 'px';
			  mui('header')[0].style.height = offset + 2.25*rem + 'px';
			  $('.mui-scroll')[0].style.minHeight = (win.innerHeight - 2.25*rem - (-1) - offset) + 'px';
			  $('.mui-scroll-wrapper')[0].style.top = 2.25*rem + offset + 'px';
		})

		document.getElementById('surePay').addEventListener('tap',function(){
			
		})
    })(window,mui)
    </script>
</html>