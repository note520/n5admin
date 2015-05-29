<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('N5Font_comon_Controller.php');

class Category extends N5Font_comon_Controller {
	public function __construct()
	{
		parent::__construct();
	}
	
	public function article_list($cid){	
		$header=$this->cartInfo($cid);
		if(!empty($header)){
			$data['comon_cart_info']=$header;
		}
		$data["siteInfo"]=$this->siteInfo();
		$data["navListArr"]=$this->navListArr();
		$data["article_list"]=$this->show_list($cid);
		//当前栏目处理
		$topSortId=$this->handlSortParent($cid);
		if($topSortId!=0){
			$topSortId=$topSortId;
		}else{
			$topSortId=$cid;
		}
		$data["subNav_list"]=$this->subNav($topSortId);
		$this->load->view('header',$data);
		$this->load->view('list_news',$data);
		$this->load->view('footer',$data);
	}
	//内容列表
	protected function show_list($cid){
		//分页
		$datawhere = array(
				'cid'=>$cid
		);
		$totalnum=$this->Article_m->get_where_num($datawhere);
		$config['base_url'] = base_url('category/article_list/'.$cid.'/');
		$config['total_rows'] = $totalnum;//总页码
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

		
		//获取当前类以及子类文章
		$currentSortArr=$this->Category_m->get_id_allCategory($cid);
		if(!empty($currentSortArr)){
			array_push($currentSortArr,$cid);
			$totalRows=$this->Article_m->current_child_row_num('cid',$currentSortArr);
			$config['total_rows'] = $totalRows;//总页码
			$offset=$this->uri->segment(4);
			if(empty($offset)){
				$offset=0;
			};
			$articleArr=$this->Article_m->current_child_article('cid',$currentSortArr,$config['per_page'],$offset);
		}else{
			$articleArr=$this->Article_m->get_sort_article($cid,$config['per_page'],$this->uri->segment(4));
		}
		
		$this->pagination->initialize($config);
		return $articleArr;
	}
	//当前类别的子菜单
	protected function subNav($pid){
		$arr=array();
		$arr=$this->Category_m->get_all_cate($pid,false,1);
		//print_r($arr);
		return $arr;
	}
}
?>
