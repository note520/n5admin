<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title><?php echo $siteInfo['seoTitle'];?></title>
    <base href="<?php echo base_url().'static/';?>"/>
    <meta name="baidu-site-verification" content="hjJozY9GHq" />
    <meta name="format-detection" content="telephone=no">
    <meta name="keywords" content="<?php echo $siteInfo['seoKeywords'];?>" />
    <meta name="description" content="<?php echo $siteInfo['seoDescription'];?>" />
    <meta name="author" content="sky@note520.com"/>

    <link rel="shortcut icon" href="/favicon.ico"/>
   <link rel="stylesheet" href="css/common.css"/>
   <link rel="stylesheet" href="css/style.css"/>
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
<section class="banner-mod">
    <div class="container-mod" id="banner">
        <div class="focus-img">
            <ul>
                <li class="active">
                    <a href="#" title="#" class="img-app-box">
                        <div class="img-box">
                            <img src="images/bannerBg_1.png" alt="#" class="responsive-img">
                        </div>
                        <div class="txt-box">
                            <h4>HTML5+CSS3+JAVASCRIPT</h4>
                            <div class="brief">
                                <div class="tb-img fl">
                                    <img src="images/smallCode_2.png" alt="#">
                                </div>
                                <div class="tb-txt fl">
                                    <p>扫一扫下载</p>
                                    <p>本站app (仅支持andorid)</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</section><!--/banner-->
<section class="container-mod">
    <article class="info-mod">
        <ul>
        <?php foreach ($latestNewsArr as $news_item):?>
         <li >
            <a target="_blank" title="<?php echo $news_item['subtitle'];?>" href="<?php echo base_url('article/show').'/'.$news_item['id'];?>" class="thumbnail">
            	<div class="img-txt-attach-mod">
                        <div class="img-box">
                            <img class="responsive-img lazy" src="<?php echo $news_item['pic'];?>">
                        </div>
                        <div class="caption">
                            <div class="animate-box">
                                <h4><?php echo $news_item['title'];?></h4>
                                <div class="img-info">
                                    <p><?php echo $news_item['resume'];?></p>
                                </div>
                            </div>
                        </div>
                 </div>
                 <div class="attach-mod">
                      <div class="ico-box fl">
                           <span class="glyphicon glyphicon-eye-open"></span>
                           <span><?php echo $news_item['created_date'];?></span>
                      </div>
                      <div class="sort-box fr">
                           <h5><?php trace_showCateName($news_item['cid']);?></h5>
                      </div>
                 </div>
                
            </a>
         </li>
        <?php endforeach ?>
        </ul>
    </article><!--/信息列表-->
    <aside class="pagination-mod">
        <ul class="pagination">
       		<?php echo $this->pagination->create_links(); ?>
        </ul>
    </aside><!--/分页-->
</section>

      