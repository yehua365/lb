<?php /* Smarty version Smarty-3.1.14, created on 2013-09-06 15:40:10
         compiled from "application\templates\front\corcms\zh\product.htm" */ ?>
<?php /*%%SmartyHeaderCode:29495522986da4d59a8-90182341%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '001e4619afaa8bbbd78f9aeed6760e234b077bd1' => 
    array (
      0 => 'application\\templates\\front\\corcms\\zh\\product.htm',
      1 => 1378342897,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '29495522986da4d59a8-90182341',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'dir_front' => 0,
    'img_url' => 0,
    'ci_uri' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_522986da5fe0e1_42446784',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_522986da5fe0e1_42446784')) {function content_522986da5fe0e1_42446784($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['dir_front']->value)."/top.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<table width="992" border="0" align="center" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="246" valign="top">
    <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['dir_front']->value)."/left_product.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
    </td>
    
    <td valign="top" aign="right">&nbsp;</td>
    <td valign="top" aign="right">
    
    <table     border="0" cellspacing="0" cellpadding="0" >
      <tr>
       
        <td  style="background:url(<?php echo $_smarty_tpl->tpl_vars['img_url']->value;?>
/daohanhh_11.jpg) repeat-x; padding-left:20px; height:25px" class="hong" width="746">
      <?php echo func_tag(array('t_id'=>49,'where'=>("id=").(((($tmp = @end(explode(",",func_my_encrypt($_smarty_tpl->tpl_vars['ci_uri']->value[3],'DECODE'))))===null||$tmp==='' ? 2 : $tmp))),'html_type'=>'detail'),$_smarty_tpl);?>


        </td>
       
      </tr>
      <tr>
        <td  class="bian1" style="padding-left:25px;"><table height="400" border="0" cellspacing="0" cellpadding="0" >
          <tr>
           <td  valign="top"  align="center"  class="product">
            <ul>
	         	 <?php echo func_tag(array('t_id'=>40,'where'=>"p_state>0  and p_pid like '".((string)func_my_encrypt($_smarty_tpl->tpl_vars['ci_uri']->value[3],'DECODE'))."%' and p_name like '%".((string)$_REQUEST['p_name'])."%'"),$_smarty_tpl);?>

            </ul>
			  </td>
			  
             </tr>
          
        </table>
		<div class="page" align="center">

 <?php echo func_tag_pager(array('t_id'=>40,'where'=>"p_state>0 and p_pid like '".((string)func_my_encrypt($_smarty_tpl->tpl_vars['ci_uri']->value[3],'DECODE'))."%' and p_name like '%".((string)$_REQUEST['p_name'])."%'"),$_smarty_tpl);?>


    </div>		</td>
      </tr>
    </table></td>
  </tr>
</table>



 
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['dir_front']->value)."/foot.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>