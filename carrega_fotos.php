<?php

// Carrega el llistat de les fotos
session_start();

// Si no hi ha sesio envia a login.php.
if (!$_SESSION['meva']) { // Comprobaci� de sessi�.
    header("Location: login.php"); //Si no hi ha sessio "meva", envia a login..
}

// Pagina per carregar les fotos per ajax despres de pujarles.

require_once 'class/Core.php';



// Si no hi ha sesio envia a login.php.
if (!$_POST['codicasa']) { // Comprobaci� de sessi�.
    header("Location: login.php"); //Si no hi ha sessio "meva", envia a login..
}
$codicasa = $_POST['codicasa']; //Codi de la casa.

$pdoCore = Core::getInstance();

// Consulta bbdd per el nom de la casa
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

// Consulta bbdd per les imatges de la casa.

$stmt = $pdoCore->db->prepare(
        "SELECT * FROM IMATGES WHERE IMA_codiCasa = :codicasa "
);
$stmt->execute(
        array(
            ':codicasa' => $codicasa
        )
);



$id_casa = $codicasa;

$result = $stmt->fetchAll();

// Fer llistat de l'array de fotos. Amb botó de borrar.

echo '<ul class="list-inline">';
foreach ($result as $n => $img) {

    echo'<li  id="recordsArray_' . $img->IMA_id . '">';
    echo '<img src="files/' . $cas->CAS_nom . '/' . $img->IMA_nomImatge . '" WIDTH="250" HEIGHT="200" class="img-thumbnail">'
    . '<a href="fotos.php?codicasa=' . $id_casa . '&borrar=' . $img->IMA_nomImatge . '" id="borrar" >'
    . '' . $img->IMA_nomImatge . ' nº ordre :' . $img->IMA_ordre . '  <button> Borrar Foto</button></a></li>';
}

echo '</ul>';

