<?php
/**
  * start page for webaccess
  *
  * PHP version 5
  *
  * @category  PHP
  * @package   Controller
  * @author    yehua <150672834@.com>
  * @copyright 2013 conqweal
  * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License
  * @version   1.0$
  * @link      http://phpsysinfo.sourceforge.net
  */
class Sales extends CI_Controller {

	function __construct()
	{
		parent::__construct();	
	
		//启用缓存显示,key is action name,val is view name
		$act_to_view = array(
			'index'=>'sales',
		);
		$this->mypage->view_cache_all($act_to_view);	
	}
	
		/**
	 * 普通用户登录
	 */
	function index()
	{
		
		$this->mypage->load_front_view("sales",$data);
		
		
	}	
	
	

	
	
 
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */