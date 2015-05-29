<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Category_m extends CI_Model{
	
	private $tableName;
	private $level;//分类等级,即当前目录的级别
	//所取分类的深度
	private $depth = 0;
	private $startLevel = 0;
	
	public function __construct()
	{
		parent::__construct();
		//初始化表参数
		$this->tableName = (isset($arr['tableName'])) ? $arr['tableName'] : 'my_categories';
		$this->cid = (isset($arr['cid'])) ? $arr['cid'] : 'cid';
		$this->pid = (isset($arr['pid'])) ? $arr['pid'] : 'pid';
		$this->cname = (isset($arr['cname'])) ? $arr['cname'] : 'cname';
		$this->sort = (isset($arr['sort'])) ? $arr['sort'] : 'sort';
		$this->keywords = (isset($arr['keywords'])) ? $arr['keywords'] : 'keywords';
		$this->content = (isset($arr['content'])) ? $arr['content'] : 'content';
		$this->clevel = (isset($arr['clevel'])) ? $arr['clevel'] : 'clevel';
	}
	
	public function index(){
	
	}
	//取所有数据
	public function get_all_data(){
		$query = $this->db->get($this->tableName);
		return $query->result_array();
	}
	//取一条数据
	public function get_one_data($cid){
		 $this->db->where($this->cid,$cid);
         $query = $this->db->get($this->tableName);
         return $query->row_array(1);
	}
	//取出所有分类信息，返回数组，包括分类名称，一般用在select标签中显示
	public function get_all_cate($pid = 0,$withself = false,$depth = 0){
		$result = array();
		$resArr = $this->get_all_data(); //获取所有分类信息
		if($pid == 0 && $withself)//包含自己
		{
			$root = array(
					$this->cid => 0,
					$this->pid =>-1,
					$this->cname => '根目录',
					$this->keywords =>'',
					$this->content =>'',
					$this->clevel => -1,
					$this->sort => 0
			);
			array_unshift($resArr, $root);//数组前插入数组$root
		}
		if (empty($resArr))//为空为0
		{
			return array();
		}
		//取得根目录
		foreach($resArr as $item)
		{
			    if ($item[$this->pid] == $pid)	
			    {
				    $level = $item[$this->clevel];
			    }
			    if ($withself)
			    {
				    if ($item[$this->cid] == $pid)	
				    {
					    $result[] = $item;
                        $level = $item[$this->clevel];
                        break;
				    }
			   }
		 }
		 
		if (!isset($level))
		 {
			return array();
		}
		$this->depth = $depth;
		$this->startLevel = $level;
		$nextLevel = $withself ? ($level + 1) : $level;
		$nextChild=$this->get_children($resArr,$pid,$nextLevel);
		return array_merge($result,$nextChild);//合并数组
	}
	//按顺序返回分类数组,用递归实现
	public function get_children($cateArr,$fatherId=0,$level = 1){
		if($this->depth != 0 && ($level >=($this->depth + $this->startLevel)))
		{
			return array();
		}
		$resultArr = array();
		$childArr = array();
		//遍历当前父ID下的所有子分类
		foreach($cateArr as $item)
		{
			if($item[$this->pid] == $fatherId && ($item[$this->clevel] == $level))
			{
				//将子分类加入数组
				$childArr[] = $item;
			}
			
		}
		if(count($childArr) == 0)
		{
			//不存在下一级，无需继续
			return array();
		}else{
			//存在下一级，按sort排序先
			usort($childArr,array('Category_m','compareBysort'));
			
			foreach($childArr as $item)
			{
				$resultArr[] = $item;
				$temp = $this->get_children($cateArr,$item[$this->cid],($item[$this->clevel] + 1));//递归实现
				if(!empty($temp))
				{
					$resultArr = array_merge($resultArr, $temp);
				}
			}
			return $resultArr;
		}
		
	}
	//比较函数,提供usort函数用
	private function compareBysort($a, $b)
	{
		if ($a == $b)
		{
			return 0;
		}
		return ($a[$this->sort] > $b[$this->sort]) ? +1 : -1;
	}
	//取出某一分类下的所有ID，返回数组，fatherId = 0为根目录
	public function get_id_allCategory($fatherId = 0,$widthself = false,$depth = 0)
	{
		$idArr = array();
		if ($widthself)
		{
			array_push($idArr,$fatherId);
		}
		$cate = $this->get_all_cate($fatherId,$widthself,$depth);
		foreach($cate as $item)
		{
			$idArr[] = $item[$this->cid];
		}
		return $idArr;
	}
	//判断是否有子类别
	function isChildren($id)
	{
		//从数据库中取出只有fatherId字段的数据，返回数组
		$this->db->select($this->pid);
		$query = $this->db->get($this->tableName);
		$resArr = $query->result_array();
		foreach ($resArr as $v)
		{
			$arr[] = $v[$this->pid];
		}
		return (in_array($id,array_unique($arr))) ? true : false;
	}
	/*****curd********/
	//添加-插入
	public function add_cate($data)
	{
		$this->db->insert($this->tableName,$data);
		return $this->db->affected_rows();
	}
	//删除
	public function delete_cate($cid,$widthChild =true){
		if ($widthChild)
		{
			$idArr = $this->get_id_allCategory($cid,true);
			$this->db->where_in($this->cid,$idArr);
		}
		else
		{
			$this->db->where($this->cid,$cid);
		}
		
		$this->db->delete($this->tableName);
		return $this->db->affected_rows();
	}
	//更新
	public function update_cate($cid,$pid,$cname,$keywords,$content,$sort,$clevel){
		//先获取父分类的信息
		$parentInfo=$this->get_one_data($pid);
		//获取当前等级
		if(isset($parentInfo['clevel']))
		{
			$level = $parentInfo['clevel'];
		}
		else
		{
			$level = 0;
		}
		$pidInt=intval($pid);
		if($pidInt!==0){
			$newLevel = $level + 1;
		}else{
			$newLevel=0;
		}
		$currentInfo = $this->get_one_data($cid);
		$levelDiff = $newLevel - $currentInfo['clevel'];
		//修改子分类的level
		if(0 != $levelDiff)
		{
			$childIdArr = $this->get_id_allCategory($cid);
			foreach($childIdArr as $item)
			{
				$this->db->set($this->clevel, $this->clevel.'+'.$levelDiff, FALSE);
				$this->db->where($this->cid, $item);
				$this->db->update($this->tableName);
			}
		}
		//修改自己的信息
		$updaeArr = array(
				'pid' => $pid,
				'cname' => $cname,
				'clevel'=> $newLevel,
				'sort' => $sort,
				'keywords' => $keywords,
				'content' => $content
		);
        $this->db->where($this->cid, $cid);
        $this->db->update($this->tableName, $updaeArr); 
        return $this->db->affected_rows();
	}
	/**生成部分视图***/
	//分类options
	public function optsList($selectId = 0,$withself = true){
		$str = '';
		$cate =$this->get_all_cate(0,$withself,0);
		if (!empty($cate))
		{
			$line = '┣';
			foreach($cate as $item)
			{
				$selected = '';
				if ($selectId != 0 && $item['cid'] == $selectId)
				{
					$selected = 'selected';
				}
				$str .= '<option '.$selected.' value="'.$item['cid'].'">'.$line.str_repeat('━',(abs($item['clevel'] -0))*1).$item['cname'].'</option>';
			}
		}
		return $str;
	}
}
?>