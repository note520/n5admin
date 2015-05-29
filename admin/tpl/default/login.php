<!DOCTYPE html>
<html class="login-bg">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>n5后台管理</title>
	<base href="<?php echo base_url().'tpl/'.ADMIN_THEME.'/'; ?>;"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no">
	<meta name="description" content="" />
	<meta name="author" content="" />
    <!-- bootstrap -->
    <link href="css/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet">
    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="css/compiled/layout.css">
    <link rel="stylesheet" type="text/css" href="css/compiled/elements.css">
    <link rel="stylesheet" type="text/css" href="css/compiled/icons.css">
    <!-- libraries -->
    <link rel="stylesheet" type="text/css" href="css/lib/font-awesome.css">
    <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/signin.css" type="text/css" media="screen" />
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->
    <!--[if IE 6]>
        <script type="text/javascript" src="js/png.js"></script>
        <script type="text/javascript">
            DD_belatedPNG.fix('.png,img');
        </script>
  	<![endif]-->
</head>
<body>
<noscript>
	<div class="xh-topmessage">
		<div class="xh-topnotice">
			<div class="xh-topnotice-bd">
				您的浏览器已禁用JavaScript脚本，这会影响您的正常访问。为了拥有更好的浏览体验，请启用脚本。
			</div>
		</div>
	</div>
</noscript><!--noscript over-->

    <div class="login-wrapper">
        <a href="index.html">
            <img class="logo" src="img/logo-white.png">
        </a>

        <div class="box">
            <div class="content-wrap">
                <h6>n5后台管理登录</h6>
                <form action="<?php echo base_url('login/checkLogin');?>" method="post">
                <input class="form-control" type="text" placeholder="用户名" name="uname" autofocus>
                <input class="form-control" type="password" placeholder="密码" name="upass">
                <?php echo $v_img;?>
            	<input type="hidden" name="word" value='<?php echo $v_word;?>'/>
                <div class="remember">
                    <input id="remember-me" type="checkbox">
                    <label for="remember-me">记住我</label>
                </div>
                <button class="btn-glow primary login" type="submit">登录</button>
                </form>
            </div>
        </div>
    </div>

	<!-- scripts -->
    <script src="js/jquery-1.7.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/theme.js"></script>

</body>
</html>