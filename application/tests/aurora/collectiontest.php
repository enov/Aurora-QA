<?php
/**
 * Test case for Aurora_Collection class
 *
 * @group Aurora
 * @group Aurora.Collection
 */
class Aurora_CollectionTest extends Unittest_TestCase
{
	public static $cols = array();

	public function provider() {
		return array(
			array('event'),
			array('category'),
		);
	}
		/**
	 * @dataProvider provider
	 */
	public function test_factory($cname) {
		$col = Collection::factory($cname);
		$this->assertTrue(Aurora_Type::is_collection($col));
	}
	/**
	 * @dataProvider provider
	 */
	public function test_add($cname) {
		$col_class = Aurora_Type::collection($cname);
		$model_class = Aurora_Type::model($cname);
		// create collection
		$collection = new $col_class;
		$this->assertTrue(Aurora_Type::is_collection($collection));
		// Model 1
		$model_1 = new $model_class;
		Aurora_Property::set_pkey($model_1, 1, TRUE);
		$collection->add($model_1);
		$this->assertEquals($collection->count(), 1);
		// Model 2
		$model_2 = new $model_class;
		Aurora_Property::set_pkey($model_2, 2, TRUE);
		$collection->add($model_2);
		$this->assertEquals($collection->count(), 2);
		// Model 1
		$model_3 = new $model_class;
		Aurora_Property::set_pkey($model_3, 3, TRUE);
		$collection->add($model_3);
		$this->assertEquals($collection->count(), 3);
		// Expected Array
		$arr_exp = array($model_1, $model_2, $model_3);
		$this->assertSame(
		  $collection->to_array(), $arr_exp
		);

		static::$cols[$cname] = $collection;
	}
	/**
	 * @dataProvider provider
	 * @depends test_add
	 */
	public function test_get($cname) {
		$collection = static::$cols[$cname];
		// get models
		$model_1 = $collection->get(1);
		$model_2 = $collection->get(2);
		$model_3 = $collection->get(3);
		// Expected Array
		$arr_exp = array($model_1, $model_2, $model_3);
		$this->assertSame(
		  $collection->to_array(), $arr_exp
		);
	}
	/**
	 * @dataProvider provider
	 * @depends test_add
	 */
	public function test_exists($cname) {
		$collection = static::$cols[$cname];
		// get models
		$this->assertTrue($collection->exists(1));
		$this->assertTrue($collection->exists(2));
		$this->assertTrue($collection->exists(3));
		$this->assertFalse($collection->exists(4));
	}
	/**
	 * @dataProvider provider
	 * @depends test_add
	 */
	public function test_remove($cname) {
		$collection = static::$cols[$cname];
		// remove Model 2
		$collection->remove(2);
		// get models
		$this->assertTrue($collection->exists(1));
		$this->assertFalse($collection->exists(2));
		$this->assertTrue($collection->exists(3));
		$this->assertFalse($collection->exists(4));
		// count
		$this->assertEquals($collection->count(), 2);
	}
}