<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Article extends Admin_Controller{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Article_m');
		$this->load->model('Category_m');
		$this->load->library('form_validation');
		$this->load->library('pagination');
		date_default_timezone_set('PRC');
	}
	//内容列表
	public function article_list(){
		//分页
		$config['base_url'] = base_url('article/article_list');
		$config['total_rows'] = $this->db->count_all('my_article');//总页码
		$config['per_page'] = 10;//每一页的数量
		$config['uri_segment'] = 3;// 表示第 3 段 URL 为当前页数，如 index.php/控制器/方法/页数，如果表示当前页的 URL 段不是第 3 段，请修改成需要的数值。
		$config['num_links'] = 2;
		//关闭标签
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = '</ul>';
		//数字html
		$config['num_tag_open'] ='<li>';
		$config['num_tag_close']='</li>';
		//当前页html
		$config['cur_tag_open'] ="<li class='active'><a href='javascript:void(0);'>";
		$config['cur_tag_close'] ="</a></li>";
		//上一页下一页html
		$config['next_tag_open']='<li>';
		$config['next_tag_close']='</li>';
		$config['prev_tag_open']='<li>';
		$config['prev_tag_close']='</li>';
		$config['next_link'] = '»';
		$config['prev_link'] = '«';
		$this->pagination->initialize($config);
		
		$data['title']="所有文章列表";
		$data['optList']=$this->Category_m->optsList(0,false);
		$data['article_list']=$this->Article_m->get_page_list($config['per_page'],$this->uri->segment(3));
		//分类列表分页
		if($data['article_list']){
			$this->_template('article_list',$data);
		}else{
			$data['article_list']=array();
			$this->_template('article_list',$data);
		}
		
	}
	//内容添加
	public function add(){
		$data['optList']=$this->Category_m->optsList(0,false);
		$this->_template('article_add',$data);
	}
	public function creat_article(){
		if($_POST){
			$pic=$this->input->post('pic',TRUE);
			$author=$this->input->post('author',TRUE);
			$source=$this->input->post('source',TRUE);
			$content=htmlspecialchars($this->input->post('content',false));
			if(empty($pic)){
				$pic="/static/images/noPic_0.jpg";
			};
			if(empty($author)){
				$author="匿名";
			}
			if(empty($source)){
				$source="网络收集";
			}
			$artArr=array(
					"title"=>trim($this->input->post('title',TRUE)),
					"subtitle"=>trim($this->input->post('subtitle',TRUE)),
					"cid"=>$this->input->post('cid',TRUE),
					"att"=>is_array($this->input->post('att'))?implode(',',$this->input->post('att')):'',
					"author"=>$author,
					"source"=>$source,
					"resume"=>$this->input->post('resume',TRUE),
					"content"=>$content,
					"created_by"=>$this->session->userdata('uname'),
					"created_date"=>date('Y-m-d H:i:s'),
					"pubdate"=>mktime(),
					"weburl"=>"",
					"delete_session_id"=>NULL,
					"hits"=>"0",
					"pic"=>$pic,
					"focus_pic"=>$this->input->post('focus_pic',TRUE)
			);
			//表单预处理验证规则
			$rule=array(
					array(
							'field'=>'title',
							'label'=>'标题',
							'rules'=>'trim|required'
					),
					array(
							'field'=>'cid',
							'label'=>'所属栏目',
							'rules'=>'trim|required|integer'
					),
					array(
						'field'=>'content',
						'label'=>'内容',
						'rules'=>'required|min_length[10]'
					)
			);
			$this->form_validation->set_rules($rule);
			if ($this->form_validation->run() == FALSE){
				$this->formTips("添加失败","表单填写不符合要求！修改失败:".validation_errors(),'article/add');
			}else{
				$query=$this->Article_m->insert($artArr);
				//提交数据
				if($query>0){
					$this->formTips("添加成功","添加成功",'article/add');
				}else{
					$this->formTips("添加失败","添加失败",'article/article_list');
				};
			}
		}else {
			echo "POST出错";
		}
	}
	//编辑
	public function editor_article(){
		$id=$this->uri->segment(3);
		$data['artArr']=$this->Article_m->get_id_article($id);
		$data['editOpts']=$this->Category_m->optsList($data['artArr']['cid'],false);
		$this->_template('article_editor',$data);
	}
	public function updata_article(){
		if($_POST){
			$id=trim($this->input->post('id',TRUE));
			$pic=$this->input->post('pic',TRUE);
			$content=htmlspecialchars($this->input->post('content',false));
			if(empty($pic)){
				$pic="/static/images/noPic_0.jpg";
			};
			$artArr=array(
					"title"=>trim($this->input->post('title',TRUE)),
					"subtitle"=>trim($this->input->post('subtitle',TRUE)),
					"cid"=>$this->input->post('cid',TRUE),
					"att"=>is_array($this->input->post('att'))?implode(',',$this->input->post('att')):'',
					"author"=>$this->input->post('author',TRUE),
					"source"=>$this->input->post('source',TRUE),
					"resume"=>$this->input->post('resume',TRUE),
					"content"=>$content,
					"created_by"=>$this->session->userdata('uname'),
					"created_date"=>date('Y-m-d H:i:s'),
					"pubdate"=>mktime(),
					"weburl"=>"",
					"delete_session_id"=>NULL,
					"hits"=>"0",
					"pic"=>$pic,
					"focus_pic"=>$this->input->post('focus_pic',TRUE)
			);
			//表单预处理验证规则
			$rule=array(
					array(
							'field'=>'title',
							'label'=>'标题',
							'rules'=>'trim|required'
					),
					array(
							'field'=>'cid',
							'label'=>'所属栏目',
							'rules'=>'trim|required|integer'
					),
					array(
						'field'=>'content',
						'label'=>'内容',
						'rules'=>'trim|required'
					)
			);
			$this->form_validation->set_rules($rule);
			if ($this->form_validation->run() == FALSE){
				$this->formTips("添加失败","表单填写不符合要求！修改失败:".validation_errors(),'article/add');
			}else{
				$query=$this->Article_m->updata_article($id,$artArr);
				//提交数据
				if($query>0){
					$this->formTips("保存成功","保存成功",'article/article_list');
				}else{
					$this->formTips("保存失败","保存失败",'article/article_list');
				};
			}
		}else {
			echo "POST出错";
		}
	}
	//删除
	public function delete_article(){
		$id=$this->uri->segment(3);
		if($this->Article_m->del_article($id)>0){
			$this->formTips("删除成功","删除成功",'article/article_list');
		}else{
			$this->formTips("删除失败","删除失败",'article/article_list');
		};
	}
	//批量删除
	public function deleteAll(){
		$arr=array();
		$arr=$this->input->post('arr');
		foreach($arr as $value){
			$query=$this->Article_m->del_article($value);
			if($query>0){
				$this->formTips("删除成功","删除成功",'Article');
			}else{
				$this->formTips("删除失败","删除失败",'Article');
			};
		}
	}
	//选择某分类id下的所有文章列表
	public function select_id_article($cid){
		//分页
		$datawhere = array(
				'cid'=>$cid,
		);
		$totalnum=$this->Article_m->get_where_num($datawhere);
		$config['base_url'] = base_url('article/select_id_article/'.$cid.'/');
		$config['total_rows'] = $totalnum;//总页码
		$config['per_page'] = 10;//每一页的数量
		$config['uri_segment'] = 4;// 表示第 3 段 URL 为当前页数，如 index.php/控制器/方法/页数，如果表示当前页的 URL 段不是第 3 段，请修改成需要的数值。
		$config['num_links'] = 2;
		//关闭标签
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = '</ul>';
		//数字html
		$config['num_tag_open'] ='<li>';
		$config['num_tag_close']='</li>';
		//当前页html
		$config['cur_tag_open'] ="<li class='active'><a href='javascript:void(0);'>";
		$config['cur_tag_close'] ="</a></li>";
		//上一页下一页html
		$config['next_tag_open']='<li>';
		$config['next_tag_close']='</li>';
		$config['prev_tag_open']='<li>';
		$config['prev_tag_close']='</li>';
		$config['next_link'] = '»';
		$config['prev_link'] = '«';
		$this->pagination->initialize($config);
		
		$data['title']="分类文章列表";
		$data['optList']=$this->Category_m->optsList(0,false);
		$data['article_list']=$this->Article_m->get_sort_article($cid,$config['per_page'],$this->uri->segment(4));
		if($data['article_list']){
			$this->_template('article_list',$data);
		}else{
			$data['article_list']=array();
			$this->_template('article_list',$data);
		}
	}
}
