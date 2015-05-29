<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 登录注册控制器
 * */
class Login extends CI_Controller {
	
	private $captchaData=array();
	
	public function __construct()
	{
		parent::__construct();
		header( "Content-type: text/html; charset=utf-8");
		$this->load->switch_theme(ADMIN_THEME);
		$this->load->model('Admin_m');
	}
	
	public function index()
	{
		$captchaData=$out_datas=$this->_captcha();
		$this->load->view('login',$out_datas);
	}
	/***登录***/
	public function checkLogin()
	{
		//对登录数据的验证
		$this->load->library('form_validation');
		$this->form_validation->set_rules('uname','用户名','trim|required|min_length[4]|max_length[12]|xss_clean');
		$this->form_validation->set_rules('upass','密码','trim|required|min_length[4]|max_length[12]|md5');
		//$this->form_validation->set_rules('rand','验证码','trim|required');
		if ($this->form_validation->run() == FALSE){
			$this->formTips("登录失败!","表单验证失败:".validation_errors(),'login');
		}else{
			$uname=$this->input->post('uname');
			$upass=$this->input->post('upass');
			$query =$this->Admin_m->login_user($uname,$upass);
			if($query){
				//echo "登录成功";
				if($query['purid']!=1){
					$this->formTips("登失败!","非管理用户!",'login','1');
					return false;
				}
				$this->load->library('session');
				$arr=array('uid'=>$query['uid'],'uname'=>$query['uname'],'purid'=>$query['purid']);
				$this->session->set_userdata($arr);
				$this->formTips("登录成功!","恭喜你登录成功!",'system','1');
				//echo $this->session->userdata('uid');//读取session
			}else{
				$this->formTips("登录失败!","用户名或密码错误!",'login');
			}
		}
	}
	//登出
	public function loginOut()
	{
		$this->session->unset_userdata('uid');
		//echo '已经成功登出！';
		$this->formTips("已经成功登出!","已经安全退出!",'login','1');
		//redirect('admin');
	}
	//验证码
	private function _captcha(){
		$this->load->helper("captcha_helper");//加载验证码
		$vals = array(
				'img_path'     => './captcha/',        //验证码图片存放的地址
				'img_url'  => base_url()."captcha/",  //图片访问的路径
				'img_width'    => '90',                //图片的宽度
				'img_height' => 30,                    //高度
				'expiration' => 1,                     //存放时间,1分钟
				'word_length'=> 4                      //显示几位验证数字
		);
		$cap = create_captcha($vals);
		$out_datas["v_img"]=$cap["image"];                //生成的图片文件
		$out_datas["v_word"]=$cap["word"];                //生成的验证码,也可放入session中管理
		return $out_datas;
	}
	
	/***注册***/
	public function reg()
	{
		$this->load->view('signup');
	}
	public function register()
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
			$this->formTips("注册失败!","表单验证失败:".validation_errors(),'login/reg');
		}else{
			//echo "表单验证通过";
			//数据库操作
			$uname=$this->input->post('uname');
			$checkName=$this->Admin_m->check_user($uname);
			if(!$checkName){
				$uname=$this->input->post('uname',true);
				$upass=$this->input->post('upass',true);
				$usex=$this->input->post('usex',true);
				$umail=$this->input->post('umail',true);
				$uqq=$this->input->post('uqq',true);
				$uphone=$this->input->post('uphone',true);
				$purid=0;
				$query =$this->Admin_m->insert_user($uname,$upass,$usex,$umail,$uqq,$uphone,$purid);
				if ($query) {
					$this->formTips("注册成功!","恭喜你注册成功!",'login');
				}else{
					$this->formTips("注册失败!","注册失败!写入数据库失败!",'login/reg');
				}
			}else{
				$this->formTips("注册失败!","用户名已经存在!",'login/reg');
			}
		}
	}
	
	//表单submit反馈信息
	private  function formTips($title="",$tips="",$url="/",$refreshTime="3"){
		$data['title']=$title;
		$data['successTips']=$tips;
		$data['url']=$url;
		$data['refreshTime']=$refreshTime;
		$this->load->view('formTips',$data);
	}
	
}
