<?php
//评价实体类
class CommendModel extends Model {
	public function __construct() {
		parent::__construct();
		$this->_fields = array('id','goods_id','order_id','attr','flag','star','content','user','date','re_content','flag');
		$this->_tables = array(DB_FREFIX.'commend');
		//$this->_check = new BrandCheck();
		list(
				$this->_R['id'],
				$this->_R['goods_id'],
				$this->_R['goodsid'],
				$this->_R['order_id']
		) = $this->getRequest()->getParam(array(
				isset($_GET['id']) ? $_GET['id'] : null,
				isset($_GET['goods_id']) ? $_GET['goods_id'] : null,
				isset($_GET['goodsid']) ? $_GET['goodsid'] : null,
				isset($_GET['order_id']) ? $_GET['order_id'] : null
		));
	}
	
	
	public function findAll() {
		$this->_tables = array(DB_FREFIX.'commend a', DB_FREFIX.'goods b');
		$_obj = parent::select(array('a.id','a.goods_id','a.content','a.re_content','a.star','a.user','b.nav','b.name'),
												array('where'=>array('a.goods_id=b.id'),'limit'=>$this->_limit,'order'=>'a.date DESC'));
		
		return $_obj;
	}

	public function findDetailsCommend() {
		$_where = array("goods_id='{$this->_R['goodsid']}' AND flag=0");
		return parent::select(array('re_content','content','star','date','user','attr','flag'), 
												array('where'=>$_where, 'limit'=>$this->_limit,'order'=>'date DESC'));
	}
	
	public function total() {
		return parent::total();
	}

	public function findUpdateOne(){
		$_where = array("id='{$this->_R['id']}'");
		$_oneCommend = parent::select(array('star','content','re_content','id','user','attr','goods_id','flag'),
										array('where'=>$_where,'limit'=>'1'));
		return $_oneCommend;

	}
	
	public function findOne() {
		$_where = array("goods_id='{$this->_R['goods_id']}' AND order_id='{$this->_R['order_id']}'");
		//if (!$this->_check->oneCheck($this, $_where)) $this->_check->error();
		return parent::select(array('star','content','re_content'),
											array('where'=>$_where, 'limit'=>'1'));
	}
	
	public function findMyCommend() {
		$this->_tables = array(DB_FREFIX.'commend a', DB_FREFIX.'goods b');
		$_where = array("user='{$_COOKIE['user']}' AND b.id=a.goods_id");
		$_obj = parent::select(array('a.goods_id','a.content','a.re_content','a.attr','a.star','a.date','b.nav','b.name','b.thumbnail2'),
												array('where'=>$_where,'limit'=>$this->_limit,'order'=>'a.date DESC'));
		
		$this->_tables = array(DB_FREFIX.'commend');
		return $_obj;
	}

	public function add() {
		//$_where = array("name='{$this->_R['name']}'");
		//if (!$this->_check->addCheck($this, $_where)) $this->_check->error();
		$_addData = $this->getRequest()->filter($this->_fields);
		$_addData['user'] = $_COOKIE['user'];
		$_addData['date'] = Tool::getDate();
		return parent::add($_addData);
	}
	
	public function delete() {
		$_where = array("id='{$this->_R['id']}'");
		return parent::delete($_where);
	}
	
	public function update() {
		$_where = array("id='{$this->_R['id']}'");
		//if (!$this->_check->oneCheck($this, $_where)) $this->_check->error();
		//if (!$this->_check->updateCheck($this)) $this->_check->error();
		$_updateData = $this->getRequest()->filter($this->_fields);
		return parent::update($_where, $_updateData);
	}
	
	public function isName() {
		$_where = array("name='{$this->_R['name']}'");
		$this->_check->ajax($this, $_where);
	}
}
?>