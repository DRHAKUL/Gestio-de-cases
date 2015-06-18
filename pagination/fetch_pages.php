<?php

require_once '../class/Core.php';
//$clase="";
//print_r($_POST);
$item_per_page = 25;

//sanitize post value
$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);

// Validacio si el numero de pagina rebut es numeric.

if (!is_numeric($page_number)) {
    die('Invalid page number!');
}

//Calcular el registre inicial. position.


$position = ($page_number * $item_per_page);

// Consulta

$pdoCore = Core::getInstance();
$stmt = $pdoCore->db->prepare(
        "SELECT * FROM CASA C "
        . "INNER JOIN PROPIETARI P ON C.CAS_idPropietari=P.PRO_id "
        . "INNER JOIN GESTOR G ON P.PRO_idGestor=G.GES_id "
        . "INNER JOIN OFICINA O ON G.GES_idOficina=O.OFI_id "
        . "ORDER BY C.CAS_nom ASC "
        . " LIMIT $position, $item_per_page"
);
$stmt->execute();

$result = $stmt->fetchAll();




// Taules generades de la base de dades de CASA.

echo '<div class="table-responsive" >';

echo '<table class="table" id="taula">';
echo '<thead>';
echo '<tr>';
echo '<th>Codi casa</th>';
echo '<th >Nom</th>';
echo '<th>Propietari</th>';
echo '<th>Gestor</th>';
echo '<th>Edicio</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
$clase = "warning";
foreach ($result as $n => $casa) {

    if ($clase == 'warning') {
        $clase = 'success';
    } else {
        $clase = 'warning';
    }

    echo '<tr class="' . $clase . '"><td>';
    echo $casa->CAS_codi;
    echo '</td><td>';
    echo $casa->CAS_nom;
    echo '</td><td>';
    echo $casa->PRO_nom;
    echo '</td><td>';
    echo $casa->GES_inicials;
    echo '</td><td>';
    echo '</td><td>';
    echo $casa->OFI_nom;
    echo '</td><td>';
    $proid = $casa->PRO_id;
    $codcasa = $casa->CAS_id;
    $casaid = $casa->CAS_codi;

    echo '<button class="btn btn-default" type="submit"><a target=“_new" href="edicasa.php?codcasa=' . "$codcasa" . '" class="glyphicon glyphicon-home">Casa</a></button> &nbsp';
    echo '<button class="btn btn-default" type="submit"><a target=“_new” href="edipropietari.php?id=' . "$proid" . '" class="glyphicon glyphicon-user">Propietari</a></button> &nbsp';
    echo '<button class="btn btn-default" type="submit"><a target=“_new" href="fotos.php?codicasa=' . "$codcasa" . '" class="glyphicon glyphicon-camera">Fotos</a></button> &nbsp';
    echo '<button class="btn btn-default" type="submit"><a target=“_new" href="descripcio.php?codicasa=' . "$codcasa" . '" class="glyphicon glyphicon-pencil">Descripció</a></button> &nbsp';
    echo '<button class="btn btn-default" type="submit"><a target=“_new" href="calendari.php?codicasa=' . "$casaid" . '" class="glyphicon glyphicon-calendar">Calendari</a></button> &nbsp';
    echo '<button class="btn btn-default" type="submit"><a target=“_new" href="mapacasa.php?codicasa=' . "$codcasa" . '" class="glyphicon glyphicon glyphicon-map-marker">Situació</a></button> &nbsp';
    echo '</td></tr>';
}

echo '</tbody>';
echo "</table>";
//echo '<div>';
echo '</div>';



