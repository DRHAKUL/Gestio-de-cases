<?php

// Pagina per carregar les fotos despres de pujarles.

require_once 'class/Core.php';


$pdoCore = Core::getInstance();
// recollim el que ens arriba per ajax.
$action = ($_POST['action']);
$updateRecordsArray = $_POST['recordsArray'];

if ($action == "updateRecordsListings") {
    // posam llista a 1
    $listingCounter = 1;
    // Es repeteix per cada foto, puja la foto i li posa un numero de ordre.
    foreach ($updateRecordsArray as $recordIDValue) {
        $stmt = $pdoCore->db->prepare(
                // Puja la imatge i posa el numero de llista que toca.
                "UPDATE IMATGES SET IMA_ordre = $listingCounter WHERE IMA_id = $recordIDValue"
        );
        $stmt->execute();
        // Aumenta el numero de llista
        $listingCounter = $listingCounter + 1;
    }
}

