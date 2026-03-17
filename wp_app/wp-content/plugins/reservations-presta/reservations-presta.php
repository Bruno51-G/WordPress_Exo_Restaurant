<?php 
/**
 * Plugin Name: Réservations de prestations
 * Description: voilà quoi
 * Author : DWWM 2503
 */

/**
 * PRESTATION
 * Nom de la prestation
 * Durée de la prestation
 * Tarif de la prestation
 */
function bg_cpt_prestation() {

    $args = [
        'labels' => [
            'name' => 'Prestations',
            'singular_name' => 'Prestation',
            'add_new_item' => 'Ajouter une prestation',
            'add_new' => 'Ajouter'
        ],
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-reddit',
        'supports' => ['title', 'thumbnail', 'editor'],
    ];
    register_post_type('prestation', $args);

}

add_action('init', 'bg_cpt_prestation');


/** AJOUTER les CHAMPS PERSONNALISES pour notre CPT
 * CPT = Custom Post Type
 * PRIX + DUREE
 */

function bg_afficher_details($post) {
    $prix = get_post_meta($post->ID, 'prestation_prix', true);
    echo '<div><label>Prix</label>';
    echo '<input type="number" step="0.1" min="1" max="500" name="prestation_prix" value="'.esc_attr($prix).'">';
    echo '</div>';

}

function bg_afficher_boite_formulaire() {
    add_meta_box('prestation_details', 'Réglages de la prestation', 'mde_afficher_details', 'prestation', 'normal');
}

add_action('add_meta_boxes', 'mde_afficher_boite_formulaire');


function bg_sauvegarder_details($post_id) {
    if(isset($_POST['prestation_prix'])) {
        $prix = floatval($_POST['prestation_prix']);
        if($prix > 0) {
            update_post_meta($post_id, 'prestation_prix', $prix);
        }        
    }
}

add_action('save_post', 'bg_sauvegarder_details');




function bg_afficher_details_front($content) {

    if(!is_singular('prestation')) { // si le contenu n'est PAS une prestation
        return $content;
    }

    $prix = get_post_meta(get_the_ID(), 'prestation_prix', true);
    $html = '<p>Tarif : ' . $prix . ' €</p>';

    return $html . $content;
}
/*
function bg_afficher_details_front2($content) {

    if(is_singular('prestation')) { // si le contenu est une prestation
        if(mb_strlen($content) >= 10) {
            
            $prix = get_post_meta(get_the_ID(), 'prestation_prix', true);
            $html = '<p>Tarif : ' . $prix . ' €</p>';
            return $html . $content;
        }        
    }

    return $content;
}
*/

add_filter('the_content', 'bg_afficher_details_front');


// 5. Affichage sur la page d'archive (la liste)

function bg_afficher_details_archive( $content ) {
    // On vérifie si on est sur une liste (archive) de prestations
    if ( is_post_type_archive('prestation') || is_tax('prestation') ) {
        $prix = get_post_meta( get_the_ID(), 'prestation_prix', true );
        $duree = 0; //get_post_meta( get_the_ID(), '_presta_duree', true );

        $info = '<p style="color: #db2777; font-weight: bold;">';
        $info .= '⏱ ' . esc_html($duree) . ' | 💰 ' . esc_html($prix) . ' €';
        $info .= '</p>';

        return $info . $content;
    }
    return $content;
}

add_filter( 'the_excerpt', 'mde_afficher_details_archive' );
add_filter( 'the_content', 'mde_afficher_details_archive' );


/**
 * RESERVATION 
 * UTILISATEUR (CLIENT) <--> PRESTATIONS(s) <--> DATE 
 */