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
global $wp_rewrite; 

?>

<div class="wrap">
        <header id="eps-header">
        <div id="icon-eggplant">&nbsp;</div>
        <h2 class="eps-title"><?php echo self::$page_title; ?> <?php echo get_option( 'eps_redirects_version' ); ?></h2>         
        </header>
        
        <?php
        if( !isset($wp_rewrite->permalink_structure) || empty($wp_rewrite->permalink_structure) ) {
            echo '<div class="error clear"><div class="eps-padding">';
                echo '<strong>WARNING:</strong> EPS 301 Redirects requires that a permalink structure is set. The Default Wordpress permalink structure is not compatible. Please update the <a href="options-permalink.php" title="Permalinks">Permalink Structure</a>.</div>';
            echo '</div></div>';
        }
        ?>
        <div id="eps-tabgroup">
        
            <div id="eps-tab-nav">
                <a href="#eps-redirect-redirects" class="active eps-tab-nav-item">Redirects</a>
            </div>
            
            <div id="eps-tabs">
                    <?php include ( EPS_REDIRECT_PATH . 'templates/admin.redirects.php'  ); ?>
            </div>
        
        </div>
        <div id="donate-box">
            <div class="eps-padding">
            <p>Comments, questions, bugs and feature requests can be sent to: <a href="mailto:plugins@eggplantstudios.ca">plugins@eggplantstudios.ca</a></p>
            <hr>
            <h3>Please consider donating</h3>
            <p>Your donations help support future versions EPS 301 Redirects.</p>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <p>
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="2WC9XYFX49CSQ">
                    <input class="button button-secondary" type="submit" name="submit" value="Donate">
                </p>
            </form>
            </div>
        </div>
</div>
    
    
    
    
