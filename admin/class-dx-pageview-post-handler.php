<?php
class DX_Pageview_Settings_Handler
{

    private $posts;
    private $forms;
    private $model;
    /**
     * [__construct description]
     *
     * @param [type] $posts [description]
     * @param [type] $forms [description]
     */
    function __construct( $posts, $forms ){
        $this->set_posts( $posts );
        $this->set_forms( $forms );
        $this->set_model();
        $this->handle_form_submissions();
    }

    /**
     * [set_forms description]
     *
     * @param [type] $forms [description]
     */
    private function set_forms( $forms ){
        $this->forms = $forms;
    }

    /**
     * [set_posts description]
     *
     * @param array $posts [description]
     */
    private function set_posts( $posts = array() ){
        /**
         * [dx-pvt-piwik-url] => aaaaaaaaaaa
         * [dx-pvt-piwik-token] => abbbbbbbbbbbbb
         * [dx-pvt-piwik-mode] =>
         * [dx-pvt-piwik-tracking-code] => ccccccccccccccc
         * [dx-pvt-piwik-script-position] =>
         * [dx_pvt_heartbeat_timer] => eeeeeeeeeeeeeee
         * [submit-dx-options-piwik] => Save Piwik Settings
         */

        if( is_array( $posts ) && !empty( $posts ) ){
            $this->posts = $posts;
        }
    }

    private function handle_form_submissions(){

        $submitted_form = $this->get_last_form_submit();

        if( ! is_null( $submitted_form ) ){



            $settings_key = 'dx_pvt_form_'. strtolower( trim( $submitted_form ) ) . '_settings';
            $submit_button_name = 'submit_dx_pvt_options_'. $submitted_form;
            //$meta = $this->validate_posts();
            //$data = $this->get_db_valid_data();

            if( isset( $this->posts[$submit_button_name] ) ){
                unset( $this->posts[$submit_button_name] );

                $this->model->add_options( $this->posts,  $settings_key );
            }
        }

    }

    private function get_db_valid_data(){

    }

    private function validate_posts(){
    }


    /**
     * [get_last_form_submit description]
     *
     * @return [type] [description]
     */
    public function get_last_form_submit(){

        foreach( $this->forms as $key ){

            // by convention, our submit buttons should follow this format
            $test_submit_key = 'submit_dx_pvt_options_'. $key;
            if( isset( $this->posts[$test_submit_key] ) && ! empty( $this->posts[$test_submit_key]) ){
                return $key;
                break;
            }
        }
        return NULL;
    }

    /**
     * [get_formdata description]
     *
     * @param  [type] $form [description]
     *
     * @return [type]       [description]
     */
    public function get_formdata( $form ){

        $key = 'dx_pvt_form_'. strtolower(trim($form));
        return $this->model->get_formdata( $key );

    }

    /**
     * [set_model description]
     */
    private function set_model(){
        require_once ( dirname( dirname( __FILE__ ) ) . DS . 'includes' .DS .'class-dx-pageview-tracker-model.php' );
        $this->model = new DX_Pageview_Tracker_Model();
    }

}