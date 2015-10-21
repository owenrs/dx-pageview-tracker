<?php

class DX_Pageview_Tracker_Piwik
{
    private $settings;

    function __construct(){

    }

    public function get_piwik_settings(){
        return $this->settings;
    }

    private function set_piwik_settings(){

        $settings = get_option( 'dx_pvt_form_piwik_settings', NULL );
        $this->settings =  unserialize( $settings );

    }

    private function set_piwik_env(){

    }

    private function cache_local_data(){

    }

    public function get_piwik_data(){

        $conditions = array(
                            'module'      => 'API',
                            'method'      => $this->settings['dx_pvt_piwik_method'],
                            'idSite'      => $this->settings['dx_pvt_piwik_site_id'],
                            'period'      => 'day', // change to range then provide timestamp - 30 mins?
                            'date'        => 'today',
                            'format'      => 'json',
                            'token_auth'  => $this->settings['dx_pvt_piwik_token'],
                           );

        foreach( $conditions as $param_k => $param_v ){
            $url_params[] = $param_k . '='. $param_v;
        }

        $url = $this->settings['dx_pvt_piwik_url'] . implode( "&", $url_params );

        // dont suppress
        // $response = @file_get_contents($url);
        $response = file_get_contents( $url );

        // file_get_contents returns FALSE if failure occurs
        if( ! $response ){
            // log errors?
            return array();
        }
        else {

            $response = json_decode( $response, true );

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

        // this should really be cached.
        // the last page activity should be accessible until the next refresh
        return $page_activity;
    }
}

/*********************************************************************************************/

include 'piwik_config.php';

// this will make a call everytime this page is accessed/called
// $page_activity should be in heartbeat mode,
// Piwik only refreshes data after 30s by default ( !recheck time piwik acct settings )
// get_piwik_API_data should only be called at least every 30s + 1s;
//
// ?? move get_piwik_API_data to separate file then cron?



if( !isset( $_POST['pwk_page_count_query']) ){
    header('400 Bad Request');
    exit();
}
else {

    /*
    if( ! isset( $_API_DATA['last_refresh'] ) ){
        $_API_DATA['last_refresh']  = time();
        $PWK_API_TRANSIENTS['list'] = get_piwik_API_data();
    }
    elseif( ($_API_DATA['last_refresh'] + PWK_REFRESH_INTERVAL ) < time() ){
    // echo "Memory Usage: " . (memory_get_usage()/1048576) . " MB \n";

    // echo  pwk_tst_get_users_by_url( $page_activity, $_POST['url'], intval( $_POST['interval']), $_POST['unique'] );
    // echo "Memory Usage: " . (memory_get_usage()/1048576) . " MB \n";
        $_API_DATA['last_refresh']  = time();
        $_API_DATA['list'] = get_piwik_API_data();
    }
    */
    // dont mind caching for now
    $PWK_API_TRANSIENTS['list'] = get_piwik_API_data();
    $PWK_API_TRANSIENTS['last_refresh'] = time();
    echo  pwk_tst_get_users_by_url( $PWK_API_TRANSIENTS['list'], $_POST['url'], intval( $_POST['interval']), $_POST['unique'] );
    exit();
}

/**
 * [get_piwik_API_data description]
 *
 * @return [type] [description]
 */
function get_piwik_API_data(){
    $conditions = array(
                        'module'      => 'API',
                        'method'      => PWK_SOURCE_METHOD,
                        'idSite'      => PWK_SITE_ID,
                        'period'      => 'day', // change to range then provide timestamp - 30 mins?
                        'date'        => 'today',
                        'format'      => 'json',
                        'token_auth'  => PWK_AUTH_TOKEN
                       );

    foreach( $conditions as $param_k => $param_v ){
        $url_params[] = $param_k . '='. $param_v;
    }

    $url = PWK_SERVICE_URL . implode( "&", $url_params );

    // dont suppress
    // $response = @file_get_contents($url);
    $response = file_get_contents($url);

    // file_get_contents returns FALSE if failure occurs
    if( ! $response ){
        // log errors?
        return array();
    }
    else {

        $response = json_decode( $response, true );

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

    // this should really be cached.
    // the last page activity should be accessible until the next refresh
    return $page_activity;
}



// http://forum.piwik.org/ test url

/**
 * just getting the number of users that visitted the given url.
 * additionally, we only count the visits that were made within a given time interval
 *
 * @param  [type]  &$page_activity [description]
 * @param  [type]  $url            [description]
 * @param  [type]  $within_time    [duration in seconds]
 * @param  boolean $unique         [only count unique visits, visits from same user - by ip - within interval doesnt get counted ]
 *
 * @return [type]                  [description]
 */
function pwk_tst_get_users_by_url( $page_activity, $url, $within_time, $unique = TRUE ){
    $count = 0;
    if( is_array( $page_activity ) && (count( $page_activity ) >= 0 ) ){
        foreach( $page_activity as $pa_key => $pa_values ){
            // not sure if this is set if the user is still active on page
            $time_spent = 1; // don't 0 this out. assume he sent at least 1 second after triggering page
            if( isset ($pa_values['timeSpent']) ){
                $time_spent = intval( $pa_values['timeSpent']);
            }

            if( pwk_tst_is_same_url( $pa_values['url'], $url ) && pwk_tst_is_within_interval( $pa_values['timestamp'], $time_spent, $within_time ) ){
                $count++;

                                // [url] => http://forum.piwik.org/read.php?15,127259,127259
                                // [pageTitle] => How to automatically anonymize visitor user_id
                 // echo '<p>'.$pa_values['url'] . '<===>' . $pa_values['user_num']. '</p>';
            }
        }
    }
    return $count;
}

// validation
function pwk_tst_is_same_url( $a, $b ){
    return strtolower(trim( $a )) == strtolower(trim( $b ));
}

function pwk_tst_is_within_interval( $time_visited, $time_spent, $time_valid_ago ){
    return ( time() - ( $time_visited + $time_spent ) ) <= $time_valid_ago;
}

/*
$test_url = 'http://forum.piwik.org/';
echo '<pre> visits for '. $test_url .' within 7200 (2 hrs) : unique user identifier <br />';
// print_r( pwk_tst_get_users_by_url( $page_activity,  $url, $within_time, TRUE ) ) ;
print_r( pwk_tst_get_users_by_url( $page_activity,  $test_url, 7200, TRUE ) ) ;
echo '</pre>';


echo '<pre> visits for ' .$test_url .'  within 1800 ( 30 mins ) : unique user identifier <br />';
// print_r( pwk_tst_get_users_by_url( $page_activity,  $url, $within_time, TRUE ) ) ;
print_r( pwk_tst_get_users_by_url( $page_activity,  $test_url, 1800, TRUE ) ) ;
echo '</pre>';


echo '<pre> visits for ' .$test_url .'  within 1800 ( 15 mins ) : unique user identifier <br />';
// print_r( pwk_tst_get_users_by_url( $page_activity,  $url, $within_time, TRUE ) ) ;
print_r( pwk_tst_get_users_by_url( $page_activity,  $test_url, 900, TRUE ) ) ;
echo '</pre>';
*/


