<?php

// Reb les fotos i les guarda.
session_start();



// Pagina per fer l'insert.

require_once 'class/Core.php';
// Comproba que arriba informacio per ajax
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

// Informacio de l'arxiu en variables.


    $file = $_FILES["file"]["name"];
//    $filetype = $_FILES["file"]["type"];
//    $filesize = $_FILES["file"]["size"];
    $codicasa = $_GET['id_casa'];

    // Select per obtenir id de la casa.
    $pdoCore = Core::getInstance();
    $stmt = $pdoCore->db->prepare(
            "SELECT CAS_nom FROM CASA "
            . "WHERE CAS_id = :casaid "
    );
    $stmt->execute(
            array(
                ':casaid' => $codicasa
            )
    );
    $cas = $stmt->fetchObject();

    $nom = $cas->CAS_nom;
    // Si no existeix la carpeta la crea.
    if (!file_exists("files/$nom/")) {
        mkdir("files/$nom/", 0700);
    }
    // Insert
    //Donam un nom a la foto random.
    $random = rand(1, 5000);
    if ($file && move_uploaded_file($_FILES["file"]["tmp_name"], "files/$nom/" . $random)) {
        // Es fa insert a la base de dades.
        $pdoCore = Core::getInstance();
        $stmt = $pdoCore->db->prepare(
                "INSERT INTO IMATGES (IMA_codiCasa,IMA_nomImatge)VALUES(:codicasa,:nom)"
        );

        $stmt->execute(
                array(
                    ':codicasa' => $codicasa,
                    ':nom' => $random
                )
        );
    }

    if (isset($_post['borrar'])) {
        echo $_post['borrar'];
    }
}