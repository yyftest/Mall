<?php /* Smarty version 2.6.26, created on 2012-05-11 11:37:06
         compiled from admin/attr/add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_checkboxes', 'admin/attr/add.tpl', 25, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>在线商城后台管理</title>
<link rel="stylesheet" type="text/css" href="view/admin/style/basic.css" />
<link rel="stylesheet" type="text/css" href="view/admin/style/attr.css" />
<script type="text/javascript" src="view/admin/js/ajax.js"></script>
<script type="text/javascript" src="view/admin/js/attr.js"></script>
</head>
<body>

<h2><a href="?a=attr">返回自定义属性列表</a>商品 -- 添加自定义属性</h2>

<form method="post" name="add" action="?a=attr&m=add">
	<input type="hidden" name="flag" id="flag" />
	<dl class="form">
		<dd>属性名称：<input type="text" name="name"  id="name" onblur="checkName();" class="text" /> <span class="red">[必填]</span> ( * 2-4位之间 )</dd>
		<dd>选择方式：<input type="radio" name="way" value="0" checked="checked" /> 单选 <input type="radio" name="way" value="1" /> 多选</dd>
		<dd><span class="middle">属性项目：</span><textarea name="item"></textarea> <span class="middle">( * 每个项目用'|'隔开，例如：白色|黑色|红色 )</span></dd>
		<dd>请选择一下关联的类别：</dd>
		<?php $_from = $this->_tpl_vars['addNav']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>
		<dd><?php echo $this->_tpl_vars['value']->name; ?>
</dd>
		<?php if ($this->_tpl_vars['value']->child): ?>
		<dd><?php echo smarty_function_html_checkboxes(array('name' => 'nav','options' => $this->_tpl_vars['value']->child), $this);?>
</dd>
		<?php endif; ?>
		<?php endforeach; else: ?>
		<dd>没有任何类别，请先<a href="?a=nav&m=add">添加</a>类别</dd>
		<?php endif; unset($_from); ?>
		<dd><input type="submit" name="send" onclick="return addAttr();" value="新增自定义属性" class="submit" /></dd>
	</dl>
</form>

</body>
</html>