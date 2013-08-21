<?php

/**
 * Test case for Aurora_Core class
 *
 * @group Aurora
 * @group Aurora.Core
 */
class Aurora_AuTest extends Unittest_TestCase
{
	public function test_au() {
		$this->assertTrue(Au::type() instanceof Aurora_Type);
		$this->assertTrue(Au::db() instanceof Aurora_Database);
		$this->assertTrue(Au::prop() instanceof Aurora_Property);
		$this->assertTrue(Au::rflx() instanceof Aurora_Reflection);
		$this->assertTrue(Au::hook() instanceof Aurora_Hook);
		$this->assertTrue(Au::type() instanceof Aurora_Type);
	}
}