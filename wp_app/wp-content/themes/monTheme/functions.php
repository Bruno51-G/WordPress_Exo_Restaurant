<?php

function bg_add_thumbnails()
{
    // activer des fonctionnalités
    add_theme_support('post-thumbnails');

}

add_action('after_setup_theme', 'bg_add_thumbnails');