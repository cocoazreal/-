function previewImg(){
	var img = $('#image')[0];
	// var  blob = URL.createObjectURL(img.files[0]);
	// var url = getPath(img);
	var url = window.URL.createObjectURL(document.getElementById('image').files[0])
	$('#preview').css('background-image','url("'+url+'")');
}



function uploadData(id){
	var eve = {};
	eve.id = id;
	eve.eventType = $('#eventType2').val();
	// eve.recommend = $('input[name=recommend]')[0].checked
	eve.preTitle = $('#preTitle2').val();
	eve.title = $('#title2').val();
	eve.detail = $('#detail2').val();
	eve.tradeBasis = $('#tradeBasis2').val();
	eve.datetime = $('#date2').val() + ' ' + $('#time2').val();
	if($('input[name=recommend]')[0].checked){
		eve.recommend = 1;
	}else{
		eve.recommend = 0;
	}
	$.ajax({
		url:'http://115.28.73.97/event_back/updateevent',
		data:eve,
		dataType:'text',
		type:'POST',
		success:function(data){
			// if(data.msg == '1') {
			// 	// alert("插入成功");
			// 	uploadImg(id);
			// }
			// else if (data.msg == '0'){
			// 	alert("插入失败");
			// }
			// else if(data.msg == '2'){
			// 	alert("已有该事件,已覆盖");
			// }
			uploadImg(id);
		},
		error:function(xhr,type,errorThrown){
			console.log(type);
		}

	})
}

function uploadImg(id){
	var form = new FormData();
	if(typeof(document.getElementById('image').files[0])=='undefined'){
		return;
	}
	form.append('upload',document.getElementById('image').files[0])
	form.append('uploadId',id);
	$.ajax({
		url:'http://115.28.73.97/event_back/addeventimage',
		data:form,
		type:'post',
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