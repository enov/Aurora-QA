<?php

class Model_Category
{
	public $id;
	public $label;

	/**
	 *
	 * @property Model_Category $parent
	 */
	private $_parent;
	public function get_parent() {
		return $this->_parent;
	}
	public function set_parent(Model_Category $parent = NULL) {
		$this->_parent = $parent;
	}

	/**
	 *
	 * @property Collection_Event $events
	 */
	private $_events;
	public function get_events() {
		if ($this->_events === NULL)
			$this->_events = new Collection_Event;
		return $this->_events;
	}
	public function set_events(Collection_Event $events) {
		$this->_events = $events;
	}
}

class Aurora_Category implements Interface_Aurora_Database
{
	public function db_persist($model) {
		return array(
			'id' => $model->$id,
			'label' => $model->$label,
		);
	}
	public function db_retrieve($model, array $row) {
		$tbl = Au::db()->table($this);
		$model->id = $row[$tbl . '.id'];
		$model->label = $row[$tbl . '.label'];
	}

	public function load_events(Model_Category $category) {
		$events = new Collection_Event;
		$category->set_events($events);
	}
}

class Collection_Category extends Collection
{

}