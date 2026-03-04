<?php
get_header();
?>

<h1>FRONT-PAGE.PHP</h1>
<div class="montheme-articles-grid">
<?php 
    if(have_posts()): // si l'url appelé correspond à du contenu  (article, page, auteur, catégorie...)
        while(have_posts()): // pour chaque élément trouvé... 
            the_post(); // on charge les données du contenu
    ?>
        <article class="montheme-article"> 
            <h1><?php the_title(); // affichage du titre ?></h1>
            <?php the_post_thumbnail('thumbnail'); ?>
            <div>
                <?php the_content(); // extrait du post ?> 
            </div>
            
        </article>
    <?php
        endwhile;
    else: 
        echo 'Aucun contenu';
    endif;
?>
</div>

<aside>
    <h3>SIDEBAR</h3>
    <h4>Widgets</h4>
</aside>

<?php 
get_footer();