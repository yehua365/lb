<?php /* Smarty version Smarty-3.1.14, created on 2013-08-15 07:11:31
         compiled from "application\templates\front\blue\zh\left_contact.htm" */ ?>
<?php /*%%SmartyHeaderCode:31976520c7f233e7115-34251305%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fbc35455be3c9d5da5835cd6914badd68871386e' => 
    array (
      0 => 'application\\templates\\front\\blue\\zh\\left_contact.htm',
      1 => 1294992928,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '31976520c7f233e7115-34251305',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'img_url' => 0,
    'contact' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520c7f23553773_35999680',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520c7f23553773_35999680')) {function content_520c7f23553773_35999680($_smarty_tpl) {?><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table width="233" border="0" cellspacing="0" cellpadding="0" style="margin-top:13px;">
        <tr>
          <td width="3"><img src="<?php echo $_smarty_tpl->tpl_vars['img_url']->value;?>
/daohanhh_09.jpg" width="3" height="28" /></td>
          <td width="212" style="background:url(<?php echo $_smarty_tpl->tpl_vars['img_url']->value;?>
/daohanhh_11.jpg) repeat-x; padding-left:15px;" class="hong">联系我们</td>
          <td width="3"><img src="<?php echo $_smarty_tpl->tpl_vars['img_url']->value;?>
/daohanhh_13.jpg" width="3" height="28" /></td>
        </tr>
        <tr>
          <td colspan="3" class="bian1" style="line-height:16px; padding:10px;"><p>地 址：<?php echo $_smarty_tpl->tpl_vars['contact']->value['addr'];?>
</p>
            <p>电 话：<?php echo $_smarty_tpl->tpl_vars['contact']->value['tel'];?>
 </p>
          <p>传 真：<?php echo $_smarty_tpl->tpl_vars['contact']->value['fax'];?>
</p></td>
        </tr>
      </table><?php }} ?>