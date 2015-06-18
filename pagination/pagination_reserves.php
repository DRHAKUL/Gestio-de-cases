
<?php

require_once '../class/Core.php';
//print_r($_POST);

$item_per_page = 25;

// CONSULTA SQL per coneixer el numero de pagines.

$pdoCore = Core::getInstance();
$stmt = $pdoCore->db->prepare(
        "SELECT * FROM CASA"
);


$stmt->execute(
);

$result = $stmt->fetchObject();
$get_total_rows = $stmt->rowCount();


// Divideix el numero de resultats entre pagines.

$pages = ceil($get_total_rows / $item_per_page) + 1;




// Crear la paginacio perque generi numeros amb link segons el numero de pagines.
if ($pages > 1) {
    $pagination = '';
    $pagination .= '<ul class="paginate" id = "pagin">';
    // Fa la llista de links a les pagines.
    for ($i = 1; $i < $pages; $i++) {
        $pagination .= '<li><a href="#" class="paginate_click" id="' . $i . '-page">' . $i . '</a></li>';
    }

    $pagination .= '</ul>';
}
echo $pagination;
?>
