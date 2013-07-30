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
					'directory' => 'api',
					'controller' => 'event',
					'id' => NULL
				),
			),
			array(
				'category',
				array(
					'directory' => 'api',
					'controller' => 'category',
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
		try {
			$url = Aurora_Route::reverse($cname);
		} catch (Exception $exc) {
			$url = 'api/' . $cname;
		}
		$this->assertSame($expected, Aurora_Route::route($url));
	}
}