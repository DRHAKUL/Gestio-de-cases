<?php

// Guarda els arxius i fa la pujada a bbdd
session_start();


if (!$_POST['nom']) {
    header("Location: login.php");
}

require_once 'class/Core.php';
$file = $_FILES["arxiu"]["name"];
$nom = $_POST['nom'];
$desc = $_POST['descripcio'];
$codicasa = $_POST['casa'];

if (!file_exists("arxius/$nom/")) {
    mkdir("arxius/$nom/", 0700);
}

if ($file && move_uploaded_file($_FILES["arxiu"]["tmp_name"], "arxius/$nom/$file")) {
    // Es fa insert a la base de dades.
    $pdoCore = Core::getInstance();
    $stmt = $pdoCore->db->prepare(
            "INSERT INTO DOCUMENTS_ADDICIONALS (DOA_codiCasa, DOA_documentAddicional, DOA_descripcio) "
            . "VALUES(:codicasa, :arxiu, :descripcio)"
    );
    $stmt->execute(
            array(
                ':codicasa' => $codicasa,
                ':arxiu' => $file,
                ':descripcio' => $desc
            )
    );
}
echo "<p>Pujada correcta</p>";
