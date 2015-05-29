<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*输出栏目名称
 * $cid number
 * */
function trace_col_name($cid){
	$CI =& get_instance();
	$colData=$CI->Category_m->get_one_data($cid);
	echo $colData['cname'];
}
/**当前位置
 *
 * */
function trace_location($currentId){
	$CI =& get_instance();
	$id=$currentId;
	$string="";
	$location_Arr=$CI->Category_m->location_sort($id);
	foreach ($location_Arr as $value) {
		$string .= "<li><a href='/category/article_list/".$value['cid']."'>".$value['cname']."</a></li>";
	}
	echo $string;
}
/*
 * 获取文章id的栏目id
 * */
function trace_showCateId($showId){
	$CI =& get_instance();
	$showArr=$CI->Article_m->get_id_article($showId);
	return $showArr;
}
/*
 * 获取文章id的栏目名称
* */
function trace_showCateName($currentId){
	$CI =& get_instance();
	$id=$currentId;
	$string="";
	$location_Arr=$CI->Category_m->location_sort($id);
	foreach ($location_Arr as $value) {
		$string = $value['cname'];
	}
	echo $string;
}
/*
 *获取当前类别的子类 
 */
function trace_subNav($pid,$depth=1){
	$CI =& get_instance();
	$arr=array();
	$arr=$CI->Category_m->get_all_cate($pid,false,$depth);
	return $arr;
}
/*
 *判断是否有子类 
 */
function bool_isChildren($id){
	$bool=false;
	$CI =& get_instance();
	$bool=$CI->Category_m->isChildren($id);
	return $bool;
}
?>