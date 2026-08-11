<?php
/**
 * Test Resend
 *
 * @package resend
 */

/**
 * Class Tests_Resend
 *
 * @package resend
 * @group core
 */
class Tests_Resend extends WP_UnitTestCase {
	/**
	 * Check that the RESEND_PLUGIN_DIR constant is defined.
	 */
	public function test_constant_defined() {
		$this->assertTrue( defined( 'RESEND__PLUGIN_DIR' ) );
	}

	/**
	 * Check that the files were included.
	 */
	public function test_classes_exist() {
		$this->assertTrue( class_exists( 'Resend' ) );
	}
}
