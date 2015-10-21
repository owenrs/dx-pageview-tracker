<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class DX_Pageview_Tracker_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Available subpages on the admin menu settings page
	 *
	 * @var array  $option_pages  available settings subpages.
	 */
	private $option_pages = array( 	'main'			=>'active',
									// 'settings'		=>'inactive',
									'piwik'			=>'active',
									// 'performance' 	=>'inactive',
								 );

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dx-pageview-tracker-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dx-pageview-tracker-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Callback function. create the plugin's settings menu
	 *
	 * @since    1.0.0
	 */
    public function add_plugin_menu(){

        add_options_page(   'Settings Page ',
                            'DX PVT Settings',
                            'manage_options',
                            'dx-pvt-settings',
                            array( &$this, 'create_settings_page' )
                        );

    }

    /**
     * Pulls all active option subpages from admin/partials directory
     *
     * @return void
     */
    public function create_settings_page(){

    	include ( dirname( __FILE__ ) . DS . 'class-dx-pageview-post-handler.php' );

    	$forms         = array_keys( $this->option_pages );
    	$settings      = new DX_Pageview_Settings_Handler( $_POST, $forms );

    	// include the main html file
    	include ( dirname( __FILE__ ) . DS . 'partials ' . DS . 'dx-pageview-tracker-admin-display.php' );

    	// loop through each individual subpage ( settings tab )
	    foreach( $this->option_pages as $subpage => $status ):

	    	// exclude inactive tabs
	    	if( 'inactive' !== $status ){

		        $file = dirname( __FILE__ ) . DS . 'partials'. DS .'dx_option_page_'. $subpage . '.php';

		        if ( file_exists( $file ) ){

                    // get the data for the html files
                    $data = $settings->get_formdata( $subpage );
		            include( $file );

		        }

		        unset( $file, $data );
	    	}
	    endforeach;

    }

    /**
     * This method is left unused. It was originally added on the presumption the the settings page would be tabbed
     *
     * @param string $page   the subpage
     * @param string $status status of the subpage you wish to reset to. valid: active, inactive, current
     * @return void
     */
    private function set_current_option_page( $page, $status = 'current' ){

    	$valid_status = array( 'active', 'inactive', 'current' );
    	$page = strtolower( trim( $page ) );
    	$status = strtolower( trim( $status ) );

    	// if this nethod was called with an invalid status, set the status to inactive
    	if( ! in_array( $status, $valid_status ) ){
    		$status = 'inactive';
    	}


    	if( in_array( $page, array_keys( $this->option_pages )  ) ){
    		foreach( $this->option_pages as $sub_page => $status ){

  				$temp[ $sub_page ] = 'active';

   				if( $page == $sub_page ){
   					$temp[ $sub_page ] = $status;
   				}
    		}
    	}

    	$this->option_pages = $temp;

    }
}