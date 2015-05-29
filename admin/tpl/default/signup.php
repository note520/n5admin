
<!DOCTYPE html>
<html class="login-bg">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>n5管理用户注册</title>
	<base href="<?php echo base_url().'tpl/'.ADMIN_THEME.'/'; ?>;"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap -->
    <link href="css/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet">
    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="css/compiled/layout.css">
    <link rel="stylesheet" type="text/css" href="css/compiled/elements.css">
    <link rel="stylesheet" type="text/css" href="css/compiled/icons.css">

    <!-- libraries -->
    <link rel="stylesheet" type="text/css" href="css/lib/font-awesome.css">
    
    <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/signup.css" type="text/css" media="screen" />
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->
    <!--[if IE 6]>
        <script type="text/javascript" src="ui/admin/js/png.js"></script>
        <script type="text/javascript">
            DD_belatedPNG.fix('.png,img');
        </script>
  	<![endif]-->
</head>
<body>
    <div class="header">
        <a href="index.html">
            <img src="img/logo.png" class="logo" />
        </a>
    </div>
    <div class="login-wrapper">
        <div class="box">
            <div class="content-wrap">
                <h6>注册新用户</h6>
                <form action="<?php echo base_url('login/register');?>" method="post">
                <input class="form-control" type="text" placeholder="用户名" autofocus name="uname">
                <input class="form-control" type="password" placeholder="密码">
                <input class="form-control" type="password" placeholder="再输出一次密码" name="upass">
                <input class="form-control" type="text" placeholder="电子邮箱" name="umail">
                <input class="form-control" type="text" placeholder="QQ" name="uqq">
                <input class="form-control" type="text" placeholder="电话/手机" name="uphone"/>
                <div class="radios">
	                <label class="label_radio col-lg-6 col-sm-6" for="radio-01">
	                    <input id="radio-01" value="1" type="radio" checked name="usex"/> 男
	                </label>
	                <label class="label_radio col-lg-6 col-sm-6" for="radio-02">
	                    <input id="radio-02" value="0" type="radio" name="usex"/> 女
	                </label>
            	</div>
            	<div style="clear:both;"></div>
	            <label class="checkbox" style="text-align:left;">
	                <input type="checkbox" value="agree this condition"> 我已经认真阅读了本协议
	            </label>
                <div class="action">
                 	<button class="btn-glow primary signup" type="submit">注册</button>
                </div>
                </form>               
            </div>
        </div>

        <div class="already">
            <p>已经注册的用户请</p>
            <a href="<?php echo base_url('login');?>">登录</a>
        </div>
    </div>

	<!-- scripts -->
    <script src="js/jquery-1.7.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/theme.js"></script>
</body>
</html>