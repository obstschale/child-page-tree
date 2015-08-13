<?php
/**
 * Created by PhpStorm.
 * User: RetinaObst
 * Date: 13.08.15
 * Time: 11:45
 */

namespace Child_Page_Tree;

class Child_Page_Tree {

	protected $tree_location;

	public function register_hooks() {
		add_action( 'post_submitbox_misc_actions', [ $this, 'render_select_box' ] );
		add_action( 'save_post', [ $this, 'save_child_page_tree_setting' ], 10, 3 );
		add_filter( 'the_content', [ $this, 'render_child_page_tree' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_style' ] );
	}

	public function render_child_page_tree( $content ) {

		// assuming you have created a page/post entitled 'debug'
		if ( is_page() ) {
			$post_id = get_the_ID();
			$this->tree_location = get_post_meta( $post_id, 'child_page_tree_action', true );

			if ( $this->tree_location == '' || $this->tree_location == 'none' )
				return $content;

			// Build Tree
			$tree = $this->get_child_page_tree_template( $post_id );

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

	function render_select_box() {
		$screen = get_current_screen();
		if ( $screen->id !== 'page' )
			return 0;

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

	public function save_child_page_tree_setting( $post_id, $post, $update ) {

		if ( $post->post_type !== 'page' ) {
			return 0;
		}

		if ( isset( $_REQUEST[ 'child_page_tree_action' ] ) ) {
			update_post_meta( $post_id, 'child_page_tree_action', sanitize_text_field( $_REQUEST[ 'child_page_tree_action' ] ) );
		}
	}

	private function get_child_page_tree_template( $post_id ) {
		$args = [
			'echo' => 0,
			'child_of' => $post_id,
			'title_li' => ''
		];
		$list = apply_filters( 'child_page_tree_output', wp_list_pages( $args ), $post_id );

		$class = $this->tree_location;
		return "<ul id='child_page_tree' class='{$class}'>" . $list . "</ul>";
	}

	public function enqueue_style() {

		// Load Style only in Frontend
		if ( is_admin() ) return 0;

		$url = plugins_url( 'assets/css/child-page-tree.css', __FILE__ );
		wp_register_style( 'child_page_tree_style', $url );
		wp_enqueue_style( 'child_page_tree_style' );
	}
}