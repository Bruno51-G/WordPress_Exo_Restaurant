<?php

function bg_add_thumbnails()
{
    // activer des fonctionnalités
    add_theme_support('post-thumbnails');

}

add_action('after_setup_theme', 'bg_add_thumbnails');



function bg_theme_menu_sidebar()
{
    register_nav_menus([
        'main' => 'Menu principal',
        'foot' => 'Menu Bas de page'
    ]);
}

add_action('init', 'bg_theme_menu_sidebar');
