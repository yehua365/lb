<?php /* Smarty version Smarty-3.1.14, created on 2013-08-21 09:54:16
         compiled from "application\templates\front\blue\zh\sales.htm" */ ?>
<?php /*%%SmartyHeaderCode:2004452148e48d778f7-64260016%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'caa43419dfa44041e5be1d194228fbf8550d2b9f' => 
    array (
      0 => 'application\\templates\\front\\blue\\zh\\sales.htm',
      1 => 1377056455,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2004452148e48d778f7-64260016',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'dir_front' => 0,
    'ci_uri' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52148e48e32e22_39540387',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52148e48e32e22_39540387')) {function content_52148e48e32e22_39540387($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['dir_front']->value)."/top.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<table width="992" border="0" align="center" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="246" valign="top">
     	<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['dir_front']->value)."/left_sales.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	  
	    <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['dir_front']->value)."/left_contact.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	  
    <td valign="top"><?php echo func_tag_detail(array('t_id'=>24,'where'=>"f_id=".((string)(($tmp = @$_smarty_tpl->tpl_vars['ci_uri']->value[3])===null||$tmp==='' ? 6 : $tmp))),$_smarty_tpl);?>
</td>
  </tr>
</table>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['dir_front']->value)."/foot.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>