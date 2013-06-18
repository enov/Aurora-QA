<?php
/**
 * Test case for Aurora_Type class
 *
 * @group Aurora
 * @group Aurora.Type
 */

class Aurora_TypeTest extends Unittest_TestCase
{
	public function test_is_model() {
		$m = new Model_Event();
		$this->assertTrue(Aurora_Type::is_model($m));
	}
	public function test_is_hi() {
		$m = new Model_Event();
		$this->assertTrue(Aurora_Type::is_model($m));
	}
	public function test_is_bye() {
		$m = new Model_Event();
		$this->assertTrue(Aurora_Type::is_model($m));
	}
}