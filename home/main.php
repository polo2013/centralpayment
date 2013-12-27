<style type="text/css">
#main_page_table
{
	width:100%;
	height:100%;
	/*border: 1px solid green;*/
}
#all_tiles
{
	margin:0 auto;  //td中的div居中，text-align不行，只能用margin。
}
#main_page_table_td_todo
{
	width:25%;
	/*border: 1px solid green;*/
}
a[id^="billTodo_"]
{
	float:left;
	margin:5px 10px;
	color:green;
	text-decoration:none;
}
a[id^="billTodo_"]:hover {
	color: #FF00FF;
}
</style>

<script type="text/javascript">
	$(document).ready(function(){
		ajaxGenTile();
		ajaxGenTodo();
	});
</script>

<table id="main_page_table">
<tr>
<td id="main_page_table_td_tiles">
	<div id="all_tiles" class="tiles blue tile-group five-wide"></div>
</td>
<td id="main_page_table_td_todo" valign="top">
	<div id="msg_todo_shell" class="easyui-panel" title="通知中心" style="padding:5px">
		<div id="msg_todo" class="easyui-accordion" data-options="multiple:true,animate:false,boder:false"></div>
	</div>
</td>
</tr>
</table>




