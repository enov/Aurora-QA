<?php

/**
 * Test case for Aurora_Aurora class
 *
 * @group Aurora
 * @group Aurora.Aurora
 */
class Aurora_AuroraTest extends Unittest_TestCase
{

	/**
	 * provider for test_insert
	 */
	public static $arrIDs = array();
	public static $arrJSONs = array();
	public function provider_crud() {
		$event = new Model_Event;
		$event->set_allDay(FALSE);
		$event->set_start(new DateTime('+1 week'));
		$event->set_end(new DateTime('+1 week'));
		$event->set_title('Event initial title');
		$event2 = new Model_Event;
		$event2->set_allDay(FALSE);
		$event2->set_start(new DateTime('+2 week'));
		$event2->set_end(new DateTime('+2 week'));
		$event2->set_title('Event 2 initial title');
		$col_event = new Collection_Event;
		$col_event->add($event);
		$col_event->add($event2);
		return array(
			array($col_event, array('title' => 'Event updated title',),
			),
		);
	}
	/**
	 * @dataProvider provider_crud
	 */
	public function test_save_insert($col) {
		$aurora = Aurora::factory($col);
		foreach ($col as $m) {
			$this->assertTrue($aurora->is_new($m));
		}
		// do the initial saving (insert)
		$aurora->save($col);
		// loop to assert if models now have IDs
		foreach ($col as $model) {
			$this->assertTrue(Au::is_loaded($model));
		}
		// save the IDs into a static field
		$cname = Au::type()->cname($col);
		static::$arrIDs[$cname] = array_map(function($model) {
			  return Au::prop()->get_pkey($model);
		  }, $col->to_array());
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_save_insert
	 */
	public function test_load_inserted($col_expected) {
		$cname = Au::type()->cname($col_expected);
		$aurora = Aurora::factory($col_expected);
		$col_loaded = $aurora->load(static::$arrIDs[$cname]);
		$this->assertTrue(Au::type()->is_collection($col_loaded));
		foreach ($col_loaded as $model) {
			$this->assertTrue($aurora->is_loaded($model));
		}

		// test to see if it has loaded correctly
		foreach (static::$arrIDs[$cname] as $key => $id) {
			$model_expected = $col_expected->offsetGet($key);
			$model_loaded = $col_loaded->offsetGet($id);
			Au::prop()->set_pkey($model_expected, $id);
			$this->assertEquals($model_expected, $model_loaded);
		}
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_load_inserted
	 */
	public function test_save_update($col, $prop_to_update) {
		$cname = Au::type()->cname($col);
		$aurora = Aurora::factory($col);
		$col_loaded = $aurora->load(static::$arrIDs[$cname]);
		$prop = key($prop_to_update);
		$value = current($prop_to_update);
		foreach ($col_loaded as $model) {
			Au::prop()->set($model, $prop, $value);
		}
		$aurora->save($col_loaded);
		$this->assertTrue(Au::type()->is_collection($col_loaded));
		foreach ($col_loaded as $model) {
			$this->assertTrue($aurora->is_loaded($model));
			$this->assertSame(Au::prop()->get($model, $prop), $value);
		}
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_save_update
	 */
	public function test_load_updated($col, $prop_to_update) {
		$cname = Au::type()->cname($col);
		$aurora = Aurora::factory($col);
		$col_loaded = $aurora->load(static::$arrIDs[$cname]);
		$this->assertTrue(Au::type()->is_collection($col_loaded));
		foreach ($col_loaded as $model) {
			$this->assertTrue($aurora->is_loaded($model));
		}
		// test to see if it has loaded correctly
		$prop = key($prop_to_update);
		$value = current($prop_to_update);
		foreach (static::$arrIDs[$cname] as $key => $id) {
			$model = $col->offsetGet($key);
			$model_loaded = $col_loaded->offsetGet($id);
			Au::prop()->set_pkey($model, $id);
			Au::prop()->set($model, $prop, $value);
			$this->assertEquals($model, $model_loaded);
		}
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_load_updated
	 */
	public function test_delete($col) {
		$cname = Au::type()->cname($col);
		$aurora = Aurora::factory($col);
		$col_loaded = $aurora->load(static::$arrIDs[$cname]);
		$aurora->delete($col_loaded);
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_delete
	 */
	public function test_load_deleted($col, $prop_to_update) {
		$cname = Au::type()->cname($col);
		$aurora = Aurora::factory($col);
		$col_loaded = $aurora->load(static::$arrIDs[$cname]);
		$this->assertTrue(Au::type()->is_collection($col_loaded));
		$this->assertSame($col_loaded->count(), 0);
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_load_inserted
	 */
	public function test_json_encode($col) {
		$cname = Au::type()->cname($col);
		$aurora = Aurora::factory($col);
		$col_loaded = $aurora->load(static::$arrIDs[$cname]);
		$json = $aurora->json_encode($col_loaded);
		$col_from_json = $aurora->json_decode($json);
		$this->assertEquals($col_loaded, $col_from_json);
	}

	/**
	 * test call
	 */
	public function test_aurora_call() {
		$cname = 'Category';
		$model = Model::factory($cname);
		$aurora = Aurora::factory($cname);

		$aurora->load_events($model);
	}
}