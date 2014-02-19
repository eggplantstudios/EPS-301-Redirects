<?php
/**
 * 
 * EPS redirects
 * 
 * 
 * 
 * This plugin creates opengrah data for pages, posts and custom post types.
 * 
 * PHP version 5
 *
 *
 * @package    EPS 301 Redirects
 * @author     Shawn Wernig ( shawn@eggplantstudios.ca )
 * @version    1.3.4
 */


?>


<div id="eps-redirect-settings-pane" class="eps-tab">


<form method="post" action="">
   
    <table class="eps-redirect-table">
        <tr>
            <td class="eps-redirect-section-spacer">&nbsp;</td>
            <td>
                    <table class="eps-redirect-meta">
                        <tr><th>Test Urls?</th>
                            <td class="padding">
                                
                                <label><input type="checkbox" name="eps_redirect_settings[test_urls]" <?php echo( isset($settings['test_urls']) && $settings['test_urls'] == 'on' ) ? 'checked="checked"': null; ?>> Turn URL testing ON </label>
                                <br>
                                <small>This will test your redirects on the Admin Panel to help debug. Warning, this can severly affect page load times, please consider not using this if you have more than 20 redirects.</small>
                            </td>
                        </tr>
                    </table>
             </td>
        </tr>

    </table>
    <hr class="eps-divider">
    <p class="submit">
        <?php wp_nonce_field('eps_redirect_setting_nonce', 'eps_redirect_setting_nonce_submit');   ?>
        <input type="submit" name="eps_redirect_settings_submit" id="submit" class="button button-primary" value="Save Changes"/>
    </p>
</form>
</div>