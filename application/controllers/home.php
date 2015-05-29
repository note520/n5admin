<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('N5Font_comon_Controller.php');

class Home extends N5Font_comon_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$data["siteInfo"]=$this->siteInfo();
		$data["navListArr"]=$this->navListArr();
		$data["banListArr"]=$this->banListArr();
		$data["recomListArr"]=$this->recommendArr();
		$data["latestNewsArr"]=$this->latestNewsArr();
		//$data["articleListArr"]=$this->article_list(11);
		$this->load->view('home',$data);
		$this->load->view('footer',$data);
	}
	//banner
	protected  function banListArr(){
		$all_article=$this->Article_m->select_where();
		$list_data=array();
		if (!empty($all_article))
		{
			foreach($all_article as $item)
			{
				if ($item['att']=='p')
				{
					$list_data[]=array("id"=>$item['id'],"focus_pic"=>$item['focus_pic'],"title"=>$item['title'],"resume"=>$item['resume']);
				}
			}
		}
		return $list_data;
	}
	//最新头条内容列表
	protected  function latestNewsArr(){
		$cid=$this->uri->segment(3);
		if(empty($cid)){
			$cid=1;
		}
		//分页
		$config['base_url'] = base_url('home/index/'.$cid.'/');
		$config['per_page'] = 12;//每一页的数量
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
		$config['first_link'] = "第一页";
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '最后一页';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
	
		$limitOver=$this->uri->segment(4);
		if(empty($limitOver)){
			$limitOver=0;
		}
		$arr=array();
		//$recom_data=$this->Article_m->select_where("`att`='a'",$limitOver,$config['per_page']);
		$recom_data=$this->Article_m->get_all_list_limit($config['per_page'],$limitOver);

		if (!empty($recom_data))
		{
			foreach($recom_data as $item)
			{
				/*if ($item['att']=='p')
				{
					$arr[]=array("id"=>$item['id'],"title"=>$item['title'],"subtitle"=>$item['subtitle'],"pic"=>$item['pic'],"created_date"=>$item['created_date']);
				}*/
				$arr[]=array("id"=>$item['id'],"title"=>$item['title'],"subtitle"=>$item['subtitle'],"pic"=>$item['pic'],"created_date"=>$item['created_date']);
			}
		}
		$datawhere = array(
				//'att'=>'a',
				'cid'=>$cid
		);
		$totalnum=$this->Article_m->get_where_num();
		$config['total_rows'] = $totalnum;//总页码
		$this->pagination->initialize($config);
		return $recom_data;
	}
	//推荐列表
	protected  function recommendArr(){
		$recom_data=$this->Article_m->select_where("`att`='b'",0,4);
		return $recom_data;
	}
	//文章列表
	protected function article_list($cid){
		$article=$this->Article_m->get_sort_article($cid);
		return $article;
	}
}
?>