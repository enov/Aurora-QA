<?php

class Aurora_DatabaseTest extends Unittest_TestCase
{
	/**
	 * provider for test_aurora
	 */
	public function provider_aurora() {
		return array(
			array(
				'event',
				array(
					'config' => 'pdo',
					'transactional' => TRUE,
					'table' => 'events',
					'pkey' => 'id',
					'row_pkey' => 'events.id',
					'qview' => DB::select()->from('events'),
				)
			),
		);
	}
	/**
	 * @dataProvider provider_aurora
	 */
	public function test_aurora($cname, $expected) {
		$au = Aurora_Core::factory($cname, 'aurora');
		$this->assertTrue(Aurora_Type::is_aurora($au));
		// test
		$this->assertEquals(Aurora_Database::config($au), $expected['config']);
		$this->assertEquals(Aurora_Database::transactional($au), $expected['transactional']);
		$this->assertEquals(Aurora_Database::table($au), $expected['table']);
		$this->assertEquals(Aurora_Database::pkey($au), $expected['pkey']);
		$this->assertEquals(Aurora_Database::row_pkey($au), $expected['row_pkey']);
		$this->assertEquals(Aurora_Database::qview($au), $expected['qview']);
	}
	/**
	 * provider for test_insert
	 */
	public static $arrIDs = array();
	public function provider_crud() {
		return array(
			array(
				'event',
				array(
					'events.id' => NULL,
					'events.date_start' => $this->mysql_set(new DateTime('+1 week')),
					'events.date_end' => $this->mysql_set(new DateTime('+1 week +1 hour')),
					'events.all_day' => 0,
					'events.title' => 'Event initial title',
				),
				array(
					'events.title' => 'Event updated title',
				),
			),
		);
	}
	/**
	 * @dataProvider provider_crud
	 */
	public function test_insert($cname, $row) {
		$au = Aurora_Core::factory($cname, 'aurora');
		$result = Aurora_Database::insert($au, $row);
		static::$arrIDs[$cname] = $result[0];
		$this->assertEquals($result[1], 1);
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_insert
	 */
	public function test_select_inserted($cname, $row) {
		$au = Aurora_Core::factory($cname, 'aurora');
		$select_result = Aurora_Database::select($au, static::$arrIDs[$cname]);
		$select_row = $select_result[0];
		$row_pkey = Aurora_Database::row_pkey($au);
		$row[$row_pkey] = static::$arrIDs[$cname];
		$this->assertEquals($select_row, $row);
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_select_inserted
	 */
	public function test_update($cname, $row, $update_column) {
		$au = Aurora_Core::factory($cname, 'aurora');
		$row_pkey = Aurora_Database::row_pkey($au);
		$row[$row_pkey] = static::$arrIDs[$cname];
		$row[key($update_column)] = current($update_column);
		$result = Aurora_Database::update($au, $row, static::$arrIDs[$cname]);
		$this->assertEquals($result, 1);
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_update
	 */
	public function test_select_updated($cname, $row, $update_column) {
		$au = Aurora_Core::factory($cname, 'aurora');
		$select_result = Aurora_Database::select($au, static::$arrIDs[$cname]);
		$select_row = $select_result[0];
		$row_pkey = Aurora_Database::row_pkey($au);
		$row[$row_pkey] = static::$arrIDs[$cname];
		$row[key($update_column)] = current($update_column);
		$this->assertEquals($select_row, $row);
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_select_updated
	 */
	public function test_delete($cname) {
		$au = Aurora_Core::factory($cname, 'aurora');
		$result = Aurora_Database::delete($au, static::$arrIDs[$cname]);
		$this->assertEquals($result, 1);
	}
	/**
	 * @dataProvider provider_crud
	 * @depends test_delete
	 */
	public function test_select_deleted($cname) {
		$au = Aurora_Core::factory($cname, 'aurora');
		/* @var $select_result Database_Result */
		$select_result = Aurora_Database::select($au, static::$arrIDs[$cname]);
		$this->assertSame($select_result->count(), 0);
	}
	//
	//
	//
	//
	//
	//
	// helper functions
	protected static function mysql_set(DateTime $date = NULL) {
		if (empty($date))
			return NULL;
		$d = clone $date;
		$d->setTimezone(new DateTimeZone('UTC'));
		return $d->format('Y-m-d H:i:s');
	}
	protected static function mysql_get($date) {
		if (empty($date))
			return NULL;
		$utc = new DateTimeZone('UTC');
		return new DateTime($date, $utc);
	}
}