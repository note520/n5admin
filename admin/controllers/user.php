<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends Admin_Controller{
	
	public function __construct()
	{
		parent::__construct();
	}
	//当前用户信息
	public function personal(){
		$uid=$this->session->userdata('uid');
		$this->load->model('Admin_m');
		$query=$this->Admin_m->select_user($uid);
		if($query){
			$data=$query;
			$this->_template('user_editor',$data);
		}
	}
	/*用户管理模块*/
	//获取用户列表
	public function user_list(){
		//分页
		$this->load->model('Admin_m');
		$this->load->library('pagination');
		$config['base_url'] = base_url('user/user_list');
		$config['total_rows'] = $this->db->count_all('my_admin');//总页码
		$config['per_page'] = 5;//每一页的数量
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
		$data['user_list'] = $this->Admin_m->get_user_list($config['per_page'],$this->uri->segment(3));
		if($data['user_list']){
			$this->_template('user_list',$data);
		}
	}
	//获取用户
	public function get_user(){
		$uid=$this->uri->segment(3);
		$this->load->model('Admin_m');
		$query=$this->Admin_m->select_user($uid);
		if($query){
			$data=$query;
			$this->_template('user_editor',$data);
		}
	}
	//删除用户
	public function delete_user(){
		$uid=$this->uri->segment(3);
		$this->load->model('Admin_m');
		$query=$this->Admin_m->del_user($uid);
		if($query){
			//echo "删除成功";
			//redirect('admin/user_list');
			$this->formTips("删除成功","删除成功",'user/user_list');
		}else{
			//echo "删除失败";
			$this->formTips("删除失败","删除失败",'user/user_list');
		}
	}
	//编辑用户
	public function editor_user(){
		$uid=$this->input->post('uid');
		$uname=$this->input->post('uname');
		$upass=md5($this->input->post('upass'));
		$umail=$this->input->post('umail');
		$uqq=$this->input->post('uqq');
		$uphone=$this->input->post('uphone');
		$arr=array(
				'upass'=>$upass,
				'umail'=>$umail,
				'uqq'=>$uqq,
				'uphone'=>$uphone
		);
		$this->load->library('form_validation');
		//表单预处理验证规则
		$config=array(
				array(
						'field'=>'upass',
						'label'=>'密码',
						'rules'=>'trim|required|min_length[4]|max_length[12]|md5'
				),
				array(
						'field'=>'umail',
						'label'=>'E-mail',
						'rules'=>'trim|required|valid_email'
				),
				array(
						'field'=>'uqq',
						'label'=>'QQ',
						'rules'=>'trim|required|integer'
				),
				array(
						'field'=>'uphone',
						'label'=>'电话/手机',
						'rules'=>'trim|required|integer'
				)
		);
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE){
			$this->formTips("修改失败!","表单填写不符合要求！修改失败:".validation_errors(),'user/user_list');
		}else{
			$this->load->model('Admin_m');
			$query=$this->Admin_m->updataVal($uid,$arr);
			$this->formTips("修改成功!","修改成功:",'user/user_list',1);
			//redirect('user/user_list');
		}
		 
	}
	//添加用户
	public function add_user()
	{
		$this->_template('user_creat');
	}
	public function creat_user()
	{
		//对注册数据的验证
		$this->load->library('form_validation');
		//表单预处理验证规则
		$config=array(
				array(
						'field'=>'uname',
						'label'=>'用户名',
						'rules'=>'trim|required|min_length[4]|max_length[12]|xss_clean'
				),
				array(
						'field'=>'upass',
						'label'=>'密码',
						'rules'=>'trim|required|min_length[4]|max_length[12]|md5'
				),
				array(
						'field'=>'usex',
						'label'=>'性别',
						'rules'=>'required'
				),
				array(
						'field'=>'umail',
						'label'=>'E-mail',
						'rules'=>'trim|required|valid_email'
				),
				array(
						'field'=>'uqq',
						'label'=>'QQ',
						'rules'=>'trim|required|integer'
				),
				array(
						'field'=>'uphone',
						'label'=>'电话/手机',
						'rules'=>'trim|required|alpha_dash'
				)
		);
		$this->form_validation->set_rules($config);
	
		if ($this->form_validation->run() == FALSE){
			$this->formTips("注册失败!","表单验证失败:".validation_errors(),'user/add_user');
		}else{
			//echo "表单验证通过";
			//数据库操作
			$this->load->model('Admin_m');
			$uname=$this->input->post('uname');
			$checkName=$this->Admin_m->check_user($uname);
			if(!$checkName){
				$uname=$this->input->post('uname');
				$upass=$this->input->post('upass');
				$usex=$this->input->post('usex');
				$umail=$this->input->post('umail');
				$uqq=$this->input->post('uqq');
				$uphone=$this->input->post('uphone');
				$purid=0;
				$query =$this->Admin_m->insert_user($uname,$upass,$usex,$umail,$uqq,$uphone,$purid);
				if ($query) {
					$this->formTips("注册成功!","恭喜你注册成功!",'user/user_list');
				}else{
					$this->formTips("注册失败!","注册失败!写入数据库失败!",'user/add_user');
				}
			}else{
				$this->formTips("注册失败!","用户名已经存在!",'user/add_user');
			}
		}
	}
	
	
}
