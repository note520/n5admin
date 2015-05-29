<?php
class Admin_m extends CI_Model {
	
 	function __construct()
    {
        parent::__construct();
    }
    //注册用户
    public function check_user($uname){
    	$this->db->where('uname',$uname);
		$this->db->select('*');
		$query = $this->db->get('my_admin');
		$checkName=$query->result();
		if($checkName){
			return true;
		}else {
			return false;
		}
    }
    //创建用户
    public function insert_user($uname,$upass,$usex,$umail,$uqq,$uphone,$purid){
		$this->db->query("INSERT INTO my_admin(uname,upass,usex,uqq,umail,uphone,rg_date,purid)VALUES('$uname','$upass','$usex','$uqq','$umail','$uphone',now(),$purid)");
    	return $this->db->affected_rows();
    }
    //登录检测
    public function login_user($uname,$upass){
		$query=$this->db->query("SELECT * FROM my_admin WHERE uname='$uname' and upass='$upass'");
    	return $query->row_array();
    }
    //查询所有用户
    public function user_list(){
    	$query=$this->db->query("SELECT * FROM my_admin");
    	return $query->result();
    }
    //查询单个用户
	public function select_user($uid){
    	$query=$this->db->query("SELECT * FROM my_admin WHERE uid=$uid");
    	return $query->row_array();
    }
    //查询分页
	function get_user_list($num, $offset) {
    	$query = $this->db->get('my_admin', $num, $offset);        
    	return $query->result();
  	}
    //删除单个用户
	public function del_user($uid){
    	$query=$this->db->query("DELETE FROM my_admin WHERE uid=$uid");
    	return $this->db->affected_rows();
    }
    //更新数据
    public function updataVal($uid,$arr){
    	$this->db->where('uid',$uid);
		$this->db->update('my_admin',$arr);
		return $this->db->affected_rows();
    }
}