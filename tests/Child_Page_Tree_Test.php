<?php

namespace Child_Page_Tree;

/**
 * Class Child_Page_Tree_Test
 *
 * @author Hans-Helge Buerger
 */
class Child_Page_Tree_Test extends \PHPUnit_Framework_TestCase {

	/**
	 * Simple test to see if our class exists. Nothing fancy here.
	 */
	public function test_class_exist() {
		$this->assertTrue( class_exists( 'Child_Page_Tree\Child_Page_Tree' ) );
	}
}
