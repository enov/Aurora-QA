<?php

/**
 * Test case for Aurora_StdClass class
 *
 * @group Aurora
 * @group Aurora.StdClass
 */
class Aurora_StdClassTest extends Unittest_TestCase
{
	public function dataprovider() {
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

		// category
		$c_label = 'Category Appointments';
		$c_id = 10;
		$cat = new Model_Category();
		$cat->id = $c_id;
		$cat->label = $c_label;
		$cat->get_events()->add($m);
//		$cat_parent = new Model_Category();
//		$cat_parent->id = $c_id + 1;
//		$cat->set_parent($cat);

		$c_std = new stdClass();
		$c_std->id = $c_id;
		$c_std->label = $c_label;
		$c_std->parent = NULL;
		$c_std->events = array($std);

		return array(
			array($m, $std),
			array($cat, $c_std),
		);
	}
	/**
	 * @dataProvider dataprovider
	 */
	public function test_from_model($model, $stdclass) {
		$this->assertEquals(
		  Aurora_StdClass::from_model($model), $stdclass
		);
	}
	/**
	 * @dataProvider dataprovider
	 */
	public function test_to_model($model, $stdclass) {
		$this->assertEquals(
		  Aurora_StdClass::to_model($stdclass, get_class($model)), $model
		);
	}
	/**
	 * @dataProvider dataprovider
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
	 * @dataProvider dataprovider
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