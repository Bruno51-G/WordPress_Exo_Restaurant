<?php
/**
 * Plugin Name: Plugin Signature
 * Description: Add signature to articles
 * Author: Bruno GODBILLOT
 */

function bg_signature($content)
{
    $signature = '<p style="color= red;">Copyright '. get_bloginfo('name') . '2026. Tous droits réservés.</p>';
    
    if(is_single()){
        return $content . $signature;
    }    

    return $content;
}

add_filter('the_content', 'bg_signature');

/**
 * Affiche un lien vers l'édition de l'article si l'utilisateur est admin
 */

function bg_afficher_lien_modif_si_admin($content)
{
    //Est ce que l'utilisateur connecté est ADMIN ?
    //Si oui : afficher le lien vers l'édition de l'article
    if(current_user_can('edit_posts')){
        $lien= get_edit_post_link();
        $couleur_fond = get_option('bg_changement_couleur', '#ffc0cb'); // Valeur par défaut : pink
        $a = '<a style="background: ' . $couleur_fond . '; border: 1px solid ' . $couleur_fond . '; font-size: 5rem; border-radius:50%; 
        text-decoration: none; padding: .3rem;" href="' .$lien. '">🖉</a>';
        return $content . ' ' . $a;
    }
    return $content;
}

add_filter('the_content', 'bg_afficher_lien_modif_si_admin');

/**
 * Ajoute un élément dans le menu Admin pour accéder aux réglages du plugin.
 */

function bg_menu_reglages()
{
    add_options_page(
        'Paramètres du plugin Signature', // Titre de la page de réglages
        'MySignature', // Libellé dans le menu Admin 
        'manage_options', // Niveau de permission requis
        'mysignature',
        'bg_menu_reglages_afficher',
    );
}

add_action('admin_menu', 'bg_menu_reglages');

/**
 * Affiche la page de réglages du plugin
 */

function bg_menu_reglages_afficher()
{
    $option_nom_copyright = get_option('bg_nom_copyright', 'DWWM2503');
    $option_couleur_fond_icone = get_option('bg_changement_couleur','#FFC0CB');
    ?>
        <h1>Réglages du plugin MySignature</h1>
        <div>
            <form method="post" action="options.php">
                <?php 
                    settings_fields('bg-settings-group');
                    do_settings_sections('bg-settings-group');
                ?>
                <div>
                    <label>Nom dans le Copyright: </label>
                    <input type="text" name="bg_nom_copyright" value="<?= $option_nom_copyright ?>">
                </div>
                <div>
                    <label>Changer la couleur de l'icône modifier</label>
                    <input type="color" name="bg_changement_couleur" value="<?= $option_couleur_fond_icone ?>">
                </div>
                <div>
                    <?php submit_button('Que la magie opère !!'); ?>
                </div>
            </form>
        </div>

    <?php
}


/**
 * Sauvegarde des options en base de données
 */

function bg_sauvegarde_reglages()
{
    register_setting('bg-settings-group', 'bg_nom_copyright');
    register_setting('bg-settings-group', 'bg_changement_couleur');
}

add_action('admin_init', 'bg_sauvegarde_reglages');

