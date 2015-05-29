    <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/new-user.css" type="text/css" media="screen" />
    <style type="text/css">
    .font-red{color:#c30;}
    #uploadPic .input-pic{width:50%;vertical-align:middle;}
    .input-pic-bottom{height:30px;}
    </style>
	<div id="pad-wrapper" class="new-user">
            <div class="row header">
                <div class="col-md-12">
                    <h3>文章编辑</h3>
                </div>                
            </div>
            <div class="row form-wrapper">
                <!-- left column -->
                <div class="col-md-9">
                    <div class="container">
                        <form class="new_user_form" action="<?php echo base_url('article/updata_article');?>" method="post" enctype="multipart/form-data" name="aritcle_form">
                        	<input type="hidden" autofocus name="id" value="<?php echo $artArr['id'];?>"/>
                            <div class="col-md-12 field-box">
                                <label><span class="font-red">*</span>标题:</label>
                                <input class="form-control inline-input" type="text" autofocus name="title" value="<?php echo $artArr['title'];?>"/>
                            </div>
                            <div class="col-md-12 field-box">
                                <label>副标题:</label>
                                <input class="col-md-9 form-control inline-input" type="text" value="<?php echo $artArr['subtitle'];?>" name="subtitle" style="width:75%;">
                            </div>
                             <div class="col-md-12 field-box">
                            	<label><span class="font-red">*</span>所属栏目</label>
                            	<div class="ui-select">
	                                <select name="cid">
	                                    <?php echo $editOpts;?>
	                                </select>
                           		</div>
                            </div>
                            <div class="col-md-12 field-box">
                                <label>属性:</label>
                                <div class="checkbox-list">
                                	<input type="checkbox" value="a" name="att[]" <?php if($artArr['att']=='a'){echo "checked";}?>><span>头条[a]</span>
								    <input type="checkbox" value="b" name="att[]" <?php if($artArr['att']=='b'){echo "checked";}?>><span>推荐[b]</span>
								    <input type="checkbox" value="c" name="att[]" <?php if($artArr['att']=='c'){echo "checked";}?>><span>热门[c]</span>
								    <input type="checkbox" value="p" name="att[]" <?php if($artArr['att']=='p'){echo "checked";}?>><span>幻灯片[p]</span>
								    <input type="checkbox" value="e" name="att[]" <?php if($artArr['att']=='e'){echo "checked";}?>><span>自定义[e]</span>
                                </div>
                            </div>
                            <div class="col-md-12 field-box">
                                <label>作者:</label>
                                <input class="col-md-9 form-control inline-input" type="text" value="<?php echo$artArr['author'];?>" name="author" style="width:75%;">
                            </div>
                            <div class="col-md-12 field-box">
                                <label>出处:</label>
                                <input class="col-md-9 form-control inline-input" type="text" value="<?php echo $artArr['source'];?>" name="source" style="width:75%;">
                            </div>
                            <div class="col-md-12 field-box">
                                <label>摘要:</label>
                                <div class="col-md-7" style="padding-left:0px;">
                                	<textarea rows="4" class="form-control" name="resume"><?php echo $artArr['resume'];?></textarea>
                            	</div>
                            </div>
                            <h4><label>封面图:</label></h4>
                            <div class="col-md-12 field-box" id="uploadPic">
                                <div>
                                <script id="uePic" type="text/plain" style="width:400px;height:20px;" name="pic"><?php echo $artArr['pic'];?></script>
                                </div>
                           </div>
                           <div class="col-md-12 field-box">
                             <script id="editor" type="text/plain" style="width:800px;height:500px;" name="content"><?php echo $artArr['content'];?></script>
                            </div>
                            <div class="col-md-11 field-box">
                                <input type="submit" class="btn btn-success" value="保存">
                                <span>OR</span>
                                <a href="javascript:history.go(-1)" class="btn btn-default reset">取消</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    <!-- end main container -->
     <script type="text/javascript" charset="utf-8" src="<?php echo base_url().SHARE_PATH.'/ue/';?>ueditor.config.js"></script>
     <script type="text/javascript" charset="utf-8" src="<?php echo base_url().SHARE_PATH.'/ue/';?>ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="<?php echo base_url().SHARE_PATH.'/ue/';?>lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript">
    var uePic = UE.getEditor('uePic',{toolbars: [['undo', 'redo','snapscreen', 'preview','simpleupload','insertimage']]});
    var ue = UE.getEditor('editor');
    </script>
