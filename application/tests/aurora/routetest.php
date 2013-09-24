<?php

/**
 * Test case for Aurora_Collection class
 *
 * @group Aurora
 * @group Aurora.Collection
 */
class Aurora_RouteTest extends Unittest_TestCase
{

	public function provider() {
		return array(
			array(
				'event',
				array(
					'directory' => 'API',
					'controller' => 'Event',
					'id' => NULL
				),
			),
			array(
				'category',
				array(
					'directory' => 'API',
					'controller' => 'Category',
					'id' => NULL
				),
			),
			array(
				'false',
				FALSE,
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

}