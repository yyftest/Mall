<?php
//收货人实体类
class AddressModel extends Model {
	public function __construct() {
		parent::__construct();
		$this->_fields = array('id','user','name','email','tel','code','buildings','address','selected','flag');
		$this->_tables = array(DB_FREFIX.'address');
		//$this->_check = new ManageCheck();
		list(
				$this->_R['id']
		) = $this->getRequest()->getParam(array(
				isset($_GET['id']) ? $_GET['id'] : null
		));
	}
	
	public function findAll() {
		return parent::select(array('id','user','email','name','tel','code','buildings','address','selected','flag'), 
								array('where'=>array("user='{$_COOKIE['user']}'")));
	}
	
	public function findOne() {
		$_where = array("user='{$_COOKIE['user']}' AND selected = 1");
		//if (!$this->_check->oneCheck($this, $_where)) $this->_check->error();
		return parent::select(array('id','user','name','email','tel','code','buildings','address','flag'),
											array('where'=>$_where, 'limit'=>'1'));
	}
	
	public function total() {
		return parent::total();
	}
	
	public function add() {
		//$_where = array("user='{$this->_R['user']}'");
		//if (!$this->_check->addCheck($this, $_where)) $this->_check->error();
		$_addData = $this->getRequest()->filter($this->_fields);
		$_addData['user'] = $_COOKIE['user'];
		return parent::add($_addData);
	}

	public function selected(){
		$_where = array("user='{$_COOKIE['user']}'");
		$_updateData['selected'] = 0;
		parent::update($_where,$_updateData);
		$_where = array("id='{$this->_R['id']}'");
		$_updateData['selected'] = 1;
		return parent::update($_where,$_updateData);
	}
}
?>