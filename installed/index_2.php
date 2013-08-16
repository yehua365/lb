<?php
//使用mysql导入
@set_time_limit(300);
header("Content-Type:text/html;charset=UTF-8");
//当前目录
$dl = DIRECTORY_SEPARATOR;
$dir  = dirname(__file__).$dl ;
//根目录
$base_dir = dirname($dir).$dl;
//数据库配置文件
$config_dir = $base_dir.$dl.'application'.$dl.'config'.$dl;
include_once($dir.'libs'.$dl.'phpMyImporter.php');
/*--------------------------------------------------
  import data 
  Email: conqweal@163.com
  Author: conqweal		
  --------------------------------------------------*/
extract($_POST);

define('CFG_DB_HOST',$db_host);				
define('CFG_DB_USER',$db_user);        			
define('CFG_DB_PASSWORD',$db_password);  			
define('CFG_DB_ADAPTER','mysql');    			
define('CFG_DB_NAME',$db_name);	
define('CFG_DB_PREFIX',$db_prefix);	
define('DB_FILE',$base_dir.'database'.$dl.$db_file);
try{
	if(!file_exists(DB_FILE)){
		throw new Exception('db file is not exists.');
	}
	if(!CFG_DB_NAME){
		throw new Exception('Please specify the database');
	}
	//update database config file
	update_config('hostname',CFG_DB_HOST,'string');
	update_config('username',CFG_DB_USER,'string');
	update_config('password',CFG_DB_PASSWORD,'string');
	update_config('database',CFG_DB_NAME,'string');
	update_config('dbprefix',CFG_DB_PREFIX,'string');


	update_config('base_url',$base_url,'string','config.php');
	$connect = @mysql_connect(CFG_DB_HOST,CFG_DB_USER,CFG_DB_PASSWORD) or tri_err('connect error.');
	$sql1 = "drop database if exists ".CFG_DB_NAME.";";
	@mysql_query($sql1) or tri_err('drop error.');	
	$sql2 = "CREATE DATABASE `".CFG_DB_NAME."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	@mysql_query($sql2) or tri_err('create db error.');	
	mysql_select_db(CFG_DB_NAME);
	mysql_query("set names 'utf8' ");			
	$query = mysql_query('show tables from '.CFG_DB_NAME,$connect); 
	while(($row = mysql_fetch_array($query)) && substr($row[0],0,strlen(CFG_DB_PREFIX)+2) != CFG_DB_PREFIX.'v_') {	
					
		@mysql_query("TRUNCATE TABLE `".$row[0]."`;") or tri_err('truncate error.');	
	}
	//import files
	$dump = new phpMyImporter(CFG_DB_NAME,$connect,DB_FILE,false);
	$dump->utf8 = true; // Uses UTF8 connection with MySQL server, default: true
	$dump->setDbPrefix(CFG_DB_PREFIX);
	$dump->doImport();
	if(is_dir($base_dir.$dl."install"))  rename($base_dir.$dl."install",$base_dir.$dl."installed");
	//安装完成，跳转
	copy($base_dir.'index.ci', $base_dir.'index.php'); 
	echo "<script>location.href='".$base_url."'</script>";
}catch (Exception $e){
    echo($e->getMessage()),'&nbsp;&nbsp;<a href="javascript:history.back(1);">&laquo;&nbsp;Back</a>';
}


function update_config($ini, $value,$type="string",$file='database.php'){ 
	global $config_dir;
	if(!file_exists($config_dir.$file)) return false; 
	$dlr = file_get_contents($config_dir.$file); 
	$dlr2=""; 
	if($type=="int"){ 
		$dlr2 = preg_replace("/".preg_quote($ini)."']\s*=.*;/", $ini."'] = ".$value.";",$dlr); 
	} 
	else{ 
		$dlr2 = preg_replace("/".preg_quote($ini)."']\s*=.*;/",$ini."'] = \"".$value."\";",$dlr); 
	} 
	file_put_contents($config_dir.$file, $dlr2); 
} 

function log_error($txt){
	echo $txt,'<hr>';
}


function tri_err($msg){
	throw new Exception($msg);
		
}
?>

