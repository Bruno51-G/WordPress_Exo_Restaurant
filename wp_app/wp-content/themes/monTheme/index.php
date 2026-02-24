<?php
get_header();

    if(have_posts()): // si l'url appelé correspond à du contenu (article, page, auteur, catégorie...)
        while(have_posts()): // pour chaque élément trouvé...
            the_post(); // on charge les données du contenu
    ?>
        <h1><?php the_title(); // affichage du titre ?></h1>
        <article>
            <?php the_excerpt(); // extrait du post ?>
        </article>
        <?php the_post_thumbnail(); ?>
        <hr>
        <article>
            <?php the_content(); // contenu du post ?>
        </article>

        <?php
            endwhile;
        else:
            echo 'Aucun contenu';
        endif;

get_footer();
