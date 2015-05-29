<?php
class System_m extends CI_Model {
	
 	function __construct()
    {
        parent::__construct();
        $this->tableName = (isset($arr['tableName'])) ? $arr['tableName'] : 'my_site_config';
    }
    //查询正个表所有信息
    public function get_id_siteInfo($siteId){
    	$query = $this->db->get_where($this->tableName,array('siteId' => $siteId));
    	return $query->row_array();
    }
   //更新站点信息
   public function updata_siteInfo($siteId,$arr){
   		$this->db->where('siteId',$siteId);
		$this->db->update($this->tableName,$arr);
		return $this->db->affected_rows();
   }
   
}