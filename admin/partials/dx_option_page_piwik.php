<h2>Piwik Settings</h2>
<hr />
<form action="" name="dx_pvt_form_piwik" method="post" class="dx-pvm-form">
    <fieldset>
            <div class="form-row">
                <label for="dx_pvt_piwik_url"><?php _e( 'Piwik Service URL : ', '' ); ?></label>
                <input type="text" name="dx_pvt_piwik_url" value="<?php echo $data['dx_pvt_piwik_url']; ?>">
            </div>
    </fieldset>
    <fieldset>
            <div class="form-row">
                <label for="dx_pvt_piwik_method"><?php _e( 'Piwik method : ', '' ); ?></label>
                <input type="text" name="dx_pvt_piwik_method" value="<?php echo $data['dx_pvt_piwik_method']; ?>">
            </div>
    </fieldset>
    <fieldset>
            <div class="form-row">
                <label for="dx_pvt_piwik_site_id"><?php _e( 'Piwik Site ID : ', '' ); ?></label>
                <input type="text" name="dx_pvt_piwik_site_id" value="<?php echo $data['dx_pvt_piwik_site_id']; ?>">
            </div>
    </fieldset>
    <fieldset>
            <div class="form-row">
                <label for="dx_pvt_piwik_token"><?php _e( 'Auth token : ', ''); ?></label>
                <input type="text" name="dx_pvt_piwik_token" value="<?php echo $data['dx_pvt_piwik_token']; ?>">
            </div>
    </fieldset>
    <!--
    <fieldset>
            <div class="form-row">
                <label for="dx_pvt_piwik_mode"><?php _e('Source Mode : ', ''); ?></label>

                <select name="dx_pvt_piwik_mode">
                <?php // do i need to save these somewhere then loop the retrieved value? not for now // ?>
                    <option value="1" <?php selected( $data['dx_pvt_piwik_mode'], 1 ); ?>>Self hosted - HTTP API</option>
                    <option value="2" <?php selected( $data['dx_pvt_piwik_mode'], 2 ); ?>>Self hosted - PHP API</option>
                    <option value="3" <?php selected( $data['dx_pvt_piwik_mode'], 3 ); ?>>Cloud hosted - Piwik PRO</option>
                </select>
            </div>
    </fieldset>
    -->
    <fieldset>
            <div class="form-row">
                <label for="dx_pvt_piwik_tracking_code"><?php _e('Tracking Code : ', ''); ?></label>
                <textarea name="dx_pvt_piwik_tracking_code" cols="50" rows="10"><?php echo stripslashes(trim( $data['dx_pvt_piwik_tracking_code'] )); ?></textarea>
            </div>
    </fieldset>

    <!--
    <fieldset>
            <div class="form-row">
                <label for="dx_pvt_piwik_script_position"><?php _e('Include tracking code with : ', ''); ?></label>

                <select name="dx_pvt_piwik_script_position">
                    <option value="header" <?php selected( $data['dx_pvt_piwik_script_position'], 'header' ); ?>>Header scripts</option>
                    <option value="footer" <?php selected( $data['dx_pvt_piwik_script_position'], 'footer' ); ?>>Footer scripts</option>
                </select>
            </div>
    </fieldset>
    -->

    <fieldset>
            <div class="form-row">
                <label for="dx_pvt_piwik_heartbeat_timer"><?php _e( 'Heartbeat Timer (seconds) : ', '' ); ?></label>
                <input type="text" name="dx_pvt_piwik_heartbeat_timer" value="<?php echo trim( $data['dx_pvt_piwik_heartbeat_timer'] ); ?>">
            </div>
    </fieldset>

    <fieldset>
            <div class="form-row">
                <label for="submit_dx_pvt_options_piwik">&nbsp;</label>
                 <input class="button-primary" type="submit" name="submit_dx_pvt_options_piwik" value="Save Piwik Settings" />
            </div>
    </fieldset>

</form>

