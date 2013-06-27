<?php

class Aurora_StdClassTest extends Unittest_TestCase
{
	public function provider() {
		$id = 1;
		$title = "Appointment with Doctor";
		$start = new DateTime('+1 week');
		$end = new DateTime('+1 week + 1 hour');
		$allDay = FALSE;

		$m = new Model_Event();
		Aurora_Property::set_pkey($m, 1, TRUE);
		$m->set_title($title);
		$m->set_start($start);
		$m->set_end($end);
		$m->set_allDay($allDay);

		$std = new stdClass();
		$std->id = $id;
		$std->title = $title;
		$std->start = $start->format(DATE_ISO8601);
		$std->end = $end->format(DATE_ISO8601);
		$std->allDay = $allDay;

		return array(
			array($m, $std)
		);
	}
	/**
	 * @dataProvider provider
	 */
	public function test_from_model($model, $stdclass) {
		$this->assertEquals(
		  Aurora_StdClass::from_model($model), $stdclass
		);
	}
	/**
	 * @dataProvider provider
	 */
	public function test_to_model($model, $stdclass) {
		$this->assertEquals(
		  Aurora_StdClass::to_model($stdclass, get_class($model)), $model
		);
	}
	/**
	 * @dataProvider provider
	 */
	public function test_from_collection($model, $stdclass) {
		$col_class = Aurora_Type::collection($model);
		$col = new $col_class;
		$col->add($model);
		$this->assertEquals(
		  Aurora_StdClass::from_collection($col), array($stdclass)
		);
	}
	/**
	 * @dataProvider provider
	 */
	public function test_to_collection($model, $stdclass) {
		$col_class = Aurora_Type::collection($model);
		$col = new $col_class;
		$col->add($model);
		$this->assertEquals(
		  Aurora_StdClass::to_collection(array($stdclass), get_class($col)), $col
		);
	}
}