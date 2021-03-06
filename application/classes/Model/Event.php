<?php

class Model_Event
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

class Aurora_Event extends Model_Event implements Interface_Aurora_Database
{
	use Trait_Aurora_Data_Map;
	public function db_persist($model) {
		return $this->map_persist($model, ['id', 'title']) + array(
			'date_start' => $this->mysql_set($model->get_start()),
			'date_end' => $this->mysql_set($model->get_end()),
			'all_day' => $model->_allDay,
		);
	}
	public function db_retrieve($model, array $row) {
		$this->map_retrieve($model, $row, ['id', 'title']);
		$model->set_start(
		  $this->mysql_get($row['events.date_start'])
		);
		$model->set_end(
		  $this->mysql_get($row['events.date_end'])
		);
		$model->set_allDay($row['events.all_day']);
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
}

class Collection_Event extends Collection
{

}