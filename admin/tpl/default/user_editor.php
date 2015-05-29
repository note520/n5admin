<link rel="stylesheet" type="text/css" href="css/compiled/form-showcase.css" >
<link rel="stylesheet" type="text/css" href="css/lib/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/lib/bootstrap.datepicker.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/lib/select2.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/lib/uniform.default.css" rel="stylesheet">
<div id="pad-wrapper" class="form-page">
            <div class="row form-wrapper">
	            <div class="row header">
	                <div class="col-md-12">
	                    <h3>用户的基本信息</h3>
	                </div>                
	            </div>
                <!-- left column -->
                <div class="col-md-8 column">
                	<?php echo validation_errors(); ?>
                    <form name="rgForm" action="<?php echo base_url('user/editor_user');?>" method="post">
                    	<input type="hidden" name="uid" value="<?php echo $uid;?>">
                        <div class="field-box">
                            <label>用户名:</label>
                            <div class="col-md-7">
                                <input class="form-control" type="text" id="uname" placeholder="<?php echo $uname;?>" name="uname" value="<?php echo $uname;?>" disabled="true"/>
                            </div>                            
                        </div>
                        <div class="field-box">
                            <label>新密码:</label>
                            <div class="col-md-7">
                                <input class="form-control" type="password" id="upass" placeholder="" name="upass" value=""/>
                            </div>                            
                        </div>
                        <div class="field-box">
                            <label>E-mail:</label>
                            <div class="col-md-7">
                                <input class="form-control inline-input" type="text" id="email" placeholder="<?php echo $umail;?>" name="umail" value="<?php echo $umail;?>">
                            </div>
                        </div>
                        <div class="field-box">
                            <label>QQ:</label>
                            <div class="col-md-7">
                                <input class="form-control inline-input" type="text" id="uqq" placeholder="<?php echo $uqq;?>" name="uqq" value="<?php echo $uqq;?>">
                            </div>
                        </div>
                        <div class="field-box">
                            <label>手机/电话:</label>
                            <div class="col-md-7">
                                <input class="form-control inline-input" type="text" id="uphone" placeholder="<?php echo $uphone;?>" name="uphone" value="<?php echo $uphone;?>">
                            </div>
                        </div>                              
                        <button type="submit" class="btn btn-success">保存</button>
                        <a href="javascript:history.go(-1)" class="btn btn-default reset">取消</a>
                    </form>
                </div>
            </div>
        </div>

	<!-- scripts for this page -->
    <script src="js/wysihtml5-0.3.0.js"></script>
    <script src="js/bootstrap.datepicker.js"></script>
    <script src="js/jquery.uniform.min.js"></script>
    <script src="js/select2.min.js"></script>
    <!-- call this page plugins -->
    <script type="text/javascript">
        $(function () {

            // add uniform plugin styles to html elements
            $("input:checkbox, input:radio").uniform();

            // select2 plugin for select elements
            $(".select2").select2({
                placeholder: "Select a State"
            });

            // datepicker plugin
            $('.input-datepicker').datepicker().on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
        });
    </script>
</body>
</html>