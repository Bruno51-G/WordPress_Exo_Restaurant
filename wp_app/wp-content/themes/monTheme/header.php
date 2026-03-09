<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.png" />
    <link rel="stylesheet" href="<?= get_stylesheet_uri() ?>">
    <link rel="stylesheet" href="<?= get_stylesheet_directory_uri() ?>/menu.css">
    <link href="https://fonts.googleapis.com/css2?family=Asimovian&display=swap" rel="stylesheet">
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/monscript.js" defer></script>
    <title>Document</title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<header class="site-header">
    <p class="logoTitre"><?php bloginfo('name'); ?></p>
    <img class="logo" src="<?= get_stylesheet_directory_uri() ?>/img/wp_sushis_logo.jpg">
</header>

<nav>
    <a href="#" id="menuToggle">≡</a>
    <?php wp_nav_menu([
        'theme_location' => 'main'
    ]) ?>
</nav>

<main>

<!-- FIN HEADER -->
