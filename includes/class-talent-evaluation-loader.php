<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Talent_Evaluation
 * @subpackage Talent_Evaluation/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Talent_Evaluation
 * @subpackage Talent_Evaluation/includes
 * @author     Jan Jäger <janjaeger2020@gmail.com>
 */
class Talent_Evaluation_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();
		$this->add_roles();
		$this->add_shortcodes();
	}

	/**
     * Hinzufügen der Benutzerrollen
     *
     * @since    1.0.0
     */
    public function add_roles() {
        // Fügen Sie die Benutzerrollen hinzu, wenn sie noch nicht existieren
        add_role('firmenkunde', 'Firmenkunde');
        add_role('dienstleister', 'Dienstleister');
    }

	/**
     * Hinzufügen der Shortcodes
     *
     * @since    1.0.0
     */
    public function add_shortcodes() {
		register_shortcodes_allgemein();
        register_shortcodes_firmenkunden();
		register_shortcodes_dienstleister();
		register_shortcodes_talents();
    }

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		$this->add_filter( 'login_redirect', $this, 'redirect_after_login', 10, 3 );
		$this->add_filter( 'show_admin_bar', $this, 'hide_wordpress_admin_bar');
		$this->add_filter( 'login_headertitle', $this, 'my_login_logo_url_title');
		$this->add_filter( 'login_headerurl', $this, 'my_login_logo_url');
		$this->add_filter( 'admin_title', $this, 'custom_title', 99);
		$this->add_filter( 'login_title', $this, 'custom_title', 99);
		$this->add_filter( 'swpm_registration_form_override', $this, 'custom_swpm_registration_form', 10, 2);
		
		$this->add_action( 'login_enqueue_scripts', $this, 'my_login_logo' );


		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}
		
		session_start();
	}

	function custom_swpm_registration_form($form, $level) {
		if ( isset( $_REQUEST['member_id'] ) ) {
			//This is a member profile edit action
			$record_id = sanitize_text_field( $_REQUEST['member_id'] );
			if ( ! is_numeric( $record_id ) ) {
				wp_die( 'Error! ID must be numeric.' );
			}
		}else{
			return "Manuelle Registrierung gesperrt!";
		}
		global $wpdb;
		$id = absint($record_id);
		$query  = "SELECT * FROM {$wpdb->prefix}swpm_members_tbl WHERE member_id = $id";
		$member = $wpdb->get_row( $query, ARRAY_A );
		if ( isset( $_POST['editswpmuser'] ) ) {
			$_POST['user_name'] = sanitize_text_field( $member['user_name'] );
			$_POST['email']     = sanitize_email( $member['email'] );
			foreach ( $_POST as $key => $value ) {
				$key = sanitize_text_field( $key );
				if ( $key == 'email' ) {
					$member[ $key ] = sanitize_email( $value );
				} else {
					$member[ $key ] = sanitize_text_field( $value );
				}
			}
		}
		extract( $member, EXTR_SKIP );
		ob_start();
		include TE_DIR.'swpm/register-form.php';
		$form = ob_get_clean();
		return $form;
	}	

	public function custom_title($origtitle) {
		// Entferne das Wort "WordPress" aus dem Seitentitel
		if ( $origtitle) {
			$origtitle = str_replace( 'WordPress', 'App', $origtitle );
		}
	
		return $origtitle;
	}

	public function my_login_logo() { 
		$logo_url = get_option('te_login_logo');
		if($logo_url){
			?>
			<style type="text/css">#login h1 a, .login h1 a {
				background-image: url("<?php echo $logo_url; ?>");
				height: auto;
				width: 235px;
				background-size: 235px auto;
				background-repeat: no-repeat;
				padding-bottom: 50px;
			}</style>
			<?php 
		}
	}
		

	public function my_login_logo_url() {
		return home_url();
	}

	public function my_login_logo_url_title() {
		$logo_title = get_option('te_login_title');
		if($logo_title){
			return $logo_title;
		}else{
			return 'Commit IQ';
		}
		
	}

	/**
     * Benutzer nach dem Login weiterleiten
     *
     * @param string $redirect_to
     * @param string $request
     * @param WP_User|WP_Error $user WP_User object if login was successful, WP_Error object otherwise.
     * @return string|void
     */
    public function redirect_after_login( $redirect_to, $request, $user ) {
        return get_user_home_url($user);
    }

	function hide_wordpress_admin_bar($hide){
		if (!current_user_can('administrator')) {
		return false;
		}
		return $hide;
		}
	/**
	 * WP Logo ausblenden
	 */
	public function customize_admin_bar() {
		global $wp_admin_bar;
		if (!is_user_logged_in()) {
			return;
		}

		// Benutzerrolle des eingeloggten Benutzers abrufen
		$user = wp_get_current_user();
		$user_roles = $user->roles;

		// Array mit den Benutzerrollen, für die das WordPress-Symbol ausgeblendet werden soll
		$roles_to_hide = array('firmenkunde', 'dienstleister');

		// Überprüfen, ob die Benutzerrolle des eingeloggten Benutzers in der Liste der Rollen zum Ausblenden enthalten ist
		foreach ($roles_to_hide as $role) {
			if (in_array($role, $user_roles)) {
				//Wenn die Rolle des Benutzers zum Ausblenden des Symbols berechtigt ist, wird das Symbol ausgeblendet
				$wp_admin_bar->remove_menu('wp-logo');
				$wp_admin_bar->add_node(array(
					'id' => 'custom_link_for_' . $role,
					'title' => 'Meine Seite', // Titel des Links
					'href' => get_user_home_url($user), // Umleitungsseite basierend auf der Benutzerrolle
					'parent' => 'top-secondary' // Position des Links in der Admin-Leiste (optional)
				));
				break; // Schleife beenden, wenn das Symbol ausgeblendet wurde
			}
		}
	}

}
