<?php

/**
 * Test case for Aurora_Collection class
 *
 * @group Aurora
 * @group Aurora.Route
 */
class Aurora_RouteTest extends Unittest_TestCase
{

	public function provider() {
		return array(
			array(
				'Event',
				array(
					'directory' => 'API',
					'controller' => 'Event',
					'id' => NULL
				),
			),
			array(
				'Category',
				array(
					'directory' => 'API',
					'controller' => 'Category',
					'id' => NULL
				),
			),
		);
	}

	public function provider_config_based() {
		return array(
			array(
				'Test_Event',
				array(
					'directory' => NULL,
					'controller' => 'API',
					'id' => NULL
				),
			),
			array(
				'Test_Category',
				array(
					'directory' => NULL,
					'controller' => 'API',
					'id' => NULL
				),
			),
		);
	}

	/**
	 * @dataProvider provider
	 */
	public function test_factory($cname, $expected) {
		$uri = Aurora_Route::reverse($cname);
		$request = new Request($uri);
		Route::set($cname, 'api/<path>', array('path' => '.*'))
		  ->filter(array('Aurora_Route', 'map'));
		$params = Route::get($cname)->matches($request);
		$params = array_intersect_key($params, $expected);
		$this->assertSame($expected, $params);
	}

	public function test_false() {
		$cname = 'false';
		$uri = 'api/' . $cname;
		$request = new Request($uri);
		Route::set('r', 'api/<path>', array('path' => '.*'))
		  ->filter(array('Aurora_Route', 'map'));
		$route = Route::get('r');
		$params = Route::get('r')->matches($request);
		$this->assertFalse($params);
	}

	/**
	 * @dataProvider provider_config_based
	 */
	public function test_config_based($cname, $expected) {
		$uri = Aurora_Route::reverse($cname);
		$request = new Request($uri);
		Route::set($cname, 'api/<path>', array('path' => '.*'))
		  ->filter(array('Aurora_Route', 'map'));
		$params = Route::get($cname)->matches($request);
		$this->assertSame($cname, $params['cname']);
		$params = array_intersect_key($params, $expected);
		$this->assertSame($expected, $params);
	}

}
