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
 class Admins extends CI_Controller{

  	function __construct(){
 		parent::__construct();
 		//验证登陆
		$this->init_auth->execute_auth();
 		$this->load->model('Admins_model');
 		$this->im = $this->Admins_model;
 		//角色模块
 		$this->load->model('Roles_model');
 		$this->act = 'admins';
 		$this->lang->load('item_backend_admins',lang_get());
 		$this->m_lang = $this->lang->language;	
 		$this->tpl->assign('lang_admins',$this->m_lang);
		//只调用中文数据库	
 		$this->ds = $this->init_db->getDs();
 			
		
 	}
 	
 	
	/**
	 * [action_add description]
	 * @return [type] [description]
	 */
 	function action_add(){ 	
 		$main_id = $this->input->get_post('admin_id'); 	  		
 		if($main_id) { 		
	 		//验证权限
	 		$this->init_auth->execute_auth(array('35,28,32,121')) ;	
 			$main_info = $this->ds->select('*',false)->from('admins')->where('admin_id',$main_id)->get()->first_row('array');
 			
 		}else{
	 		//验证权限
	 		$this->init_auth->execute_auth(array('35,28,32,120')) ;	
 			//添加
			$main_info = array(
				'group_id'=>3,				
			);
		} 		
		
		$user_group  = $this->init_db->fetchColumn('select group_id from '.$this->init_db->table('admins').' where admin_id=?',array(get_cookie('user_id')));
		
 		$groups = $this->Roles_model->fetch_roles_list();
		foreach($groups as $k=>$v):
			if($k>=$user_group){
				$group_options[$k] = $v['role_name'];
			}
		endforeach;	
		
		$data = array(
	 		'main'=>$main_info,	 	
	 		'group_options'=>$group_options,	 	
  		);	  				
 		$this->init_page->load_backend_view(strtolower($this->act).'_add',$data);
 	}
 	
 	
 
 	/**
 	 * 保存
 	 */
 	function action_save(){
 		try{ 	
	 		$this->form_validation->set_rules($this->im->validator_user());
	 		$main = $this->input->get_post('main');
	 		$post  = $this->input->get_post('main');
	 		if($this->form_validation->run()==true){
	 			//保存用户修改信息	 
	 			if(empty($main['admin_pass'])){
	 				unset($main['admin_pass']);
	 			}else{
	 				$main['admin_pass'] = $this->init_page->my_encrypt($main['admin_pass'],'ENCODE');	 
					
	 			}	
	 			$db_config = array('main'=>array('primary_key'=>'admin_id','table_name'=>$this->init_db->table($this->act.'')));
	 			$rs = $this->init_db->save(array('main'=>$main),$db_config);		
	 			$main['admin_id'] = $rs['main']['admin_id'];	 
	 		
		 			
		 			//添加日志	
		 			 
		 		$log_desc = sprintf('修改用户%s',$rs[main]['admin_user']);	
		 		$this->load->model('Logs_model');	
		 	
		 		$this->Logs_model->log_insert(array(
		 			'log_table'=>$db_config['main']['table_name'],
		 			'log_table_id'=>$rs['main'][$db_config['main']['primary_key']],
		 			'log_user'=>$this->init_auth->fetch_auth('user_name'),
		 			'log_date'=>date("Y-m-d H:i:s"),
		 			'log_sql'=>trim(implode("\n",(array)$this->ds->sql_log)),
		 			'log_type'=>'8',
		 			'log_desc'=>$log_desc,
		 		));
			 		
	 			$this->init_page->pop_redirect('保存成功',site_url("d=backend&c=".$this->act."&m=action_list"));	
	 		}else{
	 			$data = array(
	 				'main'=>$main,
		 	 	
	 	 		); 	 			
	 			$this->init_page->load_backend_view(strtolower($this->act).'_add',$data);
	 		} 		
 		
 		}catch(Exception $e){
 			show_error($e->getMessage());
 		}
 		
 	
 	}
 	
 	/**
 	 * 验证用户名
 	 */
 	function admin_user_check($str){ 	
 		if(empty($str)){
 			$this->form_validation->set_message('admin_user_check',' 对不起，%s 必须输入');
 			return false; 			
 		}
		$db_admin = $this->ds->select('admin_user,admin_id',false)->from('admins')->where('admin_user',$str)->get()->first_row('array');
		$form_admin = $this->input->get_post('main');
		if(($form_admin['admin_id']!=$db_admin['admin_id'])&&$db_admin){
			$this->form_validation->set_message('admin_user_check',' 对不起，%s 已存在');
			return false;
			
		} 
		return true; 		
 	}
 	
 	
 	
 	
 	/*检查密码是否一致 
 	 */
 	function confirm_password_check($str){
 		$main  = $this->input->get_post('main'); 	
 		if($main['admin_pass']!=''&&($main['admin_pass']!=$this->input->get_post('confirm_password'))){
 			$this->form_validation->set_message("confirm_password_check","两次输入的密码不一致！");
 			return false;
 		}else{
 			return true;
 		}
 	}
 	
 	
 	/**
 	 * 列表
 	 */
 	function action_list(){ 
 		

 		$groups = $this->Roles_model->fetch_roles_list();
 			
 		$this->ds->like('admin_user',$this->input->get_post('admin_user'));
 		$this->ds->like('name',$this->input->get_post('name'));
 		$this->ds->like('mobile',$this->input->get_post('mobile'));
	 	$this->ds->select("*",false)->from('admins',false)
	 	->order_by('admin_id','desc');	 	
	 	$data = array_merge($this->init_db->fetch_all(),array('group_options'=>$groups)); 
	 	
	 	$data['current_admin_id'] = get_cookie('user_id');
	 	
 		$data['current_role_id'] = $this->init_db->fetchColumn('select group_id from '.$this->init_db->table('admins').' where admin_id=?',array(get_cookie('user_id')));
 		$data['login_user_id'] = get_cookie('user_id');
 		$this->init_page->load_backend_view('admins_list',$data);
 		
 	}
 	
 	
 	/**
 	 * 删除 
 	 */
 	function action_delete(){
 		try{
 			$this->load->model('Logs_model');	
 			//验证权限
	 		$this->init_auth->execute_auth(array('35,28,32,122')) ;	

 			$rs = $this->init_db->delete($this->input->get_post('admin_id'),$this->im->db_config());

 			//添加日志	
	 		$cf  = $this->im->db_config();	 
	 		$log_desc = sprintf('删除用户%s',$rs['admin_user']);	
	 		
	 		
	 		$this->Logs_model->log_insert(array(
	 			'log_table'=>$cf['main']['table_name'],
	 			'log_table_id'=>$rs[$cf['main']['primary_key']],
	 			'log_user'=>$this->init_auth->fetch_auth('user_name'),
	 			'log_date'=>date("Y-m-d H:i:s"),
	 			'log_sql'=>trim(implode("\n",(array)$this->ds->sql_log)),
	 			'log_type'=>'8',
	 			'log_desc'=>$log_desc,
		 		));
	 		//跳页
	 		
 			$this->init_page->backend_redirect('d=backend&c=admins&m=action_list','删除成功');	


 			
 		}catch(Exception $e){
 			
 			$this->init_page->backend_redirect('admins/action_list',$e->getMessage());
 		}
 		
 	}
 	
 	
 	/**
 	 * 查看
 	 */
 	function action_view(){
 		$data = array('main'=>$this->ds->select('*')->from('admins')->where('admin_id',$this->input->get_post('admin_id'))->get()->first_row('array'));
 		$this->init_page->load_backend_view(strtolower($this->act).'_view',$data);
 	}
 	
 }
?>
