        <link rel="stylesheet" type="text/css" href="css/compiled/form-showcase.css"/>
        <section class="site-info-mod form-page" style="padding-left:50px;">
	        <div class="form-wrapper">
	       				<form action="<?php echo base_url('system/sys_siteInfo_editor');?>" method="post">
	       					<input type="hidden" value="<?php echo $site_info['siteId'];?>" name="siteId";>
	                        <div class="field-box">
	                            <label>站点名称:</label>
	                            <div class="col-md-7">
	                                <input type="text" placeholder="note520" class="form-control inline-input" value="<?php echo $site_info['siteName'];?>" name="siteName">
	                            </div>
	                        </div>
	                        <div class="field-box">
	                            <label>域名:</label>
	                            <div class="col-md-7">
	                                <input type="text" placeholder="note520" class="form-control inline-input" value="<?php echo $site_info['domainName'];?>" name="domainName">
	                            </div>
	                        </div>
	                        <div class="field-box">
	                            <label>站点SEO标题:</label>
	                            <div class="col-md-7">
	                                <input type="text" class="form-control inline-input" value="<?php echo $site_info['seoTitle'];?>" name="seoTitle">
	                            </div>
	                        </div>
	                        <div class="field-box">
	                            <label>站点SEO关键字:</label>
	                            <div class="col-md-7">
	                                <input type="text" class="form-control inline-input" value="<?php echo $site_info['seoKeywords'];?>" name="seoKeywords">
	                            </div>
	                        </div>                                      
	                        <div class="field-box">
	                            <label>seo描述:</label>
	                            <div class="col-md-7">
	                                <textarea rows="4" class="form-control"  name="seoDescription"><?php echo $site_info['seoDescription'];?></textarea>
	                            </div>
	                        </div>
	                        <div class="field-box">
	                        	<label>程序作者:</label><b><?php echo $site_info['siteAuthor'];?></b>
	                        </div>
	                       <div class="col-md-11 field-box">
                                <input type="submit" class="btn btn-success" value="保存">
                                <span>OR</span>
                                <a href="javascript:history.go(-1)" class="btn btn-default reset">取消</a>
                            </div>
	            	</form>
	         	</div>
        </section>