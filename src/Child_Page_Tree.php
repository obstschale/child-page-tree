<?php

namespace Child_Page_Tree;

/**
 * Class Child_Page_Tree
 *
 * This class registers needed hooks, creates dropdown in backend, performs the logic and creates tree in frontend
 *
 * @author Hans-Helge Buerger
 * @since 1.0.0
 * @package Child_Page_Tree
 */
class Child_Page_Tree {

	/**
	 * @var string|null $tree_location page meta value where tree should be printed on page
	 */
	protected $tree_location;

	/**
	 * Method registers all needed hooks for this plugin
	 *
	 * Using an own method for registering hooks than doing this in the constructor has several advantages.
	 * The main advantage for me is to make it easier for unit testing this plugin. The class can be instantiated
	 * without executing add_action or add_filter, which are not needed when unit testing
	 */
	public function register_hooks() {

		// Action to add the select box in page edit site in backend
		add_action( 'post_submitbox_misc_actions', [ $this, 'render_select_box' ] );

		// Action to save page and so saving our own post_meta
		add_action( 'save_post', [ $this, 'save_child_page_tree_setting' ], 10, 2 );

		// Filter to alter the content. Display the page tree
		add_filter( 'the_content', [ $this, 'add_child_page_tree_to_content' ] );

		// Action tor enqueue custom css
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_style' ] );

	}

	/**
	 * Method to decide whether or not adding a page tree to content.
	 *
	 * This method checks if the content belongs to a page and if the page meta is set to add a page tree.
	 * If so a switch statement is used to prepend or append the tree.
	 *
	 * @param  string $content  original post content
	 * @return string           Content
	 */
	public function add_child_page_tree_to_content( $content ) {

		// assuming you have created a page/post entitled 'debug'
		if ( is_page() ) {
			// Get page id and retrieve page meta value for page tree
			$post_id = get_the_ID();
			$this->tree_location = get_post_meta( $post_id, 'child_page_tree_action', true );

			// exit if no page tree should be added
			if ( $this->tree_location == '' || $this->tree_location == 'none' )
				return $content;

			// Build Tree
			$tree = $this->get_child_page_tree_template( $post_id );

			// Decide where to add the tree
			switch ( $this->tree_location ) {
				case 'prepend':
					return $tree . $content;
					break;

				case 'append':
					return $content . $tree;
					break;

				default:
					return $content;
			}
		}

		return $content;

	}

	/**
	 * Method adds new select box to backend on page edit sites
	 *
	 * @return int  exit if not page edit site
	 */
	function render_select_box() {

		// Get current screen and exit if user is not on page edit site
		$screen = get_current_screen();
		if ( $screen->id !== 'page' )
			return 0;

		// Get page meta value to set select box to according status
		$post_id = get_the_ID();
		$action = get_post_meta( $post_id, 'child_page_tree_action', true );
		if ( $action == '' ) $action = 'none';

		?>
		<div class="misc-pub-section my-options">
			<label for="child_page_tree_action"><i class="dashicons-before dashicons-palmtree"></i> <?php _e( 'Add Child Page Tree', 'child-page-tree' ); ?></label><br/>
			<select id="child_page_tree_action" name="child_page_tree_action">
				<option value="none" <?php echo ( $action == 'none' ) ? 'selected' : ''; ?>>
					<?php _e( 'Do Nothing', 'child-page-tree' ); ?>
				</option>
				<option value="prepend" <?php echo ( $action == 'prepend' ) ? 'selected' : ''; ?>>
					<?php _e( 'Prepend Child Page Tree', 'child-page-tree' ); ?>
				</option>
				<option value="append" <?php echo ( $action == 'append' ) ?  'selected' : ''; ?>>
					<?php _e( 'Append Child Page Tree', 'child-page-tree' ); ?>
				</option>
			</select>
		</div>
		<?php

	}

	/**
	 * Method saves the page meta value 'child_page_tree_action'
	 *
	 * @param int    $post_id  ID of current post to save
	 * @param object $post     post object of current post
	 * @return int             return 0 if not post type page
	 */
	public function save_child_page_tree_setting( $post_id, $post ) {

		// Exit if not page
		if ( $post->post_type !== 'page' ) {
			return 0;
		}

		// Save custom meta value for this page
		if ( isset( $_REQUEST[ 'child_page_tree_action' ] ) ) {
			update_post_meta( $post_id, 'child_page_tree_action', sanitize_text_field( $_REQUEST[ 'child_page_tree_action' ] ) );
		}

	}

	/**
	 * Method creates the child page tree as HTML list
	 *
	 * @param  int    $post_id  ID of current page
	 * @return string           HTML list of child pages
	 */
	private function get_child_page_tree_template( $post_id ) {

		$args = [
			'echo' => 0,
			'child_of' => $post_id,
			'title_li' => ''
		];
		/**
		 * Filter to alter the child page tree list
		 *
		 * @since 1.0.0
		 */
		$list = apply_filters( 'child_page_tree_output', wp_list_pages( $args ), $post_id );

		$class = $this->tree_location;
		return "<ul id='child_page_tree' class='{$class}'>" . $list . "</ul>";

	}

	/**
	 * Method to add custom CSS for frontend
	 *
	 * @return int  only used if in backend to skip adding frontend style
	 */
	public function enqueue_style() {

		// Load Style only in Frontend
		if ( is_admin() ) return 0;

		$url = plugins_url( 'assets/css/child-page-tree.css', __FILE__ );
		wp_register_style( 'child_page_tree_style', $url );
		wp_enqueue_style( 'child_page_tree_style' );

	}
}