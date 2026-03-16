<?php
/**
 * Plugin Name: Plugin Signature
 * Description: Add signature to articles
 * Author: Bruno GODBILLOT
 */

function bg_signature($content)
{
    $signature = '<p style="color= red;">Copyright '. get_bloginfo('name') . '2026. Tous droits réservés.</p>';
    
    if(is_single()){
        return $content . $signature;
    }    

    return $content;
}

add_filter('the_content', 'bg_signature');