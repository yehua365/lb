<?php /* Smarty version Smarty-3.1.14, created on 2013-08-16 02:55:46
         compiled from "application\templates\backend\blue\fields_add.htm" */ ?>
<?php /*%%SmartyHeaderCode:31548520d94b2724da8-82797703%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '53453e037bbd4b6450f7e89c25f83c2ccb52dc88' => 
    array (
      0 => 'application\\templates\\backend\\blue\\fields_add.htm',
      1 => 1375433031,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '31548520d94b2724da8-82797703',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'dir_backend' => 0,
    'fields_types' => 0,
    'main' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520d94b2b3c831_03709742',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520d94b2b3c831_03709742')) {function content_520d94b2b3c831_03709742($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include 'E:\\phpnow\\htdocs\\lb\\application\\libraries\\Smarty-3.1.14\\libs\\plugins\\function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['dir_backend']->value)."/top.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<fieldset>
<legend><div class="nav_title">字段维护</div></legend>

<form  name="form1" id="form1" method="post" action="<?php echo func_site_url(array('segments'=>'backend/fields/action_save'),$_smarty_tpl);?>
">
<table>
	<tr>
		<td>字段类型：</td>
		<td>
			<select name="main[f_type]">
				<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['fields_types']->value,'selected'=>$_smarty_tpl->tpl_vars['main']->value['f_type']),$_smarty_tpl);?>

			</select>
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; echo smarty_php_tag(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

				echo form_error("main[f_type]","<span id='error_span'>","</span>");
			<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_php_tag(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

		</td>
		<td></td>
	</tr>

	<tr>
		<td>字段名称：</td>
		<td>
			<input size="80" type="text" name="main[f_name]" id="main[f_name]" value="<?php echo $_smarty_tpl->tpl_vars['main']->value['f_name'];?>
">
			<?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; echo smarty_php_tag(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

				echo form_error("main[f_name]","<span id='error_span'>","</span>");
			<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_php_tag(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

		</td>
		<td></td>
	</tr>
	<tr>
		<td>html调用代码：</td>
		<td>
			<textarea  name="main[f_html]" style="width:800px;height:400px"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['main']->value['f_html'], ENT_QUOTES, 'UTF-8', true);?>
</textarea>
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; echo smarty_php_tag(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

				echo form_error("main[f_html]","<span id='error_span'>","</span>");
		<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_php_tag(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

		</td>
		<td></td>
	</tr>
	
	
	
	<tr>
		<td colspan="3" align="center">
		<?php echo create_button(array('type'=>'save'),$_smarty_tpl);?>

		<input size="80" type="hidden" name="main[f_id]" value="<?php echo $_smarty_tpl->tpl_vars['main']->value['f_id'];?>
">
	</td></tr>

</table>
</form>	
</fieldset>

<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['dir_backend']->value)."/foot.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>