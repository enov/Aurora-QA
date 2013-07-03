<?php

/**
 * Test case for Aurora_Profiler class
 *
 * @group Aurora
 * @group Aurora.Profiler
 */
class Aurora_ProfilerTest extends Unittest_TestCase
{
	/**
	 * Start of Profiling
	 */
	public function test_start($cname = 'event') {
		$aurora = Aurora_Core::factory($cname, 'aurora');
		// PROFILING OFF
		Kohana::$profiling = FALSE;
		$benchmarkFalse = Aurora_Profiler::start($aurora, 'test');
		$this->assertFalse($benchmarkFalse);
		// PROFILING ON
		Kohana::$profiling = TRUE;
		$benchmark = Aurora_Profiler::start($aurora, 'test');
		$this->assertTrue((bool) $benchmark);
		return $benchmark;
	}
	/**
	 * @depends test_start
	 */
	public function test_stop($benchmark) {
		Aurora_Profiler::stop($benchmark);
		list($time, $memory) = Profiler::total($benchmark);
		$this->assertGreaterThan(0, $time);
		$this->assertGreaterThan(0, $memory);
		return $benchmark;
	}
	/**
	 * @depends test_stop
	 */
	public function test_delete($benchmark) {
		$groups = Profiler::groups();
		$this->assertTrue(array_key_exists('aurora', $groups));
		$this->assertTrue(array_key_exists('Event::test', $groups['aurora']));
		$this->assertTrue(in_array($benchmark, $groups['aurora']['Event::test']));
		Aurora_Profiler::delete($benchmark);
		$groups = Profiler::groups();
		$this->assertTrue(array_key_exists('aurora', $groups));
		$this->assertFalse(array_key_exists('Event::test', $groups['aurora']));
	}
}