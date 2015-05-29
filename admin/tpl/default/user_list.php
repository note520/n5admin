<div id="pad-wrapper" class="users-list">
            <div class="row header">
                <h3>会员列表</h3>
                <div class="col-md-10 col-sm-12 col-xs-12 pull-right">
                    <input type="text" class="col-md-5 search" placeholder="搜索用户名...">
                    
                    <!-- custom popup filter -->
                    <!-- styles are located in css/elements.css -->
                    <!-- script that enables this dropdown is located in js/theme.js -->
                    <div class="ui-dropdown" style="margin:8px 0 0 10px;">
                        <div class="head" data-toggle="tooltip" title="Click me!">
                          	  搜索条件
                            <i class="arrow-down"></i>
                        </div>  
                        <div class="dialog">
                            <div class="pointer">
                                <div class="arrow"></div>
                                <div class="arrow_border"></div>
                            </div>
                            <div class="body">
                                <p class="title">搜索用户条件:</p>
                                <div class="form">
                                    <select>
                                        <option>Name</option>
                                        <option>Email</option>
                                        <option>Number of orders</option>
                                        <option>Signed up</option>
                                        <option>Last seen</option>
                                    </select>
                                    <select>
                                        <option>is equal to</option>
                                        <option>is not equal to</option>
                                        <option>is greater than</option>
                                        <option>starts with</option>
                                        <option>contains</option>
                                    </select>
                                    <input type="text" class="form-control" />
                                    <a class="btn-flat small">Add filter</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="<?php echo base_url('user/add_user');?>" class="btn-flat success pull-right">
                        <span>&#43;</span>添加新会员  </a>
                </div>
            </div>

            <!-- Users table -->
            <div class="row">
                <div class="col-md-12 table-products">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="col-md-2 sortable">用户名</th>
                                <th class="col-md-1 sortable">
                                    <span class="line"></span>用户类别
                                </th>
                                <th class="col-md-2 sortable">
                                    <span class="line"></span>注册时间
                                </th>
                                <th class="col-md-1 sortable">
                                    <span class="line"></span>Email
                                </th>
                                <th class="col-md-4 sortable  align-right">
                                	<span class="line"></span>操作
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($user_list as $row):?>
                        <!-- row -->
                        <tr class="first">
                            <td>
                                <img src="img/contact-img.png" class="img-circle avatar hidden-phone" />
                                <?php echo anchor("user/get_user/$row->uid","$row->uname")?></a>
                                <span class="subtext"></span>
                            </td>
                            <td>
                               <?php if($row->purid==1){echo "管理员";}else{echo "常规用户";};?>
                            </td>
                            <td>
                               <?php echo $row->rg_date;?>
                            </td>
                            <td>
                                <?php echo $row->umail;?>
                            </td>
                            <td class="align-right">
                               <ul class="actions">
                               		<li><a href="<?php echo base_url('user/get_user/'.$row->uid);?>" class="table-edit"><i class="icon-pencil"></i></a></li>
                               		<?php $purid=$this->session->userdata('purid'); if($purid==1){?>
                                    <li class='last'><a href='<?php echo base_url('user/delete_user/'.$row->uid);?>' onclick="return confirm('你确定全部删除?');"><i class='table-delete'></i></a></li>
                                    <?php };?>
                                </ul>
                            </td>
                        </tr>
                        <?php endforeach;?>  
                        </tbody>
                    </table>
                </div>                
            </div>
            <ul class="pagination pull-right">
            	<?php echo $this->pagination->create_links(); ?>
            </ul>
            <!-- end users table -->
        </div>