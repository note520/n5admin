    <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/new-user.css" type="text/css" media="screen" />
	<div id="pad-wrapper" class="new-user">
            <div class="row header">
                <div class="col-md-12">
                    <h3>添加新用户</h3>
                </div>                
            </div>
            <div class="row form-wrapper">
                <!-- left column -->
                <div class="col-md-9">
                    <div class="container">
                        <form class="new_user_form" action="<?php echo base_url('user/creat_user');?>" method="post">
                            <div class="col-md-12 field-box">
                                <label>用户名:</label>
                                <input class="form-control" type="text" placeholder="用户名" autofocus name="uname"/>
                            </div>
                            <div class="col-md-12 field-box">
                                <label>密码:</label>
                                <input class="col-md-9 form-control" type="password" placeholder="密码" name="upass" style="width:75%;">
                            </div>
                            <div class="col-md-12 field-box">
                                <label>电子邮箱:</label>
                                <input class="col-md-9 form-control" type="text" placeholder="xxx@qq.com" name="umail"/>
                            </div>
                            <div class="col-md-12 field-box">
                                <label>QQ:</label>
                                <input class="col-md-9 form-control" type="text" name="uqq" placeholder="QQ"/>
                            </div>
                            <div class="col-md-12 field-box">
                                <label>电话/手机:</label>
                                <input class="col-md-9 form-control" type="text" name="uphone"/>
                            </div>
                           <div class="radios">
				                <label class="label_radio col-lg-6 col-sm-6" for="radio-01">
				                    <input id="radio-01" value="1" type="radio" checked name="usex"/> 男
				                </label>
				                <label class="label_radio col-lg-6 col-sm-6" for="radio-02">
				                    <input id="radio-02" value="0" type="radio" name="usex"/> 女
				                </label>
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
