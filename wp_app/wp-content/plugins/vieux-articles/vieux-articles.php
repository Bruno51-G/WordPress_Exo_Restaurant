<?php

/**
 * Plugin Name: Les vieux Articles
 * Description: Message d'avertissement sur les vieux articles
 * Author: Bruno GODBILLOT
 */

function bg_old_content($content)
{
    $delai = 24 * 7 * 60 * 60; //7 jours

    if(!is_single()){
        return $content;
    }
    
    $id = get_the_ID();
    $date = get_the_date('U');
    
    $dateAuj = date('U');

    $dateEcart = ($dateAuj - $date);

    if($dateEcart > $delai){
        $avertissement = '<div style="border: 2px solid red; color: red; background: orange;">Attention articles trop vieux !</div>';
        return $avertissement . $content;
    }

    return $content;
}

add_filter('the_content', 'bg_old_content');