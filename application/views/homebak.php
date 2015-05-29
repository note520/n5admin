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
    <link rel="stylesheet" href="css/bootstrap.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="css/ie.css"/>
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
<header id="header" class="navbar navbar-fixed-top navbar-inverse" role="navigation">
    <nav class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/" title="<?php echo $siteInfo['seoTitle'];?>"><img src="images/logo.png" alt="<?php echo $siteInfo['seoDescription'];?>"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="/">首页</a></li>
                <?php foreach ($navListArr as $nav_item): ?>
                    <li><a href="<?php echo base_url('category/article_list')."/".$nav_item['id'];?>" title="<?php echo $nav_item['name'];?>"><?php echo $nav_item['name'];?></a>
                    </li>
                <?php endforeach ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li class="dropdown-header">Nav header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
            <form role="search" class="navbar-form navbar-right">
                <div class="form-group">
                    <input type="text" placeholder="Search" class="form-control">
                </div>
                <button class="btn" type="submit">Submit</button>
            </form>
        </div><!--/.nav-collapse -->
    </nav>
</header>
<section class="container doc-main" id="main">
    <div class="row">
        <div class="col-xs-12 col-lg-9">
            <section id="focus" class="focus f-fl">
                <div class="img-show">
                    <ul class="tab-cont">
                    <?php foreach ($banListArr as $ban_item):?>
                        <li>
                            <figure>
                                <a href="<?php echo base_url('article/show').'/'.$ban_item['id'];?>" title="<?php echo $ban_item['title']?>"><img class="img-responsive" src="<?php echo $ban_item['focus_pic']?>" alt="<?php echo $ban_item['title']?>" width="700" height="320"/></a>
                            </figure>
                        </li>
                    <?php endforeach ?>
                    </ul>
                </div>
                <div class="ban-mask"></div>
                <dl class="nav-text">
                <?php foreach ($banListArr as $ban_item_tit):?>
                    <dd>
                        <h3><a href="<?php echo base_url('article/show').'/'.$ban_item_tit['id'];?>" title="<?php echo $ban_item_tit['resume']?>" target="_blank"><?php echo $ban_item_tit['title']?></a>
                        </h3>
                    </dd>
                <?php endforeach ?>
                </dl>
                <div class="nav-item">
                    <ul>
                    <?php $index=0;?>
                        <?php foreach ($banListArr as $ban_item):?>
                        <li class=""><?php echo $index++;?></li>
                    <?php endforeach ?>
                    </ul>
                </div>
                <a href="javascript:void(0);" title="prev" class="prev"><i></i></a>
                <a href="javascript:void(0);" title="next" class="next"><i></i></a>
            </section><!--/focus over -->
        </div>
        <div class="collapsed col-lg-3">
            <div class="list-group doc-list-group">
                <!--热门推荐-->
                <?php foreach ($recomListArr as $recom_item):?>
                <a class="list-group-item" target="_blank" title="<?php echo $recom_item['subtitle'];?>" href="<?php echo base_url('article/show').'/'.$recom_item['id'];?>">
                    <h4 class="list-group-item-heading"><?php echo $recom_item['title'];?></h4>
                    <p class="list-group-item-text"><?php echo $recom_item['resume'];?>...</p>
                </a>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <ul class="row doc-info-list">
    <!--最新推荐-->
        <?php foreach ($latestNewsArr as $news_item):?>
         <li class="col-xs-6 col-lg-3">
            <a target="_blank" title="<?php echo $news_item['subtitle'];?>" href="<?php echo base_url('article/show').'/'.$news_item['id'];?>" class="thumbnail">
                <figure>
                   <div class="doc-img-box">
                    <img data-original="<?php echo $news_item['pic'];?>" src="images/loading.gif" class="img-responsive lazy">
                   </div>
                    <figcaption class="caption">
                        <div class="doc-animate-box">
                            <h4><?php echo $news_item['title'];?></h4>
                            <div class="doc-img-info">
                                <p><?php echo $news_item['resume'];?></p>
                            </div>
                        </div>
                    </figcaption>
                    <div class="row doc-attach-mod">
                        <div class="col-xs-9 col-lg-9 doc-ico-box">
                            <span class="glyphicon glyphicon-eye-open"></span>
                            <span><?php echo $news_item['created_date'];?></span>
                            <span class="glyphicon glyphicon-comment"></span>
                            <span><?php echo $news_item['author'];?></span>
                        </div>
                        <div class="col-xs-3 col-lg-3 doc-sort-box">
                            <h5>前端</h5>
                        </div>
                    </div>
                </figure>
            </a>
         </li>
        <?php endforeach ?>
    </ul>
    <ul class="pagination doc-pagination">
        <?php echo $this->pagination->create_links(); ?>
        <!--<li><a href="#">«</a></li>
        <li class="active"><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li><a href="#">»</a></li>-->
    </ul>
    <hr>
      