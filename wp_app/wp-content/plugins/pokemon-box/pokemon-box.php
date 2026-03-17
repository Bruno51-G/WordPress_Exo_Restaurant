<?php
/**
 * Plugin Name: La boite Pokemon de Bruno
 * Dercription: Devine ! 
 * Author: Bruno GODBILLOT
 */



function md_pokemon_box($atts, $content) {

    if(!empty($atts['couleur'])) {
        $couleur = $atts['couleur'];
    }
    else {
        $couleur = 'red';
    }

    $content = '<div style="padding: 1rem; border: 2px solid '.$couleur.';">' .$content .'</div>';
    return $content;
}

/**
 * 
 */
add_shortcode('pokemon_info', 'md_pokemon_box');