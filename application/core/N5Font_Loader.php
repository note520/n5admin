<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class N5Font_Loader extends CI_Loader
{
	/**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
	public function __construct()
	{
		parent::__construct();
	}
	// ------------------------------------------------------------------------

    /**
     * 公共模型url
     *
     * @access  public
     * @return  void
     */
	public function comon_models()
	{
		//print_r($this->_ci_model_paths);
	}
	
	// ------------------------------------------------------------------------

}
