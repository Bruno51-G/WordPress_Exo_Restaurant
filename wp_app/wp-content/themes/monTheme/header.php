<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.png" />
    <link rel="stylesheet" href="<?= get_stylesheet_uri() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Asimovian&display=swap" rel="stylesheet">
    <title>Document</title>
</head>
<body>

<header>
    <img class="bg" src="<?= get_stylesheet_directory_uri() ?>/img/9f6ffa22-83eb-4cd9-b44f-b949f2ef285f.jpg">
    <p class="logoTitre"><?php bloginfo('name'); ?></p>
    <img class="logo" src="<?= get_stylesheet_directory_uri() ?>/img/wp_sushis_logo.jpg">
</header>

<?php wp_nav_menu([
    'theme_location' => 'main'
]) ?>

<main>

<!-- FIN HEADER -->
