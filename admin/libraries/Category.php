<?php
	/*==================================================================*/
	/*		文件名:Category.php                                         */
	/*      功能：实现无限分类的增删改查，用于codeigniter框架，
	        也可以修改后用于其它用途。                                  */
    /*		作者：张礼军
    /*		英文名：JayJun             QQ：413920268                    */
	/*		创建时间：2012-08-29                                        */
	/*		最后修改时间：2012-08-31                                    */
	/*		copyright (c)2012 jayjun0805@sina.com                       */
	/*==================================================================*/
if (!defined('BASEPATH')) exit('No direct script access allowed'); 

    class Category {
        private $CI;         //CI对象
        private $tableName;  //要操作的表名
        //表的七个字段
        private $cid;        //分类ID 
        private $fatherId;   //父分类ID
        private $cateName;   //分类名称
        private $sort;       //分类排序，在同一父级下有多级时，用于排序
        private $content;    //分类介绍
        private $level;      //分类等级,即当前目录的级别
        private $display;    //分类显示状态
        //所取分类的深度
        private $depth = 0;
        private $startLevel = 0;
	    /**
	     * 构造函数
	     * @param $arr 参数包括表名，及分类表的七个字段名，如果没有定义，则采用默认，
         * 默认值 
            * 表名：category	
            * 分类ID：cid	
            * 父ID：fatherId	
            * 分类名称：cateName 
            * 分类排序：sort 
            * 分类介绍：content
            * 分类等级：level
            * 分类显示状态：display
	    */
        public function __construct($arr = array())
        {   
            //通过引用的方式赋给变量来初始化原始的CodeIgniter对象
            $this->CI = &get_instance();
            //初始化表参数
            $this->tableName = (isset($arr['tableName'])) ? $arr['tableName'] : 'category';
            $this->cid = (isset($arr['cid'])) ? $arr['cid'] : 'cid';
            $this->fatherId = (isset($arr['fatherId'])) ? $arr['fatherId'] : 'fatherId';
            $this->cateName = (isset($arr['cateName'])) ? $arr['cateName'] : 'cateName';
            $this->sort = (isset($arr['sort'])) ? $arr['sort'] : 'sort';
            $this->content = (isset($arr['content'])) ? $arr['content'] : 'content';
            $this->level = (isset($arr['level'])) ? $arr['level'] : 'level';
            $this->display = (isset($arr['display'])) ? $arr['display'] : 'display';
			$this->pinyin = (isset($arr['pinyin'])) ? $arr['pinyin'] : 'pinyin';
        }

	    /**
	     * 从数据库取所有分类数据，返回数组
        */
        public function fetchData($display)
        {
            if ($display)
            {
                $query = $this->CI->db->get_where($this->tableName,array($this->display => 0));
            }
			else
			{
			    $query = $this->CI->db->get($this->tableName);
			}

            return $query->result_array();
        }


        /**
         *取某一条分类数据
         *@param $cid 分类ID 
        */
        public function fetchOne($cid)
        {
            $this->CI->db->where($this->cid,$cid);
            $query = $this->CI->db->get($this->tableName);
            return $query->row_array(1);
        }
        /**
         *取出所有分类信息，返回数组，包括分类名称，一般用在select标签中显示
         * @param $fatherId 父类ID
	     * @param $withself 查下级分类的时候，是否包含自己，默认false不包含。
         * @param $depth    所取分类的深度,值为0表示不限深度,会取所有的子分类。
         * @param $display  分类显示状态，
        */
        public function getAllCategory($fatherId = 0,$withself = false,$depth = 0,$display = false) 
        {
		    $result = array();
            $resArr = $this->fetchData($display); //获取所有分类信息
            if($fatherId == 0 && $withself)//包含自己
            {
                $root = array(
                    $this->cid => 0,
                    $this->fatherId => -1,
                    $this->cateName => '根目录',
                    $this->level => 0,
                    $this->sort => 0
                );
                array_unshift($resArr, $root);   
            }
           
		    if (empty($resArr))
		    {
			    return array();
		    }
            //取得根目录
		    foreach($resArr as $item)
		    {
			    if ($item[$this->fatherId] == $fatherId)	
			    {
				    $level = $item[$this->level];
			    }
			    if ($withself)
			    {
				    if ($item[$this->cid] == $fatherId)	
				    {
					    $result[] = $item;
                        $level = $item[$this->level];
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
		    return array_merge($result,$this->getChildren($resArr,$fatherId,$nextLevel));
	    }


	    /**
	     * 取出某一分类下的所有ID，返回数组，fatherId = 0为根目录
	     * @param $fatherId   父类ID
	     * @param $widthself  取子分类时，是否包含自己,默认不包含
	     * @param $depth      要读取的层级深度，默认查出所有子分类
	    */

        public function getAllCategoryId($fatherId = 0,$widthself = false,$depth = 0,$display = false)
        {
            $idArr = array();
		    if ($widthself)
		    {
			    array_push($idArr,$fatherId);
		    }
			$cate = $this->getAllCategory($fatherId,$widthself,$depth,$display);
		    foreach($cate as $item)
		    {
			    $idArr[] = $item[$this->cid];
		    }
		    return $idArr;
        }

        /**
	     * 用于在下拉列表框中使用
	     * @param $fatheriId 父类ID
	     * @param $widthself 若取子分类的时候是否获取本身
         * @param $depth     分类深度
         * @param $display   分类显示状态
         * @param $selectId  用于编辑分类时自动设置默认状态为selected
        */
        public function getOptionStr($fatherId = 0,$withself = false,$depth = 0,$display = false,$selectId = 0)
        {
            $str = '';
            $cate = $this->getAllcategory($fatherId,$withself,$depth,$display);
            if (!empty($cate))
            {
                $line = '┣';
                foreach($cate as $item)
                {
                    $selected = '';
                    if ($selectId != 0 && $item[$this->cid] == $selectId)
                    {
                        $selected = 'selected';
                    }
                    $str .= '<option '.$selected.' value="'.$item[$this->cid].'">'.$line.str_repeat('━',($item[$this->level] - $this->startLevel)*2).$item[$this->cateName].'</option>';
                }
            }
            return $str; 
        }

        /**
         * 用于列表显示，按ul li标签组织
         * @param $fatherId   父分类ID
         * @param $widthself  若取子分类的时候是否获取本身
         * @param $widthHref  是否提供超链接，即编辑和删除链接
         * @param $depth      分类深度
         */
        public function getListStr($fatherId = 0,$widthself = false,$withHref = true,$depth = 0,$display = false)
        {
            //开头
            $str = '';
            $startLevel = -1;
            $preLevel = 0;
            $cate = $this->getAllCategory($fatherId,$widthself,$depth,$display);
            if (!empty($cate)) 
            {
                foreach($cate as $item)
                {
                    if ($startLevel < 0)
                    {
                        $startLevel = $item[$this->level];
                    }
                    if ($item[$this->level] < $preLevel) {
                        $str .='</td>'.str_repeat('</tr></TD>',$preLevel - $item[$this->level]);
                    }
                    elseif ($item[$this->level] > $preLevel) {
                        $str .='<tr  class=row>';
                    }	
                    else
                    {
                        $str .='';
                    }	

                    if ($withHref && $item[$this->cid]!= 0)
                    {
                        $str .= '<td>&nbsp;&nbsp;'.str_repeat('&nbsp;',($item[$this->level]-$this->startLevel)*4).''.($this->isChildren($item[$this->cid]) ? "+" : "-").'&nbsp;'.$item[$this->cateName].'</td>
						    <td align=center>'.($this->isDisplay($item[$this->cid]) ? "正常" : "待审").'</td>
							<td align=center>'.$item[$this->sort].'</td>
                            <td align=center>
                                <a href="'.site_url('cate/edit/'.$item[$this->cid]).'" class="mr50 ml200">修改</a> |
                                <a onclick=\'return confirm("Are your sure to delete?");\' href="'.site_url('cate/delete/'.$item[$this->cid]).'">删除</a>
                            </td><tr>
                            ';
                    }
                    else 
                    {
                        $str .= '<TD>'.$item[$this->cateName];
                    }
                                    
                    $preLevel = $item[$this->level];
                }
            }
            //收尾
            $str .=str_repeat('',$preLevel - $startLevel + 1);
            return $str;
        }


    	/**
	     * 增加分类
	     * @param $fatherId 父类ID
         * @param $cateName 分类名称
         * @param $content  分类介绍
         * @param $sort     分类排序， 只对同一级下的分类有用
         * @param $display  分类显示状态
	    */
        public function addCategory($fatherId,$cateName,$content,$sort,$display,$pinyin)
        {
            //先获取父类的类别信息
            $parentInfo = $this->fetchOne($fatherId);
            //p($parentInfo);
            //获取分类的分类级别
            if (isset($parentInfo[$this->level]))
            {
                $level = $parentInfo[$this->level];       
            }
            else
            {
                $level = 0;
            }
            $data = array(
                $this->fatherId => $fatherId,
                $this->cateName => $cateName,
                $this->content => $content,
                $this->sort => $sort,
                $this->level => $level + 1,
                $this->display => $display,
				$this->pinyin=>$pinyin
            );        
            $this->CI->db->insert($this->tableName,$data);
            return $this->CI->db->affected_rows();
        }

        /**
	     * 删除分类
	     * @param $cid 要删除的分类ID
	     * @param $widthChild 是否删除下面的子分类，默认会删除
        */
        public function delCategory($cid,$widthChild = true)
        {
            if ($widthChild)
            {
                $idArr = $this->getAllCategoryId($cid,true);
                $this->CI->db->where_in($this->cid,$idArr);
            }
            else
            {
                $this->CI->db->where($this->cid,$cid);
            }
            $this->CI->db->delete($this->tableName);
            return $this->CI->db->affected_rows();
        }
        
        /**
         * 更新分类
         * @param $cid	    要编辑的分类ID
         * @param $fatherId	父类ID
         * @param $cateName 分类的名称
         * @param $sort 	分类排序
         * @param $display  分类显示状态
         */
        function editCategory($cid,$fatherId,$cateName,$content,$sort,$display,$pinyin)
        {
            //先获取父分类的信息
            $parentInfo = $this->fetchOne($fatherId);
            //获取当前等级
            if(isset($parentInfo[$this->level]))
            {
                $level = $parentInfo[$this->level];
            }
            else
            {
                $level = 0;
            }
            $currentInfo = $this->fetchOne($cid);
            //p($currentInfo);
            $newLevel = $level + 1;
            $levelDiff = $newLevel - $currentInfo[$this->level];

            //修改子分类的level
            if(0 != $levelDiff)
            {
                $childIdArr = $this->getAllCategoryId($cid);
                foreach($childIdArr as $item)
                {
                    $this->CI->db->set($this->level, $this->level.'+'.$levelDiff, FALSE);
                    $this->CI->db->where($this->cid, $item);
                    $this->CI->db->update($this->tableName); 
                }
            }

            //修改自己的信息
            $data = array(
                $this->fatherId => $fatherId,
                $this->cateName => $cateName,
                $this->level => $newLevel,
                $this->sort => $sort,
                $this->display => $display,
				$this->pinyin => $pinyin,
            );		
            $this->CI->db->where($this->cid, $cid);
            $this->CI->db->update($this->tableName, $data); 
            return $this->CI->db->affected_rows();
        }  

        /**
	     * 按顺序返回分类数组,用递归实现
	     * @param unknown_type $cateArr
	     * @param unknown_type $fatherId
	     * @param unknown_type $level
	    */
        private function getChildren($cateArr,$fatherId=0,$level = 1)
        {
            if($this->depth != 0 && ($level >=($this->depth + $this->startLevel)))
            {
                return array();
            }
            $resultArr = array();
            $childArr = array();
            
            //遍历当前父ID下的所有子分类

            foreach($cateArr as $item)
            {
                if($item[$this->fatherId] == $fatherId && ($item[$this->level] == $level)) 
                {
                    //将子分类加入数组
                    $childArr[] = $item;
                }
            }
           // print_r($childArr);
            if(count($childArr) == 0)
            {
                //不存在下一级，无需继续
                return array();
            }
            //存在下一级，按sort排序先
            usort($childArr,array('Category','compareBysort'));	

            foreach($childArr as $item)
            {
                $resultArr[] = $item;
                $temp = $this->getChildren($cateArr,$item[$this->cid],($item[$this->level] + 1));	
                if(!empty($temp))
                {
                    $resultArr = array_merge($resultArr, $temp);
                }		
            }
            //print_r($resultArr);
            return $resultArr;
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
        
	    //判断是否有子类别
        function isChildren($id)
        {
		    //从数据库中取出只有fatherId字段的数据，返回数组
		    $this->CI->db->select($this->fatherId);
		    $query = $this->CI->db->get($this->tableName);
		    $resArr = $query->result_array();
            foreach ($resArr as $v)
            {
			    $arr[] = $v[$this->fatherId];
		    }
		    return (in_array($id,array_unique($arr))) ? true : false;
	    }
	
	    //判断状态是否启用
        function isDisplay($id)
        {
		    $query = $this->fetchOne($id);
		    return ($query[$this->display] == 1) ? true : false;
	    }

    }
/* End of file Category.php */
?>
