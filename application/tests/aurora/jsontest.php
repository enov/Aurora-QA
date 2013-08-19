<?php

/**
 * Test case for Aurora_JSON class
 *
 * @group Aurora
 * @group Aurora.JSON
 */
class Aurora_JSONTest extends Unittest_TestCase
{
	public function dataprovider() {
		
	}
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function test_exception_json_serialize_InvalidArgumentException() {
		$cname = 'Exceptionist';
		$au = Aurora::factory($cname);
		Au::json()->serialize($au);
	}
	/**
	 * @expectedException Kohana_Exception
	 */
	public function test_exception_json_serialize() {
		$cname = 'Exceptionist';
		$model = Model::factory($cname);
		Au::json()->serialize($model);
	}
	/**
	 * @expectedException Kohana_Exception
	 */
	public function test_exception_json_deserialize() {
		$cname = 'Exceptionist';
		$stdclass = new stdClass();
		$stdclass->id = 10;
		$stdclass->label = 'test';
		Au::json()->deserialize($stdclass, $cname);
	}
}