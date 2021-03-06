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
class Archives extends CI_Controller{
	function __construct(){
		parent::__construct();
		//验证登陆
		$this->init_auth->execute_auth();
		$this->load->model(array('Archives_model','Category_model'));
 		$this->act = 'archives';
 		$this->im = $this->Archives_model;
 		$this->lang->load('item_backend_archives',lang_get());

 		$this->m_lang = $this->lang->language;


		$this->tpl->assign('lang_archives',$this->m_lang);
	}

	
	/**
	 * 添加
	 */
	function action_add(){
		//查询
		$info_id = $this->input->get_post('info_id');
		$parent_class = $this->input->get_post('parent_class');
		if($info_id){
			$main = $this->db->select('*')->from('infos')->where('info_id',$info_id)->get()->first_row('array');		
			
		}else{
			
			$main['info_class_sn'] = $parent_class;
		}
		
		
		$parent_class_info = $this->Category_model->detail($parent_class,'bysn');
		$class_select  = $this->Category_model->fetch_category_option($parent_class,$main['info_class_sn']);
		$class_info = array(
			'class_theme'=>$parent_class_info['c_title'],
			'parent_class'=>$parent_class_info['c_sn'],
			'class_select'=>$class_select,
		);	
		
		
		$data = array(
			'main'=>$main,
			'editor'=>$data['editor']  = $this->Common_model->editor($main['info_content']),
			'class_info'=>$class_info,
			'options_slide'=>$this->init_cache->cache_fetch('select_slide'),
			'options_slide_select'=>$main['info_slide'],
		);
		
		$this->init_page->load_backend_view(strtolower($this->act).'_add',$data);		
	}
	
	
	
	/**
	 * 保存
	 */
	 function action_save(){
	 	try{
	 		
	 		//数据
	 		$main = $this->input->get_post('main'); 		 		
			$parent_class = $this->input->get_post('parent_class');
			$parent_class_info = $this->Category_model->detail($parent_class,'bysn');
			$class_select  = $this->Category_model->fetch_category_option($parent_class,$parent_class);
			$class_info = array(
				'class_theme'=>$parent_class_info['c_title'],
				'parent_class'=>$parent_class_info['c_sn'],
				'class_select'=>$class_select,
			);			
	 		
			$data  = array(
				'main'=>$main,
				'class_info'=>$class_info,
			);
	 		
		 	//验证
		 	
		 	$this->form_validation->set_rules($this->im->validator());
		 	if($this->form_validation->run()==true){	
		 		
		 		 //图片上传 		 		
			 	 $file_config = config_item('info_files');
				 $file_upload_path = $file_config['upload_path'].'/'.date('Y-m-d')."/";
				 $file_config['upload_path']	  =  $file_upload_path;	
				 $this->Common_model->mkdirs($file_upload_path);
				 $this->load->library('upload', $file_config);				
				 if($_FILES['file1']['size']>0){
					if ( ! $this->upload->do_upload("file1")){						 
							 $this->init_page->backend_redirect($this->act.'/action_add?parent_class='.$parent_class,'图片上传失败');				
					  }else{
							 $files_info = $this->upload->data();
							 @unlink('../../'.$main['info_pic']);
							$data['main']['info_pic'] = str_replace(str_replace("\\","/",FCPATH),'',$files_info['full_path']);
					 }				 	
				 }		
		 		
		 				
		 		$db_config = $this->im->db_config(); 		 		
		 		$data_var = $this->init_db->save($data,$db_config);
		 				 		
		 		
			 		
		 		
		 		$this->init_page->pop_redirect('已保存',site_url('d=backend&c=archives&m=action_list&parent_class='.$parent_class));
		 	}else{
				$data['editor']  = $this->Common_model->editor($main['info_content']);
		 		$this->init_page->load_backend_view(strtolower($this->act).'_add',$data);
		 	}
	 		
	 	}catch(Exception $e){
	 		$this->init_page->backend_redirect($this->act.'/action_add?parent_class='.$parent_class,$e->getMessage());
	 	}			
	 }
	
	
	
	//新闻列表
	
	function action_list(){
		$parent_class = $this->input->get_post('parent_class');
		$search_class = $this->input->get_post('search_class');
		$info_title = $this->input->get_post('info_title');
		$parent_class_info = $this->Category_model->detail($parent_class,'bysn');
		$class_select  = $this->Category_model->fetch_category_option($parent_class,$search_class);
		$class_info = array(
				'class_theme'=>$parent_class_info['c_title'],
				'parent_class'=>$parent_class_info['c_sn'],
				'class_select'=>$class_select,
		);		
					
		$this->init_page->fetch_css(array('backend_archives'));
		$this->db->select("a.*,b.c_title",false)->from('infos as a ')
		->join('category as b','a.info_class_sn=b.c_sn')
		->like('a.info_title',$info_title,'after')
		->like('a.info_class_sn',$parent_class,'after')
		->like('a.info_class_sn',$search_class,'after')
		->order_by("info_id","desc");
		$data = $this->init_db->fetch_all(2);	
		$data = array_merge($data,
		array(
			'class_info'=>$class_info,
		)
		);	
		$this->init_page->load_backend_view(strtolower($this->act)."_list",$data);
		
	}
	
	
	function action_del(){
		try{			
			$id = $this->input->get_post("info_id");
			$id = $id?$id:$this->input->get_post("info_id");
						
			$parent_class = $this->input->get_post('parent_class');
			$this->db->where_in('info_id',$id);
	 		$this->db->delete($this->init_db->table('infos'));			
			$this->init_page->pop_redirect('已删除',site_url('d=backend&c='.$this->act.'&m=action_list&parent_class='.$parent_class));
		}catch(Exception $e){			
			show_error($e->getMessage());
		}
	
	}
	
	
	
	
	
	
 	
 	
 
 	
	
}
?>