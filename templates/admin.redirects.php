<?php
/**
 * 
 * EPS OPENGRAPH
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
<div id="eps-redirect-redirects-pane" class="eps-tab">

<form method="post" action="">
    <table id="eps-redirect-entries">
        <tr class="redirect-entry">
            <th>Status</th>
            <th>Request URL</th>
            <th>Redirect To</th>
            <th>Hits</th>
            <th>Actions</th>
        </tr>        <?php
        echo self::do_inputs();
        echo self::get_blank_entry();
        ?>
        <tr><td colspan="3"><a class="eps-text-link new" href="#" id="eps-redirect-add">+ Add Empty</a></td></tr>
    </table>
    <hr class="eps-divider">
    <p class="submit">
        <?php wp_nonce_field('eps_redirect_nonce', 'eps_redirect_nonce_submit');   ?>
        <input type="submit" name="eps_redirect_refresh" id="submit" class="button button-secondary" value="Refresh Cache"/>&nbsp;&nbsp;
        <input type="submit" name="eps_redirect_submit" id="submit" class="button button-primary" value="Save Changes"/>
        <p><small class="eps-grey-text">Refresh the cache if the dropdowns are out of date.</small></p>
    </p>
</form>

</div>