$(document).ready(function(){
	//提交
	$("#submit").click(function(){
		ucode = $.trim($('#login').val());
		upwd =  $.md5($.trim($('#password').val()));
		if(ucode != ""){
			if(checkValue($('#login'), '用户名', '字母、数字、下划线') && checkValue($('#password'), '密码', '字母、数字、下划线')){
				$(document.body).showLoading();
				ajaxCheck(ucode,upwd);
			}
		}else{
			$('#login').grumble(
					{
						text: '请输入用户名', 
						angle: 60, 
						distance: 70, 
						type: 'alt-',  //蓝色
						showAfter: 100,
						hideAfter: 2000
					}
			);
		}
	});
	
	 $("#login").keyup(function(){ if(event.keyCode == 13){ $("#password").focus(); } });
	 $("#password").keyup(function(){ if(event.keyCode == 13){ $("#submit").focus(); } });
});

function ajaxCheck(ucode, upwd) {
    var $params = "ucode=" + decodeURI(ucode) + "&upwd=" + decodeURI(upwd);
    $.ajax({
        type: 'POST',
        url: 'CheckUserLogin.php',
        //async: false,
        cache: false,
        dataType: 'json',
        data: $params,
        timeout: 2000,  //毫秒，超时后执行error
        success: function (data) {
        	//成功
        	if(data.success){
        		$(document.body).hideLoading();
        		location.href = "../home";
        	}else{
        		$(document.body).hideLoading();
	        	art.dialog({
	        	    //content: JSON.stringify(data),
	        		content: data.info,
	        	    ok: function(){},
	        	    cancel:false
	        	});
        	}
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        	art.dialog({
        	    //content: '与服务器通讯失败，请稍后重试！<br>textStatus：'+textStatus+'<br>XMLHttpRequest.status：'+XMLHttpRequest.status+'<br>XMLHttpRequest.readyState：'+XMLHttpRequest.readyState+'<br>'+errorThrown,
        	    content: '与服务器通讯失败，请稍后重试！',
        	    ok: true,
        	    cancel:false
        	});
        	$(document.body).hideLoading();
        }
    });
    

}