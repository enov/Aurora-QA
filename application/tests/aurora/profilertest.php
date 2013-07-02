<?php

/**
 * Test case for Aurora_Profiler class
 *
 * @group Aurora
 * @group Aurora.Profiler
 */
class Aurora_ProfilerTest extends Unittest_TestCase
{
	public static $arrTokens = array();
	/**
	 * provider
	 */
	public function provider() {
		return array(
			array(
				'event',
			)
		);
	}
	/**
	 * @dataProvider provider
	 */
	public function test_start($cname) {
		$aurora = Aurora_Core::factory($cname, 'aurora');
		// PROFILING OFF
		Kohana::$profiling = FALSE;
		$benchmark = Aurora_Profiler::start($aurora, __FUNCTION__);
		$this->assertFalse($benchmark);
		// PROFILING ON
		Kohana::$profiling = TRUE;
		$benchmark = Aurora_Profiler::start($aurora, __FUNCTION__);
		$this->assertTrue((bool) $benchmark);
		static::$arrTokens[$cname] = $benchmark;
	}
	/**
	 * @dataProvider provider
	 * @depends test_start
	 */
	public function test_stop($cname) {
		$benchmark = static::$arrTokens[$cname];
		Aurora_Profiler::stop($benchmark);
		list($time, $memory) = Profiler::total($benchmark);
		$this->assertGreaterThan(0, $time);
		$this->assertGreaterThan(0, $memory);
	}
	/**
	 * @dataProvider provider
	 * @depends test_start
     * @expectedException ErrorException
	 * @expectedExceptionMessage Undefined index
	 */
	public function test_delete($cname) {
		$benchmark = static::$arrTokens[$cname];
		Aurora_Profiler::delete($benchmark);
		list($time, $memory) = Profiler::total($benchmark);
	}
}