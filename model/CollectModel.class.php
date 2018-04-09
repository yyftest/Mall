<?php 
class CollectModel extends Model{
	public function __construct(){
		parent::__construct();
		$this->_fields = array('id','goods_id','user','date');
		$this->_tables = array(DB_FREFIX.'collect');
		$this->_check = new CollectCheck();
		list(
			$this->_R['id']
		) = $this->getRequest()->getParam(array(
			isset($_GET['id'])?$_GET['id']:null
			));
	}

	public function add(){
		$_where = array("goods_id='{$this->_R['id']}'");
		if(!$this->_check->addCheck($this,$_where)) $this->_check->error();
		$_addData['goods_id'] = $this->_R['id'];
		$_addData['user'] = $_COOKIE['user'];
		$_addData['date'] = Tool::getdate();

		return parent::add($_addData);
	}

	public function delCollect(){
		$_where = array("goods_id='{$this->_R['id']}'");
		return parent::delete($_where);
	}


}
	
 ?>