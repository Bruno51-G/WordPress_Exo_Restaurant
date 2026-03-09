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

    register_sidebar([
        'id' => 'main-sidebar',
        'name' => 'Sidebar Accueil',
        'before_widjet' => '<div class= "theme-widjet">',
        'after_widjet' => '</div>'

    ]);
}

add_action('init', 'bg_theme_menu_sidebar');
