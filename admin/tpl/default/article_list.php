<link media="screen" type="text/css" href="css/compiled/tables.css" rel="stylesheet">
<div id="pad-wrapper">
	<div class="table-wrapper products-table ">
		<div class="row head">
        	<div class="col-md-12">
            	<h4><?php echo $title;?></h4>
             </div>
        </div>
        <div class="row filter-block" style="margin-top:20px;">
        	<div class="pull-left">
        		<a class="btn-flat success new-product" href="<?php echo base_url('article/add');?>">+ 添加文章</a>
        		<div class="ui-select">
                         <select name="cid" id="sortSelect">
                            <option value="0" selected=''>所有文章</option>
	                        <?php echo $optList;?>
                         </select>
                         <script type="text/javascript">
								(function(){
									var selectObj=document.getElementById("sortSelect");
									selectObj.addEventListener("change",function(){
										var cid=this.value;
										var host=location.host;
										var url="http://"+host+"/admin/article/select_id_article/"+cid;
										location.href=url;
									})
								})()
                         </script>
                     </div>
        	</div>
         	<div class="pull-right">
                     <input type="text" class="search">
              </div>
        </div>
		<div class="row">
                    <table class="table table-hover" id="tableList">
                        <thead>
                            <tr>
                                <th class="col-md-6">
                                    
                                    <span>文章标题</span>
                                </th>
                                <th class="col-md-1">
                                    <span class="line"></span>所属栏目
                                </th>
                                <th class="col-md-1">
                                    <span class="line"></span>
                                    <span>发布人/时间</span>
                                </th>
                                <th class="col-md-2" style="text-align:right;">
                                	 <span class="line"></span><span>文章操作</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($article_list as $row):?>
                            <tr class="first">
                                <td>
                                    <input type="checkbox" value="<?php echo $row->id;?>">
                                    <a class="name" href="<?php echo base_url('article/editor_article/'.$row->id);?>"><?php echo $row->title;?></a>
                                </td>
                                <td class="description">
                                    <?php echo trace_sortName($row->cid);?>
                                </td>
                                <td>
                                    <span class="label label-success">
                                    <?php echo $row->created_by."&nbsp;&nbsp;".$row->created_date;?>
                                    </span>
                                </td>
                                <td>
                                	<ul class="actions">
                                        <li><a href="<?php echo base_url('article/editor_article/'.$row->id);?>">编辑</a></li>
                                        <li class="last"><a href="<?php echo base_url('article/delete_article/'.$row->id);?>" onclick="return confirm('你确定全部删除?');">删除</a></li>
                                        <li><a target="_blank" href="<?php echo "/article/show/".$row->id;?>">预览</a></li>
                                    </ul>
                                </td>
                            </tr>
                            <?php endforeach;?>
                            <tr>
                                <th>
                                   <input type="checkbox" name="allSelected"><span>全选</span>
                                </th>
                                <th></th>
                                <th></th>
                                <th class="align-right">
	                                <ul class="actions">
                                   		<li><a id="deleteAllArticle" href="javascript:void(0);" onclick="return confirm('你确定全部删除?');"><i class="table-delete"></i><span style="margin-left:5px;">批量删除</span></a></li>
	                                </ul>
                                </th>
                            </tr>
                        </tbody>
                    </table>
			</div>
			<ul class="pagination pull-right">
            	<?php echo $this->pagination->create_links(); ?>
            </ul>
	</div>
</div>