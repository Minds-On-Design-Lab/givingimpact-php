<?php

namespace MODL\GivingImpact;

class Model {

	protected $container = false;

	protected $properties = array(
		'related' => false,
		'limit' => false,
		'offset' => false,
		'sort' => 'created_at|desc',
		'status' => false
	);

	public function publicProperties() {
		$v = create_function('', '$o = new '.get_class($this).'; return get_object_vars($o);');

		return array_keys($v());
	}

	public function assign($obj) {
		foreach(get_object_vars($obj) as $k => $v) {
			$this->$k = $v;
		}

		return $this;
	}

	public function fetch($token) {
		$rc = $this->container->restClient;
		$rc->url = $rc->url.'/'.$this->path.'/'.$token;

		return $rc->get($this->properties);
	}

	public function related($related) {
		$this->properties['related'] = $related;

		return $this;
	}

	public function limit($lim) {
		$this->properties['limit'] = $lim;

		return $this;
	}

	public function offset($os) {
		$this->properties['offset'] = $os;

		return $this;
	}

	public function sort($order) {
		$this->properties['sort'] = $order;

		return $this;
	}

	public function status($status) {
		$this->properties['status'] = $status;

		return $this;
	}
}