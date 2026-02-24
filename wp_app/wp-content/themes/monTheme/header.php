<!DOCTYPE html>
<html <?php language_attributes(); ?>> // ajoute les attributs de langue à la balise <html> en fonction des réglages de WordPress (ex: lang="fr-FR" pour le français)
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
    <link rel="stylesheet" href ="<?php echo get_stylesheet_uri(); ?>"> // inclut le fichier style.css du thème pour les styles CSS
</head>
<body <?php body_class(); ?>> // ajoute des classes CSS à la balise <body> en fonction du contexte (page d'accueil, page d'article, etc.) pour faciliter le ciblage dans les styles CSS

<?php wp_body_open(); ?> // fonction qui permet d'insérer du code juste après l'ouverture de la balise <body>, utile pour les plugins ou les thèmes enfants
  
// header.php : en-tête du thème, appelé par get_header() dans index.php
<header class="">
    <h1><?php bloginfo('name'); ?></h1> // affiche le nom du site défini dans les réglages généraux de WordPress
    <h2><?php bloginfo('description'); ?></h2> // affiche la description du site définie dans les réglages généraux de WordPress

</header>

<main>

<!-- FIN DU HEADER -->

