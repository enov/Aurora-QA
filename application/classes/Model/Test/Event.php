<?php

class Model_Test_Event
{

	/**
	 * @property-read int $id
	 */
	protected $_id = 0;
	public function get_id() {
		return $this->_id;
	}
	protected function set_id($id) {
		if (!Valid::digit($id))
			throw new Kohana_Exception("Invalid Calendar ID.");
		$this->_id = $id;
	}

	/**
	 *
	 * @property DateTimeZone $timezone
	 */
	protected $_timezone;
	protected function get_timezone() {
		if (is_null($this->_timezone))
			$this->_timezone = new DateTimeZone(date_default_timezone_get());
		return $this->_timezone;
	}
	public function set_timezone($timezone) {
		$timezone = $timezone instanceof DateTimeZone ? $timezone : new DateTimeZone($timezone);
		if (isset($this->_start))
			$this->_start->setTimezone($timezone);
		if (isset($this->_end))
			$this->_end->setTimezone($timezone);
		$this->_timezone = $timezone;
	}

	/**
	 * @property DateTime $start
	 */
	protected $_start;
	public function get_start() {
		if (is_null($this->_start))
			$this->_start = new DateTime('now');
		if ($this->_allDay)
			$this->_start->setTime(0, 0, 0);
		return $this->_start;
	}
	public function set_start(DateTime $start) {
		if (is_null($start))
			throw new Kohana_Exception("Start DateTime can not be null.");
		$start->setTimezone($this->get_timezone());
		$this->_start = $start;
	}

	/**
	 * @property DateTime $end
	 */
	protected $_end;
	public function get_end() {
		if (is_null($this->_end)) {
			$this->_end = clone $this->get_start();
		}
		if ($this->_allDay)
			$this->_end->setTime(23, 59, 59);
		return $this->_end;
	}
	public function set_end(DateTime $end = NULL) {
		if (!$end == NULL) {
			$end->setTimezone($this->get_timezone());
		} else {
			$this->_end = clone $this->_start;
		}
		$this->_end = $end;
	}

	/**
	 * @property bool $allDay
	 */
	protected $_allDay = false;
	public function get_allDay() {
		return $this->_allDay;
	}
	public function set_allDay($allDay) {
		$this->_allDay = (bool) $allDay;
	}

	/**
	 *
	 * @property string $title
	 */
	protected $_title;
	public function get_title() {
		return $this->_title;
	}
	public function set_title(/** string */ $title) {
		if (!Valid::max_length($title, 50))
			throw new Kohana_Exception("Event title is invalid");
		$this->_title = $title;
	}
}

class Aurora_Test_Event extends Model_Test_Event implements Interface_Aurora_Database, Interface_Aurora_Hook_After_Create, Interface_Aurora_Hook_After_Delete, Interface_Aurora_Hook_After_Load, Interface_Aurora_Hook_After_Save, Interface_Aurora_Hook_After_Update, Interface_Aurora_Hook_Before_Create, Interface_Aurora_Hook_Before_Delete, Interface_Aurora_Hook_Before_Load, Interface_Aurora_Hook_Before_Save, Interface_Aurora_Hook_Before_Update
{

	public $table = 'events';
	public function db_persist($model) {
		return array(
			'id' => $model->_id,
			'date_start' => $this->mysql_set($model->get_start()),
			'date_end' => $this->mysql_set($model->get_end()),
			'all_day' => $model->_allDay,
			'title' => $model->_title,
		);
	}
	public function db_retrieve($model, array $row) {
		// table name with a dot
		// $tbl = Au::db()->table($this) . '.';
		// $model->_id = $row[$tbl . 'id'];
		$model->set_id($row['events.id']);
		$model->set_start(
		  $this->mysql_get($row['events.date_start'])
		);
		$model->set_end(
		  $this->mysql_get($row['events.date_end'])
		);
		$model->set_allDay($row['events.all_day']);
		$model->set_title($row['events.title']);
	}
	protected static function mysql_set(DateTime $date = NULL) {
		if (empty($date))
			return NULL;
		$d = clone $date;
		$d->setTimezone(new DateTimeZone('UTC'));
		return $d->format('Y-m-d H:i:s');
	}
	protected static function mysql_get($date) {
		if (empty($date))
			return NULL;
		$utc = new DateTimeZone('UTC');
		return new DateTime($date, $utc);
	}

	public $hooks = array();
	public function after_create($model) {
		$this->hooks['after']['create'] = TRUE;
	}
	public function after_delete($model_or_collection) {
		$this->hooks['after']['delete'] = TRUE;
	}
	public function after_load($model_or_collection) {
		$this->hooks['after']['load'] = TRUE;
	}
	public function after_save($model_or_collection) {
		$this->hooks['after']['save'] = TRUE;
	}
	public function after_update($model) {
		$this->hooks['after']['update'] = TRUE;
	}
	public function before_create($model) {
		$this->hooks['before']['create'] = TRUE;
	}
	public function before_delete($model_or_collection) {
		$this->hooks['before']['delete'] = TRUE;
	}
	public function before_load(&$params) {
		$this->hooks['before']['load'] = TRUE;
	}
	public function before_save($model_or_collection) {
		$this->hooks['before']['save'] = TRUE;
	}
	public function before_update($model) {
		$this->hooks['before']['update'] = TRUE;
	}
}

class Collection_Test_Event extends Collection
{

}