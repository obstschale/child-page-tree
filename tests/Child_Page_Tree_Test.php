<?php

namespace Child_Page_Tree;

/**
 * Class Child_Page_Tree_Test
 *
 * @author Hans-Helge Buerger
 */
class Child_Page_Tree_Test extends \PHPUnit_Framework_TestCase {

	/**
	 * @var object $cpt instance of Child_Page_Tree
	 */
	protected $cpt;

	/**
	 * Instantiate Child_Page_Tree object
	 */
	public function setUp() {
		$this->cpt = new Child_Page_Tree();
		\WP_Mock::setUp();
	}

	public function tearDown() {
		\WP_Mock::tearDown();
	}

	/**
	 * Using Reflection to make a protected or private method accessible for testing
	 *
	 * @link http://stackoverflow.com/questions/249664/best-practices-to-test-protected-methods-with-phpunit
	 * @link https://sebastian-bergmann.de/archives/881-Testing-Your-Privates.html
	 * @param $class fully qualified class name
	 * @param $name method name
	 * @return \ReflectionMethod accessible method
	 */
	protected static function get_method( $class, $name ) {
		$class = new \ReflectionClass( $class );
		$method = $class->getMethod( $name );
		$method->setAccessible(true);
		return $method;
	}

	/**
	 * Simple test to see if our class exists. Nothing fancy here.
	 */
	public function test_class_exist() {
		$this->assertTrue( class_exists( 'Child_Page_Tree\Child_Page_Tree' ) );
	}

	/**
	 * Check if all needed actions and filters are added
	 */
	public function test_register_necessary_hooks() {
		\WP_Mock::expectActionAdded(
			'post_submitbox_misc_actions',
			[ $this->cpt, 'render_select_box' ]
		);

		\WP_Mock::expectActionAdded(
			'save_post',
			[ $this->cpt, 'save_child_page_tree_setting' ],
			10,
			2
		);

		\WP_Mock::expectFilterAdded(
			'the_content',
			[ $this->cpt, 'add_child_page_tree_to_content' ]
		);

		\WP_Mock::expectActionAdded(
			'wp_enqueue_scripts',
			[ $this->cpt, 'enqueue_style' ]
		);

		$this->cpt->register_hooks();
	}

	public function test_append_page_tree() {}
	public function test_prepend_page_tree() {}
	public function test_dont_add_page_tree_if_none() {}
	public function test_dont_add_page_tree_if_wrong_meta_value() {}
	public function test_add_selectbox_only_on_page_edit() {}
	public function test_if_post_meta_not_set_choose_default_value_none() {}
	public function test_save_post_meta() {}
	public function test_skip_saving_if_not_on_page_edit() {}

//	public function test_apply_filter_on_page_tree() {
//
//		$post_id = 4;
//
//		$method = self::get_method( '\Child_Page_Tree\Child_Page_Tree', 'get_child_page_tree_template' );
//
//		$this->assertTrue( $method->isPrivate() );
//
//		$re = "<li>Original Element</li>";
//		\WP_Mock::wpFunction( 'wp_list_pages', [
//			'args' => [ 'echo' => 0,
//						'child_of' => $post_id,
//						'title_li' => '' ],
//			'times' => 1,
//			'return' => $re
//		] );
//
//		\WP_Mock::wpFunction( 'apply_filters', [
//			'args' => [ 'child_page_tree_before_output', $re, $post_id ],
//			'times' => 1,
//			'return' => "<li>Sample Element</li>"
//		] );
//
//		$tree = $method->invoke( $this->cpt, $post_id );
//		$expected = "<ul id=\"child_page_tree\" class=\"\"><li>Sample Element</li></ul>";
//		$this->assertEquals( $expected, $tree );
//	}

	/**
	 * Check that custom CSS is properly embeded
	 */
	public function test_enqueue_style() {
		\WP_Mock::wpFunction( 'is_admin', [
			'times' => 1,
			'return' => false
		] );

		$url = "http://local.test/plugins";
		\WP_Mock::wpFunction( 'plugins_url', [
			'times' => 1,
			'return' => $url
		] );

		\WP_Mock::wpFunction( 'wp_register_style', [
			'args' => [ 'child_page_tree_style', $url ],
			'times' => 1
		] );

		\WP_Mock::wpFunction( 'wp_enqueue_style', [
			'args' => 'child_page_tree_style',
			'times' => 1
		] );

		$this->assertEquals( 1, $this->cpt->enqueue_style() );
	}

	/**
	 * Check that method exists so style are not added in backend
	 */
	public function test_dont_add_style_in_backend() {
		\WP_Mock::wpFunction( 'is_admin', [
			'times' => 1,
			'return' => true
		] );

		$this->assertEquals( 0, $this->cpt->enqueue_style() );
	}
}
