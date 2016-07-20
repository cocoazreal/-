;(function(win){
	var nowPage=1;
	var lastPage;
	var nowEventId;
	var nowUserId;
	var nowPointId;
	var nowEvents={};
	var nowUsers = {};
	var nowPoints = {};
	var index = {
		init:function(){
			var self = this;
			self.getAllEvents(1);
			self.bindEvents();
		},
		getAllEvents : function(page){
			var self = this;
			var url = 'http://115.28.73.97/event_back';
			var eventType = document.getElementById('eventType').value;
			var recommend = document.getElementById('recommend').value;
			var isAlive = document.getElementById('isAlive').value;
			// $('.pagination').paginator({current_page: 1, page_count: 10, button_number:3, pager_click:  self.getAllEvents});
			
			url += '?page=' + page + '&type=' +eventType+'&recommended='+recommend+'&isValid='+isAlive;
			$.ajax({
				url:url,
				dataType:'json',
				type:'get',
				success:function(data){
					nowPage = page;
					nowEvents.eve = data.event;
					lastPage = data.total;
					var tpl = document.getElementById('event-template').innerHTML;
					document.querySelector('.events-content tbody').innerHTML = lib.template(tpl,nowEvents);
					self.setPagination(lastPage,self.getAllEvents,page);
					
				},
				error:function(errorType){
					// alert('error: '+ errorType);
				}
			})
		},
		getAllusers : function(page){
			var self = this;
			var url = 'http://115.28.73.97/user_back/getuser';
			url += '?page='+page;
			$.ajax({
				url:url,
				dataType:'json',
				type:'get',
				success:function(data){
					nowPage = page;
					nowUsers.user = data.user;
					lastPage = data.total;
					var tpl = document.getElementById('user-template').innerHTML;
					document.querySelector('.users-content tbody').innerHTML = lib.template(tpl,nowUsers);
					self.setPagination(lastPage,self.getAllusers,page);
					
				},
				error:function(errorType){
					// alert('error: '+ errorType);
				}
			})
		},
		getAllPoints : function(page){
			var self = this;
			var url = 'http://115.28.73.97/opinion_back/getOpinion';
			url += '?page='+page;
			$.ajax({
				url:url,
				dataType:'json',
				type:'get',
				success:function(data){
					nowPage = page;
					nowPoints.user = data.opinion;
					lastPage = data.total;
					var tpl = document.getElementById('point-template').innerHTML;
					document.querySelector('.points-content tbody').innerHTML = lib.template(tpl,nowPoints);
					self.setPagination(lastPage,self.getAllPoints,page);
					
				},
				error:function(errorType){
					// alert('error: '+ errorType);
				}
			})
		},
		setPagination:function(total,callback,page){
			var self = this;
			$('.pagination').bootpag({
			   page:page,
			   total: total,
			   firstLastUse: true,
			   maxVisible: 10
			}).on('page', function(event, num){
				nowPage=num;
			    callback(num);
			});
		},
		bindEvents : function(){
			var self = this;
			$(document).on('change','#eventType',function(){
				self.getAllEvents(1);
			})
			.on('change','#recommend',function(){
				self.getAllEvents(1);
			})
			.on('change','#isAlive',function(){
				self.getAllEvents(1);
			})
			.on('click','.delete',function(){
				var check = confirm('确定删除该事件?');
				if(check==false){
					return;
				}
				var id = this.parentNode.parentNode.children[0].innerHTML;
				$.ajax({
					url : 'http://115.28.73.97/event_back/deleteEvent',
					type:'post',
					data:{eventId:id},
					dataType:'text',
					success:function(data){
						// alert('删除成功');
						self.getAllEvents(nowPage);
					},
					error:function(errorType){
						alert('error: '+errorType);
					}
				})
			})
			.on('mouseover','.slidebtn',function(){
				document.querySelector('body').setAttribute('style','transform:translateX(220px)')
			})
			.on('mouseover','.slidebar',function(){
				document.querySelector('body').setAttribute('style','transform:translateX(220px)')
			})
			.on('mouseout','.slidebtn',function(){
				document.querySelector('body').setAttribute('style','transform:translateX(0px)')
			})
			.on('mouseout','.slidebar',function(){
				document.querySelector('body').setAttribute('style','transform:translateX(0px)')
			})
			.on('click','.windup',function(){
				$('body').scrollTop(0);
				nowEventId = this.parentNode.parentNode.children[0].innerHTML;
				$('.backup,#windupbar').css({'display':'block','position':'fixed'});
			})
			.on('click','.backup',function(){
				$('.backup,#windupbar').css('display','none');
			})
			.on('click','.addPoint',function(){
				$('body').scrollTop(0);
				nowEventId = this.parentNode.parentNode.children[0].innerHTML;
				$('.backup3,#addPointbar').css('display','block');
				document.querySelector('#addPointbar h5').innerHTML = this.parentNode.parentNode.children[4].innerHTML;
			})
			.on('click','.backup3',function(){
				$('.backup3,#addPointbar').css('display','none');
			})
			.on('click','.editEvent',function(){
				$('body').scrollTop(0);
				nowEventId = this.parentNode.parentNode.children[0].innerHTML;
				$('.backup4,#editEventbar').css('display','block');
				var nodes = this.parentNode.parentNode.children;
				document.getElementById('eventType2').value = nodes[1].innerHTML;
				if(nodes[2].innerHTML=='推荐'){
					document.getElementById('recommendyes').checked = true;
				}else{
					document.getElementById('recommendyes').checked = false;
				}
				document.getElementById('preTitle2').value = nodes[3].innerHTML;
				document.getElementById('title2').value = nodes[4].innerHTML;
				document.getElementById('detail2').value = nodes[5].innerHTML;
				document.getElementById('tradeBasis2').value = nodes[8].innerHTML;
				document.getElementById('date2').value = nodes[6].innerHTML.substr(0,10);
				document.getElementById('time2').value = nodes[6].innerHTML.substr(11,8);
			})
			.on('click','.backup4',function(){
				$('.backup4,#editEventbar').css('display','none');
			})
			.on('click','#addconfirm',function(){
				var account = '小见';
				var pointTitle = document.getElementById('pointTitle').value;
				var pointContent = document.getElementById('pointContent').value;
				if(pointTitle==''||pointContent==''){
					alert('请填写标题和内容！')
					return;
				}
				$.ajax({
					url:'http://115.28.73.97/opinion/writeOpinion',
					data:{
						eventId:nowEventId,
						account:account,
						title:pointTitle,
						content:pointContent
					},
					type:'POST',
					dataType:'text',
					success:function(){
						alert('发布成功');
						$('.backup3,#addPointbar').css('display','none');
					},
					error:function(errorType){
						alert('error: '+errorType);
					}
				})
			})
			.on('click','.editdou',function(){
				$('body').scrollTop(0);
				nowUserId = this.parentNode.parentNode.children[0].innerHTML;
				$('.backup2,#editdoubar').css({'display':'block','position':'fixed'});
				var useablemoney = parseInt(this.parentNode.parentNode.children[4].innerHTML);
				var playmoney = parseInt(this.parentNode.parentNode.children[5].innerHTML);
				document.getElementById('useablemoney').value = useablemoney;
				document.getElementById('playmoney').value = playmoney;
				document.querySelector('#editdoubar h5').innerHTML = this.parentNode.parentNode.children[1].innerHTML;
			})
			.on('click','.backup2',function(){
				$('.backup2,#editdoubar').css('display','none');
			})
			.on('click','#editconfirm',function(){
				var useablemoney = document.getElementById('useablemoney').value;
				var playmoney = document.getElementById('playmoney').value;
				$.ajax({
					url:'http://115.28.73.97/user_back/fixmoney',
					data:{playMoney:playmoney,useableMoney:useablemoney,userId:nowUserId},
					type:'post',
					dataType:'text',
					success:function(){
						$('.backup2,#editdoubar').css('display','none');
						self.getAllusers(nowPage);
					},
					error:function(errorType){
						alert('error: '+errorType);
					}
				})
			})
			.on('click','.deleteuser',function(){
				nowUserId = this.parentNode.parentNode.children[0].innerHTML;
				var check = confirm('确定删除该用户?');
				if(check==false){
					return;
				}
				$.ajax({
					url:'http://115.28.73.97/user_back/deleteUser',
					data:{userId:nowUserId},
					type:'post',
					dataType:'text',
					success:function(data){
						self.getAllusers(nowPage);
					},
					error:function(errorType){
						alert('error: '+errorType);
					}
				})
			})
			.on('click','.deletepoint',function(){
				nowPointId = this.parentNode.parentNode.children[0].innerHTML;
				var check = confirm('确定删除该观点?');
				if(check==false){
					return;
				}
				$.ajax({
					url:'http://115.28.73.97/opinion_back/deleteOpinion',
					data:{opinionId:nowPointId},
					type:'post',
					dataType:'text',
					success:function(data){
						self.getAllPoints(nowPage);
					},
					error:function(errorType){
						alert('error: '+errorType);
					}
				})
			})
			.on('click','#lookup',function(){
				$.ajax({
					url:'http://115.28.73.97/calculate/calculateEvent',
					data:{id:nowEventId,look:1},
					type:'post',
					dataType:'text',
					success:function(){
						// alert('清算完成');
						$('.backup,#windupbar').css('display','none');
						self.getAllEvents(nowPage);
					},
					error:function(errorType){
						alert('error: '+errorType);
					}
				})
			})
			.on('click','#lookdown',function(){
				$.ajax({
					url:'http://115.28.73.97/calculate/calculateEvent',
					data:{id:nowEventId,look:0},
					type:'post',
					dataType:'text',
					success:function(){
						// alert('清算完成');
						$('.backup,#windupbar').css('display','none');
						self.getAllEvents(nowPage);
					},
					error:function(errorType){
						alert('error: '+errorType);
					}
				})
			})

			.on('click','.event-manage',function(){
				$('.content').css('display','none');
				$('.events-content').css('display','table');
				document.querySelector('h4').innerHTML = '<h4><i class="slidebtn"></i>事件管理</h4>';
				self.getAllEvents(1);
			})
			.on('click','.user-manage',function(){
				$('.content').css('display','none');
				$('.users-content').css('display','table');
				document.querySelector('h4').innerHTML = '<h4><i class="slidebtn"></i>用户管理</h4>';
				self.getAllusers(1);
			})
			.on('click','.point-manage',function(){
				$('.content').css('display','none');
				$('.points-content').css('display','table');
				document.querySelector('h4').innerHTML = '<h4><i class="slidebtn"></i>观点管理</h4>';
				self.getAllPoints(1);
			})
			.on('click','#submitEvent',function(){
				uploadData(nowEventId);
				$('.backup4,#editEventbar').css('visibility','hidden');
				self.getAllEvents(1);
			})

		}
	}
	index.init();
})(window);