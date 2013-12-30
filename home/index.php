<?php
include_once("../public/php/head.php");
include_once("../public/php/session.php");
?>

		<title><?php echo $SYS_TITLE; ?></title>
		<link rel="stylesheet" type="text/css" href="index.css" />
		<script src="public.js"></script>
		<script src="north.js"></script>
		<script src="south.js"></script>
		<script src="index.js"></script>
	</head>
	<body class="easyui-layout">
    	<div data-options="region:'north',href:'north.php',border:false" style="height:100px;"></div>
		<div data-options="region:'south',href:'south.php',border:false" style="height:20px;"></div>
		<div data-options="region:'center',title:'',border:false" >
			<div id="tt" class="easyui-tabs" data-options="fit:true, border:false"></div>
		</div>
	</body>
</html>