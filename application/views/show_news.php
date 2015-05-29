<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title><?php echo $article_info['title'];?>-<?php echo $siteInfo['seoTitle'];?></title>
		<base href="<?php echo base_url().'static/';?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1, maximum-scale=1">
        <meta name="format-detection" content="telephone=no">
        <meta name="keywords" content="<?php echo $article_info['subtitle'];?>" />
		<meta name="description" content="<?php echo $article_info['resume'];?>" />
		<meta name="author" content="" />
		<link rel="shortcut icon" href="/favicon.ico" />
		<link rel="apple-touch-icon" href="/apple-touch-icon.png" />
        <link rel="stylesheet" href="css/common.css"/>
        <link rel="stylesheet" href="css/style.css"/>
        <link rel="stylesheet" href="js/SyntaxHighlighter/shCoreDefault.css"/>
        <link rel="stylesheet" href="css/ueRunCode.css"/>
        <!--[if lte IE 9]>
	    <link rel="stylesheet" href="css/bug.css"/>
	    <script src="js/lib/html5shiv.min.js"></script>
	    <script src="js/lib/respond.min.js"></script>
	    <![endif]-->
	    <!--[if IE 6]>
	    <script type="text/javascript" src="js/lib/png.js"></script>
	    <script type="text/javascript">
	        DD_belatedPNG.fix('.png,img');
	    </script>
    	<![endif]-->
        <script type="text/javascript" src="js/SyntaxHighlighter/shCore.js"></script>
        <script type="text/javascript" src="js/ueRunCode.js"></script>
	</head>
	<body>
	<noscript>
	    <div class="no-js">
	        	您的浏览器已禁用JavaScript脚本，这会影响您的正常访问。为了拥有更好的浏览体验，请启用脚本。
	    </div>
	</noscript><!--noscript over-->
<header class="header" id="header">
    <section class="container-mod">
        <div class="logo-nav-mod fl">
            <div class="logo">
                <h1><a href="/" title="<?php echo $siteInfo['seoTitle'];?>"><img src="images/logo.png" alt="<?php echo $siteInfo['seoDescription'];?>" title="<?php echo $siteInfo['seoTitle'];?>"></a></h1>
                <button type="button" class="nav-toggle">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <nav class="nav" id="nav">
                <ul>
                <li class="active"><a href="/">首页</a></li>
	                <?php foreach ($navListArr as $nav_item): ?>
	                    <li>
	                    <a data-toggle="dropdown" href="<?php echo base_url('category/article_list')."/".$nav_item['id'];?>" title="<?php echo $nav_item['name'];?>">
	                    	<?php echo $nav_item['name'];?>
	                    	<?php if(bool_isChildren($nav_item['id'])){?>
	                    		<span class="caret"></span>
	                    	<?php }?></a>
	                    <?php if(bool_isChildren($nav_item['id'])){?>
		                    <dl>
		                        <?php $subArr=trace_subNav($nav_item['id']); foreach ($subArr as $subRow):?>
		                        	<dd><a href="<?php echo base_url('category/article_list/'.$subRow['cid']);?>" title="<?php echo $subRow['cname'];?>"><?php echo $subRow['cname'];?></a></dd>
		                        <?php endforeach;?>
		                    </dl>
	                    <?php }?>
	                    </li>
	                <?php endforeach ?>
                </ul>
                <div class="clear"></div>
            </nav>
            <div class="clear"></div>
        </div>
        <div class="search-mod fr">
            <div class="search-bar">
                <form class="search-form" name="searchForm" action="" method="post">
                    <input type="search" placeholder="搜索" class="search-input fl">
                    <input type="submit" title="点击搜索" value="" class="search-btn fr">
                </form>
            </div>
        </div>
        <div class="clear"></div>
    </section>
</header><!--/header-->
<section class="banner-mod sub-banner">
    <div class="container-mod sub-pos-mod">
        <div class="site-position fl">
            <ol class="breadcrumb">
                <li><a href="/">首页</a></li>
            	<?php trace_location($article_info['cid']);?>
            </ol>
        </div>
        <div class="current-txt fr">
            <?php echo $comon_cart_info['content'];?>
        </div>
    </div>
</section><!--/ban-->
<section class="container-mod content-mod">
    <article class="content-show fl">
       <div class="content-main-mod">
           <h1 class="title"><?php echo $article_info['title'];?></h1>
           <div class="tit-bar">
               <span><?php echo $article_info['author'];?></span>
               <span>发布于  <?php echo $article_info['created_date'];?></span>
               <span>查看:<?php echo $article_info['hits'];?></span>
           </div>
           <div class="content-brief">
               <strong>摘要：</strong>
               <?php echo $article_info['resume'];?>
            </div>
           <div class="content-main">
               <?php echo htmlspecialchars_decode($article_info['content']);?>
           </div>
           <div class="paging-mod">

           </div>
           <!--/分页-->
                <!-- 多说评论框 start -->
                <div class="ds-thread" data-thread-key="<?php echo $article_info['id'];?>" data-title="<?php echo $article_info['title'];?>" data-url="<?php echo '/article/show/'.$article_info['id'];?>"></div>
                <!-- 多说评论框 end -->
                <!-- 多说公共JS代码 start (一个网页只需插入一次) -->
                <script type="text/javascript">
                var duoshuoQuery = {short_name:"note520"};
                    (function() {
                        var ds = document.createElement('script');
                        ds.type = 'text/javascript';ds.async = true;
                        ds.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//static.duoshuo.com/embed.js';
                        ds.charset = 'UTF-8';
                        (document.getElementsByTagName('head')[0]
                         || document.getElementsByTagName('body')[0]).appendChild(ds);
                    })();
                </script>
                <!-- 多说公共JS代码 end -->
       </div>
    </article>
    <aside class="content-attach fr">
        <div class="info-mod">
            <img alt="#" src="images/sweepCode.jpg">
        </div>
    </aside>
    <div class="clear"></div>
</section>
    <script type="text/javascript">
    		SyntaxHighlighter.all();
    		ueRunCode.start();
    </script>