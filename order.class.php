<?php
	/**
	 *类名：Order
	 *说明：义诊预约订单类
	 */
	class Order{
		public $name = NULL;
		public $phone = NULL;
		public $problem = NULL;

		public function __construct($name, $phone, $problem) {
	       $this->name = $name;
	       $this->phone = $phone;
	       $this->problem = $problem;
	   }
	}
?>