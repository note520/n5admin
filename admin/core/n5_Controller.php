<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends CI_Controller{
	
	public function __construct()
	{
		parent::__construct();
		header( "Content-type: text/html; charset=utf-8");
		$this->load->switch_theme(ADMIN_THEME);
		//是否登录
		if(!$this->checkSession()){
			exit();
		};
	}
	
	/**
	 * 加载视图
	 *
	 * @access  protected
	 * @param   string
	 * @param   array
	 * @return  void
	 */
	protected function _template($template, $data = array())
	{
		$data['tpl'] = $template;
		$this->load->view('sys_frame', $data);//sys_entry视图
	}
	/**
	 * 信息提示
	 *
	 * @access  public
	 * @param   string
	 * @param   string
	 * @param   bool
	 * @param   string
	 * @return  void
	 */
	public function _message($msg, $goto = '', $auto = TRUE, $fix = '', $pause = 3000)
	{
		if($goto == '')
		{
			$goto = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url();
		}
		else
		{
			$goto = strpos($goto, 'http') !== false ? $goto : backend_url($goto);
		}
		$goto .= $fix;
		$this->_template('sys_message', array('msg' => $msg, 'goto' => $goto, 'auto' => $auto, 'pause' => $pause));
		echo $this->output->get_output();
		exit();
	}
	/**
	 * 表单submit反馈信息
	 * @access protected
	 * @param   string
	 * @param   string
	 * @param   string
	 * @param   string
	 * @return  void
	 */
	protected  function formTips($title="",$tips="",$url="/",$refreshTime="1"){
		$data['title']=$title;
		$data['successTips']=$tips;
		$data['url']=$url;
		$data['refreshTime']=$refreshTime;
		$this->load->view('formTips',$data);
	}
	/**
	 * 检查用户是否登录
	 *
	 * @access  protected
	 * @return  void
	 */
	protected function checkSession(){
		if($this->session->userdata('uid'))
		{
			//echo '已经登录';
			return true;
		}
		else
		{
			echo "非法登录!";
			//redirect('login');
			return false;
		}
	}
	
}