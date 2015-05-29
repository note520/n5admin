<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('category.php');
class ApiCategory extends Category {
	
	public function __construct()
	{
		parent::__construct();
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
	}
	
	public function api_article_list($cid){
		$data=$this->subNav($cid);
		$result=$this->JSON(200,"返回成功",$data);
		//print_r($result);
		$this->output->set_output(htmlspecialchars_decode($result));
	}
	
	public function api_test(){
		$data=array("name"=>"rico","sex"=>"boy","age"=>27);
		$result=$this->JSON(200,"返回成功",$data);
		$this->output->set_output(htmlspecialchars_decode($result));
	}
	
}
?>