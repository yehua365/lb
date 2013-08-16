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
class Mdata extends CI_Controller{
	function __construct(){
		parent::__construct();
		//auth login
        $this->myauth->execute_auth();		
		$this->load->model("Modules_model",'m');
		$this->load->model("Fields_model",'f');
		$this->load->model("Mdata_model",'im');
		
		

	}

	function index(){
		$data = $this->im->select_list();
		$this->mypage->load_backend_view('mdata_select',$data);
	}

	function action_set_module(){
		$this->im->set_mid($this->uri->segment(4));
		$this->mypage->backend_redirect('mdata/action_list');
	}


	function action_add(){

		$id = $this->uri->segment(4);
		//fetch fileds

		$main = array();
		if($id){
			$main = $this->im->detail($id);
		}
		//all fields
		$fields = $this->m->details($this->im->get_mid(),array('r_primary'=>'0'));
		
		//primary key
		$primary = $this->m->fetch_primary($this->im->get_mid(),'r_name');

		//fields html
		$fields_html = $this->m->fetch_f_html();
		

		$data = array('ops'=>array(1=>1,2=>2,3=>3),'main'=>$main,'fields'=>$fields,'html'=>$fields_html,'primary'=>$primary);
		$data = array_merge($data,array('theme'=>$this->m->main($this->im->get_mid(),'m_name')));
		$this->mypage->load_backend_view('mdata_add',$data);
	}

	function action_save(){
		$main = $this->input->post('main');
		$data = array('main'=>$main);
		try{
			$rules = $this->im->valid_config($data);
			$this->form_validation->set_rules($rules);
			if($this->form_validation->run()==false && $rules){
				//all fields
				$fields = $this->m->details($this->im->get_mid(),array('r_primary'=>'0'));
				//primary key
				$primary = $this->m->fetch_primary($this->im->get_mid(),'r_name');

				//fields html
				$fields_html = $this->m->fetch_f_html();
				
				$data = array('main'=>$main,'fields'=>$fields,'html'=>$fields_html,'primary'=>$primary);	
				$this->mypage->load_backend_view('mdata_add',$data);
				
			}else{	
				$this->mydb->save($data,$this->im->save_config());
				$this->mypage->backend_redirect('mdata/action_list','保存成功');
			}

		}catch(EXCEPTION $e){
			$this->mypage->backend_redirect($_SREVER['HTTP_REFERER'],$e->getMessage());
		}

	}


	function action_del(){
		try{
			$primary = $this->m->fetch_primary($this->im->get_mid(),'r_name');
			$ids = $this->input->post($primary);
            $ids = $ids?$ids:$this->uri->segment(4);
			if(empty($ids)) throw new Exception('参数错误');
			$this->mydb->delete($ids,$this->im->save_config());
			$this->mypage->backend_redirect('mdata/action_list','删除成功');
		}catch(EXCEPTION $e){
			$this->mypage->backend_redirect($_SERVER['HTTP_REFERER'],$e->getMessage());
		}
	}

	function action_list(){
		$query  = $this->input->get();
		$size = 15;
		$data = $this->im->fetch_list($size,$query);

		$data = array_merge($data,array('theme'=>$this->m->main($this->im->get_mid(),'m_name')));
		
		$this->mypage->load_backend_view('mdata_list',$data);
	}
}
?>