<?php

// Fa i retorna la taula amb el resultat de la cerca

session_start();

require_once 'class/Core.php';
// Recuperam dades de ajax dins variables.

$nom = $_POST['nom'];
$pro = $_POST['pro'];
$gest = $_POST['gest'];
$ofi = $_POST['ofi'];
$act = $_POST['act'];

// Comprobam si hi ha res escrit.

if (!empty($nom) || !empty($pro) || !empty($gest) || !empty($ofi) || !empty($act)) {
    cercar($nom, $pro, $gest, $ofi, $act);
}

//feim la cerca

function cercar($nom, $pro, $gest, $ofi, $act) {

    // Consulta amb les variables com a requisit.

    $pdoCore = Core::getInstance();
    $stmt = $pdoCore->db->prepare(
            "SELECT * FROM CASA C "
            . "INNER JOIN PROPIETARI P ON C.CAS_idPropietari=P.PRO_id "
            . "INNER JOIN GESTOR G ON P.PRO_idGestor=G.GES_id "
            . "INNER JOIN OFICINA O ON G.GES_idOficina=O.OFI_id "
            . "where C.CAS_nom LIKE :nom "
            . "AND P.PRO_nom LIKE :pro "
            . "AND G.GES_inicials LIKE :gest "
            . "AND O.OFI_nom LIKE :ofi "
            . "AND C.CAS_activada LIKE :act "
    );


    $stmt->execute(
            array(
                ':nom' => '%' . $nom . '%',
                ':pro' => '%' . $pro . '%',
                ':gest' => '%' . $gest . '%',
                ':ofi' => '%' . $ofi . '%',
                ':act' => '%' . $act . '%',
            )
    );

    $contar = $stmt->rowCount();
    $result = $stmt->fetchAll();
    //var_dump($result);
    // Si no hi ha resultats ...

    if ($contar == 0) {
        echo "No hi ha resultats per a la cerca actual";
    } else {

        // Hi ha resultats es fa la taula.

        echo '<div class="table-responsive" >';
        echo '<table class="table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Codi casa</th>';
        echo '<th >Nom</th>';
        echo '<th>Propietari</th>';
        echo '<th>Gestor</th>';
        echo '<th>Altres</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        $clase = "warning";
        //Perquecanvii de color cada fila.
        foreach ($result as $n => $casa) {

            if ($clase == 'warning') {
                $clase = 'success';
            } else {
                $clase = 'warning';
            }
            echo "<tr class='$clase'><td>";
            echo $casa->CAS_codi;
            echo "</td><td>";
            echo $casa->CAS_nom;
            echo "</td><td>";
            echo $casa->PRO_nom;
            echo "</td><td>";
            echo $casa->GES_inicials;
            echo "</td><td>";
            echo $casa->OFI_nom;
            echo "</td><td>";
            // variables per enviar amb get.
            $proid = $casa->PRO_id;
            $codcasa = $casa->CAS_id;
            $casaid = $casa->CAS_codi;
            // Botons de link a altres pagines.
            echo '<button class="btn btn-default" type="submit"><a target=“_new" href="edicasa.php?codcasa=' . "$codcasa" . '" class="glyphicon glyphicon-home">Casa</a></button> &nbsp';
            echo '<button class="btn btn-default" type="submit"><a target=“_new” href="edipropietari.php?id=' . "$proid" . '" class="glyphicon glyphicon-user">Propietari</a></button> &nbsp';
            echo '<button class="btn btn-default" type="submit"><a target=“_new" href="fotos.php?codicasa=' . "$codcasa" . '" class="glyphicon glyphicon-camera">Fotos</a></button> &nbsp';
            echo '<button class="btn btn-default" type="submit"><a target=“_new" href="descripcio.php?codicasa=' . "$codcasa" . '" class="glyphicon glyphicon-pencil">Descripció</a></button> &nbsp';
            echo '<button class="btn btn-default" type="submit"><a target=“_new" href="calendari.php?codicasa=' . "$casaid" . '" class="glyphicon glyphicon-calendar">Calendari</a></button> &nbsp';
            echo '<button class="btn btn-default" type="submit"><a target=“_new" href="mapacasa.php?codicasa=' . "$codcasa" . '" class="glyphicon glyphicon glyphicon-map-marker">Situació</a></button> &nbsp';

            echo '</td></tr>';
            echo "</td></tr>";
        }

        echo '</tbody>';
        echo "</table>";
        echo '<div>';
    }
}
