<?php /* Smarty version Smarty-3.1.14, created on 2013-09-02 15:28:41
         compiled from "application\templates\backend\x6cms\main_center.htm" */ ?>
<?php /*%%SmartyHeaderCode:9930522490cd4f80e7-80078681%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e996c711156545676d99e0dd8746a6085766644c' => 
    array (
      0 => 'application\\templates\\backend\\x6cms\\main_center.htm',
      1 => 1378135719,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9930522490cd4f80e7-80078681',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_522490cd5bcfd0_30848837',
  'variables' => 
  array (
    'img_url' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_522490cd5bcfd0_30848837')) {function content_522490cd5bcfd0_30848837($_smarty_tpl) {?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>X6CMS2.2(20130305) - 小六网络科技</title>
<style>
body {margin: 0;padding: 0;background:#E2E9EA ;cursor: E-resize;}
</style>
<script type="text/javascript" language="JavaScript">
<!--
function toggleMenu()
{
  frmBody = parent.document.getElementById('frame-body');
  imgArrow = document.getElementById('img');

  if (frmBody.cols == "0, 12, *,12")
  {
    frmBody.cols="150, 12, *,12";
    imgArrow.src = "http://localhost/x6cms/images/admin_barclose.gif";
  }
  else
  {
    frmBody.cols="0, 12, *,12";
    imgArrow.src = "http://localhost/x6cms/images/admin_baropen.gif";
  }
}

var orgX = 0;
document.onmousedown = function(e)
{
  var evt = Utils.fixEvent(e);
  orgX = evt.clientX;

  if (Browser.isIE) document.getElementById('tbl').setCapture();
}

document.onmouseup = function(e)
{
  var evt = Utils.fixEvent(e);

  frmBody = parent.document.getElementById('frame-body');
  frmWidth = frmBody.cols.substr(0, frmBody.cols.indexOf(','));
  frmWidth = (parseInt(frmWidth) + (evt.clientX - orgX));

  frmBody.cols = frmWidth + ", 12, *,12";

  if (Browser.isIE) document.releaseCapture();
}

var Browser = new Object();
Browser.isMozilla = (typeof document.implementation != 'undefined') && (typeof document.implementation.createDocument != 'undefined') && (typeof HTMLDocument != 'undefined');
Browser.isIE = window.ActiveXObject ? true : false;
Browser.isFirefox = (navigator.userAgent.toLowerCase().indexOf("firefox") != - 1);
Browser.isSafari = (navigator.userAgent.toLowerCase().indexOf("safari") != - 1);
Browser.isOpera = (navigator.userAgent.toLowerCase().indexOf("opera") != - 1);

var Utils = new Object();

Utils.fixEvent = function(e)
{
  var evt = (typeof e == "undefined") ? window.event : e;
  return evt;
}
//-->
</script>
</head>
<body onselect="return false;">
<table height="100%" cellspacing="0" cellpadding="0" id="tbl">
  <tr><td style="padding-left:1px;"><a href="javascript:toggleMenu();"><img src="<?php echo $_smarty_tpl->tpl_vars['img_url']->value;?>
admin_barclose.gif" width="11" height="60" id="img" border="0"  /></a></td></tr>
</table>
</body></html><?php }} ?>