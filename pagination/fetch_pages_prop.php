<?php

require_once '../class/Core.php';
//$clase="";
//print_r($_POST);
$item_per_page = 20;
//sanitize post value
$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);

// Validacio si el numero de pagina es numeric.

if (!is_numeric($page_number)) {
    die('Invalid page number!');
}

//Calcular el registre inicial. position.

$position = ($page_number * $item_per_page);

// Consulta

$pdoCore = Core::getInstance();
$stmt = $pdoCore->db->prepare(
        "SELECT * FROM PROPIETARI P "
        . " INNER JOIN GESTOR G ON G.GES_id= P.PRO_idGestor "
        . " LIMIT $position, $item_per_page "
);
$stmt->execute();

$result = $stmt->fetchAll();




// Taules generades de la base de dades de PROPIETARI.

echo '<div class="table-responsive" >';

echo '<table class="table" id="taula">';
echo '<thead>';
echo '<tr>';
echo '<th>Codi propietari</th>';
echo '<th >Dni</th>';
echo '<th>Nom</th>';
echo '<th>Persona contacte</th>';
echo '<th>Persona contacte2</th>';
echo '<th>PRO_email</th>';
echo '<th>PRO_telefon</th>';
echo '<th>PRO_mobil</th>';
echo '<th>PRO_fax</th>';
echo '<th>PRO_adreca</th>';
echo '<th>PRO_ciutat</th>';
echo '<th>Gestor</th>';

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
    echo $casa->PRO_id;
    echo '</td><td>';
    echo $casa->PRO_dni;
    echo '</td><td>';
    $proid = $casa->PRO_id;
    echo '<a  target=“_new” href="edipropietari.php?id=' . "$proid" . '" >' . $casa->PRO_nom . '</a>';
    echo '</td><td>';
    echo $casa->PRO_personaContacte;
    echo '</td><td>';
    echo $casa->PRO_personaContacte2;
    echo '</td><td>';
    echo $casa->PRO_email;
    echo '</td><td>';
    echo $casa->PRO_telefon;
    echo '</td><td>';
    echo $casa->PRO_mobil;
    echo '</td><td>';
    echo $casa->PRO_fax;
    echo '</td><td>';
    echo $casa->PRO_adreca;
    echo '</td><td>';
    echo $casa->PRO_ciutat;
    echo '</td><td>';
    echo $casa->GES_nom;

    echo '</td>';
}
echo '</tr>';
echo '</tbody>';
echo "</table>";
echo '</div>';



