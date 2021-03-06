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
        $this->init_auth->execute_auth();		
		$this->load->model("Modules_model",'m');
		$this->load->model("Fields_model",'f');
		$this->load->model("Mdata_model",'im');
		$this->lang->load('item_backend_mdata',lang_get());
		$this->lang->load('item_backend',lang_get());

		

	
	}

	function index(){
		$data = $this->im->select_list();
		$this->init_page->load_backend_view('mdata_select',$data);
	}

	function action_set_module(){
		$this->im->set_mid($this->input->get_post('m_id'));
		$this->init_page->backend_redirect('d=backend&c=mdata&m=action_list');
	}


	function action_add(){
		$id = $this->input->get_post('pri_id');
		$module_id = $this->im->get_mid();
		//fetch fileds

		$main = array();
		if($id){
			$main = $this->im->detail($id);
			
		}

		$detail = $this->im->details($id);

		//all fields
		$fields = $this->m->detail_exclude_primary($module_id);	


		//primary key
		$primary = $this->m->fetch_primary($module_id,'r_name');


		

		//fields html
		$fields_html = $this->m->fetch_f_html();
		

		$data = array('ops'=>array(1=>1,2=>2,3=>3),'main'=>$main,'fields'=>$fields,'html'=>$fields_html,'primary'=>$primary);
		$data = array_merge($data,array('theme'=>$this->m->main($module_id,'m_name')));

		//detail
		$dt_mid = $this->m->main($module_id,'m_sub');
		
		$dt_fields = $this->m->detail_exclude_primary($dt_mid);	

		//detail primary key
		$dt_primary = $this->m->fetch_primary($dt_mid,'r_name');

		
		$data = array_merge($data,array('dt_fields'=>$dt_fields,'dt_primary'=>$dt_primary,'detail'=>$detail,'detail_total'=>count($detail),'dt_mid'=>$dt_mid));
		
		$this->init_page->load_backend_view('mdata_add',$data);
	}

	function action_view(){
		$id = $this->input->get_post('pri_id');
		$module_id = $this->im->get_mid();
		//fetch fileds

		$main = array();
		
		$main = $this->im->detail($id);
			
		

		$detail = $this->im->details($id);

		//all fields
		$fields = $this->m->detail_exclude_primary($module_id);	

	
		
		//primary key
		$primary = $this->m->fetch_primary($module_id,'r_name');


		

		//fields html
		$fields_html = $this->m->fetch_f_html();
		

		$data = array('ops'=>array(1=>1,2=>2,3=>3),'id'=>$id,'main'=>$main,'fields'=>$fields,'html'=>$fields_html,'primary'=>$primary);
		$data = array_merge($data,array('theme'=>$this->m->main($module_id,'m_name')));

		//detail
		$dt_mid = $this->m->main($module_id,'m_sub');
		$dt_fields = $this->m->detail_exclude_primary($dt_mid);	

		//detail primary key
		$dt_primary = $this->m->fetch_primary($dt_mid,'r_name');

		$data = array_merge($data,array('dt_fields'=>$dt_fields,'dt_primary'=>$dt_primary,'detail'=>$detail,'detail_total'=>count($detail),'dt_mid'=>$dt_mid));
		
		//tid		
		$this->load->model('Templates_model');	
		$t_id = $this->Templates_model->detail_by_mid($module_id,'t_id');
		$data = array_merge($data,array('t_id'=>$t_id));

		$this->init_page->load_backend_view('mdata_view',$data);

		
	}

	function action_view_front(){
		$id = $this->input->get_post('pri_id');
		$module_id = $this->im->get_mid();
		//primary key
		$primary = $this->m->fetch_primary($module_id,'r_name');
		

		$this->load->model('Templates_model');	
		$t_id = $this->Templates_model->detail_by_mid($module_id,'t_id');
		
		$data = array('id'=>$id,'t_id'=>$t_id,'primary'=>$primary);
		$this->init_page->load_backend_view('mdata_view_front',$data);

	}

	function action_save(){
		$main = $this->input->get_post('main');
		$detail = $this->init_form->post_to_set($this->input->get_post('detail'));
		$data = array('main'=>$main,'detail'=>$detail);
		try{
			$rules = $this->im->valid_config($data);
			$this->form_validation->set_rules($rules);
			$module_id = $this->im->get_mid();
			//all fields
			$fields = $this->m->detail_exclude_primary($module_id);
			//primary key
			$primary = $this->m->fetch_primary($module_id,'r_name');

			if($this->form_validation->run()==false && $rules){
				//fields html
				$fields_html = $this->m->fetch_f_html();
				
				$data = array('main'=>$main,'fields'=>$fields,'html'=>$fields_html,'primary'=>$primary);

				//detail
				$dt_mid = $this->m->main($module_id,'m_sub');
				$dt_fields = $this->m->detail_exclude_primary($dt_mid);	

				//detail primary key
				$dt_primary = $this->m->fetch_primary($dt_mid,'r_name');

				$data = array_merge($data,array('dt_fields'=>$dt_fields,'dt_primary'=>$dt_primary,'detail'=>$detail,'detail_total'=>count($detail),'dt_mid'=>$dt_mid));	

				
				$this->init_page->load_backend_view('mdata_add',$data);
				
			}else{	
				
				$rs = $this->init_db->save($data,$this->im->save_config());

				//insert log
				$log_cf = $this->im->save_config();
				$this->load->model('Logs_model');	
		 		$this->Logs_model->log_insert(array(
		 			'log_table'=>$log_cf['main']['table_name'],
		 			'log_table_id'=>$rs['main'][$log_cf['main']['primary_key']],
		 			'log_user'=>$this->init_auth->fetch_auth('user_name'),
		 			'log_date'=>date("Y-m-d H:i:s"),
		 			'log_sql'=>trim(implode("\n",(array)$this->db->sql_log)),
		 			'log_type'=>'12',
		 			'log_desc'=>sprintf('module %s,%s ID %s success',$this->m->main($this->im->get_mid(),'m_name'),$rs['sys_db_type'],$rs['main'][$log_cf['main']['primary_key']]),
		 		));

		 		
				//create  channel cache
		 	 	$channel = array_re_index($this->db->select('*',false)->from('module_channel')->get()->result_array(),'c_id');
		 	 	$this->init_cache->cache_create($channel,'channel');	//create  channel cache
		 	 	
		 		// if(empty($main[$primary])){
   					$callback = ',top.frmright_reload()';
		 		// }
		 		@header("content-type:text/html;charset=utf-8;");
		 		echo "<script>top.art_dialog_close('".lang('success_save')."'".$callback.");</script>";
		 		exit;
			} 

		}catch(EXCEPTION $e){
			$this->init_page->backend_redirect($_SREVER['HTTP_REFERER'],$e->getMessage());
		}

	}


	function action_del(){
		try{
		
			$primary = $this->m->fetch_primary($this->im->get_mid(),'r_name');
			$ids = $this->input->get_post($primary);
            $ids = $ids?$ids:$this->input->get_post('pri_id');
			if(empty($ids)) throw new Exception(lang('errro_parameter'));
			$cfg = $this->im->save_config();
			$rs = $this->init_db->delete($ids,$cfg);

			//insert log
			$this->load->model('Logs_model');	
			foreach($rs as $v):
		 		$this->Logs_model->log_insert(array(
		 			'log_table'=>$cfg['main']['table_name'],
		 			'log_table_id'=>$v[$cfg['main']['primary_key']],
		 			'log_user'=>$this->init_auth->fetch_auth('user_name'),
		 			'log_date'=>date("Y-m-d H:i:s"),
		 			'log_sql'=>trim(implode("\n",(array)$this->db->sql_log)),
		 			'log_type'=>'12',
		 			'log_desc'=>sprintf('module %s,delete  %s success.',$this->m->main($this->im->get_mid(),'m_name'),$rs[$log_cf['main']['primary_key']]),
		 		));
	 		endforeach;
	 		//delete img
	 		$media_fid = array_keys($this->f->fields_list('f_id',array('f_ext'=>2)));
	 		$media_fields = $this->m->details($this->im->get_mid(),'f_id in ('.implode(',',$media_fid).') ','r_id,r_name');
	 		$media_fields = array_re_index($media_fields,'r_id','r_name');
	 		

	 		foreach($rs as $k=>$v){
	 			foreach($v as $k1=>$v1){
	 				if(in_array($k1, $media_fields)){
	 					if(strpos($v1, '.')!==false){
	 						$f =  realpath(str_replace(base_url().'/', '', $v1));
	 						if(file_exists($f)){
								@unlink($f);
							}
	 					}

	 					$uds = explode(',',$v1);
	 					foreach($uds as $ud){
	 						$ct = $this->db->like($k1,$ud)->from($cfg['main']['table_name'])->count_all_results();
	 						
	 						if(!$ct){
	 							$ls = $this->db->select('i_id,i_url',false)->from('module_images')->where('i_uid',$ud)->get()->result_array();
	 						
	 							foreach($ls as $item){
		 							$f =  realpath(str_replace(base_url().'/', '', $item['i_url']));
									@unlink($f);
									@unlink(str_replace('images', '_thumbs'.DIRECTORY_SEPARATOR.'Images', $f));
	 							}
	 							//delete from img table 
			 					$this->db->where('i_uid',$ud);
			 					$this->db->delete('module_images');
 							}
	 					}	


	 					
	 					
	 				}
	 			}
	 		}
			$this->init_page->backend_redirect($_SERVER['HTTP_REFERER']);
		}catch(EXCEPTION $e){
			$this->init_page->backend_redirect($_SERVER['HTTP_REFERER'],$e->getMessage());
		}
	}

	function action_list(){	

		$query  = $this->init_form->get_post();
		$size = 6;
		$data = $this->im->fetch_list($size,$query);
		$data = array_merge($data,array('theme'=>$this->m->main($this->im->get_mid(),'m_name')));
		$this->init_page->load_backend_view('mdata_list',$data);
	}


	function action_ajax_list(){
		$query  = $this->init_form->get_post();
		$size = 6;
		$data = $this->im->fetch_list($size,$query,true);
		$data = array_merge($data,array('theme'=>$this->m->main($this->im->get_mid(),'m_name')));
		ob_clean();
		echo str_replace(array("\r","\n"),array('',''),($this->init_page->load_backend_view('ajax_mdata_list',$data,1)));
		exit;
	}
}
?>