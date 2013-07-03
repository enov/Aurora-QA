<?php

/**
 * Test case for Aurora_Core class
 *
 * @group Aurora
 * @group Aurora.Core
 */
class Aurora_CoreTest extends Unittest_TestCase
{

	/**
	 * provider for test_insert
	 */
	public static $arrIDs = array();
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
		Au::save($col);
		foreach ($col as $model) {
			$this->assertTrue(Au::is_loaded($model));
		}
		$cname = Au::type()->cname($col);
		static::$arrIDs[$cname] = array_map(function($model) {
			  return Au::prop()->get_pkey($model);
		  }, $col->to_array());
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_save_insert
	 */
	public function test_load_inserted($col) {
		$cname = Au::type()->cname($col);
		$col_loaded = Au::load($cname, static::$arrIDs[$cname]);
		$this->assertTrue(Au::type()->is_collection($col_loaded));
		foreach ($col_loaded as $model) {
			$this->assertTrue(Au::is_loaded($model));
		}
		// test to see if it has loaded correctly
		foreach (static::$arrIDs[$cname] as $key => $id) {
			$model = $col->offsetGet($key);
			$model_loaded = $col_loaded->offsetGet($id);
			Au::prop()->set_pkey($model, $id);
			$this->assertEquals($model, $model_loaded);
		}
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_load_inserted
	 */
	public function test_save_update($col, $prop_to_update) {
		$cname = Au::type()->cname($col);
		$col_loaded = Au::load($cname, static::$arrIDs[$cname]);
		$prop = key($prop_to_update);
		$value = current($prop_to_update);
		foreach($col_loaded as $model) {
			Au::prop()->set($model, $prop, $value);
		}
		Au::save($col_loaded);
		$this->assertTrue(Au::type()->is_collection($col_loaded));
		foreach ($col_loaded as $model) {
			$this->assertTrue(Au::is_loaded($model));
			$this->assertSame(Au::prop()->get($model, $prop), $value);
		}
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_save_update
	 */
	public function test_load_updated($col, $prop_to_update) {
		$cname = Au::type()->cname($col);
		$col_loaded = Au::load($cname, static::$arrIDs[$cname]);
		$this->assertTrue(Au::type()->is_collection($col_loaded));
		foreach ($col_loaded as $model) {
			$this->assertTrue(Au::is_loaded($model));
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
}