<?php

require_once '../class/Core.php';
//print_r($_POST);

$item_per_page = 20;

// CONSULTA SQL PER AVERIGURAR EL NOMBRE DE PAGINES

$pdoCore = Core::getInstance();
$stmt = $pdoCore->db->prepare(
        "SELECT * FROM PROPIETARI"
);


$stmt->execute(
);

$result = $stmt->fetchObject();
$get_total_rows = $stmt->rowCount();


// Divideix el numero de resultats entre pagines.

$pages = ceil($get_total_rows / $item_per_page) + 1;
echo '<b>Nº Resultats: ' . $get_total_rows . '</b>';

// Crear la paginacio perque generi numeros amb link segons el numero de pagines.

if ($pages > 1) {
    $pagination = '';
    $pagination .= '<ul class="paginate">';
    for ($i = 1; $i < $pages; $i++) {
        $pagination .= '<li><a href="#" class="paginate_click" id="' . $i . '-page">' . $i . '</a></li>';
    }
//    if ($pages > 10) {
//        $pagination .= '<li><a href="#" class="paginate_click" id="next-page">  next  </a></li>';
//    }
    $pagination .= '</ul>';
}
echo $pagination;
