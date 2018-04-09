<?php 
class ServiceAction extends Action{

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		parent::page();
		$this->_tpl->assign('AllService',$this->_model->findAll());
		$this->_tpl->display(SMARTY_ADMIN.'service/show.tpl');
	}

	public function add(){
		if(isset($_POST['send'])) $this->_model->add()?$this->_redirect->succ('?a=service','售后服务添加成功！'):$this->_redirect->error('售后服务添加失败！');
		$this->_tpl->display(SMARTY_ADMIN.'service/add.tpl');
	}

	public function update(){
		if(isset($_POST['send'])) $this->_model->update()?$this->_redirect->succ('?a=service','售后服务修改成功！'):$this->_redirect->error();
		$this->_tpl->assign('OneService', $this->_model->findOne());
		$this->_tpl->display(SMARTY_ADMIN.'service/update.tpl');
	}

	public function delete(){
		if(isset($_GET['id'])) $this->_model->delete()?$this->_redirect->succ(Tool::getPrevPage(), '售后服务删除成功！') : $this->_redirect->error('售后服务删除失败！请取消默认首选');
	}

	public function first(){
		if(isset($_GET['id'])) $this->_model->first()?$this->_redirect->succ(Tool::getPrevPage()) : $this->_redirect->error('售后服务默认设置失败！');
	}

	public function isName(){
		$this->_model->isName();
	}

}
 ?>
