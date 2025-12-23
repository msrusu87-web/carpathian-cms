<?php

/**
 * Translate Admin Panel to All Languages
 * Uses Romanian as base to generate translations for DE, ES, FR, IT
 */

// Load Romanian translations (our master file)
$roTranslations = include(__DIR__ . '/lang/ro/messages.php');

echo "🌍 Translating from Romanian to all languages...\n";
echo "📊 Total Romanian keys: " . count($roTranslations) . "\n\n";

// Language mapping: RO -> Target Language
$languageMappings = [
    'de' => [ // German
        // Common
        'Acasă' => 'Startseite',
        'Despre' => 'Über',
        'Despre Noi' => 'Über Uns',
        'Contact' => 'Kontakt',
        'Contactează-ne' => 'Kontaktieren Sie uns',
        'Blog' => 'Blog',
        'Magazin' => 'Shop',
        'Portofoliu' => 'Portfolio',
        
        // Actions
        'Salvează' => 'Speichern',
        'Anulează' => 'Abbrechen',
        'Trimite' => 'Senden',
        'Actualizează' => 'Aktualisieren',
        'Creează' => 'Erstellen',
        'Editează' => 'Bearbeiten',
        'Șterge' => 'Löschen',
        'Elimină' => 'Entfernen',
        'Adaugă' => 'Hinzufügen',
        'Publică' => 'Veröffentlichen',
        'Previzualizare' => 'Vorschau',
        'Vezi' => 'Ansehen',
        'Caută' => 'Suchen',
        'Filtrează' => 'Filtern',
        'Sortează' => 'Sortieren',
        'Reîmprospătează' => 'Aktualisieren',
        'Resetează' => 'Zurücksetzen',
        'Aplică' => 'Anwenden',
        'Înapoi' => 'Zurück',
        
        // Status
        'Activ' => 'Aktiv',
        'Inactiv' => 'Inaktiv',
        'Publicat' => 'Veröffentlicht',
        'Ciornă' => 'Entwurf',
        'În așteptare' => 'Ausstehend',
        'Finalizat' => 'Abgeschlossen',
        
        // Fields
        'Nume' => 'Name',
        'Titlu' => 'Titel',
        'Descriere' => 'Beschreibung',
        'Conținut' => 'Inhalt',
        'Email' => 'E-Mail',
        'Telefon' => 'Telefon',
        'Adresă' => 'Adresse',
        'Oraș' => 'Stadt',
        'Județ' => 'Bundesland',
        'Țară' => 'Land',
        'Cod Poștal' => 'Postleitzahl',
        
        // Common phrases
        'Panou Control' => 'Dashboard',
        'Setări' => 'Einstellungen',
        'Utilizatori' => 'Benutzer',
        'Permisiuni' => 'Berechtigungen',
        'Conținut' => 'Inhalt',
        'Comunicații' => 'Kommunikation',
        'Design' => 'Design',
        
        // Messages
        'Succes' => 'Erfolg',
        'Eroare' => 'Fehler',
        'Avertisment' => 'Warnung',
        'Informație' => 'Information',
        'Salvat cu Succes' => 'Erfolgreich gespeichert',
        'Ești sigur?' => 'Sind Sie sicher?',
        
        // More...
        'Da' => 'Ja',
        'Nu' => 'Nein',
        'Obligatoriu' => 'Erforderlich',
        'Opțional' => 'Optional',
    ],
    
    'es' => [ // Spanish
        'Acasă' => 'Inicio',
        'Despre' => 'Acerca de',
        'Despre Noi' => 'Sobre Nosotros',
        'Contact' => 'Contacto',
        'Contactează-ne' => 'Contáctenos',
        'Blog' => 'Blog',
        'Magazin' => 'Tienda',
        'Portofoliu' => 'Portafolio',
        
        'Salvează' => 'Guardar',
        'Anulează' => 'Cancelar',
        'Trimite' => 'Enviar',
        'Actualizează' => 'Actualizar',
        'Creează' => 'Crear',
        'Editează' => 'Editar',
        'Șterge' => 'Eliminar',
        'Elimină' => 'Quitar',
        'Adaugă' => 'Añadir',
        'Publică' => 'Publicar',
        'Previzualizare' => 'Vista Previa',
        'Vezi' => 'Ver',
        'Caută' => 'Buscar',
        'Filtrează' => 'Filtrar',
        'Sortează' => 'Ordenar',
        'Reîmprospătează' => 'Actualizar',
        'Resetează' => 'Restablecer',
        'Aplică' => 'Aplicar',
        'Înapoi' => 'Atrás',
        
        'Activ' => 'Activo',
        'Inactiv' => 'Inactivo',
        'Publicat' => 'Publicado',
        'Ciornă' => 'Borrador',
        'În așteptare' => 'Pendiente',
        'Finalizat' => 'Completado',
        
        'Nume' => 'Nombre',
        'Titlu' => 'Título',
        'Descriere' => 'Descripción',
        'Conținut' => 'Contenido',
        'Email' => 'Correo electrónico',
        'Telefon' => 'Teléfono',
        'Adresă' => 'Dirección',
        'Oraș' => 'Ciudad',
        'Județ' => 'Provincia',
        'Țară' => 'País',
        'Cod Poștal' => 'Código Postal',
        
        'Panou Control' => 'Panel de Control',
        'Setări' => 'Configuración',
        'Utilizatori' => 'Usuarios',
        'Permisiuni' => 'Permisos',
        'Comunicații' => 'Comunicaciones',
        'Design' => 'Diseño',
        
        'Succes' => 'Éxito',
        'Eroare' => 'Error',
        'Avertisment' => 'Advertencia',
        'Informație' => 'Información',
        'Salvat cu Succes' => 'Guardado con éxito',
        'Ești sigur?' => '¿Estás seguro?',
        
        'Da' => 'Sí',
        'Nu' => 'No',
        'Obligatoriu' => 'Requerido',
        'Opțional' => 'Opcional',
    ],
    
    'fr' => [ // French
        'Acasă' => 'Accueil',
        'Despre' => 'À propos',
        'Despre Noi' => 'À Propos de Nous',
        'Contact' => 'Contact',
        'Contactează-ne' => 'Contactez-nous',
        'Blog' => 'Blog',
        'Magazin' => 'Boutique',
        'Portofoliu' => 'Portfolio',
        
        'Salvează' => 'Enregistrer',
        'Anulează' => 'Annuler',
        'Trimite' => 'Envoyer',
        'Actualizează' => 'Mettre à jour',
        'Creează' => 'Créer',
        'Editează' => 'Modifier',
        'Șterge' => 'Supprimer',
        'Elimină' => 'Retirer',
        'Adaugă' => 'Ajouter',
        'Publică' => 'Publier',
        'Previzualizare' => 'Aperçu',
        'Vezi' => 'Voir',
        'Caută' => 'Rechercher',
        'Filtrează' => 'Filtrer',
        'Sortează' => 'Trier',
        'Reîmprospătează' => 'Rafraîchir',
        'Resetează' => 'Réinitialiser',
        'Aplică' => 'Appliquer',
        'Înapoi' => 'Retour',
        
        'Activ' => 'Actif',
        'Inactiv' => 'Inactif',
        'Publicat' => 'Publié',
        'Ciornă' => 'Brouillon',
        'În așteptare' => 'En attente',
        'Finalizat' => 'Terminé',
        
        'Nume' => 'Nom',
        'Titlu' => 'Titre',
        'Descriere' => 'Description',
        'Conținut' => 'Contenu',
        'Email' => 'E-mail',
        'Telefon' => 'Téléphone',
        'Adresă' => 'Adresse',
        'Oraș' => 'Ville',
        'Județ' => 'Province',
        'Țară' => 'Pays',
        'Cod Poștal' => 'Code Postal',
        
        'Panou Control' => 'Tableau de Bord',
        'Setări' => 'Paramètres',
        'Utilizatori' => 'Utilisateurs',
        'Permisiuni' => 'Autorisations',
        'Comunicații' => 'Communications',
        'Design' => 'Design',
        
        'Succes' => 'Succès',
        'Eroare' => 'Erreur',
        'Avertisment' => 'Avertissement',
        'Informație' => 'Information',
        'Salvat cu Succes' => 'Enregistré avec succès',
        'Ești sigur?' => 'Êtes-vous sûr?',
        
        'Da' => 'Oui',
        'Nu' => 'Non',
        'Obligatoriu' => 'Requis',
        'Opțional' => 'Optionnel',
    ],
    
    'it' => [ // Italian
        'Acasă' => 'Home',
        'Despre' => 'Informazioni',
        'Despre Noi' => 'Chi Siamo',
        'Contact' => 'Contatto',
        'Contactează-ne' => 'Contattaci',
        'Blog' => 'Blog',
        'Magazin' => 'Negozio',
        'Portofoliu' => 'Portfolio',
        
        'Salvează' => 'Salva',
        'Anulează' => 'Annulla',
        'Trimite' => 'Invia',
        'Actualizează' => 'Aggiorna',
        'Creează' => 'Crea',
        'Editează' => 'Modifica',
        'Șterge' => 'Elimina',
        'Elimină' => 'Rimuovi',
        'Adaugă' => 'Aggiungi',
        'Publică' => 'Pubblica',
        'Previzualizare' => 'Anteprima',
        'Vezi' => 'Visualizza',
        'Caută' => 'Cerca',
        'Filtrează' => 'Filtra',
        'Sortează' => 'Ordina',
        'Reîmprospătează' => 'Aggiorna',
        'Resetează' => 'Ripristina',
        'Aplică' => 'Applica',
        'Înapoi' => 'Indietro',
        
        'Activ' => 'Attivo',
        'Inactiv' => 'Inattivo',
        'Publicat' => 'Pubblicato',
        'Ciornă' => 'Bozza',
        'În așteptare' => 'In attesa',
        'Finalizat' => 'Completato',
        
        'Nume' => 'Nome',
        'Titlu' => 'Titolo',
        'Descriere' => 'Descrizione',
        'Conținut' => 'Contenuto',
        'Email' => 'Email',
        'Telefon' => 'Telefono',
        'Adresă' => 'Indirizzo',
        'Oraș' => 'Città',
        'Județ' => 'Provincia',
        'Țară' => 'Paese',
        'Cod Poștal' => 'Codice Postale',
        
        'Panou Control' => 'Dashboard',
        'Setări' => 'Impostazioni',
        'Utilizatori' => 'Utenti',
        'Permisiuni' => 'Permessi',
        'Comunicații' => 'Comunicazioni',
        'Design' => 'Design',
        
        'Succes' => 'Successo',
        'Eroare' => 'Errore',
        'Avertisment' => 'Avviso',
        'Informație' => 'Informazione',
        'Salvat cu Succes' => 'Salvato con successo',
        'Ești sigur?' => 'Sei sicuro?',
        
        'Da' => 'Sì',
        'Nu' => 'No',
        'Obligatoriu' => 'Obbligatorio',
        'Opțional' => 'Opzionale',
    ],
];

// Process each language
foreach ($languageMappings as $lang => $mappings) {
    echo "🌐 Translating to $lang...\n";
    
    // Load existing translations for this language
    $existingFile = __DIR__ . "/lang/$lang/messages.php";
    $existing = file_exists($existingFile) ? include($existingFile) : [];
    
    // Start with Romanian as base
    $translated = [];
    
    foreach ($roTranslations as $key => $roValue) {
        // If we have a direct mapping, use it
        if (isset($mappings[$roValue])) {
            $translated[$key] = $mappings[$roValue];
        }
        // If existing translation exists, keep it
        elseif (isset($existing[$key])) {
            $translated[$key] = $existing[$key];
        }
        // Otherwise, keep the key as-is (will need manual translation)
        else {
            $translated[$key] = $roValue; // Fallback to Romanian
        }
    }
    
    // Sort alphabetically
    ksort($translated);
    
    // Generate PHP file
    $output = "<?php\n\n";
    $output .= "/**\n";
    $output .= " * " . strtoupper($lang) . " Translation File for Carpathian CMS\n";
    $output .= " * Updated: " . date('Y-m-d H:i:s') . "\n";
    $output .= " * Total keys: " . count($translated) . "\n";
    $output .= " */\n\n";
    $output .= "return " . var_export($translated, true) . ";\n";
    
    file_put_contents($existingFile, $output);
    
    echo "  ✅ $lang: " . count($translated) . " keys\n";
}

echo "\n✅ All languages updated!\n";
echo "📝 Note: Some strings may need manual review for accuracy.\n";

