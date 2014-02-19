<?php
/**
 * 
 * EPS 301 REDIRECTS
 * 
 * 
 * 
 * This plugin creates a nice Wordpress settings page for creating 301 redirects on your Wordpress 
 * blog or website. Often used when migrating sites, or doing major redesigns, 301 redirects can 
 * sometimes be a pain - it's my hope that this plugin helps you seamlessly create these redirects 
 * in with this quick and efficient interface.
 * 
 * PHP version 5
 *
 *
 * @package    EPS 301 Redirects
 * @author     Shawn Wernig ( shawn@eggplantstudios.ca )
 * @version    2.0.1
 */


    
 
/*
Plugin Name: Eggplant 301 Redirects
Plugin URI: http://www.eggplantstudios.ca
Description: Create your own 301 redirects using this powerful plugin.
Version: 2.0.1
Author: Shawn Wernig http://www.eggplantstudios.ca
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

define ( 'EPS_REDIRECT_PATH', plugin_dir_path(__FILE__) );
define ( 'EPS_REDIRECT_URL', plugin_dir_url( __FILE__ ) );
define ( 'EPS_REDIRECT_VERSION', '2.0.1');

register_activation_hook(__FILE__, array('EPS_Redirects', 'eps_redirect_activation'));
register_deactivation_hook(__FILE__, array('EPS_Redirects', 'eps_redirect_deactivation'));

include(EPS_REDIRECT_PATH.'eps-form-elements.php');  
include(EPS_REDIRECT_PATH.'class.drop-down-pages.php');

class EPS_Redirects {
    
    static $option_slug = 'eps_redirects';
    static $option_section_slug = 'eps_redirects_list';
    static $page_slug = 'eps_redirects';
    static $page_title = '301 Redirects';



    /**
     * 
     * Constructor
     * 
     * Add some actions.
     * 
     */
    public function __construct(){
        if(is_admin()){
            add_action('activated_plugin', array($this,'activation_error'));
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, '_save'));
            add_action('init', array($this, 'enqueue_resources'));
            add_action('admin_footer_text',  array($this, 'set_ajax_url'));
            
            // Ajax funcs
            add_action('wp_ajax_eps_redirect_get_new_entry',  array($this, 'ajax_get_blank_entry') ); 
            add_action('wp_ajax_eps_redirect_delete_entry',  array($this, 'ajax_eps_delete_entry') );
            
            if( isset($_GET['page']) && $_GET['page'] == self::$page_slug) {
                add_action('admin_init', array($this, 'clear_cache'));
            }
        } else {
            add_action('init', array($this,'do_redirect'), 1); // Priority 1 for redirects.
        }
        
        if ( !self::is_current_version() )  self::update_self();
        
    }
    
    /**
     * 
     * 
     * Activation and Deactivation Handlers.
     * 
     * @return nothing
     * @author epstudios
     */
    public static function eps_redirect_activation() {
            self::update_self();
    }
    public static function eps_redirect_deactivation() {
    }
    
    function is_current_version(){
        $version = get_option( 'eps_redirects_version' );
        return version_compare($version, EPS_REDIRECT_VERSION, '=') ? true : false;
    }

     /**
     * 
     * CHECK VERSION
     * 
     * This function will check the current version and do any fixes required
     * 
     * @return string - version number.
     * @author epstudios
     *      
     */
    public function update_self() {
        $version = get_option( 'eps_redirects_version' );
        self::_create_tables(); // Maybe create the tables 

        if( version_compare($version, '2.0.0', '<')) {
            // migrate old format to new format.
            self::_migrate_to_v2();
        } 
        
        update_option( 'eps_redirects_version', EPS_REDIRECT_VERSION );
        return EPS_REDIRECT_VERSION;
    }
    
    /**
     * 
     * 
     * MIGRATE TO V2
     * 
     * Will migrate the old storage method to the new tables.
     * 
     */
    public function _migrate_to_v2() {
        $redirects = get_option( self::$option_slug );
        if (empty($redirects)) return false; // No redirects to migrate.
        
        $new_redirects = array();

        foreach ($redirects as $from => $to ) {
             $new_redirects[] = array(
                    'id'        => false,
                    'url_to'    => urldecode($to),
                    'url_from'  => $from,
                    'type'    => 'url',
                    'status'    => '301'
                );       
        }

        self::_save_redirects( $new_redirects );
            
        //update_option( self::$option_slug, null );
    }
    
    /**
     * 
     * CREATE TABLES
     * 
     * Creates the new database architecture
     * 
     * @return nothing
     * @author epstudios
     * 
     */
    private function _create_tables() {
       global $wpdb;
    
       $table_name = $wpdb->prefix . "redirects";
          
       $sql = "CREATE TABLE $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          url_from VARCHAR(256) DEFAULT '' NOT NULL,
          url_to VARCHAR(256) DEFAULT '' NOT NULL,
          status VARCHAR(12) DEFAULT '301' NOT NULL,
          type VARCHAR(12) DEFAULT 'url' NOT NULL,
          count mediumint(9) DEFAULT 0 NOT NULL,
          UNIQUE KEY id (id)
       );";
    
       require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
       dbDelta( $sql );
    }
    
    
    /**
     * 
     * ENQUEUE_RESOURCES
     * 
     * This function will queue up the javascript and CSS for the plugin.
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public function enqueue_resources(){
        wp_enqueue_script('jquery');
        wp_enqueue_script('eps_redirect_script', EPS_REDIRECT_URL .'js/scripts.js');
        wp_enqueue_style('eps_redirect_styles', EPS_REDIRECT_URL .'css/eps_redirect.css');
    }
    
    /**
     * 
     * ADD_PLUGIN_PAGE
     * 
     * This function initialize the plugin page.
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public function add_plugin_page(){
        add_options_page('301 Redirects', 'EPS 301 Redirects', 'manage_options', self::$page_slug, array($this, 'do_admin_page'));
    }
    
    /**
     * 
     * DO_REDIRECT
     * 
     * This function will redirect the user if it can resolve that this url request has a redirect.
     * 
     * @author epstudios
     *      
     */
    public function do_redirect() {
        
        $redirects = self::get_redirects( true ); // True for only active redirects.
        if (empty($redirects)) return false; // No redirects.
        
        // Get current url
        $url_request = self::get_url();

        foreach ($redirects as $redirect ) {
            $from = urldecode( $redirect->url_from );
            $to   = ($redirect->type == "url" && !is_numeric( $redirect->url_to )) ? urldecode($redirect->url_to) : get_permalink( $redirect->url_to );
                                
                if( $redirect->status != 'inactive' && rtrim( trim($url_request),'/')  === self::format_from_url( trim($from) )  ) {
                    // Match, this needs to be redirected
                    //increment this hit counter.
                    self::increment_field($redirect->id, 'count');
                    
                    if( $redirect->status == '301' ) {
                        header ('HTTP/1.1 301 Moved Permanently');
                    } elseif ( $redirect->status == '302' ) {
                        header ('HTTP/1.1 301 Moved Temporarily');
                    }
                    header ('Location: ' . $to, true, (int) $redirect->status); 
                    exit();
                }
                
        }
    }

    /**
     * 
     * FORMAT FROM URL
     * 
     * Will construct and format the from url from what we have in storage.
     * 
     * @return url string
     * @author epstudios
     * 
     */
    private function format_from_url( $string ) {
        $from = home_url() . '/' . $string;
        return strtolower( rtrim( $from,'/') );    
    }
    
    /**
     * 
     * GET_URL
     * 
     * This function returns the current url.
     * 
     * @return URL string
     * @author epstudios
     *      
     */
    function get_url() {
        $protocol = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) ? 'https' : 'http';
        return strtolower( urldecode( $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ) );
    }
        
        
    /**
     * 
     * DO_ADMIN_PAGE
     * 
     * This function will create the admin page.
     * 
     * @author epstudios
     *      
     */
    public function do_admin_page(){
        include ( EPS_REDIRECT_PATH . 'templates/admin.php'  );
    }
    
    /**
     * 
     * _SAVE
     * 
     * This function handles various POST requests.
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public function _save(){
       
       // Refresh the Transient Cache
       if ( isset( $_POST['eps_redirect_refresh'] ) && wp_verify_nonce( $_POST['eps_redirect_nonce_submit'], 'eps_redirect_nonce') )  {
           $post_types = get_post_types(array('public'=>true), 'objects');
            foreach ($post_types as $post_type ) {
                $options = eps_dropdown_pages( array('post_type'=>$post_type->name ) );
                set_transient( 'post_type_cache_'.$post_type->name, $options, HOUR_IN_SECONDS );
           }
       }
       
       // Save Redirects
       if ( isset( $_POST['eps_redirect_submit'] ) && wp_verify_nonce( $_POST['eps_redirect_nonce_submit'], 'eps_redirect_nonce') ) 
            $this->_save_redirects( self::_parse_serial_array($_POST['redirect']) );

    }
    
    /**
     * 
     * PARSE SERIAL ARRAY
     * 
     * A necessary data parser to change the POST arrays into save-able data.
     * 
     * @return array of redirects
     * @author epstudios
     * 
     */
    private function _parse_serial_array( $array ){
        $new_redirects = array();
        $total = count( $array['url_from'] );
        
        for( $i = 0; $i < $total; $i ++ ) {
            
            if( empty( $array['url_to'][$i]) || empty( $array['url_from'][$i] ) ) continue;
            $new_redirects[] = array(
                    'id'        => isset( $array['id'][$i] ) ? $array['id'][$i] : null,
                    'url_from'  => $array['url_from'][$i],
                    'url_to'    => $array['url_to'][$i],
                    'type'      => ( is_numeric($array['url_to'][$i]) ) ? 'post' : 'url',
                    'status'    => isset( $array['status'][$i] ) ? $array['status'][$i] : 'active'
                    ); 
        }
        return $new_redirects;
    }
    
    /**
     * 
     * SAVE REDIRECTS
     * 
     * Saves the array of redirects.
     * 
     * TODO: Maybe refactor this to reduce the number of queries.
     * 
     * @return nothing
     * @author epstudios
     */
    private function _save_redirects( $array ) {
       if( empty( $array ) ) return false;
       global $wpdb;
       $table_name = $wpdb->prefix . "redirects";
       
       foreach( $array as $redirect ) {
            if( !$redirect['id'] || empty($redirect['id']) ) {
                // new
                $wpdb->insert( 
                    $table_name, 
                    array( 
                        'url_from'      => trim( $redirect['url_from'] ),
                        'url_to'        => trim( $redirect['url_to']),
                        'type'          => trim( $redirect['type']),
                        'status'        => trim( $redirect['status'])
                    )
                );

            } else {
                // existing
                $wpdb->update( 
                    $table_name, 
                    array( 
                        'url_from'  => trim( $redirect['url_from']),
                        'url_to'    => trim( $redirect['url_to']),
                        'type'      => trim( $redirect['type']), 
                        'status'    => trim( $redirect['status'])
                    ), 
                    array( 'id' => $redirect['id'] )
                );
            }
            
        }
        
    }
    /**
     * GET REDIRECTS    
     * 
     * Gets the redirects. Can be switched to return Active Only redirects.
     * 
     * @return array of redirects
     * @author epstudios
     * 
     */
    public function get_redirects( $active_only = false ) {
        global $wpdb;
        $table_name = $wpdb->prefix . "redirects";
        $results = $wpdb->get_results( 
            "SELECT * FROM $table_name " . ( ( $active_only ) ? "WHERE status != 'inactive'" : null )
        );
        return $results;
    }
    
    /**
     * 
     * INCREMENT FIELD
     * 
     * Add +1 to the specified field for a given id
     * 
     * @return the result
     * @author epstudios
     * 
     */
    public function increment_field( $id, $field ) {
        global $wpdb;
        $table_name = $wpdb->prefix . "redirects";
        $results = $wpdb->query( "UPDATE $table_name SET $field = $field + 1 WHERE id = $id");
        return $results;
    }
    
    /**
     * 
     * DO_INPUTS
     * 
     * This function will list out all the current entries.
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public function do_inputs(){
        $redirects = self::get_redirects( );
        $html = '';
        if (empty($redirects)) return false;
        ob_start();
        foreach ($redirects as $redirect ) {           
            $dfrom = urldecode($redirect->url_from);
            $dto   = urldecode($redirect->url_to  );
            include( EPS_REDIRECT_PATH . 'templates/template.redirect-entry.php');
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    
    /**
     * 
     * DELETE_ENTRY
     * 
     * This function will remove an entry.
     * 
     * @return nothing 
     * @author epstudios
     *      
     */
    public static function ajax_eps_delete_entry(){
        if( !isset($_POST['id']) ) exit();
        
        global $wpdb;
        $table_name = $wpdb->prefix . "redirects";
        $results = $wpdb->delete( $table_name, array( 'ID' => intval( $_POST['id'] ) ) );
        echo json_encode( array( 'id' => $_POST['id']) );
        exit();
    }
    
    /**
     * 
     * GET_BLANK_ENTRY
     * AJAX_GET_BLANK_ENTRY
     * 
     * This function will return a blank row ready for user input.
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public static function get_blank_entry() {
        ob_start();
        include( EPS_REDIRECT_PATH . 'templates/template.redirect-entry-empty.php');
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
    
    public static function ajax_get_blank_entry() {
        echo self::get_blank_entry(); exit();
    }
    
    public function clear_cache() {
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Content-Type: application/xml; charset=utf-8");
    }
    
    
    /**
     * 
     * SET_AJAX_URL
     * 
     * This function will output a variable containing the admin ajax url for use in javascript.
     * 
     * @author epstudios
     *      
     */
    public static function set_ajax_url() {
        echo '<script>var eps_redirect_ajax_url = "'. admin_url( 'admin-ajax.php' ) . '"</script>';
    }
    
    public function activation_error() {
        file_put_contents(EPS_REDIRECT_PATH. '/error_activation.html', ob_get_contents());
    }
    
  
}



/**
 * Outputs an object or array in a readable form.
 *
 * @return void
 * @param $string = the object to prettify; Typically a string.
 * @author epstudios
 */
if( !function_exists('eps_prettify')) {
function eps_prettify( $string ) {
    return ucwords( str_replace("_"," ",$string) );
}
}

if( !function_exists('eps_view')) {
function eps_view( $object ) {
    echo '<pre>';
    print_r($object);
    echo '</pre>';   
}
}




// Run the plugin.
$EPS_Redirects = new EPS_Redirects();
?>