<?php 
class ServiceCheck extends Check{
	public function addCheck(&$_model,Array $_Param){
		if (self::isNullString($_POST['name'])) {
			$this->_message[] = '售后服务名称不得为空！';
			$this->_flag = false;
		}
		if (self::checkStrLength($_POST['name'], 2, 'min')) {
			$this->_message[] = '售后服务名称不得小于2位！';
			$this->_flag = false;
		}
		if (self::checkStrLength($_POST['name'], 20, 'max')) {
			$this->_message[] = '售后服务名称不得大于20位！';
			$this->_flag = false;
		}
		if ($_model->isOne($_Param)) {
			$this->_message[] = '售后服务名称被占用！';
			$this->_flag = false;
		}
		return $this->_flag;
	}

	public function ajax(Model &$_model, Array $_param) {
		echo $_model->isOne($_param) ? 1 : 2;
	}


	

}

 ?>