<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class N5Font_comon_Controller extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('System_m');
	}
	//导航分类
	public function navListArr()
	{
		$arr=array();
		$cate = array_reverse($this->Category_m->get_all_cate($pid = 0,$withself = false,$depth = 1));
		if (!empty($cate))
		{
			foreach($cate as $item)
			{
				if ($item['cid']!= 0)
				{
					$arr[]=array("id"=>$item['cid'],"name"=>$item['cname']);
				}
			}
		};
		return $arr;
	}
	//获取网站配置信息
	public function siteInfo(){
		$site_info=$this->System_m->get_id_siteInfo(1);
		return $site_info;
	}
	//获取当前栏目信息
	public function cartInfo($cid){
		$info=$this->Category_m->get_one_data($cid);
		return $info;
	}
	//获取当前类目的顶级栏目
	public function handlSortParent($cid){
		//获取当前顶级父类id
		$topParentId=0;
		$tempId=$initPid=intval($this->getSortParent($cid));//初始化获取当前id的fid
		if(!empty($initPid)&& $initPid!=0){
			//循环执行寻找其父id，如果fid不是0则则继续循环，再次循环找其fid，直到找到顶级类目
			do{
				$pid=intval($this->getSortParent($tempId));
				if($pid!=0){
					$topParentId=$pid;
					$tempId=$pid;
				}else{
					$topParentId=$tempId;
				}
			}while($pid!=0);
		}
		return $topParentId;
	}
	//获取当栏目的父id
	protected function getSortParent($cid){
		$currentData=$this->Category_m->get_one_data($cid);
		$parentId=$currentData['pid'];
		return $parentId;
	}
	
	/**************************************************************
	 *
	*    使用特定function对数组中所有元素做处理
	*    @param  string  &$array     要处理的字符串
	*    @param  string  $function   要执行的函数
	*    @return boolean $apply_to_keys_also     是否也应用到key上
	*    @access public
	*
	*************************************************************/
	protected function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
	{
		static $recursive_counter = 0;
		if (++$recursive_counter > 1000) {
			die('possible deep recursion attack');
		}
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
			}else {
				$array[$key] = $function($value);
			}
	
			if ($apply_to_keys_also && is_string($key)) {
				$new_key = $function($key);
				if ($new_key != $key) {
					$array[$new_key] = $array[$key];
					unset($array[$key]);
				}
			}
		}
		$recursive_counter--;
	}
	/**************************************************************
	 *
	*    输出支持中文的json格式
	*    @param  string  $code     状态
	*    @param  string  $message  提示信息
	*    @param array   $data     数据
	*    @return string
	*
	*************************************************************/
	protected function JSON($code,$message="",$data=array()) {
		if(!is_numeric($code)){
			return "";
		};
		$this->arrayRecursive($data, 'urlencode', true);
		$result=array(
			"code"=>$code,
			"message"=>urlencode($message),
			"data"=>$data,
		);
		$json = json_encode($result);
		return urldecode($json);
	}
	/**************************************************************
	 *
	*    stdClass Object转换成array
	*    @param array   $data     数据
	*    @return array
	*
	*************************************************************/
	protected function object_array($array)
	{
		if(is_object($array))
		{
			$array = (array)$array;
		}
		if(is_array($array))
		{
			foreach($array as $key=>$value)
			{
				$array[$key] = $this->object_array($value);
			}
		}
		return $array;
	}
	
	public function json_encode($input){
		// 从 PHP 5.4.0 起, 增加了这个选项.
		if(defined('JSON_UNESCAPED_UNICODE')){
			return json_encode($input, JSON_UNESCAPED_UNICODE);
		}
		if(is_string($input)){
			$text = $input;
			$text = str_replace('\\', '\\\\', $text);
			$text = str_replace(
					array("\r", "\n", "\t", "\""),
					array('\r', '\n', '\t', '\\"'),
					$text);
			return '"' . $text . '"';
		}else if(is_array($input) || is_object($input)){
			$arr = array();
			$is_obj = is_object($input) || (array_keys($input) !== range(0, count($input) - 1));
			foreach($input as $k=>$v){
				if($is_obj){
					$arr[] = self::json_encode($k) . ':' . self::json_encode($v);
				}else{
					$arr[] = self::json_encode($v);
				}
			}
			if($is_obj){
				return '{' . join(',', $arr) . '}';
			}else{
				return '[' . join(',', $arr) . ']';
			}
		}else{
			return $input . '';
		}
	}
	
}
?>
