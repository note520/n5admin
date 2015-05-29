<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('N5Font_comon_Controller.php');

class Article extends N5Font_comon_Controller {
	public function __construct()
	{
		parent::__construct();
	}
	public function show($id){
		$data["siteInfo"]=$this->siteInfo();
		$data["navListArr"]=$this->navListArr();
		$data["article_info"]=$this->get_Article_info();
		$cid=$data["article_info"]['cid'];
		$header=$this->cartInfo($cid);
		if(!empty($header)){
			$data['comon_cart_info']=$header;
		}
		//当前栏目处理
		$topSortId=$this->handlSortParent($cid);
		if($topSortId!=0){
			$topSortId=$topSortId;
		}else{
			$topSortId=$cid;
		}; 
		$data["subNav_list"]=$this->subNav($topSortId);
		$this->load->view('show_news',$data);
		$this->load->view('footer',$data);
	}
	protected function get_Article_info(){
		$showId=$this->uri->segment(3);
		$showArr=$this->Article_m->get_id_article($showId);
		return $showArr;
	}
	//当前类别的子菜单
	protected function subNav($pid){
		$arr=array();
		$arr=$this->Category_m->get_all_cate($pid,false,1);
		return $arr;
	}
}
