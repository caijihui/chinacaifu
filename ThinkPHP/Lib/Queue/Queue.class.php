<?php
Import("libs.Cache.RedisList", ADDON_PATH);
class Queue {
	protected $prefix;
	private static $instance;
	public $step = 1;	//如果设为1，pop出来的都是单个数据，大于1的话，返回一组数据。
	const DEAL_SUCCESS = 231;
	const DEAL_FAIL    = 232;
	
	const STATUS_INIT = 1; //插入队列
	const STATUS_SUCCESS = 2; //处理成功
	const STATUS_FAIL = 3; //处理失败

	public static function create($prefix) {
		require_once APP_PATH . "Queue/jobs/{$prefix}.php";
		$q = "{$prefix}";
		return new $q();
	}

	public function __construct() {
		$this->prefix = $this->prefix();
		if (!self::$instance[$this->prefix]){
			self::$instance[$this->prefix] = new RedisList($this->prefix, 30*3600*24);
			self::$instance[$this->prefix]->select(1);//不能选0，否则会出现一些queue进不去的问题。
		}
	}

	public function pop() {
		if ($this->step <= 1) {
			$data     = self::$instance[$this->prefix]->lPop();
			return $data ? unserialize($data) : $data;
		} else {
			$data_arr = array();
			for($i=0; $i<$this->step; $i++) {
				if (($item = self::$instance[$this->prefix]->lPop($this->prefix)) !== false) $data_arr[] = unserialize($item);
				else break;
			}
			return empty($data_arr) ? false : $data_arr;
		}
	}

	public function count() {
		return self::$instance[$this->prefix]->lSize($this->prefix);
	}

	public function dump() {
		return self::$instance[$this->prefix]->lRange($this->prefix, 0, -1);
	}

	public function push($data) {
		$has = $this->hasDbData($data);
		if(!$has) {
			$id = $this->startDb($data);
			if(!$id) throw_exception("数据库写入失败");
		} else {
            if($has['status'] == self::DEAL_SUCCESS) throw_exception("已经处理");
			$id = $has['id'];
		}
		$len = self::$instance[$this->prefix]->RPush($data);
		if($len === false){
			Import("libs.Counter.SimpleCounter", ADDON_PATH);
			$queue_fail_counter = SimpleCounter::init(Counter::GROUP_QUEUE_COUNTER, Counter::LIFETIME_TODAY);
			$queue_fail_counter->incr("fail");
			if ($this->step > 1) $data = array($data);
			$res = $this->deal($data);	//如果queue失败，同步处理，避免数据丢失
			if(self::DEAL_SUCCESS){
				$this->endDb($data, self::STATUS_SUCCESS, '');
			} else {
				$this->endDb($data, self::STATUS_FAIL, '实时处理失败');
			}
		}
		return $len;
	}
	
	public function hasDbData($data){
		$key = $this->getKey($data);
		if(!$key) return throw_exception("请写key的解析方法");
		$data = M('queue_record')->where(array('queue'=>$this->prefix, 'tkey'=>$key))->find();
		return $data;
	}

	public function startDb($data){
		$queueDb['queue'] = $this->prefix;
		$queueDb['data'] = serialize($data);
		$queueDb['tkey'] = $this->getKey($data);
		if(!$queueDb['tkey']) return throw_exception("请写key的解析方法");
		$queueDb['status'] = self::STATUS_INIT;
		$queueDb['ctime'] = time();
		$queueDb['mtime'] = time();
		$id = M('queue_record')->add($queueDb);
		return $id;
	}
	
	public function endDb($data, $status, $error_msg){
		$key = $this->getKey($data);
		if(!$key) return throw_exception("请写key的解析方法");
// 		$queueDb['tkey'] = $key;
		$queueDb['status'] = $status;
		$queueDb['error_msg'] = $error_msg;
		$queueDb['mtime'] = time();
		$ref = M('queue_record')->where(array('queue'=>$this->prefix, 'tkey'=>$key))->save($queueDb);
		return $ref;
	}
	
	public function getKey($data){
		return null;
	}
	
	public function deal($data) {	} //you must return Queue::DEAL_SUCCESS or Queue::DEAL_FAIL

	public function end() {	} //收尾工作
	
	private function prefix() {
		return get_class($this);
	}

	public function log($message,$class) {
		$file = APP_PATH."/Queue/logs/" . $class . "_" . date('Y-m-d') . ".log";
		@file_put_contents($file, date('H:i:s ') . $message . "\n", FILE_APPEND);
	}

	public static function getQueueList() {
		$queues = array();
		$d = dir(APP_PATH . "Queue/jobs/");
		while(($name = $d->read()) !== false) {
			$tail = substr($name, -4);
			if ($tail == '.php') {
				$queues[] = substr($name, 0, -4);
			}
		}
// 		$queues = array_unique($queues);
		sort($queues);
		return $queues;
	}
}