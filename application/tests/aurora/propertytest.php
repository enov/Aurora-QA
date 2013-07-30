<?php

/**
 * Test case for Aurora_Property class
 *
 * @group Aurora
 * @group Aurora.Property
 */
class Aurora_PropertyTest extends Unittest_TestCase
{
	public function provider_getters() {
		return array(
			// Model_Event
			array(
				new Model_Event,
				array(
					'id' => array(
						'type' => 'method',
						'name' => 'id',
					),
					'start' => array(
						'type' => 'method',
						'name' => 'start',
					),
					'end' => array(
						'type' => 'method',
						'name' => 'end',
					),
					'allDay' => array(
						'type' => 'method',
						'name' => 'allDay',
					),
					'title' => array(
						'type' => 'method',
						'name' => 'title',
					),
				)
			),
			// Model_Category
			array(
				new Model_Category,
				array(
					'id' => array(
						'type' => 'property',
						'name' => 'id',
					),
					'label' => array(
						'type' => 'property',
						'name' => 'label',
					),
				)
			),
		);
	}
	/**
	 * @dataProvider provider_getters
	 */
	public function test_getters($model, $expected) {
		$this->assertEquals(
		  Aurora_Property::getters($model), $expected
		);
	}
	public function provider_setters() {
		return array(
			// Model_Event
			array(
				new Model_Event,
				array(
					'timezone' => array(
						'type' => 'method',
						'name' => 'timezone',
					),
					'start' => array(
						'type' => 'method',
						'name' => 'start',
					),
					'end' => array(
						'type' => 'method',
						'name' => 'end',
					),
					'allDay' => array(
						'type' => 'method',
						'name' => 'allDay',
					),
					'title' => array(
						'type' => 'method',
						'name' => 'title',
					),
				)
			),
			// Model_Category
			array(
				new Model_Category,
				array(
					'id' => array(
						'type' => 'property',
						'name' => 'id',
					),
					'label' => array(
						'type' => 'property',
						'name' => 'label',
					),
				)
			),
		);
	}
	/**
	 * @dataProvider provider_setters
	 */
	public function test_setters($model, $expected) {
		$this->assertEquals(
		  Aurora_Property::setters($model), $expected
		);
	}
	public function provider_getset() {
		return array(
			// Model_Event
			array(new Model_Event, 'title', 'Appointment with doctor'),
			array(new Model_Category, 'label', 'Personal Calendar'),
		);
	}
	/**
	 * @dataProvider provider_getset
	 */
	public function test_getset($model, $property, $value) {
		Aurora_Property::set($model, $property, $value);

		$this->assertEquals(
		  Aurora_Property::get($model, $property), $value
		);
	}
	public function provider_getset_pkey() {
		return array(
			// Model_Event
			array(new Model_Event, 10),
			array(new Model_Category, 10),
			array(new Model_ProtectedID, 10),
		);
	}
	/**
	 * @dataProvider provider_getset_pkey
	 */
	public function test_getset_pkey($model, $pkey_value) {
		$force = TRUE;
		Aurora_Property::set_pkey($model, $pkey_value, $force);

		$this->assertEquals(
		  Aurora_Property::get_pkey($model), $pkey_value
		);

	}
}
class Model_ProtectedID {
	 protected $id;
	 public function get_id () {
		 return $this->id;
	 }
}
class Aurora_ProtectedID {

}