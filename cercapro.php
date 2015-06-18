<?php

// Fa i retorna el resultat de la cerca de propietaris.
session_start();

require_once 'class/Core.php';
// Variables d'ajax

$pro = $_POST['pro'];
$gest = $_POST['gest'];


// Comprobam si s'escriu res.

if (!empty($pro) || !empty($gest)) {
    cercar($pro, $gest);
}

//Feim la cerca
function cercar($pro, $gest) {

    // Select

    $pdoCore = Core::getInstance();
    $stmt = $pdoCore->db->prepare(
            "SELECT * FROM PROPIETARI P "
            . " INNER JOIN GESTOR G ON G.GES_id= P.PRO_idGestor "
            . "where P.PRO_nom LIKE :pro "
            . "AND G.GES_nom LIKE :gest "
    );


    $stmt->execute(
            array(
                ':pro' => '%' . $pro . '%',
                ':gest' => '%' . $gest . '%',
            )
    );



    $contar = $stmt->rowCount();
    $result = $stmt->fetchAll();

    // Si no hi ha resultats ...
    echo $contar;
    if ($contar == 0) {
        echo "No hi ha resultats per a la cerca actual";
    } else {

        // Taula.
        echo '<div class="table-responsive" >';

        echo '<table class="table" id="taula">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Codi propietari</th>';
        echo '<th >Dni</th>';
        echo '<th>Nom</th>';
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

            echo '</td></tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }
}
