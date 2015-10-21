<h2>Plugin Settings</h2>
<hr />
<form action="" name="dx_pvt_form_main" method="post" class="dx-pvm-form">
    <fieldset>
            <div class="form-row">
                <label for="dx_pvt_track_post"><?php _e( 'Track WP Posts : ', '' ); ?></label>
                <?php $dx_pvt_track_post = 0; if( isset($data['dx_pvt_track_post']) ){ $dx_pvt_track_post = intval($data['dx_pvt_track_post']);} ?>
                <input type="checkbox" value="1" name="dx_pvt_track_post" <?php checked( $dx_pvt_track_post, '1', TRUE ); ?> />
            </div>
    </fieldset>
    <fieldset>
            <div class="form-row">
                <label for="dx_pvt_track_pages"><?php _e( 'Track WP Pages : ', '' ); ?></label>
                <?php $dx_pvt_track_pages = 0; if( isset($data['dx_pvt_track_pages']) ){ $dx_pvt_track_pages = intval($data['dx_pvt_track_pages']);} ?>
                <input type="checkbox" value="1" name="dx_pvt_track_pages" <?php checked( $dx_pvt_track_pages, '1', TRUE ); ?> />
            </div>
    </fieldset>

    <fieldset>
            <div class="form-row">
                <label for="dx_pvt_shortcode_enable"><?php _e( 'Enable Shortcode : ', '' ); ?></label>
                <?php $dx_pvt_shortcode_enable = 0; if( isset($data['dx_pvt_shortcode_enable']) ){ $dx_pvt_shortcode_enable = intval($data['dx_pvt_shortcode_enable']);} ?>
                <input type="checkbox" value="1" name="dx_pvt_shortcode_enable" <?php checked( $dx_pvt_shortcode_enable, '1', TRUE ); ?> />
            </div>
    </fieldset>

    <fieldset>
            <div class="form-row">
                <label for="dx_pvt_shortcode_html"><?php _e( 'Shortcode Html : ', '' ); ?></label>
                <textarea name="dx_pvt_shortcode_html" cols="50" rows="4"><?php echo stripslashes(trim( $data['dx_pvt_shortcode_html'] )); ?></textarea>
                <?php _e( ' * <i>include <strong>%COUNT%</strong> to be replaced by code counter</i>.', ''); ?>
            </div>
    </fieldset>

    <fieldset>
            <div class="form-row">
                <label for="dx_pvt_tracking_interval"><?php _e( 'Refresh Interval (seconds): ', '' ); ?></label>
                <input type="text" name="dx_pvt_tracking_interval" value="<?php echo trim($data['dx_pvt_tracking_interval']); ?>">
                <?php _e( ' * <i>how long before the tracking data gets refreshed. </i>.', ''); ?>
            </div>
    </fieldset>

    <fieldset>
            <div class="form-row">
                <label for="dx_pvt_cache_enable"><?php _e( 'Enable Caching : ', '' ); ?></label>
                <?php $dx_pvt_cache_enable = 0; if( isset($data['dx_pvt_cache_enable']) ){ $dx_pvt_cache_enable = intval($data['dx_pvt_cache_enable']);} ?>
                <input type="checkbox" value="1" name="dx_pvt_cache_enable" <?php checked( $dx_pvt_cache_enable, '1', TRUE ); ?> />
            </div>
    </fieldset>

    <fieldset>
            <div class="form-row">
                <label for="submit_dx_pvt_options_main">&nbsp;</label>
                 <input class="button-primary" type="submit" name="submit_dx_pvt_options_main" value="Save Settings" />
            </div>
    </fieldset>

</form>