<?php

/**
 * Plugin Name: Child Page Tree
 * Plugin URI: https://github.com/obstschale/child-page-tree
 * Description: Small Plugin, which can add a page tree of all child pages of a specific page
 * Version: 1.0.0
 * Author: Hans-Helge Buerger
 * Author URI: http://hanshelgebuerger.de
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 * Text Domain: child-page-tree
 * Domain Path: /languages
*/

namespace Child_Page_Tree;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Child_Page_Tree\Child_Page_Tree' ) ) {

	// Lets Start. Create new Child_Page_Tree object
	require_once( 'src/Child_Page_Tree.php' );
	$child_page_tree = new Child_Page_Tree();

	// Register Hooks: Doing this separated from constructor is good practice
	$child_page_tree->register_hooks();

}