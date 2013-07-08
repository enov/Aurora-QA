<?php

/**
 * Test case for Aurora_Controller_API class
 *
 * @group Aurora
 * @group Aurora.Controller_API
 */
class Aurora_Controller_APITest extends Unittest_TestCase
{

	public static $arrIDs = array();
	public static $arrJSONs = array();
	public function provider_crud() {
		$event = new Model_Event;
		$event->set_allDay(FALSE);
		$event->set_start(new DateTime('+1 week'));
		$event->set_end(new DateTime('+1 week'));
		$event->set_title('Event initial title');
		return array(
			array($event, array('title' => 'Event updated title',),
			),
		);
	}
	/**
	 * @dataProvider provider_crud
	 */
	public function test_create($model) {
		$cname = Aurora_Type::cname($model);
		$uri = Aurora_Route::reverse($cname);
		$request_json = Au::json_encode($model);
		$response_json = Request::factory($uri)
			->method('POST')
			->body($request_json)
			->execute()->body();
		$created_model = Au::json_decode($response_json, $cname);
		$id = Aurora_Property::get_pkey($created_model);
		Au::prop()->set_pkey($model, $id);
		$this->assertTrue(Au::type()->is_model($created_model));
		$this->assertEquals($created_model, $model);
		static::$arrIDs[$cname] = $id;
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_create
	 */
	public function test_index_create($model) {
		$cname = Aurora_Type::cname($model);
		$uri = Aurora_Route::reverse($cname);
		$id = static::$arrIDs[$cname];
		$response_json = Request::factory($uri . '/' . $id)
			->execute()->body();
		$created_model = Au::json_decode($response_json, $cname);
		Au::prop()->set_pkey($model, $id);
		$this->assertTrue(Au::type()->is_model($created_model));
		$this->assertEquals($created_model, $model);
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_index_create
	 */
	public function test_update($model, $prop_to_update) {
		$cname = Aurora_Type::cname($model);
		$uri = Aurora_Route::reverse($cname);
		$id = static::$arrIDs[$cname];
		$prop = key($prop_to_update);
		$value = current($prop_to_update);
		Au::prop()->set_pkey($model, $id);
		Au::prop()->set($model, $prop, $value);
		$request_json = Au::json_encode($model);
		$response_json = Request::factory($uri)
			->method('PUT')
			->body($request_json)
			->execute()->body();
		$updated_model = Au::json_decode($response_json, $cname);
		$this->assertTrue(Au::type()->is_model($updated_model));
		$this->assertEquals($updated_model, $model);
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_update
	 */
	public function test_index_update($model, $prop_to_update) {
		$cname = Aurora_Type::cname($model);
		$uri = Aurora_Route::reverse($cname);
		$id = static::$arrIDs[$cname];
		$response_json = Request::factory($uri . '/' . $id)
			->execute()->body();
		$updated_model = Au::json_decode($response_json, $cname);
		$prop = key($prop_to_update);
		$value = current($prop_to_update);
		Au::prop()->set_pkey($model, $id);
		Au::prop()->set($model, $prop, $value);
		$this->assertTrue(Au::type()->is_model($updated_model));
		$this->assertEquals($updated_model, $model);
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_index_update
	 */
	public function test_delete($model, $prop_to_update) {
		$cname = Aurora_Type::cname($model);
		$uri = Aurora_Route::reverse($cname);
		$id = static::$arrIDs[$cname];
		$prop = key($prop_to_update);
		$value = current($prop_to_update);
		Au::prop()->set_pkey($model, $id);
		Au::prop()->set($model, $prop, $value);
		$response_json = Request::factory($uri . '/' . $id)
			->method('DELETE')
			->execute()->body();
		$deleted_model = Au::json_decode($response_json, $cname);
		$this->assertTrue(Au::type()->is_model($deleted_model));
		$this->assertEquals($deleted_model, $model);
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_delete
	 */
	public function test_index_delete($model) {
		$cname = Aurora_Type::cname($model);
		$uri = Aurora_Route::reverse($cname);
		$id = static::$arrIDs[$cname];
		$response_json = Request::factory($uri . '/' . $id)
			->execute()->body();
		$this->assertFalse(json_decode($response_json));
	}
}
