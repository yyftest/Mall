<?php
//订单实体类
class OrderModel extends Model {
	public function __construct() {
		parent::__construct();
		$this->_fields = array('id','user','name','email','tel','address','buildings','code','delivery','pay','price','ordernum','text','goods','ps','date','order_pay','order_state','order_delivery','delivery_name','delivery_url','delivery_number','refund');
		$this->_tables = array(DB_FREFIX.'order');
		//$this->_check = new ManageCheck();
		list(
				$this->_R['id'],
				$this->_R['out_trade_no'],
				$this->_R['goods_id'],
				$this->_R['order_id'],
				$this->_R['user']
		) = $this->getRequest()->getParam(array(
				isset($_GET['id']) ? $_GET['id'] : null,
				isset($_GET['out_trade_no']) ? $_GET['out_trade_no'] : null,
				isset($_GET['goods_id']) ? $_GET['goods_id'] : null,
				isset($_GET['order_id']) ? $_GET['order_id'] : null,
				isset($_POST['user']) ? $_POST['user'] : null,
		));
	}

	public function findAll(){
		return parent::select(array('id','ordernum','date','price','order_pay','order_state','order_delivery','refund'),
						array('limit'=>$this->_limit,'order'=>'date DESC'));
	}

	public function findUserAll(){
		return parent::select(array('id','ordernum','date','price','order_pay','order_state','order_delivery','refund'),
						array('where'=>array("user='{$_COOKIE['user']}'"),'limit'=>$this->_limit,'order'=>'date DESC'));
	}

	//修改支付状态
	public function updateOrderNum(){
		$_where = array("ordernum='{$this->_R['out_trade_no']}'");
		$_updateData = $this->getRequest()->filter($this->_fields);
		$_updateData['order_pay'] = '已付款';
		//执行成交表入库
		$this->record();
		return parent::update($_where,$_updateData);
	}

	public function findUserDetails(){
		$_orderDetails = parent::select(array('id','ordernum','date','user','name','email','code','tel','buildings','address','delivery','pay','price','goods','delivery','pay','price','text','ps','order_state','order_pay','order_delivery','delivery_name','delivery_url','delivery_number','refund'),
								array('where'=>array("id='{$this->_R['id']}'")));
		$_orderDetails[0]->goods = unserialize(htmlspecialchars_decode($_orderDetails[0]->goods));
		if(Validate::isArray($_orderDetails[0]->goods)){
			foreach($_orderDetails[0]->goods as $_key=>$_value){
			$_orderDetails[0]->goods[$_key] = unserialize($_value);
			}
		}
		return $_orderDetails;
	}

	public function findCommendOrder(){
		$_where = array("id='{$this->_R['order_id']}'");
		$_obj = parent::select(array('goods'),
								array('where'=>$_where,'limit'=>'1'));
		$_obj = unserialize(htmlspecialchars_decode($_obj[0]->goods));
		//print_r(unserialize($_obj[$this->_R['goods_id']]));
		return unserialize($_obj[$this->_R['goods_id']]);
	}
	
	public function order() {
		//$_where = array("user='{$this->_R['user']}'");
		//if (!$this->_check->addCheck($this, $_where)) $this->_check->error();
		$_orderData = $this->getRequest()->filter($this->_fields);
		$_orderData['date'] = Tool::getDate();
		$_orderData['ordernum'] = Tool::getOrderNum();
		foreach ($_COOKIE['cart'] as $_key => $_value) {
			$_COOKIE['cart'][$_key] = stripslashes($_value);
		}
		$_orderData['goods'] = serialize($_COOKIE['cart']);
		return parent::add($_orderData);
		//return true;
	}

	public function isCommendOrder() {
		$_where = array("id='{$this->_R['order_id']}'");
		$_obj = parent::select(array('order_delivery'),
											array('where'=>$_where, 'limit'=>'1'));
		if ($_obj[0]->order_delivery != '已发货') {
			$this->_check = new Check();
			$this->_check->setMessage('您的订单尚未发货，此商品无法进行评价！');
			$this->_check->error();
		}
	}

	public function refund() {
		$_where = array("id='{$this->_R['id']}' AND (order_pay='已付款' OR order_delivery='已配货' OR order_delivery='已发货')");
		$_updateData['refund'] = 1;
		return parent::update($_where, $_updateData);
	}

	public function total(){
		if(isset($_COOKIE['user']) && $_GET['a'] == 'member' && $_GET['m'] == 'order'){
			return parent::total(array('where'=>array("user='{$_COOKIE['user']}'")));
		}else{
			return parent::total();
		}
	}

	public function update() {
		$_where = array("id='{$this->_R['id']}'");
		$_updateData = $this->getRequest()->filter($this->_fields);
		if(!isset($_updateData['refund'])) $_updateData['refund'] = '';
		if ($_updateData['order_state'] == '已取消' || $_updateData['refund'] == 2) {
			$_goods = array();
			$_obj = parent::select(array('goods'), array('where'=>$_where));
			$_goods = unserialize(htmlspecialchars_decode($_obj[0]->goods));
			$this->_tables = array(DB_FREFIX.'goods');
			foreach ($_goods as $_key=>$_value) {
				$_temp = unserialize($_value);
				parent::update(array("id='{$_key}'"), array('inventory'=>array('inventory+'.$_temp['num']),'sales'=>array('sales-'.$_temp['num'])));
			}
			$this->_tables = array(DB_FREFIX.'order');
		}
		if($_updateData['order_pay'] == '已付款'){
			//执行成交表入库
			$this->record();
		}
		return parent::update($_where, $_updateData);
	}

	public function record(){
		$_where = array("id='{$this->_R['id']}'");
		$_goods = array();
		$_obj = parent::select(array('goods'),array('where'=>$_where));
		$_goods = unserialize(htmlspecialchars_decode($_obj[0]->goods));
		$this->_tables = array(DB_FREFIX.'record');
		foreach($_goods as $_key=>$_value){
			$_temp = unserialize($_value);
			$_addData['goods_id'] = $_temp['id'];
			$_addData['name'] = $_temp['name'];
			$_addData['num'] = $_temp['num'];
			$_addData['price'] = $_temp['price_sale'];
			$_addData['attr'] = '';
			foreach($_temp['attr'] as $_key2=>$_value2){
				$_addData['attr'] .= $_key2.':';
				foreach($_value2 as $_key3=>$_value3){
					$_addData['attr'] .= $_value3.',';
				}
			}
			$_addData['attr'] = substr($_addData['attr'],0,-1).';';
			$_addData['user'] = $this->_R['user'];
			$_addData['date'] = Tool::getDate();
			parent::add($_addData);
		}
		$this->_tables = array(DB_FREFIX.'order');
	}

	//申请退款订单数量
	public function refundGoodsCount(){
		$refundGoodsCount = parent::select(array('COUNT(*) as count'),
										array('where'=>array("refund=1")));
		return $refundGoodsCount[0]->count;
	}

	//未确认订单数量
	public function orderStateCount(){
		$orderStateCount = parent::select(array('COUNT(*) as count'),
										array('where'=>array("order_state='未确认'")));
		return $orderStateCount[0]->count;
	}

	//未付款订单数量
	public function orderPayCount(){
		$orderPayCount = parent::select(array('COUNT(*) as count'),
										array('where'=>array("order_state='已确认' AND order_pay='未付款'")));
		return $orderPayCount[0]->count;
	}

	//待发货订单数量
	public function orderDeliveryCount(){
		$orderDeliveryCount = parent::select(array('COUNT(*) as count'),
										array('where'=>array("order_state='已确认' AND order_pay='已付款' AND order_delivery='未发货'")));
		return $orderDeliveryCount[0]->count;
	}

	public function getNextId(){
		return parent::nextId();
	}

	public function delete(){
		$_where = array("id='{$this->_R['id']}' AND order_state='已取消'");
		return parent::delete($_where);
	}

	public function cancel() {
		$_where = array("id='{$this->_R['id']}'");
		$_goods = array();
		$_obj = parent::select(array('goods'), array('where'=>$_where));
		$_goods = unserialize(htmlspecialchars_decode($_obj[0]->goods));
		$this->_tables = array(DB_FREFIX.'goods');
		foreach ($_goods as $_key=>$_value) {
			$_temp = unserialize($_value);
			parent::update(array("id='{$_key}'"), array('inventory'=>array('inventory+'.$_temp['num']),'sales'=>array('sales-'.$_temp['num'])));
		}
		$this->_tables = array(DB_FREFIX.'order');
		$_updateData['order_state'] = '已取消';
		return parent::update($_where, $_updateData);
	}

	public function clear() {
		$_where = array("HOUR(TIMEDIFF(NOW(), date))>24 AND order_state='未确认' AND order_pay='未付款' AND order_delivery='未发货'");
		$_obj = parent::select(array('goods'), array('where'=>$_where));
		$this->_tables = array(DB_FREFIX.'goods');
		foreach ($_obj as $_key=>$_value) {
			$_goods = unserialize(htmlspecialchars_decode($_obj[$_key]->goods));
			foreach ($_goods as $_key=>$_value) {
				$_temp = unserialize($_value);
				parent::update(array("id='{$_key}'"), array('inventory'=>array('inventory+'.$_temp['num']),'sales'=>array('sales-'.$_temp['num'])));
			}
		}
		$this->_tables = array(DB_FREFIX.'order');
		$_updateData['order_state'] = '已取消';
		return parent::update($_where, $_updateData);
	}

	public function isCart(){
		if(isset($_COOKIE['cart'])){
			return true;
		}else{
			$this->_check = new Check();
			$this->_check->setMessage('结算列表中没有商品，无法生成订单！');
			$this->_check->error();
		}
	}
	
}
?>