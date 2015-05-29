<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class System extends Admin_Controller{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('System_m');
		$this->load->library('form_validation');
		date_default_timezone_set('PRC');
	}
	
	public function index(){
		$this->_template('sys_home');
	}
	//站点基本信息
	public function site_info(){
		$data['site_info']=$this->System_m->get_id_siteInfo("1");
		$this->_template('sys_siteInfo',$data);
	}
	//编辑站点信息
	public function sys_siteInfo_editor(){
		if($_POST){
			$id=trim($this->input->post('siteId',TRUE));
			$artArr=array(
					"siteName"=>trim($this->input->post('siteName',TRUE)),
					"domainName"=>trim($this->input->post('domainName',TRUE)),
					"seoTitle"=>$this->input->post('seoTitle',TRUE),
					"seoKeywords"=>$this->input->post('seoKeywords',TRUE),
					"seoDescription"=>$this->input->post('seoDescription',TRUE),
			);
			//表单预处理验证规则
			$rule=array(
					array(
							'field'=>'siteName',
							'label'=>'站点名称',
							'rules'=>'trim|required'
					)
			);
			$this->form_validation->set_rules($rule);
			if ($this->form_validation->run() == FALSE){
				$this->formTips("添加失败","表单填写不符合要求！修改失败:".validation_errors(),'article/add');
			}else{
				$query=$this->System_m->updata_siteInfo($id,$artArr);
				//提交数据
				if($query>0){
					$this->formTips("保存成功","保存成功",'system/site_info');
				}else{
					$this->formTips("保存失败","保存失败",'system/site_info');
				};
			}
		}else {
			echo "POST出错";
		}
	}
}
