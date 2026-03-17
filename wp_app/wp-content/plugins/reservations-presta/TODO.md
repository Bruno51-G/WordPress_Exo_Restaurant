
# Gérer l'affichage des prestations

L'idée est de dire à WordPress : « Si tu cherches le design pour une Prestation, ne regarde pas dans le thème, regarde dans mon dossier de plugin ».

### 1. La structure des fichiers

Dans votre dossier `reservations-presta/`, créez un sous-dossier nommé `templates/` et placez-y un fichier nommé `single-prestation.php`.

### 2. Le code pour "forcer" l'utilisation du template

Ajoutez cette fonction à votre fichier principal de plugin. Elle sert d'aiguilleur du ciel pour WordPress.

```php
// 6. Charger le template depuis le plugin
add_filter('template_include', 'mde_charger_templates_prestation');

function mde_charger_templates_prestation($template) {
    // 1. Pour la page seule
    if (is_singular('prestation')) {
        $new_template = plugin_dir_path(__FILE__) . 'templates/single-prestation.php';
        if (file_exists($new_template)) return $new_template;
    }
    
    // 2. Pour la page ARCHIVE (la liste)
    if (is_post_type_archive('prestation')) {
        $new_template = plugin_dir_path(__FILE__) . 'templates/archive-prestation.php';
        if (file_exists($new_template)) return $new_template;
    }
    
    return $template;
}
```

### 3. Le contenu du fichier `templates/single-prestation.php`
Ce fichier sera le "vêtement" de votre prestation. Vous pouvez le styliser comme vous voulez.

```php
<?php get_header(); ?>

<main id="main" class="site-main" style="max-width: 800px; margin: 40px auto; padding: 20px;">
    <?php while (have_posts()) : the_post(); ?>
        
        <article>
            <h1><?php the_title(); ?></h1>
            
            <div class="prestation-image" style="margin-bottom: 20px;">
                <?php the_post_thumbnail('large'); ?>
            </div>

            <div class="prestation-content">
                <?php the_content(); ?>
            </div>

            <?php 
                $prix = get_post_meta(get_the_ID(), '_presta_prix', true);
                $duree = get_post_meta(get_the_ID(), '_presta_duree', true);
            ?>

            <div class="prestation-card" style="background: #fdf2f8; border: 2px solid #db2777; padding: 30px; border-radius: 15px; text-align: center;">
                <p style="font-size: 1.2rem;">⏱ Durée : <strong><?php echo esc_html($duree); ?></strong></p>
                <p style="font-size: 2rem; color: #db2777; margin: 10px 0;">Price : <?php echo esc_html($prix); ?> €</p>
                <a href="/contact" style="display: inline-block; background: #db2777; color: #fff; padding: 15px 30px; border-radius: 50px; text-decoration: none; font-weight: bold;">PRENDRE RENDEZ-VOUS</a>
            </div>
        </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
```

### 4 Contenu de archive-prestation

```php
<?php get_header(); ?>

<main style="max-width: 1000px; margin: 40px auto; padding: 20px;">
    <h1>Nos Prestations de Coiffure</h1>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article style="border: 1px solid #ddd; padding: 20px; border-radius: 10px;">
                <?php the_post_thumbnail('medium', array('style' => 'width:100%; height:auto;')); ?>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                
                <?php 
                    $prix = get_post_meta(get_the_ID(), '_presta_prix', true);
                    $duree = get_post_meta(get_the_ID(), '_presta_duree', true);
                ?>
                
                <p><strong>⏱ Durée :</strong> <?php echo esc_html($duree); ?></p>
                <p><strong>💰 Prix :</strong> <?php echo esc_html($prix); ?> €</p>
                
                <a href="<?php the_permalink(); ?>">Voir les détails</a>
            </article>
        <?php endwhile; endif; ?>
    </div>
</main>

<?php get_footer(); ?>
```

## Tri des prestations 

Pour trier les prestations par prix sur la page d'archive, nous allons utiliser une **`WP_Query`** personnalisée. C'est un concept fondamental : on ne se contente plus de ce que WordPress nous donne, on lui dicte l'ordre des résultats.

Voici comment mettre cela en place.

### 1. Ajouter le formulaire de tri (HTML)
Dans votre fichier `templates/archive-prestation.php`, juste avant le début de votre grille de prestations, ajoutez ce petit formulaire :

```php
<form method="get" style="margin-bottom: 30px; background: #f3f4f6; padding: 15px; border-radius: 8px;">
    <label for="tri_prix">Trier par prix :</label>
    <select name="ordre_prix" id="tri_prix" onchange="this.form.submit()">
        <option value="">Par défaut</option>
        <option value="ASC" <?php selected($_GET['ordre_prix'], 'ASC'); ?>>Du moins cher au plus cher</option>
        <option value="DESC" <?php selected($_GET['ordre_prix'], 'DESC'); ?>>Du plus cher au moins cher</option>
    </select>
</form>
```

### 2. Modifier la requête (PHP)

Il faut maintenant dire à WordPress : "Si l'utilisateur a choisi un ordre, modifie la liste". Ajoutez ce bloc dans votre fichier principal de plugin (`mde-reservation.php`) :

```php
// 7. Modifier l'ordre des prestations sur l'archive
add_action('pre_get_posts', 'mde_trier_prestations_prix');

function mde_trier_prestations_prix($query) {
    // On ne modifie la requête QUE sur le site (pas l'admin) et QUE pour l'archive prestation
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('prestation')) {
        
        if (isset($_GET['ordre_prix']) && !empty($_GET['ordre_prix'])) {
            $query->set('meta_key', 'prestation_prix'); // On cible notre champ caché
            $query->set('orderby', 'meta_value_num'); // Tri numérique (et non alphabétique)
            $query->set('order', $_GET['ordre_prix']); // ASC ou DESC
        }
    }
}
```

### Rechercher une prestation


Pour ajouter une barre de recherche spécifique aux prestations, nous allons utiliser le paramètre HTML `name="s"`. C'est le nom réservé par WordPress pour déclencher sa propre mécanique de recherche interne.

Voici comment l'intégrer proprement dans votre archive.

### 1. Le code HTML (dans `templates/archive-prestation.php`)

Ajoutez ce formulaire juste au-dessus (ou à côté) de votre menu de tri :

```php
<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="margin-bottom: 20px; display: flex; gap: 10px;">
    <input type="text" name="s" placeholder="Rechercher une prestation..." value="<?php echo get_search_query(); ?>" style="flex-grow: 1; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <input type="hidden" name="post_type" value="prestation">
    <button type="submit" style="background: #db2777; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
        🔍 Rechercher
    </button>
</form>
```

---

### 2. Pourquoi ça fonctionne ? (L'astuce du `hidden`)

C'est le point le plus important à expliquer à vos élèves :

* **`name="s"`** : Quand WordPress voit ce paramètre dans l'URL (ex: `?s=coupe`), il change automatiquement sa requête pour chercher les mots-clés dans les titres et le contenu des articles.
* **`name="post_type" value="prestation"`** : Par défaut, la recherche WordPress fouille dans **tout** le site (pages, articles, prestations). En ajoutant ce champ caché, on force WordPress à filtrer les résultats pour n'afficher que le type `prestation`. 

### 3. Améliorer l'expérience : "Aucun résultat"

Si un client cherche "Massage" chez une coiffeuse, il faut gérer le cas où rien n'est trouvé. Dans votre boucle PHP, ajoutez le `else` :

```php
<div style="display: grid; ...">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php endwhile; ?>
    
    <?php else : ?>
        <p style="grid-column: 1 / -1; text-align: center; padding: 40px; background: #f9f9f9;">
            Désolé, aucune prestation ne correspond à votre recherche.
        </p>
    <?php endif; ?>
</div>
```

---

# Les réservations

Voici la suite logique pour transformer votre plugin en un véritable outil de gestion. Nous allons ajouter le **CPT Réservation**, les **champs de données** (date, heure, prestations choisies) et le **formulaire client**.

### 1. Déclarer le CPT "Réservation"
Ajoutez ceci à votre fichier principal pour créer le dossier de stockage des rendez-vous.

```php
// 8. Créer le type de contenu "Réservation"
add_action('init', 'mde_creer_cpt_reservation');
function mde_creer_cpt_reservation() {
    register_post_type('reservation', array(
        'labels' => array('name' => 'Réservations', 'singular_name' => 'Réservation'),
        'public' => false, 
        'show_ui' => true,  
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title') // Le titre sera le nom du client
    ));
}
```

### 2. Le Formulaire de Réservation (Shortcode)
Ce code permet d'afficher le formulaire n'importe où (ex: sur une page "Réserver") en tapant `[mde_formulaire_reservation]`.

```php
add_shortcode('mde_formulaire_reservation', 'mde_genere_formulaire');

function mde_genere_formulaire() {
    $prestations = get_posts(array('post_type' => 'prestation', 'posts_per_page' => -1));
    
    ob_start(); ?>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" style="background:#f9f9f9; padding:20px; border-radius:10px;">
        <input type="hidden" name="action" value="mde_soumettre_reservation">
        
        <h3>1. Choisissez vos prestations</h3>
        <?php foreach($prestations as $p): ?>
            <label style="display:block; margin-bottom:5px;">
                <input type="checkbox" name="choix_presta[]" value="<?php echo $p->ID; ?>"> 
                <?php echo $p->post_title; ?> (+<?php echo get_post_meta($p->ID, '_presta_prix', true); ?>€)
            </label>
        <?php endforeach; ?>

        <h3>2. Date et Heure</h3>
        <input type="date" name="res_date" required>
        <input type="time" name="res_heure" required>

        <h3>3. Vos informations</h3>
        <input type="text" name="client_nom" placeholder="Votre nom" required style="width:100%; margin-bottom:10px;">
        
        <button type="submit" style="background:#db2777; color:white; padding:10px 20px; border:none; cursor:pointer; width:100%;">Confirmer la réservation</button>
    </form>
    <?php
    return ob_get_clean();
}
```

### 3. Traitement et Calcul (La "Cerveau" du plugin)
Ce bloc intercepte la validation du formulaire, calcule les totaux et crée la réservation dans l'admin.

```php
add_action('admin_post_nopriv_mde_soumettre_reservation', 'mde_traiter_reservation');
add_action('admin_post_mde_soumettre_reservation', 'mde_traiter_reservation');

function mde_traiter_reservation() {
    if (!isset($_POST['choix_presta'])) wp_die('Veuillez choisir au moins une prestation.');

    $nom = sanitize_text_field($_POST['client_nom']);
    $prestations_ids = $_POST['choix_presta'];
    
    // Calculs
    $total_prix = 0;
    foreach ($prestations_ids as $id) {
        $total_prix += (int) get_post_meta($id, '_presta_prix', true);
    }

    // Création de la réservation (le CPT)
    $res_id = wp_insert_post(array(
        'post_title'  => $nom . ' - ' . $_POST['res_date'],
        'post_type'   => 'reservation',
        'post_status' => 'publish'
    ));

    // Sauvegarde des détails
    update_post_meta($res_id, '_res_prix_total', $total_prix);
    update_post_meta($res_id, '_res_date', $_POST['res_date']);
    update_post_meta($res_id, '_res_heure', $_POST['res_heure']);
    update_post_meta($res_id, '_res_details', implode(', ', $prestations_ids));

    wp_redirect(home_url('/merci')); // Rediriger vers une page de succès
    exit;
}
```

---

Pour que la coiffeuse puisse gérer ses rendez-vous, nous allons personnaliser la liste des réservations dans l'administration de WordPress. Par défaut, elle ne verrait que le titre. Nous allons ajouter des colonnes pour le **Prix**, la **Date** et l'**Heure**.

Ajoutez ce code dans votre fichier principal de plugin :

### 1. Ajouter les colonnes personnalisées
On dit à WordPress : "Ajoute ces nouvelles cases dans le tableau des réservations".

```php
// 9. Ajouter les colonnes dans l'administration
add_filter('manage_reservation_posts_columns', 'mde_colonnes_reservation');
function mde_colonnes_reservation($columns) {
    $columns['res_date'] = 'Date du RDV';
    $columns['res_heure'] = 'Heure';
    $columns['res_prix'] = 'Prix Total';
    return $columns;
}
```

### 2. Remplir les colonnes avec les données
C'est ici qu'on va chercher les "Meta" (les données cachées) pour les afficher.

```php
// 10. Remplir les colonnes avec les données de la base
add_action('manage_reservation_posts_custom_column', 'mde_contenu_colonnes_reservation', 10, 2);
function mde_contenu_colonnes_reservation($column, $post_id) {
    switch ($column) {
        case 'res_date':
            echo esc_html(get_post_meta($post_id, '_res_date', true));
            break;
        case 'res_heure':
            echo esc_html(get_post_meta($post_id, '_res_heure', true));
            break;
        case 'res_prix':
            echo esc_html(get_post_meta($post_id, '_res_prix_total', true)) . ' €';
            break;
    }
}
```

### 3. Rendre les colonnes triables
Pour que la coiffeuse puisse voir ses prochains rendez-vous en premier.

```php
// 11. Permettre de trier par date
add_filter('manage_edit-reservation_sortable_columns', 'mde_tri_colonnes_reservation');
function mde_tri_colonnes_reservation($columns) {
    $columns['res_date'] = 'res_date';
    return $columns;
}
```

---

### Explication pédagogique pour vos élèves

Le tableau de bord de WordPress fonctionne comme une feuille de calcul :
1. **Le Filter (`manage_..._columns`)** : C'est l'en-tête du tableau. On définit le nom des colonnes.
2. **L'Action (`manage_..._custom_column`)** : C'est le contenu des lignes. Pour chaque réservation, WordPress appelle cette fonction pour savoir quoi écrire dans la case.
3. **Le "Switch"** : On utilise un `switch` car WordPress passe dans cette fonction pour chaque colonne. On lui dit : "Si c'est la colonne prix, affiche le prix, si c'est la date, affiche la date".

### Ce qui a été construit :
* Un catalogue de prestations avec prix et durée.
* Un système de recherche et de tri pour les clients.
* Un formulaire de réservation qui calcule le prix total.
* **Un tableau de bord de gestion pour la coiffeuse.**


### envoi d'email 

Pour finaliser l'outil, nous allons utiliser la fonction `wp_mail()`. C'est le "service de la poste" interne de WordPress.

Voici le code à ajouter dans votre fonction `mde_traiter_reservation`, juste avant la redirection finale :

### 1. Le code d'envoi d'e-mail

```php
// ... (juste après les update_post_meta dans mde_traiter_reservation)

// Préparation de l'e-mail
$destinataire = get_option('admin_email'); // L'e-mail de la coiffeuse (admin du site)
$sujet = "Nouvelle réservation : " . $nom;

$message = "Bonjour,\n\n";
$message .= "Une nouvelle réservation a été effectuée :\n";
$message .= "Client : " . $nom . "\n";
$message .= "Date : " . $_POST['res_date'] . "\n";
$message .= "Heure : " . $_POST['res_heure'] . "\n";
$message .= "Prix total : " . $total_prix . " €\n\n";
$message .= "Connectez-vous à l'administration pour voir les détails.";

$headers = array('Content-Type: text/plain; charset=UTF-8');

// Envoi effectif
wp_mail($destinataire, $sujet, $message, $headers);

// ... (votre wp_redirect existant)
```

---

### 2. Points techniques importants

* **`get_option('admin_email')`** : Très pratique, cela récupère automatiquement l'adresse e-mail configurée dans les réglages généraux de WordPress. Pas besoin de l'écrire en dur !
* **`wp_mail()`** : Cette fonction est une enveloppe autour de la fonction PHP `mail()`, mais elle est plus robuste car elle permet d'utiliser des filtres et des en-têtes (headers) WordPress.
* **Le format** : Ici, nous envoyons un e-mail au format "Texte brut" (`text/plain`). C'est le plus simple pour commencer et cela évite que le message finisse en SPAM.

---

### 3. Note pour vos élèves (Test en Local)

C'est un moment crucial pour l'apprentissage : **`wp_mail()` ne fonctionne pas par défaut sur un serveur local** (comme WAMP, MAMP ou LocalWP) sans configuration spécifique.

> **Conseil pédagogique** : Expliquez-leur que pour envoyer de vrais e-mails depuis leur ordinateur, ils auraient besoin d'un plugin comme "WP Mail SMTP" ou d'un outil de capture d'e-mails comme **MailHog**. Sinon, le code est correct, mais l'e-mail ne "partira" pas réellement tant que le site n'est pas en ligne.

### Félicitations !

Votre plugin est maintenant un **écosystème complet** :
1.  **Catalogue** (CPT Prestations)
2.  **Affichage** (Templates personnalisés)
3.  **Recherche/Tri** (WP_Query)
4.  **Réservation** (Formulaire + Shortcode + wp_insert_post)
5.  **Gestion** (Colonnes Admin personnalisées)
6.  **Notification** (wp_mail)

Souhaitez-vous que je vous aide à rédiger une **fiche récapitulative** des fonctions WordPress utilisées pour que vos élèves puissent réviser ?
