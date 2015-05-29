<section class="banner-mod sub-banner">
    <div class="container-mod sub-pos-mod">
        <div class="site-position fl">
            <ol class="breadcrumb">
                <li><a href="/">首页</a></li>
            	<?php $id=$this->uri->segment(3);echo trace_location($id);?>
            </ol>
        </div>
        <div class="current-txt fr">
            <?php echo $comon_cart_info['content'];?>
        </div>
    </div>
</section><!--/ban-->

<section class="container-mod">
    <article class="info-mod">
        <ul>
        <?php foreach ($article_list as $row):?>
        	<li>
                <a class="thumbnail" href="<?php echo base_url('article/show/'.$row['id']);?>" title="<?php echo $row['title'];?>">
                    <div class="img-txt-attach-mod">
                        <div class="img-box">
                            <img class="responsive-img lazy" src="<?php echo $row['pic'];?>" alt="<?php echo $row['title'];?>">
                        </div>
                        <div class="caption">
                            <div class="animate-box">
                                <h4><?php echo $row['title'];?></h4>
                                <div class="img-info">
                                    <p><?php echo $row['resume'];?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="attach-mod">
                        <div class="ico-box fl">
                            <span class="glyphicon glyphicon-eye-open"></span>
                            <span><?php echo $row['created_date'];?></span>
                        </div>
                        <div class="sort-box fr">
                            <h5><?php trace_showCateName($row['cid']);?></h5>
                        </div>
                    </div>
                </a>
            </li>
    	<?php endforeach;?>
        </ul>
    </article><!--/信息列表-->
    <aside class="pagination-mod">
        <ul class="pagination">
            <?php echo $this->pagination->create_links(); ?>
        </ul>
    </aside><!--/分页-->
</section>

