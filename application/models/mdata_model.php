<?php
/**
  * start page for webaccess
  *
  * PHP version 5
  *
  * @category  PHP
  * @package   Modle
  * @author    yehua <150672834@.com>
  * @copyright 2013 conqweal
  * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License
  * @version   1.0$
  * @link      http://phpsysinfo.sourceforge.net
  */
class Mdata_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	function  save_config(){
		$config = array('main'=>array(
				'table_name'=>$this->m->main($this->get_mid(),'m_tb'),
				'primary_key'=>$this->m->fetch_primary($this->get_mid(),'r_name')
			));


		$dt_mid = $this->m->main($this->get_mid(),'m_sub');
		if($dt_mid){
		
			$config =array_merge($config,array('detail'=>array(
					'table_name'=> $this->m->main($dt_mid,'m_tb'),
					'primary_key'=> $this->m->fetch_primary($dt_mid,'r_name'),

			)));

		}
		

		return $config;

	}

	function valid_config($info){
		$module_id = $this->get_mid();
		$config = array();
		$fields = $this->m->detail_exclude_primary($module_id);
		foreach($fields as $v):
				if(empty($v[r_valid])) continue;
				array_push($config,array(
					"field"=>"main[$v[r_name]]",	
 					"label"=>$v[r_alias],	
 					"rules"=>$v[r_valid],		
				)
		);
		endforeach;


		$dt_mid = $this->m->main($module_id,'m_sub');
		$dt_fields = $this->m->detail_exclude_primary($dt_mid);	
		if($dt_mid){
			foreach($info['detail'] as $k=>$v){
				foreach($dt_fields as $v):
						if(empty($v[r_valid])) continue;
						array_push($config,array(
							"field"=>"detail[$v[r_name]][$k]",	
		 					"label"=>$v[r_alias],	
		 					"rules"=>$v[r_valid],		
							)
						);
				endforeach;		
			}
		}

		
		return $config;
	}

	


	function get_mid(){

		return get_cookie('m_id');
	}

	function set_mid($id){
		$cookie = array(
               'name'   => 'm_id',
               'value'  => $id,
               'expire' => '86500',
               'domain' => '',
               'path'   => '/'
              
           );	
		set_cookie($cookie);
		
	}

	function fetch_list($size,$query=null,$ajax=false){
		
		//config table
		$tb = $this->m->main($this->get_mid(),'m_tb');
	    $query_types = $this->init_cache->cache_fetch('query_types');
		//config fields
		$fields_r = $this->m->details($this->get_mid());
		
		//fields html
		$fields_html = $this->m->fetch_f_html();
		//field ext
		$field_ext = $this->m->fetch_field_ext();
		foreach($fields_r as $k=>$v){
			if($this->m->is_primary($v['f_id'],$field_ext)) $primary = $v['r_name'];
		}

		foreach($fields_r as $k=>$v){
		
			if($v['r_queryable']){


				if(is_array($query[$v['r_name']])){
					foreach($query[$v['r_name']] as $v1):
						$this->db->where("FIND_IN_SET('$v1',$v[r_name])>0",NULL,'or');
					endforeach;	
				}else{
					$query[$v['r_name']]!=='' && $query[$v['r_name']] && $this->db->like($v['r_name'],$query[$v['r_name']],$query_types[$v[r_queryable]]);
				}

				$query_html = $v['r_query_define']?$v['r_query_define']:$fields_html[$v['f_id']];
				$querys[$v['r_name']] = array('name'=>$v['r_alias'],'html'=>$query_html);
			}
			//数据列表页面，隐藏未配置输出格式的字段
			if(empty($v['r_output'])) unset($fields_r[$k]);
		}

		$fields_out = array_re_index($fields_r,'r_name','r_output');
		$this->db->select(implode(',',array_keys($fields_out)),false)->from($tb);		
		if($query['mdata_sort']) $this->db->order_by($query['mdata_sort'],$query['mdata_sort_direction']);
		
		$this->db->order_by($primary,'desc');
		$ls = $this->init_db->fetch_all($size,$ajax);

		
		//按输出格式对数据处理后再输出
		foreach($ls['list'] as $k=>$v){

			foreach($v as $k1=>$v1){
				if($fields_out[$k1]){	
					$t = str_replace("%value",$v1,$fields_out[$k1]);
					$t = htmlspecialchars_decode($t);
					$t='?'.'>'.($t);
					ob_start();
					eval($t);
					$t = ob_get_contents();
					ob_end_clean();	
					$v[$k1] = $t;
				}
			}
			$ls['list'][$k] = $v;
		}
	
		return array_merge($ls,array('fields'=>$fields_r,'querys'=>$querys,'primary'=>$primary));
	}

	//detail
	function detail($id){
		$tb = $this->m->main($this->get_mid(),'m_tb');
		$primary = $this->m->fetch_primary($this->get_mid(),'r_name');
		return $this->db->select('*',false)->from($tb)->where($primary,$id)->get()->first_row('array');
	}



	//details for main table 
	function details($main_id){
		
		$sub_id = $this->m->main($this->get_mid(),'m_sub');
		if(!$sub_id) return false;
		$primary = $this->m->fetch_primary($this->get_mid(),'r_name');
		$detail_tb = $this->m->main($sub_id,'m_tb');
		$detail_primary = $this->m->fetch_primary($sub_id,'r_name');
		$dt = array();
		if($main_id){

			$dt =  $this->db->select('*',false)->from($detail_tb)->where($primary,$main_id);

			$dt = $this->db->order_by($detail_primary,'asc')->get()->result_array();
		}
		return array_pad($dt,3,array());
	}





	/**
	 * [select_list description]
	 * @return [type] [description]
	 */
	function select_list(){
		$list = $this->m->all();
		$m_sub = array_re_index($this->m->all(array("m_sub > "=>0)),'m_sub','m_sub');
		
		foreach($list as $k=>&$v){
			if($v[m_lock] || in_array($v['m_id'], $m_sub)){
				unset($list[$k]);
				continue;
			}
			$v['m_stat'] = $this->db->count_all_results($v['m_tb']);
		}
		return array('list'=>$list);
	}

	function fetch_seo_var($mid,$id){
		$primary = $this->m->fetch_primary($mid,'r_name');
		$tb = $this->m->main($mid,'m_tb');
		$fs = $this->m->fetch_seo($mid,'r_name');
		$r = $this->db->select('*',false)->from($tb)->where($primary,$id)->get()->first_row('array');
		return $r[$fs];
	}
}




?>