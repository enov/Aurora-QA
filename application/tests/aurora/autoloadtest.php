<?php

/**
 * Test case for Aurora_Core class
 *
 * @group Aurora
 * @group Aurora.Core
 * @group Aurora.Core.Autoload
 */
class Aurora_AutoloadTest extends Unittest_TestCase
{
	/**
	 * provider for test_load_aurora and test_load_collection
	 */
	public function provider_autoload() {
		return array(
			array('event'),
		);
	}
	/**
	 * @dataProvider provider_autoload
	 */
	public function test_load_aurora($cname) {
		$au = Au::factory($cname, 'Aurora');
		$this->assertTrue(Au::type()->is_aurora($au));
	}
	/**
	 * @dataProvider provider_autoload
	 */
	public function test_load_collection($cname) {
		$col = Au::factory($cname, 'Collection');
		$this->assertTrue(Au::type()->is_collection($col));
	}
}