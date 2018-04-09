<?php /* Smarty version 2.6.26, created on 2018-03-29 15:05:17
         compiled from admin/nav/update.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_checkboxes', 'admin/nav/update.tpl', 20, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>在线商城后台管理</title>
<link rel="stylesheet" type="text/css" href="view/admin/style/basic.css" />
<link rel="stylesheet" type="text/css" href="view/admin/style/nav.css" />
<script type="text/javascript" src="view/admin/js/nav.js"></script>
</head>
<body>

<h2><a href="?a=nav">返回导航条列表</a>商品 -- 修改导航条</h2>

<form method="post" name="update" action="?a=nav&m=update&id=<?php echo $this->_tpl_vars['OneNav'][0]->id; ?>
">
	<input type="hidden" name="flag" id="flag" />
	<dl class="form">
		<dd>名　　称：<?php echo $this->_tpl_vars['OneNav'][0]->name; ?>
 </dd>
		<dd><span class="middle">简　　介：</span><textarea name="info"><?php echo $this->_tpl_vars['OneNav'][0]->info; ?>
</textarea> <span class="middle">( * 200位以内 )</span></dd>
		<?php if ($this->_tpl_vars['OneNav'][0]->sid != 0): ?>
		<dd>关联品牌：<?php echo smarty_function_html_checkboxes(array('options' => $this->_tpl_vars['AllBrand'],'selected' => $this->_tpl_vars['selectedBrand'],'name' => 'brand'), $this);?>
</dd>
		<?php endif; ?>
		<dd>价格区间：<?php echo smarty_function_html_checkboxes(array('options' => $this->_tpl_vars['AllPrice'],'selected' => $this->_tpl_vars['selectedPrice'],'name' => 'price'), $this);?>
</dd>
		<dd><input type="submit" name="send" onclick="return updateNav();" value="修改导航" class="submit" /></dd>
	</dl>
</form>

</body>
</html>