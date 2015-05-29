<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('N5Font_comon_Controller.php');
class API extends N5Font_comon_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
	}
	//json转换中文有问题
	protected function out_json($data,$stateCode=200,$replayStr="返回成功"){
		if(!empty($data)){
			$result=$this->JSON($stateCode,$replayStr,$data);
			$this->output->set_output(htmlspecialchars_decode($result));
		}else{
			$result=$this->JSON($stateCode,$replayStr,array());
			$this->output->set_output(htmlspecialchars_decode($result,ENT_QUOTES));
		}
	}
	
	/**************************************************************
	 *
	*    首页json数据
	*    @access public
	*
	*************************************************************/
	public function home($page=1,$num=12){
		$all_sort=$this->all_sort();
		$news_list=$this->news_list($page,$num);
		$data=array(
			"tagName"=>"首页",
			"cateList"=>$all_sort,
			"infoList"=>$news_list
		);
		$result=json_encode($data);
		$this->output->set_output(htmlspecialchars_decode($result));
	}
	/**************************************************************
	 *
	*    详情页json数据
	*    @access public
	*
	*************************************************************/
	public function details($id){
		$article=$this->Article_m->get_article_result($id);
		$content=htmlspecialchars_decode($article->content);		
		$data=array(
				"tagName"=>"详情页",
				"id"=>$article->id,
				"cid"=>$article->cid,
				"title"=>$article->title,
				"subtitle"=>$article->subtitle,
				"resume"=>$article->resume,
				"created_date"=>$article->created_date,
				"author"=>$article->author,
				"hits"=>$article->hits,
				"content"=>$content
		);		
		$result=json_encode($data);
		$this->output->set_output($result);
	}
	
	/**************************************************************
	 *
	*    分类页json数据
	*    @access public
	*
	*************************************************************/
	public function cate($cid,$offset=0,$limit=12){
		$articleArr=array();
		$currentSortArr=$this->Category_m->get_id_allCategory($cid);		
		if(!empty($currentSortArr)){
			array_push($currentSortArr,$cid);
			$totalRows=$this->Article_m->current_child_row_num('cid',$currentSortArr);
			if(empty($offset)){
				$offset=0;
			};
			$articleArr=$this->Article_m->current_child_article('cid',$currentSortArr,$limit,$offset);
		}else{
			$articleArr=$this->sort_news_list($cid,$page=1,$num=10);
		};
		
		$tmpArr=array();
		foreach ($articleArr as $key=>$value){
			$tmpArr=$this->cartInfo($value["cid"]);
			$articleArr[$key]["cname"]=$tmpArr["cname"];
		};
		
		$data=array(
				"tagName"=>"分类页",
				"cate_news_list"=>$articleArr
		);
		
 		$result=json_encode($data);
		$this->output->set_output(htmlspecialchars_decode($result));
	}
	
	
	
	/**************************************************************
	 *
	*    所有分类
	*    @return array 
	*    @access public
	*
	*************************************************************/
	protected  function all_sort(){
		$data=$this->Category_m->get_all_data(false);
		return $data;
	}
	/**************************************************************
	 *
	*    分页新闻列表
	*    @param  int  $page  当前分页号
	*    @param  int  $num   每页显示数量
	*    @return null 
	*    @access public
	*
	*************************************************************/
	protected function news_list($page=1,$num=12){
		$data=$this->Article_m->select_list("id,cid,title,resume,pubdate,created_date,hits,pic,author",$num,($page-1)*$num);
		$tmpArr=array();
		foreach ($data as $key=>$value){
			$tmpArr=$this->cartInfo($value->cid);
			$data[$key]->canme=$tmpArr["cname"];
		};
		return $data;
	}
	/**************************************************************
	 *
	*    分类新闻列表
	*    @param  int  $cid   分类id 
	*    @param  int  $page  当前分页号
	*    @param  int  $num   每页显示数量
	*    @return null
	*    @access public
	*
	*************************************************************/
	protected function sort_news_list($cid,$page=1,$num=10){
		$data=$this->Article_m->get_sort_article($cid,$num,($page-1)*$num);
		return $data;
	}

	
};
?>