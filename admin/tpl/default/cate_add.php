    <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/new-user.css" type="text/css" media="screen" />
	<div id="pad-wrapper" class="new-user">
            <div class="row header">
                <div class="col-md-12">
                    <h3>添加分类</h3>
                </div>                
            </div>
            <div class="row form-wrapper">
                <!-- left column -->
                <div class="col-md-9">
                    <div class="container">
                        <form class="new_user_form" action="<?php echo base_url('category/creat');?>" method="post">
                            <div class="col-md-12 field-box">
                                <label>分类名称:</label>
                                <input class="form-control" type="text" placeholder="分类名称" autofocus name="cname"/>
                            </div>
                            <div class="col-md-12 field-box">
                            	<label>父目录</label>
                            	<div class="ui-select">
	                                <select name="pid">
	                                    <?php echo $optList;?>
	                                </select>
                           		</div>
                            </div>
							<div class="col-md-12 field-box">
                                <label>分类关键字:</label>
                                <input class="form-control" type="text" placeholder="分类关键字" autofocus name="keywords"/>
                            </div>
                            <div class="col-md-12 field-box">
                                <label>排序:</label>
                                <input class="form-control" type="text" placeholder="99" autofocus name="sort"/>
                            </div>
                            <div class="col-md-12 field-box">
                                <label>分类简介:</label>
                                <div class="col-md-7" style="padding-left:0px;">
                                	<textarea rows="4" class="form-control" name="content"></textarea>
                            	</div>
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
    <script type="text/javascript">
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
