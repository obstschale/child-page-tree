<?php

namespace Child_Page_Tree;

/**
 * Class Child_Page_Tree_Test
 *
 * @author Hans-Helge Buerger
 */
class Child_Page_Tree_Test extends \PHPUnit_Framework_TestCase {

	public function test_class_exist() {
		$this->assertTrue( class_exists( 'Child_Page_Tree' ) );
	}
}
