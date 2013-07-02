<?php

/**
 * Test case for Aurora_Reflection class
 *
 * @group Aurora
 * @group Aurora.Reflection
 */
class Aurora_ReflectionTest extends Unittest_TestCase
{
	public function provider_cname() {
		return array(
			array(new Model_Event, 'set_start', 'DateTime'),
			array(new Model_Event, 'set_end', 'DateTime'),
			array(new Model_Event, 'set_title', NULL),
		);
	}
	/**
	 * @dataProvider provider_cname
	 */
	public function test_typehint($model, $setter, $expected) {
		$this->assertEquals(
		  Aurora_Reflection::typehint($model, $setter), $expected
		);
	}

}