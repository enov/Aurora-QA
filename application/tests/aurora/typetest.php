<?php

/**
 * Test case for Aurora_Type class
 *
 * @group Aurora
 * @group Aurora.Type
 */
class Aurora_TypeTest extends Unittest_TestCase
{
	public function provider_cname() {
		return array(
			array('Event'),
		);
	}

	protected function prepare($cname) {
		$arrTypes = array('Model', 'Collection', 'Aurora', 'Controller_API');

		return array_map(function($type) use ($cname) {
			  $class = $type . '_' . $cname;
			  if ($type == 'Controller_API')
				  return new $class(Request::factory(), Response::factory());
			  else
				  return new $class;
		  }, $arrTypes);
	}
	/**
	 * @dataProvider provider_cname
	 */
	public function test_is_model($cname) {
		list($m, $col, $au, $ctrl) = $this->prepare($cname);

		$this->assertTrue(Aurora_Type::is_model($m));
		$this->assertTrue(Aurora_Type::is_model($m, TRUE));
		$this->assertFalse(Aurora_Type::is_model(get_class($m)));
		$this->assertTrue(Aurora_Type::is_model(get_class($m), TRUE));

		$this->assertFalse(Aurora_Type::is_model($col));
		$this->assertFalse(Aurora_Type::is_model($col, TRUE));
		$this->assertFalse(Aurora_Type::is_model(get_class($col)));
		$this->assertFalse(Aurora_Type::is_model(get_class($col), TRUE));

		$this->assertFalse(Aurora_Type::is_model($au));
		$this->assertFalse(Aurora_Type::is_model($au, TRUE));
		$this->assertFalse(Aurora_Type::is_model(get_class($au)));
		$this->assertFalse(Aurora_Type::is_model(get_class($au), TRUE));

		$this->assertFalse(Aurora_Type::is_model($ctrl));
		$this->assertFalse(Aurora_Type::is_model($ctrl, TRUE));
		$this->assertFalse(Aurora_Type::is_model(get_class($ctrl)));
		$this->assertFalse(Aurora_Type::is_model(get_class($ctrl), TRUE));
	}
	/**
	 * @dataProvider provider_cname
	 */
	public function test_is_collection($cname) {
		list($m, $col, $au, $ctrl) = $this->prepare($cname);

		$this->assertTrue(Aurora_Type::is_collection($col));
		$this->assertTrue(Aurora_Type::is_collection($col, TRUE));
		$this->assertFalse(Aurora_Type::is_collection(get_class($col)));
		$this->assertTrue(Aurora_Type::is_collection(get_class($col), TRUE));

		$this->assertFalse(Aurora_Type::is_collection($m));
		$this->assertFalse(Aurora_Type::is_collection($m, TRUE));
		$this->assertFalse(Aurora_Type::is_collection(get_class($m)));
		$this->assertFalse(Aurora_Type::is_collection(get_class($m), TRUE));

		$this->assertFalse(Aurora_Type::is_collection($au));
		$this->assertFalse(Aurora_Type::is_collection($au, TRUE));
		$this->assertFalse(Aurora_Type::is_collection(get_class($au)));
		$this->assertFalse(Aurora_Type::is_collection(get_class($au), TRUE));

		$this->assertFalse(Aurora_Type::is_collection($ctrl));
		$this->assertFalse(Aurora_Type::is_collection($ctrl, TRUE));
		$this->assertFalse(Aurora_Type::is_collection(get_class($ctrl)));
		$this->assertFalse(Aurora_Type::is_collection(get_class($ctrl), TRUE));
	}
	/**
	 * @dataProvider provider_cname
	 */
	public function test_is_aurora($cname) {
		list($m, $col, $au, $ctrl) = $this->prepare($cname);

		$this->assertTrue(Aurora_Type::is_aurora($au));
		$this->assertTrue(Aurora_Type::is_aurora($au, TRUE));
		$this->assertFalse(Aurora_Type::is_aurora(get_class($au)));
		$this->assertTrue(Aurora_Type::is_aurora(get_class($au), TRUE));

		$this->assertFalse(Aurora_Type::is_aurora($m));
		$this->assertFalse(Aurora_Type::is_aurora($m, TRUE));
		$this->assertFalse(Aurora_Type::is_aurora(get_class($m)));
		$this->assertFalse(Aurora_Type::is_aurora(get_class($m), TRUE));

		$this->assertFalse(Aurora_Type::is_aurora($col));
		$this->assertFalse(Aurora_Type::is_aurora($col, TRUE));
		$this->assertFalse(Aurora_Type::is_aurora(get_class($col)));
		$this->assertFalse(Aurora_Type::is_aurora(get_class($col), TRUE));

		$this->assertFalse(Aurora_Type::is_aurora($ctrl));
		$this->assertFalse(Aurora_Type::is_aurora($ctrl, TRUE));
		$this->assertFalse(Aurora_Type::is_aurora(get_class($ctrl)));
		$this->assertFalse(Aurora_Type::is_aurora(get_class($ctrl), TRUE));
	}
	/**
	 * @dataProvider provider_cname
	 */
	public function test_is_controller_api($cname) {
		list($m, $col, $au, $ctrl) = $this->prepare($cname);

		$this->assertTrue(Aurora_Type::is_controller_api($ctrl));
		$this->assertTrue(Aurora_Type::is_controller_api($ctrl, TRUE));
		$this->assertFalse(Aurora_Type::is_controller_api(get_class($ctrl)));
		$this->assertTrue(Aurora_Type::is_controller_api(get_class($ctrl), TRUE));

		$this->assertFalse(Aurora_Type::is_controller_api($m));
		$this->assertFalse(Aurora_Type::is_controller_api($m, TRUE));
		$this->assertFalse(Aurora_Type::is_controller_api(get_class($m)));
		$this->assertFalse(Aurora_Type::is_controller_api(get_class($m), TRUE));

		$this->assertFalse(Aurora_Type::is_controller_api($col));
		$this->assertFalse(Aurora_Type::is_controller_api($col, TRUE));
		$this->assertFalse(Aurora_Type::is_controller_api(get_class($col)));
		$this->assertFalse(Aurora_Type::is_controller_api(get_class($col), TRUE));

		$this->assertFalse(Aurora_Type::is_controller_api($au));
		$this->assertFalse(Aurora_Type::is_controller_api($au, TRUE));
		$this->assertFalse(Aurora_Type::is_controller_api(get_class($au)));
		$this->assertFalse(Aurora_Type::is_controller_api(get_class($au), TRUE));

	}

	/**
	 * @dataProvider provider_cname
	 */
	public function test_type_juggling($cname) {
		// MODEL
		$m_class = Aurora_Type::model($cname);
		$this->assertTrue(Aurora_Type::is_model($m_class, TRUE));
		$m = new $m_class;
		$this->assertTrue(Aurora_Type::is_model($m));
		// COLLECTION
		$col_class = Aurora_Type::collection($m);
		$this->assertTrue(Aurora_Type::is_collection($col_class, TRUE));
		$col = new $col_class;
		$this->assertTrue(Aurora_Type::is_collection($col));
		// AURORA
		$au_class = Aurora_Type::aurora($col);
		$this->assertTrue(Aurora_Type::is_aurora($au_class, TRUE));
		$au = new $au_class;
		$this->assertTrue(Aurora_Type::is_aurora($au));
		// CONTROLLER API
		$ctrl_class = Aurora_Type::controller_api($au);
		$this->assertTrue(Aurora_Type::is_controller_api($ctrl_class, TRUE));
		$ctrl = new $ctrl_class(Request::factory(), Response::factory());
		$this->assertTrue(Aurora_Type::is_controller_api($ctrl));
	}
}