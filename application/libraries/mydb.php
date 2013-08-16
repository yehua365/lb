<?php
if (!defined('BASEPATH')) show_error('No direct script access allowed'); 
/**
  * start page for webaccess
  *
  * PHP version 5
  *
  * @category  PHP
  * @package   Library
  * @author    yehua <150672834@.com>
  * @copyright 2013 conqweal
  * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License
  * @version   1.0$
  * @link      http://phpsysinfo.sourceforge.net
  */
 class Mydb {
 	private static $instance;
 	private $ds;
 	
 	function __construct($db_conn=null){
 		
 		//new instance
 		$CI = & get_instance();	
 		
 		$this->uri = $CI->uri;	 		
 		$this->myform  = &get_myform();	 
 		$this->mypage  = &get_mypage();	 		
 		$this->config  = $CI->config;
 		$this->pagination  = $CI->pagination;	 		
 		self::$instance = $this;
 		
 				
 		
 	}
 	 
 	
 	 	 	 
 	public static function &get_mydb(){ 	
 		return self::$instance;
 	}
 		
 	 public function setDs($ds){
 	 	$this->ds = $ds;
 	 }	

 	 public function getDs(){
 	 	return $this->ds;
 	 }
 	 	
 	public function save($save_info,$save_config){
 		if(empty($save_config)) throw new Exception('系统异常，没有配置保存参数!');
		$this->ds->query("set sql_mode=''");
		$info  = $this->main_save($save_info,$save_config); //保存主表信息		
		
		isset($save_config['detail']) && $info['detail'] = $this->detail_save($info,$save_config); //保存明细表信息
		return $info; 		
 	}
 	
 	/**
 	 * 保存主表信息
 	 */
 	function main_save($info,$save_config){ 
 		//deal with checkbox
 		foreach($info['main'] as $k=>$v){
 			if(is_array($v)){
 				$v = implode(",",$v);
 			}
 			$info['main'][$k] = $v;
 		}
 		$CI = &get_instance();
 		try{
 			extract($save_config['main']);	 		
		 	if(empty($info['main'][$primary_key])){ 
		 		unset($info['main'][$primary_key]);
		 		$this->ds->insert($table_name,$info['main']);
		 		$info['main'][$primary_key] = $this->ds->insert_id();
		 		$info['sys_db_type'] = 'inesrt';
		 		
		 	}else{
		 		$flag = $this->ds->get_where($table_name,array($primary_key=>$info['main'][$primary_key]))->num_rows();
		 		if($flag){
		 			$this->ds->where($primary_key,$info['main'][$primary_key]);
			 		$this->ds->update($table_name,$info['main']);
			 		$info['sys_db_type'] = 'update';
		 		}else{
		 			//unset($info['main'][$primary_key]);
		 			$this->ds->insert($table_name,$info['main']);
		 			$info['main'][$primary_key] = $this->ds->insert_id();
			 		$info['sys_db_type'] = 'inesrt';
			 		
		 		}
		 		
		 	}
		 	$this->ds->close();	 
		 	return $info;
 		}catch(Exception $e){
 			throw new Exception($e->getMessage());
 		}
 	}
 	
 	/**
 	 * 保存明细表信息
 	 */
 	function detail_save($info,$save_config){
 		extract($save_config['detail']); 		
 		$detail = $info['detail']; 
 		if(empty($detail)) return false;	 
 		if($info['sys_db_type']=='update'){					
			$this->detail_left_delete($info,$save_config);
		}
 		foreach($detail as &$v){ 
 			if($v[$primary_key]){
 				$this->ds->where($primary_key,$v[$primary_key]);
 				$this->ds->update($table_name,$v);				
 				
 			}else{
 				//unset($v[$primary_key]);
 				$v[$save_config['main']['primary_key']] = $info['main'][$save_config['main']['primary_key']];
 				$this->ds->insert($table_name,$v);
 				$this->ds->close();
 			
 			}
 		} 
		
		return $detail;
 	} 	
 	
 	/**
 	 * 删除多余明细信息
 	 */
 	
 	function detail_left_delete($info,$save_config){ 
 		//表单提交过来的detail_id 
 		$key_form = $this->myform->array_re_index($info['detail'],$save_config['detail']['primary_key'],$save_config['detail']['primary_key']);
 		//数据库中的detail_id 	 		
 		$this->ds->select($save_config['detail']['primary_key']);
 		$this->ds->from($save_config['detail']['table_name']);
 		$this->ds->where($save_config['main']['primary_key'],$info['main'][$save_config['main']['primary_key']]);
 		$key_db_temp = $this->ds->get()->result_array();
 		$key_db = $this->myform->array_re_index($key_db_temp,$save_config['detail']['primary_key'],$save_config['detail']['primary_key']); 
 		$diff_key = array_diff($key_db,$key_form);	 
 		if(!empty($diff_key)){
 			$this->ds->where_in($save_config['detail']['primary_key'],$diff_key);
 			$this->ds->delete($save_config['detail']['table_name']);
 		}
 	}
 	

 
 	
 	/**
 	 * 删除信息
 	 */
 	function delete($id,$save_config){
 					 	 	
 		if(empty($save_config)) throw new Exception('系统异常，没有配置删除参数');	
 		$main = $this->main_delete($id,$save_config);
 		isset($save_config['detail']) && $this->detail_delete($id,$save_config);
 		return $main; 		
 	}
 	
 	
 	/**
 	 * 删除主表信息
 	 */
 	
 	function main_delete($id,$save_config){ 
 		$main_temp = $this->ds->from($save_config['main']['table_name'])->where_in($save_config['main']['primary_key'],$id)->get()->result_array();
 		$this->ds->where_in($save_config['main']['primary_key'],$id);
 		$this->ds->delete($save_config['main']['table_name']);
 		return $main_temp[0];
 		
 	}
 	
 	/**
 	 * 删除明细信息 
 	 */
 	function detail_delete($id,$save_config){
 			 				
 		$this->ds->where_in($save_config['main']['primary_key'],$id);
 		$this->ds->delete($save_config['detail']['table_name']); 		
 	}
 	
 	/**
 	 * 查询列表
 	 */
 	
 	function fetch_all($page_size=null,$ajax=false){
 		
 		//set
 		
		$this->set_lt($page_size);	

 		$this->set_list_sq();	

 		$this->set_list_ds();	
 			
 		$ds = $this->get_list_ds();	 

 		$this->set_page_link($this->get_lt('to'),$ds['count'],$ajax); 	
 			
 		$pager = $this->get_page_link($ajax);
 		
 		$r =  array(
 			'list'=>$ds['list'],
 			'count'=>$ds['count'],
 			'page_link'=>$pager,
 		);
 	

 		return $r;
 	}

 	function fetch_all2($page_size=null,$ajax=false){
 		$page_size = $page_size?$page_size:$this->config->item('per_page');
 		$limit_from = $_GET['per_page']; 		
 		$link_str = $this->mypage->array_to_url($_GET);		
 		$params = array( 		
 			'limit_to'=>$page_size,
 			'limit_from'=>$limit_from,
 		); 	 		
 		$config['base_url'] = 		current_url().$link_str;
 		if(!isset($params['limit_from'])) $params['limit_from'] = 0;	
 			$sql_count =  $this->ds->_compile_select();

			$this->ds->limit($params['limit_to'],$params['limit_from']);
			$sql = $this->ds->_compile_select();	
			$count = $this->ds->query("select count(1) as total from ($sql_count) as t ")->first_row('array');
			$data = array(
				'count' => $count['total'],
				'list' => $this->ds->query($sql)->result_array(),
		);		
 			
 		$config['per_page'] = $page_size; 			
 		$config['total_rows'] = $data['count'];  		
 		$this->pagination->initialize($config); 
 		if($ajax){
 			$data['page_link'] = $this->pagination->create_ajax_links();
 		}else{
 			$data['page_link'] = $this->pagination->create_links(); 
 		}
 		
 		$data['page_size'] = $page_size; 
 		$this->ds->_reset_select();
 		return $data; 	
 	}
 	

 	
 	/**
 	 * [set_lt description]
 	 * @param [type] $page_size [description]
 	 */
 	function  set_lt($page_size){
 		$from = $_GET['per_page'];
 		$this->_lt = array(
 			'from'=>$from?$from:0,
 			'to'=>$page_size?$page_size:$this->config->item('per_page')
 		);
 		
 	}


 	/**
 	 * [get_lt description]
 	 * @param  [type] $key [description]
 	 * @return [type]      [description]
 	 */
	function get_lt($key=null){
 		return $key?$this->_lt[$key]:$this->_lt;
 	}

 	/**
 	 * [set_list_sq description]
 	 */
 	function set_list_sq(){

 		//ct
 		$sq_t =  $this->ds->_compile_select();
 		//list
 		$_lt = $this->get_lt();
 	
 		
		$this->ds->limit($_lt['to'],$_lt['from']);
		$sq = $this->ds->_compile_select();	
		$this->ds->_reset_select();
		$this->_list_sq = array(
			'dt'=>"select count(1) as total from (".$sq_t.") as t",
			'ds'=>$sq,
		);

		

 	}

 	/**
 	 * [get_list_sq description]
 	 * @return [type] [description]
 	 */
 	function get_list_sq(){
 		return $this->_list_sq;
 	}
 	
 	
 	/**
 	 * [set_list_ds description]
 	 */
 	function set_list_ds($default=null){
 		
 		$sq = $this->get_list_sq();
 		$q_l = $this->ds->query($sq['ds']);
 		$q_dt = $this->ds->query($sq['dt']);

 		$ds =  array(
 			'count'=>current($q_dt->first_row()),
 			'list'=>$q_l->result_array(),
 		);
 		$q_l->free_result();
 		$q_dt->free_result();
 		$this->_list_ds = $ds;
	 		
 		
 	}

 	/**
 	 * [get_list_ds description]
 	 * @param  [type] $key [description]
 	 * @return [type]      [description]
 	 */
 	function get_list_ds($key = null){
 		return $key?$this->_list_ds[$key]:$this->_list_ds;
 	}


 	/**
 	 * [set_page_link description]
 	 * @param [type] $ajax [description]
 	 */
 	function set_page_link($size,$total,$ajax=null){
 		if($total<=0) return '';
 		$config = array(
 			'base_url'=>current_url().$this->mypage->array_to_url($_GET),
 			'per_page'=>$size,
 			'total_rows'=>$total,
 		);		
 		
 		$this->pagination->initialize($config); 
 		if($ajax){
 			
 			$this->_pager = $this->pagination->create_ajax_links();
 		}else{
 			
 			$this->_pager = $this->pagination->create_links(); 
 		}
 		

 	}

 	/**
 	 * [get_page_link description]
 	 * @return [type] [description]
 	 */
	function get_page_link(){
 		
 		return $this->_pager;
 	}
 	
 	

 	
 	/**
 	 * 查询列表
 	 */
 	function ms_fetch_all($page_size=null,$pri_key=null){	
 		$page_size = $page_size?$page_size:$this->config->item('per_page');
 		$limit_from = $_GET['per_page']; 
 		$limit_from = $limit_from?$limit_from:0;		
 		$link_str = $this->mypage->array_to_url($_GET);		
 		$params = array( 		
 			'limit_to'=>$page_size,
 			'limit_from'=>$limit_from,
 		); 	 		
 		$config['base_url'] = 		current_url().$link_str;
 		if(!isset($params['limit_from'])) $params['limit_from'] = 0;
		//统计				
		$sql_count =  $this->ds->_compile_select();
		$sql_count = preg_replace("/order by.*/i",'',$sql_count);			
		$this->ds->limit(0,2000);
		//直接查询语句
		$sql_order  = implode(',',$this->ds->ar_orderby);
		$sql = $this->ds->_compile_select();	
		$count = $this->ds->count_all("($sql_count) as t ");		//统计		
		$page_top = $limit_from?$limit_from+$page_size:$page_size;		
		$top_last = ($count-$limit_from)<$page_size? $count-$limit_from:$page_size;	//判断单次应提取记录	
		$sql = "select top {$top_last} *  from (select top {$page_top} *    from ($sql) as  t order by $pri_key asc ) t_list order by  {$pri_key} desc  ";
		$data = array(
			'count' => $count,
			'list' => $this->ds->query($sql)->result_array(),
		);		
 			
 		$config['per_page'] = $page_size; 			
 		$config['total_rows'] = $data['count'];  		
 		$this->pagination->initialize($config); 
 		$data['page_link'] = $this->pagination->create_links(); 
 		$data['page_size'] = $page_size; 
 		$this->ds->_reset_select();
 		return $data; 		
 		
 	}
 	
 	
 	
 	
 
 	/**
 	 * 提取单记录信息s
 	 */
 	
 	function fetch_one($array=null){
 		if(empty($array)) return false; 		
 		extract($array);
 		if(empty($primary_val)) return null;
 		if(empty($fields)) $fields = '*';
 		$CI = &get_instance();
 		
 		$rq =  $this->ds->select($fields,false)->from($table_name)->where($primary_id,$primary_val)->order_by($primary_id,'desc')->limit(1)->get();
 		$rs = $rq->first_row('array');
 		
 	
 		return $rs;
 	}
 	
 	
 	/*
 	 *  提取单记录信息，返回单个值
 	 */
 	
 	function fetch_value($sql,$field=null){ 		
 		$rs = $this->ds->query($sql)->first_row('array');
 		
 		return $field ? $rs[$field]:$rs;
 	}
 	
 	
 	
 	/*
 	 *  提取单记录信息，返回单个值
 	 */
 	
 	function fetch_values($sql){ 		
 		return  $this->ds->query($sql)->result_array();
 		
 	}
 	/*
 	 * 取表格名称
 	 */
 	function table($tb){
 		return  $this->ds->dbprefix.$tb;
 	}
 	
 	/**
 	 * 调整sql语句，确保可批量执行
 	 */
 	function adjust_sql_str($str){ 
 		if(empty($str)) return '';		
		$str=str_replace("\r","",$str);
		$str=str_replace("\n","",$str);
		$str=str_replace("`","",$str);
		$sql_array_temp=explode(";",trim($str));
		$pre = '';				
		foreach($sql_array_temp as $key => $values)
		{
			$sql_execute .= ($values[0]=='#' || $values[0].$values[1] == '--')  ? '' : $pre.$values;
			$pre = ";\n";
			
		}
 	
 		return  $sql_execute;
 	}


 	/**
 	 * [primary fetch primary key]
 	 * @param  [type] $tb [description]
 	 * @return [type]     [description]
 	 */
	function primary($tb){
		$rs = $this->ds->field_data($tb);
		foreach($rs as $v){
			if($v->primary_key==1){
				$primary = $v->name;
				break;
			}
		}
		return $primary;
	}
 	
 
 } 
 
 function &get_mydb(){	
	return Mydb::get_mydb();
} 
	


 
 
?>