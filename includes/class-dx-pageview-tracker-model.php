<?php
/**
 *
 */
class DX_Pageview_Tracker_Model
{

    private $form_defaults = array();
    /**
     * [__construct description]
     */
    public function __construct(){
        if( !$this->form_defaults ){
        //    $this->set_default_settings();
        }
    }


    public function get_default_settings( $key ){
        return $this->defaults[$key];
    }

    /**
     * Method called during install to set default admin form values
     */
    public function set_default_settings(){
        // these defaults should be created during instal
        $form_piwik = array( 'dx_pvt_form_piwik' => array(
                                                            'dx_pvt_piwik_url'              => 'http://demo.piwik.org/index.php?',
                                                            'dx_pvt_piwik_method'           => 'Live.getLastVisitsDetails',
                                                            'dx_pvt_piwik_site_id'          => 7,
                                                            'dx_pvt_piwik_token'            => 'anonymous',
                                                            'dx_pvt_piwik_mode'             => 1,
                                                            'dx_pvt_piwik_tracking_code'    => '<script></script>',
                                                            'dx_pvt_piwik_script_position'  => 'footer',
                                                            'dx_pvt_piwik_heartbeat_timer'  => 30
                                                          )
                            );
        $form_main  = array( 'dx_pvt_form_main' => array(
                                                            'dx_pvt_track_post'             => 1,
                                                            'dx_pvt_track_pages'            => 1,
                                                            'dx_pvt_shortcode_enable'       => 0,
                                                            'dx_pvt_shortcode_html'         => '<div>%count%</div>',
                                                            'dx_pvt_tracking_interval'      => 30,
                                                            'dx_pvt_cache_enable'           => 1
                                                        )
                            );

        $form_defaults = array_merge( $form_piwik, $form_main );

        // save this defaulots to db
        $this->add_options( $form_defaults['dx_pvt_form_piwik'], 'dx_pvt_form_piwik_settings' );
        $this->add_options( $form_defaults['dx_pvt_form_main'], 'dx_pvt_form_main_settings' );

        $this->form_defaults = $form_defaults;
    }

    public function get_formdata( $form ){


        $settings_key = strtolower( trim( $form ) ) . '_settings';
        $data = $this->get_options( $settings_key );
        return $data;

    }

    /**
     * [add_options description]
     *
     * @param [type] $data [description]
     * @param [type] $key  [description]
     */
    public function add_options( $data, $key ){

        update_option( $key, serialize( $data ) );

    }

    /**
     * [get_options description]
     *
     * @param  string $key  wp_options meta key for retrieving options
     *
     * @return array  key->value pairs
     */
    private function get_options( $key ){

        $data = get_option( $key, NULL );
        if( !is_null( $data ) ){
            return unserialize( $data );
        }

        return NULL;

    }
}