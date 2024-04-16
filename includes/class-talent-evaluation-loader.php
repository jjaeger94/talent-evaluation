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
        register_shortcodes_firmenkunden();
		register_shortcodes_dienstleister();
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
		$this->add_filter( 'query_vars', $this, 'register_query_vars');
		$this->add_filter( 'show_admin_bar', $this, 'hide_wordpress_admin_bar');
		$this->add_filter( 'login_headertitle', $this, 'my_login_logo_url_title');
		$this->add_filter( 'login_headerurl', $this, 'my_login_logo_url');
		// $this->add_action( 'wp_before_admin_bar_render', $this, 'customize_admin_bar' );
		$this->add_action( 'login_enqueue_scripts', $this, 'my_login_logo' );
		$this->add_action( 'init', $this, 'register_pdf_viewer_rewrite_rule' );
		$this->add_action( 'template_redirect', $this, 'pdf_viewer_template_redirect' );
		$this->add_action('show_user_profile', $this, 'custom_user_fields');
        $this->add_action('edit_user_profile', $this, 'custom_user_fields');
		$this->add_action( 'personal_options_update', $this, 'save_custom_user_fields' );
		$this->add_action( 'edit_user_profile_update', $this, 'save_custom_user_fields' );


		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

	}

	public function my_login_logo() { 
		$logo_url = get_option('te_login_logo');
		if($logo_url){
			?>
			<style type="text/css">#login h1 a, .login h1 a {
				background-image: url("<?php echo $logo_url; ?>");
				height: 150px;
				width: 235px;
				background-size: 235px 150px;
				background-repeat: no-repeat;
				padding-bottom: 30px;
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

	// Funktion zum Anzeigen benutzerdefinierter Felder auf der Benutzerbearbeitungsseite
	public function custom_user_fields($user) {
		?>
		<h3>Talent Evaluation</h3>
		<table class="form-table">
			<tr>
				<th><label for="company">Firma</label></th>
				<td>
					<input type="text" name="company" id="company" value="<?php echo esc_attr(get_user_meta($user->ID, 'company', true)); ?>" class="regular-text">
				</td>
			</tr>
		</table>
		<?php
	}

	// Funktion zum Speichern benutzerdefinierter Felder
	public function save_custom_user_fields($user_id) {
		if (!current_user_can('edit_user', $user_id)) {
			return false;
		}

		update_user_meta($user_id, 'company', $_POST['company']);
	}

	public function register_pdf_viewer_rewrite_rule() {
		add_rewrite_rule('^pdf-viewer-page/?$', 'index.php?pdf_viewer_page=1', 'top');
	}

	public function register_query_vars($vars) {
		$vars[] = 'pdf_viewer_page';
		return $vars;
	}

	public function pdf_viewer_template_redirect() {
		if (get_query_var('pdf_viewer_page')) {
			include(plugin_dir_path( dirname( __FILE__ ) ) . 'pdf-viewer.php');
			exit;
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
