<?php
/**
 * 
 */
class AccessPress_Admin_Links extends AccessPress_Admin_Basic {
	
	function __construct() {
		
		$settings_field = 'member-access-links';
		$default_settings = array();
		
		$menu_ops = array(
			'submenu' => array(
				'parent_slug'	=> 'premise-member',
				'page_title'	=> 'Membership Link Manager',
				'menu_title'	=> 'Link Manager',
				'capability'	=> 'manage_options'
			)
		);
		
		$this->create( 'member-access-links', $menu_ops, array(), $settings_field );
		
		add_action( 'admin_init', array( $this, 'actions' ) );
		
	}
	
	function notices() {
		
		if ( ! accesspress_is_menu_page( $this->page_id ) )
			return;
			
		if ( isset( $_REQUEST['created'] ) && 'true' == $_REQUEST['created'] )
			echo '<div id="message" class="updated"><p><strong>' . __( 'Link created.', 'premise' ) . '</strong></p></div>';
		elseif ( isset( $_REQUEST['deleted'] ) && 'true' == $_REQUEST['deleted'] )
			echo '<div id="message" class="updated"><p><strong>' . __( 'Link deleted.', 'premise' ) . '</strong></p></div>';
		elseif ( isset( $_REQUEST['edited'] ) && 'true' == $_REQUEST['edited'] )
			echo '<div id="message" class="updated"><p><strong>' . __( 'Link edited.', 'premise' ) . '</strong></p></div>';
		
		if ( isset( $_REQUEST['error'] ) && 'true' == $_REQUEST['error'] )
			echo '<div id="message" class="error"><p><strong>' . __( 'Something went wrong. Please try again.', 'premise' ) . '</strong></p></div>';
		
	}
	
	function actions() {
		
		if ( ! accesspress_is_menu_page( $this->page_id ) )
			return;
			
		if ( isset( $_REQUEST['action'] ) && 'create' == $_REQUEST['action'] )
			$this->create_link( $_POST['create_link'] );
		elseif( isset( $_REQUEST['action'] ) && 'edit' == $_REQUEST['action'] )
			$this->edit_link( $_REQUEST['id'], $_POST['edit_link'] );
		elseif( isset( $_REQUEST['action'] ) && 'delete' == $_REQUEST['action'] )
			$this->delete_link( $_REQUEST['id'] );
		
	}
	
	function error() {
		
		accesspress_admin_redirect( $this->page_id, array( 'error' => 'true' ) );
		exit;
		
	}
	
	function create_link( $info = array() ) {
		
		$info = wp_parse_args( $info, array(
			'name' => '',
			'filename' => '',
			'delay' => 0,
			'access-levels' => array(),
		) );
		
		if ( ! $info['name'] || ! $info['filename'] || ! is_numeric( $info['delay'] ) )
			$this->error();
		
		$id = md5( time() );
		
		/** Merge new link */
		$links = wp_parse_args( (array) get_option( $this->settings_field ), array(
			$id => $info
		) );
		
		update_option( $this->settings_field, $links );
		
		accesspress_admin_redirect( $this->page_id, array( 'created' => 'true' ) );
		exit;
		
	}
	
	function edit_link( $id = '', $info = array() ) {
		
		if ( ! $id )
			$this->error();
		
		/** Merge Defaults */
		$info = wp_parse_args( $info, array(
			'name' => '',
			'filename' => '',
			'delay' => 0,
			'access-levels' => array(),
		) );
		
		if ( ! $info['name'] || ! $info['filename'] || ! is_numeric( $info['delay'] ) )
			$this->error();
			
		$links = get_option( $this->settings_field );
		
		if ( ! isset( $links[$id] ) )
			$this->error();
			
		/** Merge old and new */
		$links[$id] = wp_parse_args( $info, $links[$id] );
		
		update_option( $this->settings_field, $links );
		
		accesspress_admin_redirect( $this->page_id, array( 'view' => 'edit', 'id' => $id, 'edited' => 'true' ) );
		exit;
		
	}
	
	function delete_link( $id = '' ) {
		
		if ( ! $id )
			$this->error();
			
		$links = get_option( $this->settings_field );
		
		if ( array_key_exists( $id, (array) $links ) ) {
			unset( $links[$id] );
			update_option( $this->settings_field, $links );
		}
		else {
			$this->error();
		}
			
		accesspress_admin_redirect( $this->page_id, array( 'deleted' => 'true' ) );
		
	}
	
	function admin() {
	?>
		
		<div class="wrap">

			<?php screen_icon( 'link-manager' ); ?>
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			
			<?php
			$links = get_option( $this->settings_field );
			$menu_page_url = html_entity_decode( menu_page_url( $this->page_id, 0 ) );
			
			if ( isset( $_REQUEST['view'] ) && 'edit' == $_REQUEST['view'] ) {
				if ( ! isset( $_REQUEST['id'] ) || ! isset( $links[$_REQUEST['id']] ) )
					_e( 'Nice try, but you have to select a valid link to edit.', 'premise' );
				else
					require_once( PREMISE_MEMBER_INCLUDES_DIR . 'views/admin/links-admin-edit.php' );
			}
			else {
				require_once( PREMISE_MEMBER_INCLUDES_DIR . 'views/admin/links-admin.php' );
			}
			?>
		
		</div>
	
	<?php	
	}
	
}

add_action( 'init', 'accesspress_admin_links_init' );
/**
 * 
 */
function accesspress_admin_links_init() {
	
	new AccessPress_Admin_Links;
	
}