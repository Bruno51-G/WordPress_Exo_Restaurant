<?php

// functions.php : fichier de fonctions du thème, appelé automatiquement par WordPress, pour ajouter des fonctionnalités au thème
function bg_add_thumbnails()
{
    // activer des fonctionnalités
    add_theme_support('post-thumbnails');
}

add_action('after setup_theme', 'bg_add_thumbnails'); // action qui se déclenche après l'initialisation du thème, pour activer les fonctionnalités (ici les vignettes/miniatures des articles)


// Enregistrer les zones de menu
function bg_theme_menu_sidebar()
{
    // enregistrement d'une zone de menu
    register_nav_menus([
        'main' => 'Menu principal'
    ]);
}

add_action('init', 'bg_theme_menu_sidebar'); // action qui se déclenche à l'initialisation de WordPress, pour enregistrer les zones de menu (ici une zone "Menu principal")