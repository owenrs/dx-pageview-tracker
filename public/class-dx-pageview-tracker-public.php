<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 * @author     Your Name <email@example.com>
 */
class DX_Pageview_Tracker_Public {

	private $remote_settings;
	private $local_settings;

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader class will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dx-pageview-tracker-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 */
		$local_settings = $this->get_local_settings();
		$remote_settings = $this->get_remote_settings();

		$refresh_interval = $this->get_optimal_refresh_interval();

		$handle = $this->plugin_name;
		$handle_external = $this->plugin_name . '_piwik_tracker_cookie';

		$parameters = array(
								'ajaxurl' => admin_url( 'admin-ajax.php' ),
								'interval' => $refresh_interval,
								'remote_url' => $remote_settings['dx_pvt_piwik_url'],
								'html_string' => $local_settings['dx_pvt_shortcode_html']
							);


		// script to run for ajax calls
		wp_register_script( $handle, plugin_dir_url( __FILE__ ) . 'js/dx-pageview-tracker-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $handle, 'PARAMS', $parameters );

		add_action( 'wp_ajax_nopriv_dx_pvt_ajax_request', array( $this, 'ajax_request_handler' ) );
		add_action( 'wp_ajax_dx_pvt_ajax_request', array( $this, 'ajax_request_handler' ) );

		$external_parameters = $this->prepare_external_parameters();

		// include the script on pages that you wish to be tracked
		wp_register_script( $handle_external, plugin_dir_url( __FILE__ ) . 'js/dx-pageview-tracker-piwik.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $handle_external, 'EXTERNAL_PARAMS', $external_parameters );

		if( is_single() &&  ( ! empty( $local_settings['dx_pvt_track_post']) )  ){
			// include the tracking js on the footer or header
			wp_enqueue_script( $handle_external );

			// include the ajax caller
			wp_enqueue_script( $handle );

		}

		if( is_page() &&  ( ! empty( $local_settings['dx_pvt_track_pages']) )  ){

			// include the tracking js on the footer or header
			wp_enqueue_script( $handle_external );

			// include the ajax caller
			wp_enqueue_script( $handle );

		}

	}

	private function prepare_external_parameters(){

		$remote_settings = $this->get_remote_settings();

		$dx_pvt_piwik_url 				= $this->remove_http( $remote_settings['dx_pvt_piwik_url'] );
		$dx_pvt_piwik_url 				= rtrim( $dx_pvt_piwik_url, '\/') ;

		$dx_pvt_piwik_site_id 				= intval( $remote_settings['dx_pvt_piwik_site_id'] );
		$dx_pvt_piwik_heartbeat_timer 	= intval( $remote_settings['dx_pvt_piwik_heartbeat_timer'] );

		$params = array(
						'dx_pvt_piwik_url' => $dx_pvt_piwik_url ,
						'dx_pvt_piwik_site_id' => $dx_pvt_piwik_site_id,
						'dx_pvt_piwik_heartbeat_timer' => $dx_pvt_piwik_heartbeat_timer
						);
		return $params;
	}

	private function remove_http( $url ){

	   $disallowed = array('http://', 'https://');
	   foreach( $disallowed as $d ){
	      if(strpos($url, $d) === 0) {
	         return str_replace($d, '', $url);
	      }
	   }
	   return $url;
	}
	/**
	 * [get_optimal_refresh_interval description]
	 *
	 * @return [type] [description]
	 */
	private function get_optimal_refresh_interval(){

		$local_settings = $this->get_local_settings();
		$remote_settings = $this->get_remote_settings();

		// time before new tracker data is available. default to 30 seconds
		$remote_interval = 30;
		if( isset( $remote_settings['dx_pvt_piwik_heartbeat_timer'] ) && ( intval( $remote_settings['dx_pvt_piwik_heartbeat_timer'] ) > 0 ) ){
			$remote_interval = $remote_settings['dx_pvt_piwik_heartbeat_timer'];
		}

		// time before the page wants a new set of data.
		$local_interval = $remote_interval + 1;
		if( isset( $local_settings['dx_pvt_tracking_interval'] ) && ( intval( $local_settings['dx_pvt_tracking_interval'] ) > 0 ) ){
			$local_interval = intval( $local_settings['dx_pvt_tracking_interval'] );
		}

		if( $local_interval >= $remote_interval ){
			return $local_interval;
		}

		return $remote_interval;
	}

	/**
	 *
	 *
	 * @return [type] [description]
	 */
	public function generate_tracking_data(){

		if( ! isset( $this->remote_settings) ){
			$this->set_remote_settings();
		}

		if( ! isset($this->remote_settings) ){
			$this->set_local_settings();
		}

		// careful when running this. we dont want this to run every pagecall.
		if( FALSE == $this->has_transients() ){
			$this->set_transients();
		}

	}

	/**
	 * this is when the api is called.
	 */
	private function set_transients(){

		$raw_remote_data = $this->get_piwik_data();
		$page_visits = $this->count_page_visits( $raw_remote_data );
		$expire_time = $this->local_settings['dx_pvt_tracking_interval']; // cache exipation in seconds

		set_transient( 'dx_pvt_remote_data', $page_visits, $expire_time );

	}

	public function get_transients(){
		return get_transient( 'dx_pvt_remote_data' );
	}

	/**
	 * check if we have transient data without getting the transient values
	 *
	 * @return boolean
	 */
	private function has_transients(){

		$transient_name = 'dx_pvt_remote_data';
		$data_timeout = get_option('_transient_timeout_' . $transient_name );

		if( $data_timeout > time() ){
			return TRUE;
		}
		return FALSE;

	}

	public function set_local_settings(){

		$this->local_settings = $this->get_local_settings();

	}

	private function get_local_settings(){

        $data = get_option( 'dx_pvt_form_main_settings', NULL );
        if( !is_null( $data ) ){
           return unserialize( $data );
        }

        return array();

	}

	public function set_remote_settings(){

		$this->remote_settings = $this->get_remote_settings();

	}

    public function get_remote_settings(){

        $data = get_option( 'dx_pvt_form_piwik_settings', NULL );
        if( !is_null( $data ) ){
           return unserialize( $data );
        }

        return array();
    }

	/**
	 * Getting data from remote source
	 *
	 * @return [type] [description]
	 */
	public function get_piwik_data(){
		// consider wp_remote_get
		// https://codex.wordpress.org/Function_Reference/wp_remote_get
		//
		$page_activity = array();
        $conditions = array(
                            'module'      => 'API',
                            'method'      => $this->remote_settings['dx_pvt_piwik_method'],
                            'idSite'      => $this->remote_settings['dx_pvt_piwik_site_id'],
                            'period'      => 'day', // change to range then provide timestamp - 30 mins?
                            'date'        => 'today',
                            'format'      => 'json',
                            'token_auth'  => $this->remote_settings['dx_pvt_piwik_token'],
                           );

        foreach( $conditions as $param_k => $param_v ){
            $url_params[] = $param_k . '='. $param_v;
        }

        $url = $this->remote_settings['dx_pvt_piwik_url'] . implode( "&", $url_params );

        // $response = @file_get_contents($url);
        $response = wp_remote_get( $url );

        if( is_wp_error( $response ) ){
            return array();
        }
        else {

            if( count( $response['body'] ) > 0 ){
	            $response = json_decode( $response['body'], true );


	            if( is_array( $response ) && ( count( $response ) > 0 ) ){
	                foreach( $response as $num_key => $general_data ){
	                                        // contains data for all pages visited by user
	                                        // [type] => action
	                                        // [url] => http://forum.piwik.org/read.php?15,127259,127259
	                                        // [pageTitle] => How to automatically anonymize visitor user_id
	                                        // [pageIdAction] => 1732370
	                                        // [serverTimePretty] => Oct 15, 2015 5:25:53 PM
	                                        // [pageId] => 73305894
	                                        // [generationTime] => 0.17s
	                                        // [icon] =>
	                                        // [timestamp] => 1444929953//
	                                        // echo '<br />'.$general_data[ $primary_column ][0]["url"] .' ====> '. date( 'M d, Y h:s a',  $general_data[ $primary_column ][0]['timestamp']);
	                    foreach( $general_data[ 'actionDetails' ] as $user_actions ){
	                        $user_actions['user_num']  = $num_key;
	                        $page_activity[] = $user_actions;
	                    }
	                }
	                unset( $response, $user_actions, $general_data );
	            }
        	}
        }

        // this should really be cached.
        // the last page activity should be accessible until the next refresh
        return $page_activity;

    }

    /**
     * [sort_remote_data description]
     *
     * @param  array $raw_remote_data  expecting a json decoded array
     *
     * @return array                   returns a nested array using hashed (md5) value of the urls as key
     */
	private function count_page_visits( $raw_remote_data ){

	    $pages = array();
	    if( is_array( $raw_remote_data ) && ( count( $raw_remote_data ) >= 0 ) ){
	        foreach( $raw_remote_data as $pa_key => $pa_values ){


	        	// we want to have an array of pages with their corresponding occurance count

	        	// the remote data tracks total time spent by the user on each page.
	        	// however, if the user is still on the page, this value wont be set
	        	$url_key = md5( $pa_values['url'] );

	            $time_spent = 0;
	            if( isset ($pa_values['timeSpent']) ){
	               $time_spent = intval( $pa_values['timeSpent']);
	            }

	            // if( $this->pwk_tst_is_within_interval( $pa_values['timestamp'], $time_spent, $within_time ) ){
	            // actually, if the site already knows how much time the user spent, then the user already left.
	            // if not, then the user is still on the page. so we count those.
	            if( $time_spent == 0  ){
	            	if( !isset( $pages[$url_key] ) ){
	                	$pages[$url_key]['url'] 	= $pa_values['url'];
	                	$pages[$url_key]['count'] 	= 1;
	            	}
	            	else {
	            		$pages[$url_key]['count'] ++;
	            	}
	            }
	        }
	    }
	    return $pages;

	}

	public function ajax_request_handler(){

		$url = $_POST['local_page']; // $_POST['local_page'];
		$count = $this->get_page_hits( $url );
		$response = json_encode( $count );

		header( "Content-Type: application/json");
		echo $response;
		exit();
	}

	private function get_page_hits( $url ){

		$url = strtolower( trim( $url ));
		$key = md5($url);

		$data = get_transient( 'dx_pvt_remote_data' );
		if( isset($data[$key]) ){
			return $data[$key]['count'];
		}

		return 1; // this is a cheat. when the page is openned, obvously, we get at least 1 hit.
	}

	/**
	*
	*
	*/
	public function display_shortcode(){

		$local_settings = $this->get_local_settings();

		if( ! isset( $local_settings['dx_pvt_shortcode_enable'] ) ){
			return;
		}

		$string = $local_settings['dx_pvt_shortcode_html'];

		if( strlen(trim($string)) <= 0 ){
			$string = '%count%';
		}

		// $string = str_replace();
		return;

	}

}
