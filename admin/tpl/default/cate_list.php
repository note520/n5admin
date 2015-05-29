<link rel="stylesheet" type="text/css" href="css/compiled/tables.css">
<style type="text/css">
.table-wrapper .table tr td input[type="checkbox"]{margin-top:0px;vertical-align:bottom;float:none;}
</style>
<div id="pad-wrapper">
            <div class="table-wrapper products-table">
                <div class="row header">
                    <h4>分类管理</h4>
                    <div class="col-md-10 col-sm-12 col-xs-12">
                        <a class="btn-flat success new-product pull-right" href="<?php echo base_url('category/add');?>">+添加分类</a>
                    </div>
                </div>
                <div class="row">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="col-md-6">
                                	分类列表
                                </th>
                                <th class="col-md-3" style='text-align:right;'>
                                    <span class="line"></span>操作选项
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php echo $trListStr;?>
                            <tr>
                                <th>
                                   <input type="checkbox" name="allSelected"><span>全选</span>
                                </th>
                                <th class="align-right">
	                                <ul class="actions">
                                   		<li><a id="deleteAllCate" href="javascript:void(0);" onclick="return confirm('你确定全部删除?');"><i class="table-delete"></i><span style="margin-left:5px;">批量删除</span></a></li>
	                                </ul>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
</div>
