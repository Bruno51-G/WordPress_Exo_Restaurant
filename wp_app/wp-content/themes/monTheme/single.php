<?php
get_header();
?>

<h1>SINGLE.PHP</h1>
<div class="montheme-articles-grid">
<?php 
    if(have_posts()): // si l'url appelé correspond à du contenu  (article, page, auteur, catégorie...)
        while(have_posts()): // pour chaque élément trouvé... 
            the_post(); // on charge les données du contenu
    ?>
        <article class="montheme-article">
            <header>
                <h1><?php the_title(); // affichage du titre ?></h1>
                <aside>
                    <p>écrit par <?php the_author_link(); ?> le <?php the_date(); ?>
                    dans <?php the_category(', '); ?>
                </p>
                    <p>modifié le <?php the_modified_date(); ?> par <?php the_modified_author(); ?></p>
                </aside>
            </header>
            
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

<?php 
get_footer();