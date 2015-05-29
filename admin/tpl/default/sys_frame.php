<!DOCTYPE html>
<html>
<head>
	<title>n5后台管理</title>
	<base href="<?php echo base_url().'tpl/'.ADMIN_THEME.'/'; ?>;"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="js/jquery-1.7.1.min.js"></script>
    <!-- bootstrap -->
    <link href="css/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />

    <!-- libraries -->
    <link href="css/lib/jquery-ui-1.10.2.custom.css" rel="stylesheet" type="text/css" />
    <link href="css/lib/font-awesome.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="css/compiled/layout.css">
    <link rel="stylesheet" type="text/css" href="css/compiled/elements.css">
    <link rel="stylesheet" type="text/css" href="css/compiled/icons.css">

    <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/index.css" type="text/css" media="screen" />
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
    <!-- navbar -->
    <header class="navbar navbar-inverse" role="banner">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" id="menu-toggler">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url('system');?>"><img src="img/logo.png"></a>
        </div>
        <ul class="nav navbar-nav pull-right hidden-xs">
            <li class="hidden-xs hidden-sm">
                <input class="search" type="text" />
            </li>
            <li><a href="/" target="_blank">预览网站</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle hidden-xs hidden-sm" data-toggle="dropdown">
                    <?php echo $this->session->userdata('uname');?>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                	
                    <li><a href="<?php echo base_url('user/personal');?>">个人基本信息</a></li>
                    <li><a href="<?php echo base_url('login/loginOut');?>">安全退出</a></li>
                </ul>
            </li>
            <li class="settings hidden-xs hidden-sm">
                <a href="<?php echo base_url('login/loginOut');?>" role="button">
                    <i class="icon-share-alt"></i>
                </a>
            </li>
        </ul>
    </header>
    <!-- end navbar -->

    <!-- sidebar -->
    <div id="sidebar-nav">
        <ul id="dashboard-menu">
            <li class="active">
                <div class="pointer">
                    <div class="arrow"></div>
                    <div class="arrow_border"></div>
                </div>
                <a href="<?php echo base_url('system');?>">
                    <i class="icon-home"></i>
                    <span>管理首页</span>
                </a>
            </li> 
            <li>
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <i class="icon-edit"></i>
                    <span>内容管理</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo base_url('article/article_list');?>">内容列表</a></li>
                    <li><a href="<?php echo base_url('article/add');?>">添加内容</a></li>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <i class="icon-th-large"></i>
                    <span>栏目管理</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo base_url('category');?>">分类列表</a></li>
                    <li><a href="<?php echo base_url('category/add');?>">添加分类</a></li>
                </ul>
            </li> 
            <li>
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <i class="icon-group"></i>
                    <span>会员管理</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo base_url('user/user_list');?>">用户列表</a></li>
                    <li><a href="<?php echo base_url('user/add_user');?>">添加用户</a></li>
                </ul>
            </li>
            <li>
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <i class="icon-cog"></i>
                    <span>站点设置</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo base_url('system/site_info');?>">站点基本信息</a></li>
                    <li><a href="javascript:void(0);">更新缓存</a></li>
                    <li><a href="javascript:void(0);">扩展插件</a></li>
                    <li><a href="javascript:void(0);">附件管理</a></li>
                </ul>
            </li>      
        </ul>
    </div>
    <!-- end sidebar -->


	<!-- main container -->
    <div class="content">

        <!-- settings changer -->
        <div class="skins-nav">
            <a href="#" class="skin first_nav selected">
                <span class="icon"></span><span class="text">Default skin</span>
            </a>
            <a href="#" class="skin second_nav" data-file="css/compiled/skins/dark.css">
                <span class="icon"></span><span class="text">Dark skin</span>
            </a>
        </div>
        <?php if($this->uri->rsegment(1) != 'module'): ?>
    	<?php $this->load->view(isset($tpl) && $tpl ? $tpl : 'sys_home');?>
        <?php else: ?>
        <?php if(!isset($msg)){echo $content;}else{$this->load->view($tpl);} ?>
        <?php endif; ?>
        </div>



	<!-- scripts -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-ui-1.10.2.custom.min.js"></script>
    <!-- knob -->
    <script src="js/jquery.knob.js"></script>
    
    <script src="js/theme.js"></script>

    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();

            // jQuery UI Sliders
            $(".slider-sample1").slider({
                value: 100,
                min: 1,
                max: 500
            });
            $(".slider-sample2").slider({
                range: "min",
                value: 130,
                min: 1,
                max: 500
            });
            $(".slider-sample3").slider({
                range: true,
                min: 0,
                max: 500,
                values: [ 40, 170 ],
            });

        });
    </script>
</body>
</html>