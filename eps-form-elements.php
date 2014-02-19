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
 * @version    1.4.0
 */

 /**
 * 
 * GET_TYPE_SELECT
 * 
 * This function will initialze a series of html form elements so a user can narrow down their redirect destination.
 * 
 * @return html string
 * @author epstudios
 *      
 */

function eps_get_selector( $redirect = false ) {
    $current_post = ( isset( $redirect->url_to ) && is_numeric( $redirect->url_to ) ) ? get_post( intval( $redirect->url_to ) ) : null;
    
    $post_types = get_post_types(array('public'=>true), 'objects');
    
    
    $html = eps_get_type_select($post_types, $current_post);
    
    
    
    // The default input, javascript will populate this input with the final URL for submission.
    $html .= '<input class="eps-redirect-url" 
                     type="text"
                     name="redirect[url_to][]"  
                     value="'.( isset( $redirect->url_to ) ? $redirect->url_to : null ).'" 
                     placeholder="'.get_bloginfo('home').'" '.
                     ( ( isset( $redirect->type ) && $redirect->type != 'post' || !isset($redirect->type) ) ? null : ' style="display:none;"' ).
                     '/>';
    
   
    // Get all the post type select boxes.
    foreach ($post_types as $post_type ) {
        $html .= eps_get_post_type_selects($post_type->name, $current_post);
    }
        //$html .=  
    
    // Get the term select box.
    $html .= eps_get_term_archive_select();
    return $html;
    
 
}


function eps_get_post_type_selects( $post_type, $current_post = false ) {
    // Start the select.
    $html = '<select class="'.$post_type.' url-selector" '.( (isset($current_post) && $current_post->post_type == $post_type ) ? null : 'style="display:none;"').'>';
    $html .= '<option value="">...</option>';
    
    if ( false === ( $options = get_transient( 'post_type_cache_'.$post_type ) ) ) {
        $options = eps_dropdown_pages( array('post_type'=>$post_type ) );
        set_transient( 'post_type_cache_'.$post_type, $options, HOUR_IN_SECONDS );
    }    
    
    foreach( $options as $option => $value ) {
        $html .= '<option value="'.$value.'" '.( isset($current_post) && $current_post->ID == $value ? 'selected="selected"' : null ).' >'. ( strlen($option) > 32 ? substr($option,0,32).'...' : $option ) . '</option>';
    }
    $html .= '</select>';
    return $html;
    
}
function eps_get_type_select( $post_types, $current_post = false ){
    $html = '<select class="type-select">';
    $html .= '<option value="eps-redirect-url">Custom</option>';
    
    foreach ($post_types as $post_type ) {
        $html .= '<option value="'.$post_type->name.'" '.( isset( $current_post ) && $current_post->post_type == $post_type->name  ? 'selected="selected"' : null).'>'. $post_type->labels->singular_name. '</option>';
    }
    $html .= '<option value="term">Term Archive</option>';
    $html .= '</select>';
    return $html;
}


    /**
     * 
     * GET_TERM_ARCHIVE_SELECT
     * 
     * This function will output a select box with all the taxonomies and terms.
     * 
     * @return html string
     * @author epstudios
     *      
     */
    function eps_get_term_archive_select(){
        $taxonomies = get_taxonomies( '', 'objects' );
        
        if (!$taxonomies) return false;
        
        // Start the select.
        $html = '<select class="term url-selector" style="display:none;">';
        $html .= '<option value="" selected default>...</option>';
        
        // Loop through all taxonomies.
        foreach ($taxonomies as $tax ) {
            $terms = get_terms( $tax->name, array('hide_empty' => false) ); // show empty terms.
            $html .= '<option value="'.$tax->name.'" disabled>'. $tax->labels->singular_name. '</option>';
            
            // Loop through all terms in this taxonomy and insert them as options.
            foreach($terms as $term)
                $html .= '<option value="'.get_term_link($term).'">&nbsp;&nbsp;- '. $term->name. '</option>';
            
        }
        $html .= '</select>';
        return $html;
    }
?>