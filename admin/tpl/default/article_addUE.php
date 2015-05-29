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
                    <h3>添加文章</h3>
                </div>                
            </div>
            <div class="row form-wrapper">
                <!-- left column -->
                <div class="col-md-9">
                    <div class="container">
                        <form class="new_user_form" action="<?php echo base_url('article/creat_article');?>" method="post">
                            <div class="col-md-12 field-box">
                                <label><span class="font-red">*</span>标题:</label>
                                <input class="form-control inline-input" type="text" placeholder="标题" autofocus name="title"/>
                            </div>
                            <div class="col-md-12 field-box">
                                <label>副标题:</label>
                                <input class="col-md-9 form-control inline-input" type="text" placeholder="副标题" name="subtitle" style="width:75%;">
                            </div>
                             <div class="col-md-12 field-box">
                            	<label><span class="font-red">*</span>所属栏目</label>
                            	<div class="ui-select">
	                                <select name="cid">
	                                    <?php echo $optList;?>
	                                </select>
                           		</div>
                            </div>
                            <div class="col-md-12 field-box">
                                <label>属性:</label>
                                <div class="checkbox-list">
                                	<input type="checkbox" value="a" name="att[]"><span>头条[a]</span>
								    <input type="checkbox" value="b" name="att[]"><span>推荐[b]</span>
								    <input type="checkbox" value="c" name="att[]"><span>热门[c]</span>
								    <input type="checkbox" value="p" name="att[]"><span>幻灯片[p]</span>
								    <input type="checkbox" value="e" name="att[]"><span>自定义[e]</span>
                                </div>
                            </div>
                            <div class="col-md-12 field-box">
                                <label>作者:</label>
                                <input class="col-md-9 form-control inline-input" type="text" placeholder="作者" name="author" style="width:75%;">
                            </div>
                            <div class="col-md-12 field-box">
                                <label>出处:</label>
                                <input class="col-md-9 form-control inline-input" type="text" placeholder="出处" name="source" style="width:75%;">
                            </div>
                            <div class="col-md-12 field-box">
                                <label>摘要:</label>
                                <div class="col-md-7" style="padding-left:0px;">
                                	<textarea rows="4" class="form-control" name="resume"></textarea>
                            	</div>
                            </div>
                            <div class="col-md-12 field-box" id="uploadPic">
                                <label>封面图:</label>
                                <input type="text" id="url1" name="pic" class="input-pic"/> <input type="button" id="image1" value="选择图片" class="input-pic-bottom"/>
                            </div>
                           <div class="col-md-12 field-box">
                            	<script id="editor" type="text/plain" style="width:800px;height:500px;" name="content">必填内容...</script>
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
    	var ue = UE.getEditor('editor');
        $(function () {
            // toggle form between inline and normal inputs
            var $buttons = $(".toggle-inputs button");
            var $form = $("form.new_user_form");

            $buttons.click(function () {
                var mode = $(this).data("input");
                $buttons.removeClass("active");
                $(this).addClass("active");

                if (mode === "inline") {
                    $form.addClass("inline-input");
                } else {
                    $form.removeClass("inline-input");
                }
            });
        });
    </script>
