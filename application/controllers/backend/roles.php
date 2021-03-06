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
 class Roles extends CI_Controller{
 	
 	function __construct(){
 		parent::__construct();
 		//验证登陆
		$this->init_auth->execute_auth();
 		$this->init_page->fetch_css('rights','loadview',$this->init_page->getRes('css','backend'));
 		$this->load->model('Roles_model');
 		$this->im = $this->Roles_model;
 		$this->act = 'roles';
 		$this->lang->load('item_backend_roles',lang_get());
 		$this->m_lang = $this->lang->language;	
 		
 		$this->tpl->assign('lang_roles',$this->lang->language);
 				
		//只调用中文数据库	
 		$this->ds = $this->init_db->getDs();

 	}
 	
 	
 	/**
 	 * 添加
 	 */
 	 
 	function action_add(){ 
 		try{
 			
	 		$main_id = $this->input->get_post('role_id'); 	  		
	 		if($main_id) { 
		 		//验证权限
		 		$this->init_auth->execute_auth(array('35,28,29,118')) ;
	 			$main_info = $this->db->select('*',false)->from('roles')->where('role_id',$main_id)->get()->first_row('array');	
	 			$this->ds->_reset_select();
	 			$rights_have = unserialize($main_info['rights']);	
	 		}else{
	 			//验证权限
		 		$this->init_auth->execute_auth(array('35,28,29,117')) ;
	 			//添加
				$main_info = array(
					'group_id'=>3,				
				);
			} 		
			//递归函数
			function recursion_rights($detail,$rights_have=null,$parent_key = null){
				foreach($detail as $key=>$item):
					if($item['detail']){
						$param_key =  $parent_key?$parent_key.'[detail]'.'['.$key.']':'['.$key.']';
						$func_return = recursion_rights($item['detail'],$rights_have,$param_key);
						$detail_string .= "<li class='fir'><em>".$item['r_title']."</em></li>".'<li class="fir"><ul class="sec">'.$func_return['detail_string'].'</ul></li>';
					}else{
						$input_key =  $parent_key .'[detail]'.'['.$key.']';
						$var_str = 'isset($rights_have'.$input_key.')';
						 eval("\$val = $var_str;");
						$checked = $val ? "checked=checked":''; 
						$detail_string .= "<li class='fir'  ><input type='checkbox' ".$checked." id='rights_".$key."' name='admin".$input_key."' value='1'  ><label for='rights_".$key."' rel='".str_replace(array('[detail]','[',']'), array(',',''), $parent_key.','.$key)."'>".$item['r_title']."</label></li>";
					} 
				endforeach;
				
				return array('detail_string'=>$detail_string,'parent_key'=>$parent_key);
			}
			
			//递归所有权限成字符
			$all_rights  = $this->im->get_rights_item();
			$rights_options = recursion_rights($all_rights['admin'],$rights_have);
			$data = array(
		 		'main'=>$main_info,	 	
		 		'rights_options'=>$rights_options['detail_string'],	 
		 		'rights_have'=>$rights_have,	 	
	  		);   	  		
	 		$this->init_page->load_backend_view(strtolower($this->act).'_add',$data);
 		}catch(Exception $e){
 			$this->init_page->pop_redirect($e->getMessage(),"javascript:history.go(-1);");
 		}	
 	}
 	
 	
 
 	/**
 	 * 保存
 	 */
 	function action_save(){
 		try{ 			
	 		$main = $this->input->get_post('main'); 

	 		$main['rights'] = serialize($this->input->get_post('admin'));
	 		$db_config = array('main'=>array('primary_key'=>'role_id','table_name'=>$this->init_db->table('roles')));			
 			
 			$roles_return = $this->init_db->save(array('main'=>$main),$db_config);
 			
 			//添加日志	
	 		$lang = $this->m_lang;				 
	 		$log_desc = sprintf($lang['log_mod'],$this->input->get_post('role_name'));	
	 		$this->load->model('Logs_model');	
	 	
	 		$this->Logs_model->log_insert(array(
	 			'log_table'=>$db_config['main']['table_name'],
	 			'log_table_id'=>$roles_return['main'][$db_config['main']['primary_key']],
	 			'log_user'=>$this->init_auth->fetch_auth('user_name'),
	 			'log_date'=>date("Y-m-d H:i:s"),
	 			'log_sql'=>trim(implode("\n",(array)$this->ds->sql_log)),
	 			'log_type'=>'9',
	 			'log_desc'=>$log_desc,
	 		));
		 		
		 				
 			$this->init_page->pop_redirect('保存成功',site_url("d=backend&c=".$this->act."&m=action_list"));	
 		}catch(Exception $e){
 			show_error($e->getMessage());
 		}
 		
 	
 	}
 	
 	
 	
 	/**
 	 * 列表
 	 */
 	function action_list(){ 
 				
 		$data = array('list'=>$this->im->fetch_roles_list());
 		
 		$data['current_role_id'] = $this->init_db->fetchColumn('select group_id from '.$this->init_db->table('admins').' where admin_id=?',array(get_cookie('user_id')));
 		$this->init_page->load_backend_view(strtolower($this->act).'_list',$data);
 		
 	}
 	
 	
 	/**
 	 * 删除 
 	 */
 	function action_del(){
 		try{
 			//验证权限
		 	$this->init_auth->execute_auth(array('35,28,29,119')) ;
 			$rs = $this->init_db->delete($this->input->get_post('role_id'),$this->im->db_config());
 			//-------------添加日志
 			$cf = $this->im->db_config();
 			$this->load->model('Logs_model');
 			$this->Logs_model->log_insert(array(
	 			'log_table'=>$cf['main']['table_name'],
	 			'log_table_id'=>$rs['main'][$cf['main']['primary_key']],
	 			'log_user'=>$this->init_auth->fetch_auth('user_name'),
	 			'log_date'=>date("Y-m-d H:i:s"),
	 			'log_sql'=>trim(implode("\n",(array)$this->ds->sql_log)),
	 			'log_type'=>'9',
	 			'log_desc'=>'删除角色',
	 		));

 			$this->init_page->pop_redirect('删除成功',site_url("d=backend&c=".$this->act."&m=action_list"));	

 			

 			
 		}catch(Exception $e){
 			show_error($e->getMessage());
 		}
 		
 	}
 	
 	
 	
 	
 }
?>
