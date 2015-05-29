<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Category extends Admin_Controller{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Category_m');
	}

	public function index(){
		//$data["trList"]=$this->Category_m->get_all_cate();
		$data['trListStr']=$this->trListStr();
		//分类列表分页
		$this->_template('cate_list',$data);
	}
	//分类列表
 	public function trListStr()
 	{
            //开头 <li><a href="'.base_url('category/move/'.$item['cid']).'"><i class="icon-exchange"></i><span style="margin-left:5px;">移动</span></a></li>
            $str = '';
            $startLevel = -1;
            $preLevel = 0;
            $cate = $this->Category_m->get_all_cate();
            if (!empty($cate)) 
            {
                foreach($cate as $item)
                {
                    if ($item['cid']!= 0)
                    {
                        $str .= '<tr><td><input type="checkbox" value="'.$item['cid'].'"><a style="text-decoration:none;" href="'.base_url('category/edit/'.$item['cid']).'">'.str_repeat('&nbsp;',($item['clevel']-$startLevel)*4).''.($this->Category_m->isChildren($item['cid']) ? "+" : "-").'&nbsp;'.$item['cname'].'
                            </a></td><td class="align-right"><ul class="actions">
                                <li><a href="'.base_url('category/edit/'.$item['cid']).'" class="table-edit"><i class="icon-pencil"></i><span style="margin-left:5px;">编辑</span></a></li>
                                <li><a onclick=\'return confirm("你确定删除?");\' href="'.base_url('category/delete/'.$item['cid']).'"><i class="table-delete"></i><span style="margin-left:5px;">删除</span></a></li>
                            </ul></td></tr>
                            ';
                    }           
                }
            }
            return $str;
        }
	//添加分类
	public function add(){
		//生成select选项
		$data['optList']=$this->Category_m->optsList();
		$this->_template('cate_add',$data);
	}
	public function creat(){
		if($_POST){
			$pid=$this->input->post('pid');
			$sort=$this->input->post('sort');
			if(empty($sort)||isset($sort)){
				$sort=99;
			};
			//先获取父类的类别信息
			$parentInfo = $this->Category_m->get_one_data($pid);
			if (isset($parentInfo['clevel']))
			{
				$level = $parentInfo['clevel']+ 1;
			}
			else
			{
				$level = 0;
			}
			//表单预处理验证规则
			$this->load->library('form_validation');
			$config=array(
					array(
							'field'=>'cname',
							'label'=>'分类名称',
							'rules'=>'trim|required|max_length[12]|xss_clean'
					),
					array(
							'field'=>'sort',
							'label'=>'排序',
							'rules'=>'trim|integer'
					)
			);
			$this->form_validation->set_rules($config);
			if ($this->form_validation->run() == FALSE){
				$this->formTips("表单验证失败","- - 表单验证失败".validation_errors(),'category/add');
			}else{
				$str = array(
						'pid'=>$pid,
						'cname'=>$this->input->post('cname'),
						'content'=>strip_tags($this->input->post('content')),
						'keywords'=>$this->input->post('keywords'),
						'sort'=>$this->input->post('sort'),
						'clevel'=>$level,
						'sort'=>$sort
				);
				//提交数据
				if($this->Category_m->add_cate($str)>0){
					$this->formTips("添加成功","添加成功",'category/add');
				}else{
					$this->formTips("添加失败","添加失败",'category');
				};
				//echo "<script language=\"javascript\">history.go(-1);</script>";
			};
		};
	}
	
	//编辑
	public function edit(){
		$cid=$this->uri->segment(3);
		$data['editArr']=$this->Category_m->get_one_data($cid);
		$data['editOpts']=$this->Category_m->optsList($data['editArr']['pid']);
		//echo print_r($data['editOpts']);
		$this->_template('cate_editor',$data);
	}
	public function update(){
		if($_POST){
			$cid=$_POST['cid'];
			$pid=$_POST['pid'];
			$cname=$_POST['cname'];
			$keywords=$_POST['keywords'];
			$content=$_POST['content'];
			$sort=$_POST['sort'];
			$clevel=$_POST['clevel'];
			if($pid==$cid){
				$this->formTips("不能选择本身类别","不能选择本身类别",'category');
				return;
			};
			$jquery=$this->Category_m->update_cate($cid,$pid,$cname,$keywords,$content,$sort,$clevel);
			if($jquery>0){
				$this->formTips("修改成功","修改成功",'category');
			}else{
				$this->formTips("修改失败","修改失败",'category');
			}
		}else{
			$this->formTips("更新数据失败","更新数据失败",'category');
		}
	}
	//删除
	public function delete(){
		$cid=$this->uri->segment(3);
		$query=$this->Category_m->delete_cate($cid);
		//提交数据
		if($query>0){
			$this->formTips("删除成功","删除成功",'category');
		}else{
			$this->formTips("删除失败","删除失败",'category');
		};
	}
	//批量删除
	public function deleteArr(){
		$arr=array();
		$arr=$this->input->post('arr');
		foreach($arr as $value){
			$query=$this->Category_m->delete_cate($value);
			if($query>0){
				echo 1;
			}else{
				echo 0;
			};
		}
	}
}
