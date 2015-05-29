<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title><?php echo $title;?></title>
		<base href="<?php echo base_url().'tpl/'.ADMIN_THEME.'/'; ?>;"/>
		<meta http-equiv="refresh" content="<?php echo $refreshTime?>;url=<?php echo base_url($url);?>"/> 
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1, maximum-scale=1">
        <meta name="format-detection" content="telephone=no">
		<meta name="description" content="" />
		<meta name="author" content="" />
		<link rel="shortcut icon" href="/favicon.ico" />
		<link rel="apple-touch-icon" href="/apple-touch-icon.png" />
	</head>
	<body>
		<div class="formTips-mod" style="width:500px;margin:0 auto;">
			<h3><?php echo $successTips;?></h3>
			<p><?php echo $refreshTime?>秒后系统将自动跳转</p>
		</div>
	</body>
</html>