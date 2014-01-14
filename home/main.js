$(document).ready(function(){
	console.info(JSON.stringify(openedPanels)+'ready');
	
	ajaxGenTile();
	ajaxGenTodo();
	var tempHeight = $('#main_page_table_td_todo').height();
	$('#msg_todo_shell').panel({height:tempHeight});
});

/*
//刷新 tiles
function ajaxGenTile() {
	var tileWide = ['one-wide','two-wide'];
	//var tileWide = ['one-wide'];
	var tileDelay = [2000,3000,4000];
	var tileMode = ['fade','flip','carousel','slide'];
	var tileColor = ['amber', 'blue', 'brown', 'cobalt', 'crimson', 'cyan', 'emerald', 'green', 'indigo', 'lime', 'magenta', 'mango', 'mauve', 'olive', 'orange', 'pink', 'purple', 'red', 'sienna', 'steel', 'teal', 'violet', 'yellow'];
	var tileDir = ['horizontal', 'vertical'];
	$.getJSON("CheckMenu.php", {flag:"all"}, function(data){
		$("#all_tiles").empty();
		var randomWideTxt = '';
		var randomWideVal = 0;
		var sumWide = 0;
		var tile = '';
		var i = 1;
		var j = 10 - i;
		var tempsum = 0;
		var imageFormat = '';
		var imageFormat_t = '';
		var sumWideOne = 0;  //一个宽度的有多少个了，不允许超过5个，确保5个one5个two。
		
		$.each(data, function(idx,value){
			$.each(value.MODULES, function(idx2,value2){
				while(true){
					randomWideTxt = getRandomArrVal(tileWide);
					switch (randomWideTxt){
					case 'one-wide':
						randomWideVal = 1;
						imageFormat = '../public/images/tile/'+value2.MURL+'.png';
						imageFormat_t = '../public/images/tile/icon/'+value2.MURL+'_icon.png';
						break;
					case 'two-wide':
						randomWideVal = 2;
						imageFormat = '../public/images/tile/'+value2.MURL+'2.png';
						imageFormat_t = '../public/images/tile/icon/'+value2.MURL+'2_icon.png';
						break;
					}

					//确保每行塞满
					if(sumWide < 5)
						tempsum = 5;
					else if(sumWide >= 5 && sumWide < 10)
						tempsum = 10;
					else if(sumWide >= 10 && sumWide < 15)
						tempsum = 15;
					
					if(sumWide + randomWideVal <= tempsum){
						//确保三行搞定
						if(sumWide + randomWideVal + j <= 15){
							var hasright = '';
							if(value2.HASRIGHT){
								hasright = ' data-link= "javascript:openTabs(\''+value2.MCODE+'\',\''+value2.MNAME+'\',\''+value2.MURL+'\',\''+value2.MOBJ+'\',\'new\');"';
							}
							tile = '<div id="tile_'+idx+'_'+idx2+'" '
							+' class="live-tile '+ randomWideTxt + ' ' +getRandomArrVal(tileColor)+'" '
							+' data-mode="'+getRandomArrVal(tileMode)+'" '
							+' data-delay="'+getRandomArrVal(tileDelay)+'" '
							+' data-stops="50%,100%,0px" '
							+' data-direction="'+getRandomArrVal(tileDir)+'" '
							+' data-bounce=true '
							+ hasright + '>'
							+'<span class="tile-title">'+value2.MNOTE+'</span>'
							+'<div><img class="full" src="'+imageFormat+'" alt="1" /></div>'
							+'<div><img class="full" src="'+imageFormat_t+'" alt="2" /></div>'
							+'</div>';
	
							$("#all_tiles").append(tile);

							sumWide = sumWide + randomWideVal;
							if(randomWideVal == 1){
								sumWideOne ++;
							}
								
							i ++;
							j --;
							break;
						}
					}
				}
			});
		});
		
		$("div[id^='tile_']").liveTile();
		
	});
}
*/

//刷新 tiles
function ajaxGenTile() {
	var tileDelay = [2000,3000,4000];
	var tileMode = ['fade','flip','carousel','slide'];
	var tileColor = ['amber', 'blue', 'brown', 'cobalt', 'crimson', 'cyan', 'emerald', 'green', 'indigo', 'lime', 'magenta', 'mango', 'mauve', 'olive', 'orange', 'pink', 'purple', 'red', 'sienna', 'steel', 'teal', 'violet', 'yellow'];
	var tileDir = ['horizontal', 'vertical'];
	
	$.ajax({
        type: 'POST',
        url: '../public/php/calcAllGroup.php',
        async: true,
        cache: false,
        dataType: 'json',
        timeout: 5000,  //毫秒，超时后执行error
        success: function (allGroup) {
        	//alert(JSON.stringify(allGroup));
			$.getJSON("CheckMenu.php", {flag:"all"}, function(data){
				$("#all_tiles").empty();
				var i = 0;
				var randomWideTxt = '';
				
				$.each(data, function(idx,value){
					$.each(value.MODULES, function(idx2,value2){
						switch (allGroup[i]){
						case 1:
							randomWideTxt = 'one-wide';
							imageFormat = '../public/images/tile/'+value2.MURL+'.png';
							imageFormat_t = '../public/images/tile/icon/'+value2.MURL+'_icon.png';
							break;
						case 2:
							randomWideTxt = 'two-wide';
							imageFormat = '../public/images/tile/'+value2.MURL+'2.png';
							imageFormat_t = '../public/images/tile/icon/'+value2.MURL+'2_icon.png';
							break;
						}
	
						var hasright = '';
						if(value2.HASRIGHT){
							hasright = ' data-link= "javascript:openTabs(\''+value2.MCODE+'\',\''+value2.MNAME+'\',\''+value2.MURL+'\',\''+value2.MOBJ+'\',\'new\');"';
						}
						var tile = '<div id="tile_'+idx+'_'+idx2+'" '
						+' class="live-tile '+ randomWideTxt + ' ' +getRandomArrVal(tileColor)+'" '
						+' data-mode="'+getRandomArrVal(tileMode)+'" '
						+' data-delay="'+getRandomArrVal(tileDelay)+'" '
						+' data-stops="50%,100%,0px" '
						+' data-direction="'+getRandomArrVal(tileDir)+'" '
						+' data-bounce=true '
						+ hasright + '>'
						+'<span class="tile-title">'+value2.MNOTE+'</span>'
						+'<div><img class="full" src="'+imageFormat+'" alt="1" /></div>'
						+'<div><img class="full" src="'+imageFormat_t+'" alt="2" /></div>'
						+'</div>';
			
						$("#all_tiles").append(tile);
										
						i ++;
						
					});
				});
				
				$("div[id^='tile_']").liveTile();
				
			});
        }
	});
}


//刷新消息中心
function ajaxGenTodo() {
	/**
	 * 如果直接在ready函数中写下列语句，是不行的，原因不知，可能是元素还没有加载完成。
	 * 而通过 ajax 返回函数来处理，就能成功，非常的费解。如下就能成功。奇怪
	 */		
	$.ajax({
        type: 'POST',
        url: 'CheckTodo.php',
        async: true,
        cache: false,
        dataType: 'json',
        timeout: 5000,  //毫秒，超时后执行error
        success: function (data) {
			console.info('callback');
			
			var panels = $('#msg_todo').accordion('panels');
			$.each(panels, function(idx,value){
				$('#msg_todo').accordion('remove', panels[idx]);
			});
			
			$.each(data, function(idx,value){
				var titleFormat = value.STAT+'（'+value.CNT+'）';
				var contentFormat = '';
				$.each(value.DETAIL, function(idx2,value2){
					contentFormat = contentFormat 
						//+'<a id="billTodo_'+idx+'_'+idx2+'" href="javascript:void(0)" onclick="actView(\''+value2.NUM+'\')">付款汇总表编号：'
						//+value2.BILLNUM+' [ 金额：'+value2.TOTALAMT+' ] </a><br>';
					+'<a id="billTodo_'+idx+'_'+idx2+'" href="javascript:void(0)" onclick="actView(\''+value2.NUM+'\')">系统单号：'+value2.NUM+' [ 金额：'+value2.TOTALAMT+' ] </a><br>';
				});
				
				//add方法有点怪，必须要异步请求后，才能正常使用，否则jquery报错找不到panel。
				$('#msg_todo').accordion('add', {
					title: titleFormat,
					content: contentFormat,
					selected: true
				});
	
			});
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        	art.dialog({
        	    content: '与服务器通讯失败，请稍后重试！<br>textStatus：'+textStatus+'<br>XMLHttpRequest.status：'+XMLHttpRequest.status+'<br>XMLHttpRequest.readyState：'+XMLHttpRequest.readyState+'<br>'+errorThrown,
        	    ok: true
        	});
        }
	});
	
	
	
}
//展开通知中心
function expandAllMsg(){
	var panels = $('#msg_todo').accordion('panels');
	$.each(panels, function(idx,value){
		$('#msg_todo').accordion('select', $('#msg_todo').accordion('getPanelIndex', panels[idx]));
		//alert(JSON.stringify($('#msg_todo').accordion('getPanelIndex', panels[idx])));
	});
}
//收缩通知中心
function collapseAllMsg(){
	var panels = $('#msg_todo').accordion('panels');
	$.each(panels, function(idx,value){
		$('#msg_todo').accordion('unselect', $('#msg_todo').accordion('getPanelIndex', panels[idx]));
	});
}
//记住通知中心状态
function rememberMsgStat(){
	openedPanels = $('#msg_todo').accordion('getSelections');
}



