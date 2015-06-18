<?php
require_once 'class/Core.php';
session_start();

// Si no hi ha sesio envia a login.php.
//if (!$_GET['codcasa']) {
//    header("Location: login.php");
//}
?>
<!DOCTYPE html>
<html lang="es">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <script type="text/javascript" src="js/jquery-1.11.3.js"></script>


        <title>Editor de Cases</title>


        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">


        <script src="js/bootstrap.js"></script>
        <script>

//            Jquery que fa que no canvii de pestanya quan s'envia el formulari.

            $(document).ready(function () {

                var hash = window.location.hash;
                hash && $('ul.nav a[href="' + hash + '"]').tab('show');

            });



            $(document).ready(function () {
                $('[data-toggle="tooltip"]').tooltip(); //funcio per els avisos dels botons.
                // Funcio per evitar sortir sense guardar
                var avis = ""; // Variable buida
                $('input:text,input:checkbox,input:radio,textarea,select').one('change', function () {
                    avis = "Si surts ara no es guardaran els canvis fets.";

                    $('input:submit').click(function (e) { // Quan envia el formulari no actua.
                        avis = "";
                    });

                    window.onbeforeunload = function () {
                        if (avis !== '') {
                            return avis; // Avis de sortida sense guardar.
                        }
                    };
                });
            });

        </script>


    </head>

    <body>

        <div id="wrapper">


            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">Ca S'amitger</a>
                </div>

            </nav>

            <div id="page-wrapper">

                <div class="container-fluid">


                    <div class="row">


                    </div>

                    <div class="breadcrumb">
                        <div class="bs-example">
                            <?php
                            $pdoCore = Core::getInstance();

                            // Arriba el codi de la casa ----------------

                            if (isset($_GET['codcasa'])) {
                                $codicasa = $_GET['codcasa'];
                                $stmt = $pdoCore->db->prepare(
                                        "SELECT * FROM CASA  WHERE CAS_id = :casaid "
                                );
                                $stmt->execute(
                                        array(
                                            ':casaid' => $codicasa
                                        )
                                );
                                $casa = $stmt->fetchObject();
                                echo '<table class = "table">';
                                echo '<th colspan ="3" class="warning">Edició de la Casa: ' . $casa->CAS_nom . ' amb codi: ' . $casa->CAS_id . '</th>';
                                echo '</table>';
                            }
                            ?>
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#sectionA">DADES</a></li>
                                <li><a data-toggle="tab" href="#sectionB">GESTIÓ</a></li>
                                <li><a data-toggle="tab" href="#sectionC">INFORMACIÓ</a></li>
                                <li><a data-toggle="tab" href="#sectionD">ENTORN-VISTES</a></li>
                                <li><a data-toggle="tab" href="#sectionE">PISCINA-JARDI</a></li>
                                <li><a data-toggle="tab" href="#sectionF">EQUIPAMENT</a></li>
                                <li class="dropdown">
                                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">HABITACIONS<b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a data-toggle="tab" href="#dropdown1">CUINA</a></li>
                                        <li><a data-toggle="tab" href="#dropdown2">SALA</a></li>
                                        <li><a data-toggle="tab" href="#dropdown3">MENJADOR</a></li>
                                        <li><a data-toggle="tab" href="#dropdown4">DORMITORI</a></li>
                                        <li><a data-toggle="tab" href="#dropdown5">BANY</a></li>
                                        <li><a data-toggle="tab" href="#dropdown6">BUGADERIA</a></li>
                                        <li><a data-toggle="tab" href="#dropdown7">GENERAL</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="tab" href="#sectionG">INFORMACIÓ ADICIONAL</a></li>
                                <li><a data-toggle="tab" href="#sectionH">DISTÀNCIES</a></li>


                            </ul>
                            <div class="tab-content">


                                <div id="sectionA" class="tab-pane fade in active">

                                    <?php
                                    // ------------------------------------ Dades casa ---------------------------------------
                                    // Modificació dades

                                    if (isset($_POST['dades'])) {


                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE DADES_CASA SET  Address = :Address, "
                                                . "GeograficCoordinates = :geo, City = :city, PostCode = :post "
                                                . "WHERE DAD_id = :iddad"
                                        );


                                        $stmt->execute(array(':Address' => $_POST['Address'], ':geo' => $_POST['GeograficCoordinates'],
                                            ':city' => $_POST['City'], ':post' => $_POST['PostCode'], ':iddad' => $_POST['iddad']));
                                    }

                                    // Selecció de  dades

                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM DADES_CASA  WHERE DAD_codiCasa = :casaid "
                                    );
                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

                                    $casa = $stmt->fetchObject();

                                    // Formulari dades

                                    echo '<form action="#sectionA" method="post">';
                                    echo '<table class="table">';
                                    echo '<th colspan ="3" class="success">Dades Casa</th>';
                                    echo '<tr>';

                                    echo '<td>';
                                    echo '<input type = "hidden" name = "iddad" value = "' . $casa->DAD_id . '">';
                                    echo '<label for = "codiCasa"> CODI CASA </label>';
                                    echo "<input type = 'text' readonly=readonly "
                                    . "class = 'form-control' name = 'DAD_codiCasa' value = '$casa->DAD_codiCasa' size = '10'>";
                                    echo '</td><td>';
                                    echo '<label for = "address"> DIRECCIÓ </label>';
                                    echo '<input type = "text" class = "form-control" name = "Address" value = "' . $casa->Address . '" size = "10">';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<label for = "geo"> COORDENADES </label>';
                                    echo "<input type = 'text' pattern='^([-]?\d{1,2}[.]\d+),\s*([-]?\d{1,3}[.]\d+)$' "
                                    . "class = 'form-control' name = 'GeograficCoordinates' value = '$casa->GeograficCoordinates' size = '10' title='Latitud,longitud'>";
                                    echo '</td></tr>';
                                    echo '<tr><td><label for = "ciutat"> CIUTAT </label>';
                                    echo '<input type = "text" pattern= "[a-zA-Z\sàèòñáéíóú]*" class = "form-control" name = "City" value = "' . $casa->City . '" size = "10">';
                                    echo '</td><td>';
                                    echo '<label for = "post"> CODI POSTAL </label>';
                                    echo "<input type = 'text' pattern='\d{5}' class = 'form-control' name = 'PostCode' value = '$casa->PostCode' size = '10'><td></tr>";

                                    echo '<tr><td colspan = "3"><input type="submit" name ="dades" class="btn btn-primary" value="Modificar Dades" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"></td></tr>';

                                    echo '</table>';
                                    echo '</form>';
                                    ?>
                                </div>

                                <div id="sectionB" class="tab-pane fade">
                                    <?php

                                    // --------------------------------------------- Gestio -------------------------------------
                                    //
                                    // funcio per quan el check es igual a 'on' i el canvia per 1.
                                    function canvi($post) {
                                        if (isset($_POST[$post])) {
                                            if ($_POST[$post] == 'on') {
                                                $_POST[$post] = '1';
                                                RETURN $_POST[$post];
                                            }
                                        } else {
                                            $_POST[$post] = '0';
                                        }
                                    }

                                    // Modificació, formulari
                                    if (isset($_POST['gestio'])) {




                                        // crida de la funcio per els check

                                        canvi('netetges_ges_prop');
                                        canvi('netetges_ges_csm');
                                        canvi('netetges_ges_col');
                                        canvi('netetges_desp_prop');
                                        canvi('netetges_desp_csm');
                                        canvi('bugaderia_ges_prop');
                                        canvi('bugaderia_ges_csm');
                                        canvi('bugaderia_ges_col');
                                        canvi('bugaderia_desp_prop');
                                        canvi('bugaderia_desp_csm');
                                        canvi('posada_ges_prop');
                                        canvi('posada_ges_csm');
                                        canvi('posada_ges_col');
                                        canvi('posada_desp_prop');
                                        canvi('posada_desp_csm');

                                        canvi('piscina_ges_prop');
                                        canvi('piscina_ges_csm');
                                        canvi('piscina_ges_col');
                                        canvi('piscina_desp_prop');
                                        canvi('piscina_desp_csm');

                                        canvi('jardi_ges_prop');
                                        canvi('jardi_ges_csm');
                                        canvi('jardi_ges_col');
                                        canvi('jardi_desp_prop');
                                        canvi('jardi_desp_csm');

                                        canvi('entrades_ges_prop');
                                        canvi('entrades_ges_csm');
                                        canvi('entrades_ges_col');
                                        canvi('entrades_desp_prop');
                                        canvi('entrades_desp_csm');

                                        canvi('sortides_ges_prop');
                                        canvi('sortides_ges_csm');
                                        canvi('sortides_ges_col');
                                        canvi('sortides_desp_prop');
                                        canvi('sortides_desp_csm');
                                        canvi('capsa');
                                        canvi('KeyStore');
                                        canvi('ChangeOfClothesAutomatic');
                                        canvi('ChangeOfClothesOwner');


                                        // Modificació gestio

                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE GESTIO SET netetges_ges_prop = :netetgesgesprop, netetges_ges_csm = :netetgesgescsm, "
                                                . "netetges_ges_col = :netetgesgescol, netetges_desp_prop = :netetges_desp_prop, netetges_desp_csm = :netetges_desp_csm, "
                                                . "bugaderia_ges_prop = :bugaderia_ges_prop, bugaderia_ges_csm = :bugaderia_ges_csm, bugaderia_ges_col = :bugaderia_ges_col, "
                                                . "bugaderia_desp_prop = :bugaderia_desp_prop, bugaderia_desp_csm = :bugaderia_desp_csm, "
                                                . "posada_ges_prop = :posada_ges_prop, posada_ges_csm = :posada_ges_csm, posada_ges_col = :posada_ges_col, "
                                                . "posada_desp_prop = :posada_desp_prop, posada_desp_csm = :posada_desp_csm, "
                                                . "piscina_ges_prop = :piscina_ges_prop, piscina_ges_csm = :piscina_ges_csm, piscina_ges_col = :piscina_ges_col, "
                                                . "piscina_desp_prop = :piscina_desp_prop, piscina_desp_csm = :piscina_desp_csm, "
                                                . "jardi_ges_prop = :jardi_ges_prop, jardi_ges_csm = :jardi_ges_csm, jardi_ges_col = :jardi_ges_col, "
                                                . "jardi_desp_prop = :jardi_desp_prop, jardi_desp_csm = :jardi_desp_csm, "
                                                . "entrades_ges_prop = :entrades_ges_prop, entrades_ges_csm = :entrades_ges_csm, entrades_ges_col = :entrades_ges_col, "
                                                . "entrades_desp_prop = :entrades_desp_prop, entrades_desp_csm = :entrades_desp_csm, "
                                                . "sortides_ges_prop = :sortides_ges_prop, sortides_ges_csm = :sortides_ges_csm, sortides_ges_col = :sortides_ges_col, "
                                                . "sortides_desp_prop = :sortides_desp_prop, sortides_desp_csm = :sortides_desp_csm, SpecialConditionsPayment = :SpecialConditionsPayment, "
                                                . "capsa = :capsa, KeyStore = :KeyStore, KeyStoreCode = :KeyStoreCode, UbicationKeyStore = :UbicationKeyStore,"
                                                . " ChangeOfClothesAutomatic = :ChangeOfClothesAutomatic, ChangeOfClothesOwner = :ChangeOfClothesOwner "
                                                . "WHERE GES_id = :iddad"
                                        );


                                        $stmt->execute(array(':netetgesgesprop' => $_POST['netetges_ges_prop'], ':netetgesgescsm' => $_POST['netetges_ges_csm'], ':netetgesgescol' => $_POST['netetges_ges_col'],
                                            ':netetges_desp_prop' => $_POST['netetges_desp_prop'], ':netetges_desp_csm' => $_POST['netetges_desp_csm'],
                                            ':bugaderia_ges_prop' => $_POST['bugaderia_ges_prop'], ':bugaderia_ges_csm' => $_POST['bugaderia_ges_csm'], ':bugaderia_ges_col' => $_POST['bugaderia_ges_col'],
                                            ':bugaderia_desp_prop' => $_POST['bugaderia_desp_prop'], ':bugaderia_desp_csm' => $_POST['bugaderia_desp_csm'], ':iddad' => $_POST['gesid'],
                                            ':posada_ges_prop' => $_POST['posada_ges_prop'], ':posada_ges_csm' => $_POST['posada_ges_csm'], ':posada_ges_col' => $_POST['posada_ges_col'],
                                            ':posada_desp_prop' => $_POST['posada_desp_prop'], ':posada_desp_csm' => $_POST['posada_desp_csm'],
                                            ':piscina_ges_prop' => $_POST['piscina_ges_prop'], ':piscina_ges_csm' => $_POST['piscina_ges_csm'], ':piscina_ges_col' => $_POST['piscina_ges_col'],
                                            ':piscina_desp_prop' => $_POST['piscina_desp_prop'], ':piscina_desp_csm' => $_POST['piscina_desp_csm'],
                                            ':jardi_ges_prop' => $_POST['jardi_ges_prop'], ':jardi_ges_csm' => $_POST['jardi_ges_csm'], ':jardi_ges_col' => $_POST['jardi_ges_col'],
                                            ':jardi_desp_prop' => $_POST['jardi_desp_prop'], ':jardi_desp_csm' => $_POST['jardi_desp_csm'],
                                            ':entrades_ges_prop' => $_POST['entrades_ges_prop'], ':entrades_ges_csm' => $_POST['entrades_ges_csm'], ':entrades_ges_col' => $_POST['entrades_ges_col'],
                                            ':entrades_desp_prop' => $_POST['entrades_desp_prop'], ':entrades_desp_csm' => $_POST['entrades_desp_csm'],
                                            ':sortides_ges_prop' => $_POST['sortides_ges_prop'], ':sortides_ges_csm' => $_POST['sortides_ges_csm'], ':sortides_ges_col' => $_POST['sortides_ges_col'],
                                            ':sortides_desp_prop' => $_POST['sortides_desp_prop'], ':sortides_desp_csm' => $_POST['sortides_desp_csm'],
                                            ':SpecialConditionsPayment' => $_POST['SpecialConditionsPayment'], ':capsa' => $_POST['capsa'], ':KeyStore' => $_POST['KeyStore'], ':KeyStoreCode' => $_POST['KeyStoreCode'],
                                            ':UbicationKeyStore' => $_POST['UbicationKeyStore'], ':ChangeOfClothesAutomatic' => $_POST['ChangeOfClothesAutomatic'],
                                            ':ChangeOfClothesOwner' => $_POST['ChangeOfClothesOwner']
                                        ));
                                    }

                                    // Consulta taula GESTIO

                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM GESTIO G WHERE G.GES_codiCasa = :casaid "
                                    );
                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

                                    $ges = $stmt->fetchObject();


                                    // Formulari per GESTIO
                                    echo '<table class="table">';
                                    echo '<th colspan ="4" class="success">Gestio</th>';
                                    echo '</td></tr></table>';
                                    echo '<form action="#sectionB" method="post">';

                                    echo '<table class="table table-bordered" style="text-align:center">';
                                    echo '<tr><td style="border: none"></td><td colspan="3" class="warning">GESTIÓ a càrrec de:</td><td colspan="2" class="warning">DESPESES a càrrec de:</td></tr>';
                                    echo '<tr><td style="border: none"></td><td>Propietari</td><td>Casamitger</td><td>col·laborador</td><td>Propietari</td><td>Casamitger</td></tr>';

                                    echo '<input type="hidden" value=' . $ges->GES_id . ' name ="gesid">';

                                    echo '<tr><td class="warning">Netetges</td>'
                                    . '<td><input type="checkbox" name="netetges_ges_prop"  ' . ($ges->netetges_ges_prop ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="netetges_ges_csm"  ' . ($ges->netetges_ges_csm ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="netetges_ges_col"  ' . ($ges->netetges_ges_col ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="netetges_desp_prop" ' . ($ges->netetges_desp_prop ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="netetges_desp_csm" ' . ($ges->netetges_desp_csm ? "checked" : "" ) . '></td></tr>';



                                    echo '<tr><td class="warning">Bugaderia</td><td><input type="checkbox" name="bugaderia_ges_prop" value="' . $ges->bugaderia_ges_prop . ' " ' . ($ges->bugaderia_ges_prop ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="bugaderia_ges_csm" ' . ($ges->bugaderia_ges_csm ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="bugaderia_ges_col" ' . ($ges->bugaderia_ges_col ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="bugaderia_desp_prop" ' . ($ges->bugaderia_desp_prop ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="bugaderia_desp_csm" ' . ($ges->bugaderia_desp_csm ? "checked" : "" ) . '></td></tr>';


                                    echo '<tr><td class="warning">Posada a punt</td><td><input type="checkbox" name="posada_ges_prop" value="' . $ges->posada_ges_prop . ' " ' . ($ges->posada_ges_prop ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="posada_ges_csm" ' . ($ges->posada_ges_csm ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="posada_ges_col" ' . ($ges->posada_ges_col ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="posada_desp_prop" ' . ($ges->posada_desp_prop ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="posada_desp_csm" ' . ($ges->posada_desp_csm ? "checked" : "" ) . '></td></tr>';



                                    echo '<tr><td class="warning">piscina</td><td><input type="checkbox" name="piscina_ges_prop"  ' . ($ges->piscina_ges_prop ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="piscina_ges_csm" ' . ($ges->piscina_ges_csm ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="piscina_ges_col" ' . ($ges->piscina_ges_col ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="piscina_desp_prop" ' . ($ges->piscina_desp_prop ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="piscina_desp_csm" ' . ($ges->piscina_desp_csm ? "checked" : "" ) . '></td></tr>';


                                    echo '<tr><td class="warning">Jardí</td><td><input type="checkbox" name="jardi_ges_prop"  ' . ($ges->jardi_ges_prop ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="jardi_ges_csm" ' . ($ges->jardi_ges_csm ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="jardi_ges_col" ' . ($ges->jardi_ges_col ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="jardi_desp_prop" ' . ($ges->jardi_desp_prop ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="jardi_desp_csm" ' . ($ges->jardi_desp_csm ? "checked" : "" ) . '></td></tr>';


                                    echo '<tr><td class="warning">Entrades en persona</td><td><input type="checkbox" name="entrades_ges_prop"  ' . ($ges->entrades_ges_prop ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="entrades_ges_csm" ' . ($ges->entrades_ges_csm ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="entrades_ges_col" ' . ($ges->entrades_ges_col ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="entrades_desp_prop" ' . ($ges->entrades_desp_prop ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="entrades_desp_csm" ' . ($ges->entrades_desp_csm ? "checked" : "" ) . '></td></tr>';

                                    echo '<tr><td class="warning">Sortides en persona</td><td><input type="checkbox" name="sortides_ges_prop"  ' . ($ges->sortides_ges_prop ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="sortides_ges_csm" ' . ($ges->sortides_ges_csm ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="sortides_ges_col" ' . ($ges->sortides_ges_col ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="sortides_desp_prop" ' . ($ges->sortides_desp_prop ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="sortides_desp_csm" ' . ($ges->sortides_desp_csm ? "checked" : "" ) . '></td></tr>';

                                    echo '</table>';

                                    echo '<table class="table">';
                                    echo '<tr><td>Condicions Especials de Pagament</td><td><input type="text" name ="SpecialConditionsPayment" class = "form-control1" size="90" value="' . $ges->SpecialConditionsPayment . '" style="width:100%"></td></tr>';
                                    echo '<tr><td><input type="checkbox" name="capsa"  ' . ($ges->capsa ? "checked" : "" ) . '> Entrades i sortides amb capsa</td></tr>';
                                    echo '<tr><td colspan="5"><input type="checkbox" name="KeyStore"  ' . ($ges->KeyStore ? "checked" : "") . '>Caixa claus + contrasenya: '
                                    . '<input type="text" name="KeyStoreCode" class = "form-control1" value="' . $ges->KeyStoreCode . '" size="20"></td></tr>';
                                    echo '<tr><td>Lloc capseta:</td><td><textarea name="UbicationKeyStore" class = "form-control1" rows="2" cols="90" style="width:100%">' . $ges->UbicationKeyStore . '</textarea></td></tr>';
                                    echo '<tr><td>Canvi de roba:</td><td><input type="checkbox" name="ChangeOfClothesAutomatic"  ' . ($ges->ChangeOfClothesAutomatic ? "checked" : "" ) . '>'
                                    . ' Automatic <input type="checkbox" name="ChangeOfClothesOwner"  ' . ($ges->ChangeOfClothesOwner ? "checked" : "" ) . '> Propietari</td></tr>';

                                    echo '<tr><td><input type="submit" name= "gestio" class="btn btn-primary" id="gestio" value="Modificar Gestio" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"><td><tr>';

                                    echo '</table>';
                                    echo '</form>';
                                    ?>


                                </div>
                                <div id="sectionC" class="tab-pane fade">
                                    <?php
// ---------------------------------- Informacio Basica ---------------------------------
                                    // Modificació
                                    if (isset($_POST['infobas'])) {


                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE INFORMACIO_BASICA SET MaximumCapacity = :MaximumCapacity, ExtraCapacity = :ExtraCapacity, "
                                                . "capacitat_total = :capacitat_total, Plot = :Plot, House = :House, NumberBedrooms = :NumberBedrooms, "
                                                . "NumberBathrooms = :NumberBathrooms,ExtraCapacityType = :ExtraCapacityType "
                                                . "WHERE INF_id = :idinf"
                                        );


                                        $stmt->execute(array(':MaximumCapacity' => $_POST['MaximumCapacity'], ':ExtraCapacity' => $_POST['ExtraCapacity'],
                                            ':capacitat_total' => $_POST['capacitat_total'],
                                            ':Plot' => $_POST['Plot'], ':House' => $_POST['House'], ':NumberBedrooms' => $_POST['NumberBedrooms'],
                                            ':NumberBathrooms' => $_POST['NumberBathrooms'], ':ExtraCapacityType' => $_POST['ExtraCapacityType'], ':idinf' => $_POST['idinfo']));
                                    }

                                    // Consulta taula.
                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM INFORMACIO_BASICA I WHERE I.INF_codiCasa = :casaid "
                                    );
                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

                                    $inf = $stmt->fetchObject();
                                    //Formulari
                                    echo '<form action="#sectionC" method="post">';
                                    echo '<input type="hidden" name="idinfo" value="' . $inf->INF_id . '">';
                                    echo '<table class="table">';
                                    echo '<th colspan ="6" class="success">INFORMACIO BASICA</th>';
                                    echo '<tr><td>Capacitat Pax: </td><td><input type="number" name="MaximumCapacity" min="0" max="50" class = "form-control2" value="' . $inf->MaximumCapacity . '"></td>'
                                    . '<td>Extra Pax: </td><td><input type="number" name="ExtraCapacity" min="0" max="50" class = "form-control2"  value="' . $inf->ExtraCapacity . '"></td>';
                                    echo '<td>Tipus Plaça Extra: <select name="ExtraCapacityType" class = "form-control3l">';
                                    echo '<option  value="" ' . ($dor->ExtraCapacityType == " " ? "selected='selected'" : " ") . '></option>';
                                    echo '<option  value="sofà-llit" ' . ($dor->ExtraCapacityType == "sofà-llit" ? "selected='selected'" : " ") . '>sofà-llit</option>';
                                    echo '<option  value="sofà-llit individual" ' . ($dor->ExtraCapacityType == "sofà-llit individual" ? "selected='selected'" : " ") . '>sofà-llit individual</option>';
                                    echo '<option  value="plegatin doble" ' . ($dor->ExtraCapacityType == "plegatin doble" ? "selected='selected'" : " ") . '>plegatin doble</option>';
                                    echo '<option value = "plegatin individual" ' . ($dor->ExtraCapacityType == "plegatin individual" ? "selected='selected'" : " ") . '>plegatin individual</option>';
                                    echo '</select></td></tr>';
                                    echo '<tr><td>Capacitat Total Pax: </td><td><input type="number" name="capacitat_total" class = "form-control2" min="0" max="50" value="' . $inf->capacitat_total . '"></td>';
                                    echo '<td>Parcel-la m²: </td><td><input type="number" min="0" max="100000" name="Plot" class = "form-control2" value="' . $inf->Plot . '"></td><td>Vivienda m²: '
                                    . '<input type="number" min="0" max="10000" name="House" class = "form-control2l" value="' . $inf->House . '"></td></tr>';
                                    echo '<tr><td>Dormitoris nº: </td><td><input type="number" name="NumberBedrooms" class = "form-control2" min="0" max="20" value="' . $inf->NumberBedrooms . '"></td>'
                                    . '<td>Banys nº:  </td><td colspan="3"><input type="number" class = "form-control2" name="NumberBathrooms" min="0" max="20" value="' . $inf->NumberBathrooms . '"></td></tr>';
                                    echo '<tr><td colspan = "3"><input type="submit" name="infobas" class="btn btn-primary" value="Modificar Informació" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"><td><tr>';
                                    echo '</table>';


                                    echo '</form>';
                                    ?>
                                </div>

                                <div id="sectionD" class="tab-pane fade">
                                    <?php
// ------------------------------- Entorn i vistes --------------------------------------

                                    if (isset($_POST['entorn'])) {


                                        canvi('FirstLineToBeach');
                                        canvi('NoViews');
                                        canvi('PrivacyPartial');
                                        canvi('Mountain');
                                        canvi('SeaView');
                                        canvi('PrivacyTotal');
                                        canvi('Countryside');
                                        canvi('TownView');
                                        canvi('Village');
                                        canvi('ForestView');
                                        canvi('ResidentialArea');
                                        canvi('CountrysideView');
                                        canvi('CoastZone');
                                        canvi('MountainView');
                                        canvi('Neighbors');
                                        canvi('GolfView');


                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE ENTORN_VISTES SET FirstLineToBeach = :FirstLineToBeach, NoViews = :NoViews, "
                                                . "PrivacyPartial = :PrivacyPartial, Mountain = :Mountain, SeaView = :SeaView, "
                                                . "PrivacyTotal = :PrivacyTotal, Countryside = :Countryside, TownView = :TownView, "
                                                . "Village = :Village, ForestView = :ForestView, "
                                                . "ResidentialArea = :ResidentialArea, CountrysideView = :CountrysideView, CoastZone = :CoastZone, "
                                                . "MountainView = :MountainView, Neighbors = :Neighbors, "
                                                . "GolfView = :GolfView "
                                                . "WHERE ENT_id = :ident"
                                        );


                                        $stmt->execute(array(':FirstLineToBeach' => $_POST['FirstLineToBeach'], ':NoViews' => $_POST['NoViews'], ':PrivacyPartial' => $_POST['PrivacyPartial'],
                                            ':Mountain' => $_POST['Mountain'], ':SeaView' => $_POST['SeaView'],
                                            ':PrivacyTotal' => $_POST['PrivacyTotal'], ':Countryside' => $_POST['Countryside'], ':TownView' => $_POST['TownView'],
                                            ':Village' => $_POST['Village'], ':ForestView' => $_POST['ForestView'], ':ResidentialArea' => $_POST['ResidentialArea'],
                                            ':CountrysideView' => $_POST['CountrysideView'], ':CoastZone' => $_POST['CoastZone'], ':MountainView' => $_POST['MountainView'],
                                            ':Neighbors' => $_POST['Neighbors'], ':GolfView' => $_POST['GolfView'],
                                            ':ident' => $_POST['ident']
                                        ));
                                    }


                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM ENTORN_VISTES E WHERE E.ENT_codiCasa = :casaid "
                                    );
                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

                                    $ent = $stmt->fetchObject();

                                    echo '<form action="#sectionD" method="post">';
                                    echo '<table class="table">';
                                    echo '<th colspan ="4" class="success">ENTORN I VISTES</th>';
                                    echo '<tr><td>';
                                    echo '<table class="table">';
                                    echo '<th style="border:solid 1px grey">Situacio</th>';
                                    echo '<input type = "hidden" value="' . $ent->ENT_id . ' "  name ="ident">';
                                    echo '<tr><td><input type="checkbox" name="FirstLineToBeach"  ' . ($ent->FirstLineToBeach ? "checked" : "" ) . '>1ª linia de mar</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="Mountain"  ' . ($ent->Mountain ? "checked" : "") . '>Zona montanya</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="Countryside"  ' . ($ent->Countryside ? "checked" : "") . '>Camp</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="Village"  ' . ($ent->Village ? "checked" : "") . '>Poble</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="ResidentialArea"  ' . ($ent->ResidentialArea ? "checked" : "") . '>Urbanització</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="CoastZone"  ' . ($ent->CoastZone ? "checked" : "") . '>Zona Costanera</td></tr>';

                                    echo '</table>';
                                    echo '</td>';


                                    echo '<td>';
                                    echo '<table class="table">';
                                    echo '<th style="border:solid 1px grey">Vistes</th>';
                                    echo '<tr><td><input type="checkbox" name="NoViews"  ' . ($ent->NoViews ? "checked" : "") . '>Sense vistes</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="SeaView"  ' . ($ent->SeaView ? "checked" : "") . '>Mar</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="TownView"  ' . ($ent->TownView ? "checked" : "") . '>Poble</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="ForestView"  ' . ($ent->ForestView ? "checked" : "") . '>Bosc</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="CountrysideView"  ' . ($ent->CountrysideView ? "checked" : "") . '>Camp</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="MountainView"  ' . ($ent->MountainView ? "checked" : "") . '>Muntanyes</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="Neighbors"  ' . ($ent->Neighbors ? "checked" : "") . '>Veïns a menys de 50m</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="GolfView"  ' . ($ent->GolfView ? "checked" : "") . '>Vistes a camp de golf</td></tr>';


                                    echo '</table>';
                                    echo '</td>';



                                    echo '<td>';
                                    echo '<table class="table">';
                                    echo '<th style="border:solid 1px grey">Privacitat</th>';
                                    echo '<tr><td><input type="checkbox" name="PrivacyPartial"  ' . ($ent->PrivacyPartial ? "checked" : "") . '>Total</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="PrivacyTotal"  ' . ($ent->PrivacyTotal ? "checked" : "") . '>Parcial</td></tr>';
                                    echo '</table>';
                                    echo '</td></tr>';

                                    echo '<tr><td colspan = "3"><input type="submit" name = "entorn" class="btn btn-primary" value="Modificar Entorn-vistes" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"><td><tr>';

                                    echo '</table>';
                                    echo '</form>';
                                    ?>
                                </div>


                                <div id="sectionE" class="tab-pane fade">
                                    <?php
// --------------------------------- Piscina jardi ------------------------------------------------------

                                    if (isset($_POST['piscina'])) {


                                        canvi('PrivatePool');
                                        canvi('NoViews');
                                        canvi('FencedPool');
                                        canvi('SwimmingPoolWithSalt');
                                        canvi('SwimmingPoolWithChlorine');
                                        canvi('HeatedSwimmingPool');
                                        canvi('IntegratedJacuzzi');
                                        canvi('SeparateJacuzzi');
                                        canvi('ChildrenPool');
                                        canvi('FixedBarbecue');
                                        canvi('MobileBarbecue');
                                        canvi('OutdoorShower');
                                        canvi('Umbrellas');
                                        canvi('DeckChair');
                                        canvi('ThereBalcony');
                                        canvi('ThereTerrace1');
                                        canvi('ThereTerrace2');
                                        canvi('ThereTerrace3');
                                        canvi('FurnishedTerrace1');
                                        canvi('FurnishedTerrace2');
                                        canvi('FurnishedTerrace3');
                                        canvi('FurnishedPorche1');
                                        canvi('FurnishedPorche2');
                                        canvi('FurnishedPorche3');
                                        canvi('TableTennis');
                                        canvi('IndoorPool');
                                        canvi('Floor');
                                        canvi('Grass');
                                        canvi('VegetableGarden');
                                        canvi('Fence');
                                        canvi('FruitTree');
                                        canvi('FloorSurfaceCommunity');
                                        canvi('GrassCommunity');
                                        canvi('FenceCommunity');
                                        canvi('AboveGroundPool');
                                        canvi('PrivacyPartial');
                                        canvi('SharedSwimmingPool');
                                        canvi('FloorCommunity');




                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE PISCINA_JARDI SET PrivatePool = :PrivatePool, SharedSwimmingPool = :SharedSwimmingPool, "
                                                . "FencedPool = :FencedPool, PoolDimensions = :PoolDimensions, PoolDepth = :PoolDepth, "
                                                . "AboveGroundPool = :AboveGroundPool, ProgrammeLightsPool = :ProgrammeLightsPool, SwimmingPoolWithSalt = :SwimmingPoolWithSalt, "
                                                . "SwimmingPoolWithChlorine = :SwimmingPoolWithChlorine, HeatedSwimmingPool = :HeatedSwimmingPool, "
                                                . "IntegratedJacuzzi = :IntegratedJacuzzi, SeparateJacuzzi = :SeparateJacuzzi, ChildrenPool = :ChildrenPool, "
                                                . "FixedBarbecue = :FixedBarbecue, OutdoorShower = :OutdoorShower, "
                                                . "MobileBarbecue = :MobileBarbecue, Umbrellas = :Umbrellas, DeckChair = :DeckChair, "
                                                . "NumberOfUmbrellas = :NumberOfUmbrellas, NumberOfDeckChair = :NumberOfDeckChair, "
                                                . "ThereTerrace1 = :ThereTerrace1, "
                                                . "ThereTerrace2 = :ThereTerrace2, "
                                                . "ThereTerrace3 = :ThereTerrace3, "
                                                . "TerraceDimensions1 = :TerraceDimensions1, "
                                                . "TerraceDimensions2 = :TerraceDimensions2, "
                                                . "TerraceDimensions3 = :TerraceDimensions3, "
                                                . "ThereBalcony = :ThereBalcony, "
                                                . "BalconyDimensions = :BalconyDimensions, "
                                                . "FurnishedTerrace1 = :FurnishedTerrace1, "
                                                . "FurnishedTerrace2 = :FurnishedTerrace2, "
                                                . "FurnishedTerrace3 = :FurnishedTerrace3, "
                                                . "FurnishedPorche1 = :FurnishedPorche1, "
                                                . "FurnishedPorche2 = :FurnishedPorche2, "
                                                . "FurnishedPorche3 = :FurnishedPorche3, "
                                                . "PorchDimensions1 = :PorchDimensions1, "
                                                . "PorchDimensions2 = :PorchDimensions2, "
                                                . "PorchDimensions3 = :PorchDimensions3, "
                                                . "TableTennis = :TableTennis, IndoorPool = :IndoorPool, Floor = :Floor, "
                                                . "FloorSurface = :FloorSurface, Grass = :Grass, "
                                                . "GrassSurface = :GrassSurface, VegetableGarden = :VegetableGarden, Fence = :Fence, "
                                                . "FruitTree = :FruitTree, GardenAcces = :GardenAcces, FloorCommunity = :FloorCommunity, "
                                                . "FloorSurfaceCommunity = :FloorSurfaceCommunity, GrassCommunity = :GrassCommunity, GrassSurfaceCommunity = :GrassSurfaceCommunity, "
                                                . " FenceCommunity = :FenceCommunity, "
                                                . " GardenNotes = :GardenNotes "
                                                . "WHERE PIS_id = :idpis"
                                        );


                                        $stmt->execute(array(':PrivatePool' => $_POST['PrivatePool'], ':SharedSwimmingPool' => $_POST['SharedSwimmingPool'], ':FencedPool' => $_POST['FencedPool'],
                                            ':PoolDimensions' => $_POST['PoolDimensions'], ':PoolDepth' => $_POST['PoolDepth'],
                                            ':AboveGroundPool' => $_POST['AboveGroundPool'], ':ProgrammeLightsPool' => $_POST['ProgrammeLightsPool'], ':SwimmingPoolWithSalt' => $_POST['SwimmingPoolWithSalt'],
                                            ':SwimmingPoolWithChlorine' => $_POST['SwimmingPoolWithChlorine'], ':HeatedSwimmingPool' => $_POST['HeatedSwimmingPool'], ':IntegratedJacuzzi' => $_POST['IntegratedJacuzzi'],
                                            ':SeparateJacuzzi' => $_POST['SeparateJacuzzi'], ':ChildrenPool' => $_POST['ChildrenPool'], ':FixedBarbecue' => $_POST['FixedBarbecue'],
                                            ':OutdoorShower' => $_POST['OutdoorShower'], ':MobileBarbecue' => $_POST['MobileBarbecue'],
                                            ':Umbrellas' => $_POST['Umbrellas'], ':DeckChair' => $_POST['DeckChair'], ':NumberOfUmbrellas' => $_POST['NumberOfUmbrellas'],
                                            ':NumberOfDeckChair' => $_POST['NumberOfDeckChair'],
                                            ':ThereBalcony' => $_POST['ThereBalcony'],
                                            ':BalconyDimensions' => $_POST['BalconyDimensions'],
                                            ':ThereTerrace1' => $_POST['ThereTerrace1'],
                                            ':ThereTerrace2' => $_POST['ThereTerrace2'],
                                            ':ThereTerrace3' => $_POST['ThereTerrace3'],
                                            ':TerraceDimensions1' => $_POST['TerraceDimensions1'],
                                            ':TerraceDimensions2' => $_POST['TerraceDimensions2'],
                                            ':TerraceDimensions3' => $_POST['TerraceDimensions3'],
                                            ':FurnishedTerrace1' => $_POST['FurnishedTerrace1'],
                                            ':FurnishedTerrace2' => $_POST['FurnishedTerrace2'],
                                            ':FurnishedTerrace3' => $_POST['FurnishedTerrace3'],
                                            ':FurnishedPorche1' => $_POST['FurnishedPorche1'],
                                            ':FurnishedPorche2' => $_POST['FurnishedPorche2'],
                                            ':FurnishedPorche3' => $_POST['FurnishedPorche3'],
                                            ':PorchDimensions1' => $_POST['PorchDimensions1'],
                                            ':PorchDimensions2' => $_POST['PorchDimensions2'],
                                            ':PorchDimensions3' => $_POST['PorchDimensions3'],
                                            ':TableTennis' => $_POST['TableTennis'],
                                            ':IndoorPool' => $_POST['IndoorPool'], ':Floor' => $_POST['Floor'], ':FloorSurface' => $_POST['FloorSurface'],
                                            ':Grass' => $_POST['Grass'], ':GrassSurface' => $_POST['GrassSurface'],
                                            ':VegetableGarden' => $_POST['VegetableGarden'], ':Fence' => $_POST['Fence'], ':FruitTree' => $_POST['FruitTree'],
                                            ':GardenAcces' => $_POST['GardenAcces'], ':FloorCommunity' => $_POST['FloorCommunity'],
                                            ':FloorSurfaceCommunity' => $_POST['FloorSurfaceCommunity'], ':GrassCommunity' => $_POST['GrassCommunity'], ':GrassSurfaceCommunity' => $_POST['GrassSurfaceCommunity'],
                                            ':FenceCommunity' => $_POST['FenceCommunity'], ':GardenNotes' => $_POST['GardenNotes'],
                                            ':idpis' => $_POST['idpis']
                                        ));
                                    }






                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM PISCINA_JARDI P WHERE P.PIS_codiCasa = :casaid "
                                    );
                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

                                    $pis = $stmt->fetchObject();
                                    echo '<form action="#sectionE" method="post">';

                                    echo '<table class="table table-responsive table-condensed">';
                                    echo '<th colspan ="7" class="success">PISCINA</th>';
                                    echo '<input type="hidden" name = "idpis" value = "' . $pis->PIS_id . '">';
                                    echo '<tr><td colspan="2"><input type="checkbox" name ="PrivatePool"  ' . ($pis->PrivatePool ? "checked" : "") . '>Privada</td>'
                                    . '<td><input type="checkbox" name="SharedSwimmingPool"  ' . ($pis->SharedSwimmingPool ? "checked" : "") . '>Compartida</td>'
                                    . '<td><td colspan="3"><input type="checkbox" name="FencedPool"  ' . ($pis->FencedPool ? "checked" : "") . '>Protecció per a nins(Vallada)</td></tr>';


                                    echo '<tr><td>Dimensions: </td><td><input type="text" name="PoolDimensions" class = "form-control3" size="10" value="' . $pis->PoolDimensions . '" data-toggle="tooltip" data-placement="top" title="En metres"></td>'
                                    . '<td>Profunditat: </td>'
                                    . '<td><input type="text" name="PoolDepth" class = "form-control2l" size="6" value="' . $pis->PoolDepth . '"></td>'
                                    . '<td colspan="2"><input type="checkbox" name="AboveGroundPool"  ' . ($pis->AboveGroundPool ? "checked" : "") . '>Elevada</td></tr>';
                                    echo '<tr><td>Horari llums: </td><td><input type="text" name="ProgrammeLightsPool" class = "form-control3" size="10" value="' . $pis->ProgrammeLightsPool . '"></td>'
                                    . '<td colspan="2"><input type="checkbox" name="SwimmingPoolWithSalt"  ' . ($pis->SwimmingPoolWithSalt ? "checked" : "") . '>Sal</td>'
                                    . '<td colspan="3"><input type="checkbox" name="SwimmingPoolWithChlorine"  ' . ($pis->SwimmingPoolWithChlorine ? "checked" : "") . '>Clor</td></tr>';
                                    echo '<tr><td colspan="2"><input type="checkbox" name="HeatedSwimmingPool"  ' . ($pis->HeatedSwimmingPool ? "checked" : "") . '>Cimatizada</td>'
                                    . '<td><input type="checkbox" name="IntegratedJacuzzi"  ' . ($pis->IntegratedJacuzzi ? "checked" : "") . '>Jacuzzi integrat</td>'
                                    . '<td colspan="4"><input type="checkbox" name="SeparateJacuzzi"  ' . ($pis->SeparateJacuzzi ? "checked" : "") . '>Jacuzzi separat</td></tr>';
                                    echo '<tr><td colspan="2"><input type="checkbox" name="ChildrenPool"  ' . ($pis->ChildrenPool ? "checked" : "") . '>Piscina infantil</td>'
                                    . '<td><input type="checkbox" name="FixedBarbecue"  ' . ($pis->FixedBarbecue ? "checked" : "") . '>Barbacoa fixa</td>'
                                    . '<td colspan="4"><input type="checkbox" name="MobileBarbecue"  ' . ($pis->MobileBarbecue ? "checked" : "") . '>Barbacoa móbil</td></tr>';
                                    echo '<tr><td colspan="2"><input type="checkbox" name="OutdoorShower"  ' . ($pis->OutdoorShower ? "checked" : "") . '>Ducha exterior</td>'
                                    . '<td><input type="checkbox" name="Umbrellas"  ' . ($pis->Umbrellas ? "checked" : "") . '>Sombrilles nº:</td>'
                                    . '<td><input type="number" min="0" max="30" name="NumberOfUmbrellas" class = "form-control2l" value="' . $pis->NumberOfUmbrellas . '"></td>'
                                    . '<td><input type="checkbox" name="DeckChair"  ' . ($pis->DeckChair ? "checked" : "") . '>Tumbones  nº: </td>'
                                    . '<td><input type="number" name="NumberOfDeckChair" class = "form-control2l" min="0" max="30" value="' . $pis->NumberOfDeckChair . '"></td></tr>';
                                    echo '<td><input type="checkbox" name="ThereTerrace1"   ' . ($pis->ThereTerrace1 ? "checked" : "") . '>Terrassa1: </td>'
                                    . '<td><input type="number" name="TerraceDimensions1" class = "form-control2l" min="0" max="1000" value="' . $pis->TerraceDimensions1 . '"> m² </td>'
                                    . '<td colspan="2"><input type="checkbox" name=""  ' . ($pis->FurnishedTerrace1 ? "checked" : "") . '>Terrassa amoblada1</td>'
                                    . '<td><input type="checkbox" name="ThereTerrace2"  ' . ($pis->ThereTerrace2 ? "checked" : "") . '>Terrassa2: </td>'
                                    . '<td><input type="number" name="TerraceDimensions2" class = "form-control2l" min="0" max="1000" value="' . $pis->TerraceDimensions2 . '"> m²</td>'
                                    . '<td colspan="2"><input type="checkbox" name=""  ' . ($pis->FurnishedTerrace2 ? "checked" : "") . '>Terrassa amoblada2</td><tr>';
                                    echo '<td><input type="checkbox" name="ThereTerrace3"  ' . ($pis->ThereTerrace3 ? "checked" : "") . '>Terrassa3: </td>'
                                    . '<td><input type="number" name="TerraceDimensions3" class = "form-control2l" min="0" max="1000" value="' . $pis->TerraceDimensions3 . '"> m²</td>'
                                    . '<td colspan="2"><input type="checkbox" name=""  ' . ($pis->FurnishedTerrace3 ? "checked" : "") . '>Terrassa amoblada3</td>'
                                    . '<td><input type="checkbox" name="ThereBalcony"  ' . ($pis->ThereBalcony ? "checked" : "") . '>Balcó: </td>'
                                    . '<td><input type="number" name="BalconyDimensions" class = "form-control2l" min="0" max="1000" value="' . $pis->BalconyDimensions . '"> m</td>'
                                    . '<td colspan="2"><input type="checkbox" name="FurnishedBalcony"  ' . ($pis->FurnishedBalcony ? "checked" : "") . '>Balco amoblat</td><tr>';

                                    echo '<tr><td><input type="checkbox" name="FurnishedPorche1"  ' . ($pis->FurnishedPorche1 ? "checked" : "") . '>Porxo1</td>'
                                    . '<td><input type="number" name="PorchDimensions1" class = "form-control2l" min="0" max="1000" value="' . $pis->PorchDimensions1 . '"> m²</td>';
                                    echo '<td><input type="checkbox" name="FurnishedPorche2"  ' . ($pis->FurnishedPorche2 ? "checked" : "") . '>Porxo2</td>'
                                    . '<td><input type="number" name="PorchDimensions2" class = "form-control2l" min="0" max="1000" value="' . $pis->PorchDimensions2 . '"> m²</td>';
                                    echo '<td><input type="checkbox" name="FurnishedPorche3"  ' . ($pis->FurnishedPorche3 ? "checked" : "") . '>Porxo3'
                                    . '</td><td><input type="number" name="PorchDimensions3" class = "form-control2l" min="0" max="1000" value="' . $pis->PorchDimensions3 . '"> m² </td></tr>';
                                    echo '<tr><td colspan="2"><input type="checkbox" name="TableTennis"  ' . ($pis->TableTennis ? "checked" : "") . '>Ping-Pong</td>'
                                    . '<td colspan="5"><input type="checkbox" name="IndoorPool"  ' . ($pis->IndoorPool ? "checked" : "") . '>Piscina interior</td></tr>';

                                    echo '<th colspan ="7" class="success">Jardi Privat</th>';
                                    echo '<tr><td><input type="checkbox" name="Floor"  ' . ($pis->Floor ? "checked" : "") . '>Trispol: </td>'
                                    . '<td><input type="number" min="0" max="1000" name="FloorSurface" class = "form-control2l" value="' . $pis->FloorSurface . '"> m²</td>'
                                    . '<td><input type="checkbox" name="Grass"  ' . ($pis->Grass ? "checked" : "") . '>Gespa</td>'
                                    . '<td  colspan="4"><input type="number" min="0" max="1000" name="GrassSurface" class = "form-control2l" value="' . $pis->GrassSurface . '"> m²</td></tr>';
                                    echo '<tr><td colspan="2"><input type="checkbox" name="VegetableGarden"  ' . ($pis->VegetableGarden ? "checked" : "") . '>Hort</td>'
                                    . '<td><input type="checkbox" name="Fence" value="' . $pis->Fence . ' " ' . ($pis->Fence ? "checked" : "") . '>Vallat</td><td>'
                                    . '<td colspan="2"><input type="checkbox" name="FruitTree"  ' . ($pis->FruitTree ? "checked" : "") . '>Arbres fruiters</td></tr>';
                                    echo '<tr><td>Accés a l`hort</td><td colspan="6"><textarea rows=2 name="GardenAcces" class = "form-control" cols=125 style="width:100%">' . $pis->GardenAcces . '</textarea></td></tr>';
                                    echo '<th colspan ="7" class="success">Jardi Comunitari</th>';
                                    echo '<tr><td><input type="checkbox" name="FloorCommunity"  ' . ($pis->FloorCommunity ? "checked" : "") . '>Trispol: </td>'
                                    . '<td><input type="number" min="0" max="1000" name="FloorSurfaceCommunity" class = "form-control2l" value="' . $pis->FloorSurfaceCommunity . '"> m²</td>'
                                    . '<td><input type="checkbox" name="GrassCommunity"  ' . ($pis->GrassCommunity ? "checked" : "") . '>Gespa</td>'
                                    . '<td  colspan="2"><input type="number" min="0" max="1000" name="GrassSurfaceCommunity" class = "form-control2l" value="' . $pis->GrassSurfaceCommunity . '"> m²</td>'
                                    . '<td><input type="checkbox" name="FenceCommunity"  ' . ($pis->FenceCommunity ? "checked" : "") . '>Vallat</td></td></tr>';
                                    echo '<tr><td>Notes: </td><td colspan="6"><textarea name="GardenNotes" class = "form-control" rows=2 cols=125 style="width:100%">' . $pis->GardenNotes . '</textarea></td></tr>';

                                    echo '<tr><td><input type="submit" value="Modificar Piscina-jardi" name = "piscina" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"><td><tr>';

                                    echo '</table>';
                                    echo '</form>';
                                    ?>

                                </div>
                                <div id="sectionF" class="tab-pane fade">
                                    <?php
// --------------------------------------- Equipament ----------------------------------------------

                                    if (isset($_POST['equipament'])) {

                                        canvi('AirConditioning');
                                        canvi('AirConditioningNumber');
                                        canvi('GasStove');
                                        canvi('WoodStove');
                                        canvi('CentralDieselHeating');
                                        canvi('CentralGasHeating');
                                        canvi('ElectricRadiators');
                                        canvi('Chimney');
                                        canvi('Fans');
                                        canvi('UnderfloorHeating');
                                        canvi('Wifi');
                                        canvi('Television');
                                        canvi('SatelliteTelevision');
                                        canvi('EnglishChannel');
                                        canvi('GermanChannel');
                                        canvi('ItalianChannel');
                                        canvi('FrenchChannel');
                                        canvi('DutchChannel');
                                        canvi('RussianChannel');
                                        canvi('ArabianChannel');
                                        canvi('DvdPlayer');
                                        canvi('DVD');
                                        canvi('CD');
                                        canvi('CdPlayer');
                                        canvi('ChildrensGames');
                                        canvi('Films');
                                        canvi('VideoGames');
                                        canvi('Books');
                                        canvi('Strongbox');
                                        canvi('AlarmSystem');



                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE EQUIPAMENT SET AirConditioning = :AirConditioning, AirConditioningLocation = :AirConditioningLocation, "
                                                . "AirConditioningNumber = :AirConditioningNumber, AirConditioningNotes = :AirConditioningNotes, GasStove = :GasStove, "
                                                . "WoodStove = :WoodStove, CentralDieselHeating = :CentralDieselHeating, CentralGasHeating = :CentralGasHeating, "
                                                . "ElectricRadiators = :ElectricRadiators, NumberOfElectricRadiators = :NumberOfElectricRadiators, "
                                                . "Chimney = :Chimney, NumberOfFireplaces = :NumberOfFireplaces, Fans = :Fans, "
                                                . "NumberOfFans = :NumberOfFans, ContractedPower = :ContractedPower, "
                                                . "UnderfloorHeating = :UnderfloorHeating, Wifi = :Wifi, WifiCode = :WifiCode, "
                                                . "ElectricalBox = :ElectricalBox, Television = :Television, "
                                                . "SatelliteTelevision = :SatelliteTelevision, EnglishChannel = :EnglishChannel, GermanChannel = :GermanChannel, "
                                                . "ItalianChannel = :ItalianChannel, FrenchChannel = :FrenchChannel, "
                                                . "DutchChannel = :DutchChannel, RussianChannel = :RussianChannel, ArabianChannel = :ArabianChannel, "
                                                . "DvdPlayer = :DvdPlayer, DVD = :DVD, "
                                                . "CD = :CD, CdPlayer = :CdPlayer, ChildrensGames = :ChildrensGames, "
                                                . "Films = :Films, VideoGames = :VideoGames, TypeOfVideoGames = :TypeOfVideoGames, "
                                                . "Books = :Books, TypeOfBooks = :TypeOfBooks, Strongbox = :Strongbox, "
                                                . "AlarmSystem = :AlarmSystem "
                                                . "WHERE EQU_id = :idequ"
                                        );


                                        $stmt->execute(array(':AirConditioning' => $_POST['AirConditioning'], ':AirConditioningLocation' => $_POST['AirConditioningLocation'],
                                            ':AirConditioningNumber' => $_POST['AirConditioningNumber'],
                                            ':AirConditioningNotes' => $_POST['AirConditioningNotes'], ':GasStove' => $_POST['GasStove'],
                                            ':WoodStove' => $_POST['WoodStove'], ':CentralDieselHeating' => $_POST['CentralDieselHeating'], ':CentralGasHeating' => $_POST['CentralGasHeating'],
                                            ':ElectricRadiators' => $_POST['ElectricRadiators'], ':NumberOfElectricRadiators' => $_POST['NumberOfElectricRadiators'], ':Chimney' => $_POST['Chimney'],
                                            ':NumberOfFireplaces' => $_POST['NumberOfFireplaces'], ':Fans' => $_POST['Fans'], ':NumberOfFans' => $_POST['NumberOfFans'],
                                            ':ContractedPower' => $_POST['ContractedPower'], ':UnderfloorHeating' => $_POST['UnderfloorHeating'],
                                            ':Wifi' => $_POST['Wifi'], ':WifiCode' => $_POST['WifiCode'], ':ElectricalBox' => $_POST['ElectricalBox'],
                                            ':Television' => $_POST['Television'], ':SatelliteTelevision' => $_POST['SatelliteTelevision'],
                                            ':EnglishChannel' => $_POST['EnglishChannel'], ':GermanChannel' => $_POST['GermanChannel'], ':ItalianChannel' => $_POST['ItalianChannel'],
                                            ':FrenchChannel' => $_POST['FrenchChannel'], ':DutchChannel' => $_POST['DutchChannel'],
                                            ':RussianChannel' => $_POST['RussianChannel'], ':ArabianChannel' => $_POST['ArabianChannel'], ':DvdPlayer' => $_POST['DvdPlayer'],
                                            ':DVD' => $_POST['DVD'], ':CD' => $_POST['CD'],
                                            ':CdPlayer' => $_POST['CdPlayer'], ':ChildrensGames' => $_POST['ChildrensGames'], ':Films' => $_POST['Films'],
                                            ':VideoGames' => $_POST['VideoGames'], ':TypeOfVideoGames' => $_POST['TypeOfVideoGames'],
                                            ':Books' => $_POST['Books'], ':TypeOfBooks' => $_POST['TypeOfBooks'], ':Strongbox' => $_POST['Strongbox'],
                                            ':AlarmSystem' => $_POST['AlarmSystem'],
                                            ':idequ' => $_POST['idequ']
                                        ));
                                    }


                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM EQUIPAMENT E WHERE E.EQU_codiCasa = :casaid "
                                    );
                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

                                    $equ = $stmt->fetchObject();
                                    echo '<form action="#sectionF" method="post">';

                                    echo '<table class="table table-responsive">';
                                    echo '<th colspan ="6" class="success">Climatització</th>';
                                    echo '<input type = "hidden" value = "' . $equ->EQU_id . '" name = "idequ">';
                                    echo '<tr><td><input type="checkbox" name="AirConditioning"  ' . ($equ->AirConditioning ? "checked" : "") . '>Aire acondicionat: </td>'
                                    . '<td>on i quans <input type="text" name="AirConditioningLocation" class = "form-control2l" size = "20" value="' . $equ->AirConditioningLocation . '"></td>'
                                    . '<td colspan="4">Situació: <select name="AirConditioningNumber" class = "form-control1">';
                                    echo '<option  value="" ' . ($equ->AirConditioningNumber == " " ? "selected='selected'" : " ") . '></option>';
                                    echo '<option  value="tot" ' . ($equ->AirConditioningNumber == "tot" ? "selected='selected'" : " ") . '>Per tot</option>';
                                    echo '<option value = "qualcunes_estancies" ' . ($equ->AirConditioningNumber == "qualcunes_estancies" ? "selected='selected'" : " ") . '>Qualcunes estancies</option>';
                                    echo '</select></td></tr>';
                                    echo '<tr><td>Notes aire acondicionat: </td><td colspan="5"><textarea name="AirConditioningNotes" class = "form-control" rows=2 cols=80 style="width:100%">' . $equ->AirConditioningNotes . '</textarea></td></tr>';
                                    echo '<tr><td colspan = "2"><input type="checkbox" name="GasStove"  ' . ($equ->GasStove ? "checked" : "") . '>Estufa de Gas </td>'
                                    . '<td colspan="4"><input type="checkbox" name="WoodStove" value="' . $equ->WoodStove . ' " ' . ($equ->WoodStove ? "checked" : "") . '>Estufa de llenya </td>';
                                    echo '<tr><td colspan = "2"></td><td><input type="checkbox" name="CentralDieselHeating"  ' . ($equ->CentralDieselHeating ? "checked" : "") . '>Calefacció central gasoil</td>'
                                    . '<td colspan="3"><input type="checkbox" name="CentralGasHeating"  ' . ($equ->CentralGasHeating ? "checked" : "") . '>Calefacció central gas </td></tr>';
                                    echo '<tr><td><input type="checkbox" name="ElectricRadiators"  ' . ($equ->ElectricRadiators ? "checked" : "") . '>Radiadors Elèctrics </td>'
                                    . '<td>nº <input type="number" name="NumberOfElectricRadiators" class = "form-control2l" min="0" max="30" value="' . $equ->NumberOfElectricRadiators . '"></td>'
                                    . '<td><input type="checkbox" name="Chimney"  ' . ($equ->Chimney ? "checked" : "") . '>Xemeneia</td>'
                                    . '<td>nº <input type="number" name="NumberOfFireplaces" class = "form-control2l" min="0" max="10" value="' . $equ->NumberOfFireplaces . '"></td><'
                                    . 'td><input type="checkbox" name="Fans"  ' . ($equ->Fans ? "checked" : "") . '>Ventiladors </td>'
                                    . '<td>nº <input type="number" name="NumberOfFans" class = "form-control2l" min="0" max="30" value="' . $equ->NumberOfFans . '"></td></tr>';
                                    echo '<tr><td colspan="3">Potencia contractada: <input type="text" name="ContractedPower" class = "form-control3l" size="50" value="' . $equ->ContractedPower . '"></td>'
                                    . '<td colspan="3"><input type="checkbox" name="UnderfloorHeating"  ' . ($equ->UnderfloorHeating ? "checked" : "") . '>Sol radiant</td></tr>';
                                    echo '<th colspan ="6" class="success">Electronica i entreteniment</th>';
                                    echo '<tr><td><input type="checkbox" name="Wifi"  ' . ($equ->Wifi ? "checked" : "") . '>Wifi </td>'
                                    . '<td colspan = "5">Clau <input type="text" name="WifiCode" class = "form-control1" size="14" value="' . $equ->WifiCode . '"></td></tr>';
                                    echo '<tr><td colspan = "6">Quadre elèctric: <input type="text" name="ElectricalBox" class = "form-control3l" size="46" value="' . $equ->ElectricalBox . '" data-toggle="tooltip" data-placement="right" title="Situacio del quadre"></td></tr>';
                                    echo '<tr><td><input type="checkbox" name="Television"  ' . ($equ->Television ? "checked" : "") . '>TV</td>'
                                    . '<td><input type="checkbox" name="SatelliteTelevision"  ' . ($equ->SatelliteTelevision ? "checked" : "") . '>TV Satèl-lit</td>'
                                    . '<td><input type="checkbox" name="EnglishChannel"  ' . ($equ->EnglishChannel ? "checked" : "") . '>Angles</td>'
                                    . '<td><input type="checkbox" name="GermanChannel"  ' . ($equ->GermanChannel ? "checked" : "") . '>Alemany</td>'
                                    . '<td colspan="2"><input type="checkbox" name="ItalianChannel"  ' . ($equ->ItalianChannel ? "checked" : "") . '>Italia</td></tr>'
                                    . '<tr><td></td><td></td><td><input type="checkbox" name="FrenchChannel"  ' . ($equ->FrenchChannel ? "checked" : "") . '>Francès</td>'
                                    . '<td><input type="checkbox" name="DutchChannel"  ' . ($equ->DutchChannel ? "checked" : "") . '>Holandes</td>'
                                    . '<td colspan="2"><input type="checkbox" name="RussianChannel"  ' . ($equ->RussianChannel ? "checked" : "") . '>Rus</td></tr>'
                                    . '<tr><td></td><td></td><td colspan="4"><input type="checkbox" name="ArabianChannel"  ' . ($equ->ArabianChannel ? "checked" : "") . '>Arab</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="DvdPlayer"  ' . ($equ->DvdPlayer ? "checked" : "") . '>Reproductor DVD</td>'
                                    . '<td><input type="checkbox" name="DVD"  ' . ($equ->DVD ? "checked" : "") . '>DVD`S</td><td></td>'
                                    . '<td><input type="checkbox" name="CdPlayer"  ' . ($equ->DvdPlayer ? "checked" : "") . '>Reproductor CD</td>'
                                    . '<td colspan="2"><input type="checkbox" name="CD"  ' . ($equ->CD ? "checked" : "") . '>CD`S</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="ChildrensGames"  ' . ($equ->ChildrensGames ? "checked" : "") . '>Jocs per nins</td>'
                                    . '<td><input type="checkbox" name="Films"  ' . ($equ->Films ? "checked" : "") . '>Pel·lícules </td>'
                                    . '<td><input type="checkbox" name="VideoGames"  ' . ($equ->VideoGames ? "checked" : "") . '>Consola videojocs</td>'
                                    . '<td>Tipus Videojocs: <input type="text" name="TypeOfVideoGames" class = "form-control1" size="15" value="' . $equ->TypeOfVideoGames . '"></td>'
                                    . '<td><input type="checkbox" name="Books"  ' . ($equ->Books ? "checked" : "") . '>Llibres</td>'
                                    . '<td>Tipus: <input type="text" name="TypeOfBooks" class = "form-control1" size="20" value="' . $equ->TypeOfBooks . '"></td></tr>';
                                    echo '<th colspan ="6" class="success">Seguretat</th>';
                                    echo '<tr><td colspan = "2"><input type="checkbox" name="Strongbox"  ' . ($equ->Strongbox ? "checked" : "") . '>Caixa forta </td>'
                                    . '<td colspan="4"><input type="checkbox" name="AlarmSystem"  ' . ($equ->AlarmSystem ? "checked" : "") . '>Alarma </td>';


                                    echo '<tr><td colspan = "5"><input type="submit" name = "equipament" value="Modificar Equipament" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"><td><tr>';

                                    echo '</table>';
                                    echo '</form>';
                                    ?>

                                </div>




                                <div id="dropdown1" class="tab-pane fade">

                                    <?php
// ----------------------------------------------------- Cuina -------------------------------------------------------
                                    // Insertar cuina

                                    if (isset($_POST['novcuina'])) {

//                                        $codicasa = $_POST['codcasa'];

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO CUINA (CUI_codiCasa) VALUES ('$codicasa')"
                                        );
                                        $stmt->execute();
                                    }

                                    // Borrar cuina

                                    if (isset($_POST['borrarcui'])) {

                                        $codicui = $_POST['idcui'];

                                        $stmt = $pdoCore->db->prepare(
                                                "DELETE FROM CUINA WHERE CUI_id =$codicui"
                                        );
                                        $stmt->execute();
                                    }


                                    // Update cuina

                                    if (isset($_POST['modcuina'])) {

                                        canvi('SeparateKitchen');
                                        canvi('LivingDiningRoom');
                                        canvi('KitchenDiningRoom');
                                        canvi('HighChairKitchen');
                                        canvi('BarWithStoolsKitchen');
                                        canvi('BlenderKitchen');
                                        canvi('ToasterKitchen');
                                        canvi('ExtractorKitchen');
                                        canvi('MicrowaveKitchen');
                                        canvi('DishwasherKitchen');
                                        canvi('SqueezerKitchen');
                                        canvi('FridgeWithFreezerKitchen');
                                        canvi('FreezerKitchen');
                                        canvi('ElectricFurnaceKitchen');
                                        canvi('FridgeKitchen');
                                        canvi('KettleKitchen');
                                        canvi('GasKitchen');
                                        canvi('VitroceramicKitchen');
                                        canvi('GasFurnaceKitchen');
                                        canvi('InductionKitchen');
                                        canvi('ElectricCoffeeMakerKitchen');
                                        canvi('ItalianCoffeeMakerKitchen');
                                        canvi('TablesKitchen');
                                        canvi('ChairsKitchen');
                                        canvi('LarderKitchen');
                                        canvi('GasStove');



                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE CUINA SET "
                                                . "SeparateKitchen = :SeparateKitchen, "
                                                . "LivingDiningRoom = :LivingDiningRoom, "
                                                . "KitchenDiningRoom = :KitchenDiningRoom, "
                                                . "DimensionsKitchen = :DimensionsKitchen, "
                                                . "HighChairKitchen = :HighChairKitchen, "
                                                . "BarWithStoolsKitchen = :BarWithStoolsKitchen, "
                                                . "BlenderKitchen = :BlenderKitchen, "
                                                . "ToasterKitchen = :ToasterKitchen, "
                                                . "ExtractorKitchen = :ExtractorKitchen, "
                                                . "MicrowaveKitchen = :MicrowaveKitchen, "
                                                . "DishwasherKitchen = :DishwasherKitchen, "
                                                . "SqueezerKitchen = :SqueezerKitchen, "
                                                . "FridgeWithFreezerKitchen = :FridgeWithFreezerKitchen, "
                                                . "FreezerKitchen = :FreezerKitchen, "
                                                . "ElectricFurnaceKitchen = :ElectricFurnaceKitchen, "
                                                . "FridgeKitchen = :FridgeKitchen, "
                                                . "KettleKitchen = :KettleKitchen, "
                                                . "GasKitchen = :GasKitchen, "
                                                . "VitroceramicKitchen = :VitroceramicKitchen, "
                                                . "GasFurnaceKitchen = :GasFurnaceKitchen, "
                                                . "InductionKitchen = :InductionKitchen, "
                                                . "ElectricCoffeeMakerKitchen = :ElectricCoffeeMakerKitchen, "
                                                . "ItalianCoffeeMakerKitchen = :ItalianCoffeeMakerKitchen, "
                                                . "TablesKitchen = :TablesKitchen, "
                                                . "NumberOfTablesKitchen = :NumberOfTablesKitchen, "
                                                . "ChairsKitchen = :ChairsKitchen, "
                                                . "NumberOfChairsKitchen = :NumberOfChairsKitchen, "
                                                . "LarderKitchen = :LarderKitchen "
                                                . "WHERE CUI_id = :idcui "
                                        );




                                        $stmt->execute(array(
                                            ':SeparateKitchen' => $_POST['SeparateKitchen'],
                                            ':LivingDiningRoom' => $_POST['LivingDiningRoom'],
                                            ':KitchenDiningRoom' => $_POST['KitchenDiningRoom'],
                                            ':DimensionsKitchen' => $_POST['DimensionsKitchen'],
                                            ':HighChairKitchen' => $_POST['HighChairKitchen'],
                                            ':BarWithStoolsKitchen' => $_POST['BarWithStoolsKitchen'],
                                            ':BlenderKitchen' => $_POST['BlenderKitchen'],
                                            ':ToasterKitchen' => $_POST['ToasterKitchen'],
                                            ':ExtractorKitchen' => $_POST['ExtractorKitchen'],
                                            ':MicrowaveKitchen' => $_POST['MicrowaveKitchen'],
                                            ':DishwasherKitchen' => $_POST['DishwasherKitchen'],
                                            ':SqueezerKitchen' => $_POST['SqueezerKitchen'],
                                            ':FridgeWithFreezerKitchen' => $_POST['FridgeWithFreezerKitchen'],
                                            ':FreezerKitchen' => $_POST['FreezerKitchen'],
                                            ':ElectricFurnaceKitchen' => $_POST['ElectricFurnaceKitchen'],
                                            ':FridgeKitchen' => $_POST['FridgeKitchen'],
                                            ':KettleKitchen' => $_POST['KettleKitchen'],
                                            ':GasKitchen' => $_POST['GasKitchen'],
                                            ':VitroceramicKitchen' => $_POST['VitroceramicKitchen'],
                                            ':GasFurnaceKitchen' => $_POST['GasFurnaceKitchen'],
                                            ':InductionKitchen' => $_POST['InductionKitchen'],
                                            ':ElectricCoffeeMakerKitchen' => $_POST['ElectricCoffeeMakerKitchen'],
                                            ':ItalianCoffeeMakerKitchen' => $_POST['ItalianCoffeeMakerKitchen'],
                                            ':TablesKitchen' => $_POST['TablesKitchen'],
                                            ':NumberOfTablesKitchen' => $_POST['NumberOfTablesKitchen'],
                                            ':ChairsKitchen' => $_POST['ChairsKitchen'],
                                            ':NumberOfChairsKitchen' => $_POST['NumberOfChairsKitchen'],
                                            ':LarderKitchen' => $_POST['LarderKitchen'],
                                            ':idcui' => $_POST['idcui']
                                        ));
                                    }

                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM CUINA  WHERE CUI_codiCasa = :casaid "
                                    );
                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

                                    // funcio per llevar codi en fer checkbox

                                    function checkbox($camp_bd, $name, $texte) {
                                        return '<input name="' . $name . '" type="checkbox"  ' . ($camp_bd ? "checked" : "" ) . '  />' . $texte;
                                    }

                                    $result = $stmt->fetchAll();

                                    echo '<form action = "#dropdown1" method = "post">';
                                    echo '<hr/>';
                                    echo '<input type = "submit" value = "Afegir Cuina" class = "btn btn-primary" name = "novcuina">';
                                    echo '</form>';

                                    foreach ($result as $n => $cui) {



                                        echo '<form action = "#dropdown1" method = "post" id = "cuina' . $cui->CUI_id . '">';
                                        echo '<table class = "table table-responsive">';
                                        echo '<th colspan = "3" class = "success">Cuina</th>';
                                        echo '<input type = "hidden" name = "idcui" value = "' . $cui->CUI_id . '">';
                                        echo '<tr><td><input type = "checkbox" name = "SeparateKitchen" ' . ($cui->SeparateKitchen ? "checked" : "" ) . ' > Cuina independent </td>'
                                        . '<td><input type = "checkbox" name = "LivingDiningRoom" ' . ($cui->LivingDiningRoom ? "checked" : "" ) . '>Cuina sala-menjador </td>'
                                        . '<td><input type = "checkbox" name = "KitchenDiningRoom" ' . ($cui->KitchenDiningRoom ? "checked" : "" ) . '>Cuina menjador </td></tr>';
                                        echo '<tr><td>Dimensions cuina: <input type = "text" min = "0" max = "1000" name = "DimensionsKitchen" class = "form-control2l" '
                                        . 'value = "' . $cui->DimensionsKitchen . '" data-toggle="tooltip" data-placement="right" title="En metres"> m²</td>'
                                        . '<td><input type = "checkbox" name = "HighChairKitchen" ' . ($cui->HighChairKitchen ? "checked" : "" ) . '>Trona </td>'
                                        . '<td><input type = "checkbox" name = "BarWithStoolsKitchen" ' . ($cui->BarWithStoolsKitchen ? "checked" : "" ) . '>Barra amb taburets </td></tr>';
                                        echo '<tr><td><input type = "checkbox" name = "BlenderKitchen" ' . ($cui->BlenderKitchen ? "checked" : "" ) . '>Batedora </td>'
                                        . '<td><input type = "checkbox" name = "ToasterKitchen" ' . ($cui->ToasterKitchen ? "checked" : "" ) . '>Torradora </td>'
                                        . '<td><input type = "checkbox" name = "ExtractorKitchen" ' . ($cui->ExtractorKitchen ? "checked" : "" ) . '>Extractor fum </td></tr>';

                                        echo '<tr><td><input type = "checkbox" name = "MicrowaveKitchen" ' . ($cui->MicrowaveKitchen ? "checked" : "" ) . '>Microones </td>'
                                        . '<td><input type = "checkbox" name = "DishwasherKitchen" ' . ($cui->DishwasherKitchen ? "checked" : "" ) . '>Rentavaixelles </td>'
                                        . '<td><input type = "checkbox" name = "SqueezerKitchen" ' . ($cui->SqueezerKitchen ? "checked" : "" ) . '>Exprimidor </td></tr>';

                                        echo '<tr><td><input type = "checkbox" name = "FridgeWithFreezerKitchen" ' . ($cui->FridgeWithFreezerKitchen ? "checked" : "" ) . '>Gelera amb congelador </td>'
                                        . '<td><input type = "checkbox" name = "FreezerKitchen" ' . ($cui->FreezerKitchen ? "checked" : "" ) . '>Congelador </td>'
                                        . '<td><input type = "checkbox" name = "FridgeKitchen" ' . ($cui->FridgeKitchen ? "checked" : "" ) . '>Gelera </td></tr>';

                                        echo '<tr><td><input type = "checkbox" name = "GasFurnaceKitchen" ' . ($cui->GasFurnaceKitchen ? "checked" : "") . '>Forn de gas </td>'
                                        . '<td><input type = "checkbox" name = "ElectricFurnaceKitchen" ' . ($cui->ElectricFurnaceKitchen ? "checked" : "") . '>Forn electric </td>'
                                        . '<td><input type = "checkbox" name = "KettleKitchen" ' . ($cui->KettleKitchen ? "checked" : "") . '>Bollidora aigua </td></tr>';
                                        echo '<tr><td><input type = "checkbox" name = "GasKitchen" ' . ($cui->GasKitchen ? "checked" : "") . '>Cuina de gas </td>'
                                        . '<td><input type = "checkbox" name = "VitroceramicKitchen" ' . ($cui->VitroceramicKitchen ? "checked" : "") . '>Cuina de vitro ceràmica </td>'
                                        . '<td><input type = "checkbox" name = "InductionKitchen" ' . ($cui->InductionKitchen ? "checked" : "") . '>Cuina d`inducció </td></tr>';
                                        echo '<tr><td><input type="checkbox" name="ElectricCoffeeMakerKitchen"  ' . ($cui->ElectricCoffeeMakerKitchen ? "checked" : "") . '>Cafetera electrica </td>'
                                        . '<td colspan="2"><input type="checkbox" name="ItalianCoffeeMakerKitchen"  ' . ($cui->ItalianCoffeeMakerKitchen ? "checked" : "") . '>Cafetera italiana </td></tr>';
                                        echo '<tr><td><input type="checkbox" name="TablesKitchen"  ' . ($cui->TablesKitchen ? "checked" : "") . '>Taules nº '
                                        . '<input type="number" name="NumberOfTablesKitchen" class = "form-control2l" min="0" max="20" value="' . $cui->NumberOfTablesKitchen . '"></td>'
                                        . '<td><input type="checkbox" name="ChairsKitchen"  ' . ($cui->ChairsKitchen ? "checked" : "") . '>Cadires nº '
                                        . '<input type="number" name="NumberOfChairsKitchen" class = "form-control2l" min="0" max="50" value="' . $cui->NumberOfChairsKitchen . '"></td>';


                                        //checkbox($cui->LarderKitchen, 'LarderKitchen', 'Rebost');

                                        echo '<td><input type="checkbox" name="LarderKitchen"  ' . ($cui->LarderKitchen ? " checked" : "") . '>Rebost </td></tr>';

                                        echo'<tr><td colspan = "6"><input type="submit" value="Modificar Cuina" name = "modcuina' . $n . '" class="btn btn-primary" data-toggle="tooltip" data-placement="top" '
                                        . 'title="Segur que vol canviar?"><input type="submit" value="Eliminar Cuina" name = "borrarcui" class="btn btn-danger" data-toggle="tooltip" '
                                        . 'data-placement="right" title="¡¡Alerta esta apunt de borrar!!"></td></tr>';

                                        echo '</table>';
                                        echo '</form>';
                                    }
                                    ?>

                                </div>

                                <div id="dropdown2" class="tab-pane fade">
                                    <?php
// --------------------------------------------------Sala ------------------------------------
                                    // Insertar sala

                                    if (isset($_POST['novsala'])) {

                                        $codicasa = $_POST['codsala'];

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO SALA (SAL_codiCasa) VALUES ('$codicasa')"
                                        );
                                        $stmt->execute();
                                    }

                                    // Borrar sala

                                    if (isset($_POST['borrasal'])) {

                                        $codisal = $_POST['idsala'];

                                        $stmt = $pdoCore->db->prepare(
                                                "DELETE FROM SALA WHERE SAL_id =$codisal"
                                        );
                                        $stmt->execute();
                                    }



                                    if (isset($_POST['modsala'])) {



                                        canvi('SofasHall');
                                        canvi('SofaBedHall');
                                        canvi('ArmChairHall');
                                        canvi('HallDiningroom');


                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE SALA SET "
                                                . "DimensionsHall = :DimensionsHall, "
                                                . "SofasHall = :SofasHall, "
                                                . "ArmChairHall = :ArmChairHall, "
                                                . "ArmChairSeatsHall = :ArmChairSeatsHall, "
                                                . "NumberOfArmChairHall = :NumberOfArmChairHall, "
                                                . "SofaSeatsHall = :SofaSeatsHall, "
                                                . "NumberOfSofasHall = :NumberOfSofasHall, "
                                                . "SofaBedHall = :SofaBedHall, "
                                                . "SofaBedSeatsHall = :SofaBedSeatsHall, "
                                                . "TypeOfSofaBedHall = :TypeOfSofaBedHall, "
                                                . "NumberOfSofasBedHall = :NumberOfSofasBedHall, "
                                                . "AirConditioningHall = :AirConditioningHall, "
                                                . "HallDiningroom = :HallDiningroom "
                                                . "WHERE SAL_id = :idsala "
                                        );



                                        $stmt->execute(array(
                                            ':DimensionsHall' => $_POST['DimensionsHall'],
                                            ':SofasHall' => $_POST['SofasHall'],
                                            ':ArmChairHall' => $_POST['ArmChairHall'],
                                            ':ArmChairSeatsHall' => $_POST['ArmChairSeatsHall'],
                                            ':NumberOfArmChairHall' => $_POST['NumberOfArmChairHall'],
                                            ':SofaSeatsHall' => $_POST['SofaSeatsHall'],
                                            ':NumberOfSofasHall' => $_POST['NumberOfSofasHall'],
                                            ':SofaBedHall' => $_POST['SofaBedHall'],
                                            ':SofaBedSeatsHall' => $_POST['SofaBedSeatsHall'],
                                            ':TypeOfSofaBedHall' => $_POST['TypeOfSofaBedHall'],
                                            ':NumberOfSofasBedHall' => $_POST['NumberOfSofasBedHall'],
                                            ':HallDiningroom' => $_POST['HallDiningroom'],
                                            ':AirConditioningHall' => $_POST['AirConditioningHall'],
                                            ':idsala' => $_POST['idsala']
                                        ));
                                    }

                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM SALA  WHERE SAL_codiCasa = :casaid "
                                    );
                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

                                    echo '<form action="#dropdown2" method="post">';
                                    echo '<input type= "hidden" name= "codsala" value = "' . $codicasa . '">';
                                    echo '<hr/>';
                                    echo '<input type="submit" value="Afegir SALA" name = "novsala" class="btn btn-primary">';
                                    echo '</form>';

                                    $result = $stmt->fetchAll();
                                    foreach ($result as $n => $sal) {



                                        echo '<form action="#dropdown2" method="post" id="sala' . $sal->SAL_id . '">';
                                        echo '<table class="table">';
                                        echo '<tr>';
                                        echo '<th colspan ="5" class="success">Sala</th>';
                                        echo '</tr>';
                                        echo '<input type = "hidden" name="idsala" value = "' . $sal->SAL_id . '">';
                                        echo '<tr>'
                                        . '<td>Dimensions m²: <input type="text" name="DimensionsHall" class = "form-control2l" min="0" max="1000" value="' . $sal->DimensionsHall . '"></td>'
                                        . '</td><td>' . checkbox($sal->SofasHall, "SofasHall", "Sofàs") . ' nº:</td><td> '
                                        . '<input type="number" name="NumberOfSofasHall" class = "form-control2l" min="0" max="15" value="' . $sal->NumberOfSofasHall . '"></td>'
                                        . '<td colspan="3">Seients: <input type="number" name="SofaSeatsHall" class = "form-control2l" min="0" max="50" value="' . $sal->SofaSeatsHall . '"></td></tr>';
                                        echo '<tr>'
                                        . '<td>' . checkbox($sal->HallDiningroom, "HallDiningroom", "Sala-Menjador") . ' </td></td><td>' . checkbox($sal->ArmChairHall, "ArmChairHall", "Butaques") . ' nº:</td><td> '
                                        . '<input type="number" name="NumberOfArmChairHall" class = "form-control2l" min="0" max="15" value="' . $sal->NumberOfArmChairHall . '"></td>'
                                        . '<td colspan="3">Seients: <input type="number" name="ArmChairSeatsHall" class = "form-control2l" min="0" max="50" value="' . $sal->ArmChairSeatsHall . '"></td></tr>';
                                        echo '<tr><td>' . checkbox($sal->SofaBedHall, "SofaBedHall", "Sofàs-llits ") . ''
                                        . 'nº <input type="number" name="NumberOfSofasBedHall" class = "form-control2l" min="0" max="50" value="' . $sal->NumberOfSofasBedHall . '">'
                                        . '</td><td>Tipus</td><td> <input type="text" name="TypeOfSofaBedHall" class = "form-control2l" size="5" value="' . $sal->TypeOfSofaBedHall . '">'
                                        . '</td><td>Seients: <input type="number" name="SofaBedSeatsHall" class = "form-control2l" min="0" max="50" value="' . $sal->SofaBedSeatsHall . '"></td>'
                                        . '<td>Climatització: <input type="text" name="AirConditioningHall" class = "form-control3l" size="5" value="' . $sal->AirConditioningHall . '"></tr>';



                                        echo'<tr><td colspan="6"><input type="submit" value="Modificar Sala" name = "modsala" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Segur que vol canviar?">'
                                        . '<input type="submit" value="Eliminar Sala" name = "borrasal" class="btn btn-danger" data-toggle="tooltip" data-placement="right" title="¡¡Alerta esta apunt de borrar!!"></td></tr>';
                                        echo '</table>';
                                        echo '</form>';
                                    }
                                    ?>
                                </div>
                                <div id="dropdown3" class="tab-pane fade">

                                    <?php
// --------------------------------- Menjador --------------------------------------------
// Insertar Menjador

                                    if (isset($_POST['novmen'])) {

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO MENJADOR (MEN_codiCasa) VALUES ('$codicasa')"
                                        );
                                        $stmt->execute();
                                    }

// Borrar Menjador

                                    if (isset($_POST['borrarmen'])) {
//                                        var_dump($_POST);
                                        $codimen = $_POST['menid'];

                                        $stmt = $pdoCore->db->prepare(
                                                "DELETE FROM MENJADOR WHERE MEN_id =$codimen"
                                        );
                                        $stmt->execute();
                                    }

// Modificar Menjador

                                    if (isset($_POST['modmenjador'])) {

                                        canvi('SideTableDiningRoom');


                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE MENJADOR SET "
                                                . "DimensionsLivingroom = :DimensionsLivingroom, "
                                                . "SideTableDiningRoom = :SideTableDiningRoom, "
                                                . "SideDiningTableSeatsDining = :SideDiningTableSeatsDining, "
                                                . "SeatsDining = :SeatsDining "
                                                . "WHERE MEN_id = :menid "
                                        );




                                        $stmt->execute(array(
                                            ':DimensionsLivingroom' => $_POST['DimensionsLivingroom'],
                                            ':SideTableDiningRoom' => $_POST['SideTableDiningRoom'],
                                            ':SideDiningTableSeatsDining' => $_POST['SideDiningTableSeatsDining'],
                                            ':SeatsDining' => $_POST['SeatsDining'],
                                            ':menid' => $_POST['menid']
                                        ));
                                    }


                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM MENJADOR WHERE MEN_codiCasa = :casaid "
                                    );
                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

//

                                    echo '<form action="#dropdown3" method="post">';
                                    echo '<hr/>';
                                    echo '<input type="submit" value="Afegir Menjador" name = "novmen" class="btn btn-primary">';
                                    echo '</form>';

                                    $result = $stmt->fetchAll();
                                    foreach ($result as $n => $men) {


                                        echo '<form action="#dropdown3" method="post" id="menjador' . $men->MEN_id . '">';
                                        echo '<table class="table">';
                                        echo '<input type = "hidden" name = "menid" value = "' . $men->MEN_id . '" >';
                                        echo '<th colspan ="2" class="success">Menjador</th>';
                                        echo '<tr><td> Dimensions m²: <input type="text" name="DimensionsLivingroom" class = "form-control2l" min="0" max="1000" value="' . $men->DimensionsLivingroom . '" data-toggle="tooltip" data-placement="right" title="En metres"></td>'
//
                                        . '<td><input type="checkbox" name="SideTableDiningRoom"  ' . ($men->SideTableDiningRoom ? "checked" : "") . '>Taules auxiliars '
                                        . 'nº: <input type="number" name="SideDiningTableSeatsDining" class = "form-control2l" min="0" max="15" value="' . $men->SideDiningTableSeatsDining . '"></td></tr>';
                                        echo '<tr><td colspan = "2">nº llocs taula menjador: <input type="number" name="SeatsDining" class = "form-control2l" min="0" max="50" value="' . $men->SeatsDining . '"></td></tr>';

                                        echo'<tr><td colspan = "2"><input type="submit" value="Modificar Menjador" name = "modmenjador" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Segur que vol canviar?">'
                                        . '<input type="submit" value="Eliminar Menjador" name = "borrarmen" class="btn btn-danger" data-toggle="tooltip" data-placement="right" title="¡¡Alerta esta apunt de borrar!!"></td></tr>';
                                        echo '</table>';
                                        echo '</form>';
                                    }
                                    ?>



                                </div>
                                <div id="dropdown4" class="tab-pane fade">
                                    <?php
// ----------------------------------- Dormitori ----------------------------------------
//
//
// Insertar Dormitori

                                    if (isset($_POST['novdor'])) {

                                        $codicasa = $_POST['coddor'];

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO DORMITORI (DOR_codiCasa) VALUES ('$codicasa')"
                                        );
                                        $stmt->execute();
                                    }

// Borrar Dormitori

                                    if (isset($_POST['borrardor'])) {

                                        $codidor = $_POST['iddormitori'];

                                        $stmt = $pdoCore->db->prepare(
                                                "DELETE FROM DORMITORI WHERE DOR_id = $codidor"
                                        );
                                        $stmt->execute();
                                    }

// Modificar Dormitori

                                    if (isset($_POST['moddormitori'])) {

                                        canvi('AttachedBedroom');
                                        canvi('DoubleBedBedroom');
                                        canvi('EnsuiteBedroom');
                                        canvi('SingleBedNumberBedroom');
                                        canvi('AuxiliaryBedBedroom');
                                        canvi('TrundleBedBedroom');
                                        canvi('CotBedroom');
                                        canvi('BunkBedBedroom');
                                        canvi('BalconyBedroom');
                                        canvi('WindowlessBedroom');
                                        canvi('ACBedRoom');
                                        canvi('FanBedRoom');
                                        canvi('DieselCentralHeatingBedRoom');
                                        canvi('ChimneyBedRoom');
                                        canvi('RadiatorsBedRoom');
                                        canvi('GasBedRoom');
                                        canvi('WardrobeBedroom');
                                        canvi('HallwayBedroom');


                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE DORMITORI SET "
                                                . "BedroomDimensions = :BedroomDimensions, "
                                                . "FloorBedroom = :FloorBedroom, "
                                                . "AttachedBedroom = :AttachedBedroom, "
                                                . "DoubleBedBedroom = :DoubleBedBedroom, "
                                                . "WidthDoubleBedBedroom = :WidthDoubleBedBedroom, "
                                                . "HeightDoubleBedBedroom = :HeightDoubleBedBedroom, "
                                                . "NumberOfDoubleBedsBedroom = :NumberOfDoubleBedsBedroom, "
                                                . "EnsuiteBedroom = :EnsuiteBedroom, "
                                                . "SingleBedNumberBedroom = :SingleBedNumberBedroom, "
                                                . "WidthSingleBedBedroom = :WidthSingleBedBedroom, "
                                                . "HeightSingleBedBedroom = :HeightSingleBedBedroom, "
                                                . "NumberOfSingleBedsBedroom = :NumberOfSingleBedsBedroom, "
                                                . "AuxiliaryBedBedroom = :AuxiliaryBedBedroom, "
                                                . "TrundleBedBedroom = :TrundleBedBedroom, "
                                                . "WidthTrundleBedBedroom = :WidthTrundleBedBedroom, "
                                                . "HeightTrundleBedBedroom = :HeightTrundleBedBedroom, "
                                                . "CapacityTrundleBed = :CapacityTrundleBed, "
                                                . "CotBedroom = :CotBedroom, "
                                                . "BunkBedBedroom = :BunkBedBedroom, "
                                                . "WidthBunkBedBedroom = :WidthBunkBedBedroom, "
                                                . "HeightBunkBedBedroom = :HeightBunkBedBedroom, "
                                                . "BalconyBedroom = :BalconyBedroom, "
                                                . "WindowlessBedroom = :WindowlessBedroom, "
                                                . "ACBedRoom = :ACBedRoom, "
                                                . "FanBedRoom = :FanBedRoom, "
                                                . "DieselCentralHeatingBedRoom = :DieselCentralHeatingBedRoom, "
                                                . "ChimneyBedRoom = :ChimneyBedRoom, "
                                                . "RadiatorsBedRoom = :RadiatorsBedRoom, "
                                                . "GasBedRoom = :GasBedRoom, "
                                                . "TypeOfCotBedRoom = :TypeOfCotBedRoom, "
                                                . "WardrobeBedroom = :WardrobeBedroom, "
                                                . "ExitToTerraceBedroom = :ExitToTerraceBedroom, "
                                                . "HallwayBedroom = :HallwayBedroom, "
                                                . "ViewsBedroom = :ViewsBedroom, "
                                                . "NotesBedroom = :NotesBedroom "
                                                . "WHERE DOR_id = :iddormitori "
                                        );




                                        $stmt->execute(array(
                                            ':BedroomDimensions' => $_POST['BedroomDimensions'],
                                            ':FloorBedroom' => $_POST['FloorBedroom'],
                                            ':AttachedBedroom' => $_POST['AttachedBedroom'],
                                            ':DoubleBedBedroom' => $_POST['DoubleBedBedroom'],
                                            ':WidthDoubleBedBedroom' => $_POST['WidthDoubleBedBedroom'],
                                            ':HeightDoubleBedBedroom' => $_POST['HeightDoubleBedBedroom'],
                                            ':NumberOfDoubleBedsBedroom' => $_POST['NumberOfDoubleBedsBedroom'],
                                            ':EnsuiteBedroom' => $_POST['EnsuiteBedroom'],
                                            ':SingleBedNumberBedroom' => $_POST['SingleBedNumberBedroom'],
                                            ':WidthSingleBedBedroom' => $_POST['WidthSingleBedBedroom'],
                                            ':HeightSingleBedBedroom' => $_POST['HeightSingleBedBedroom'],
                                            ':NumberOfSingleBedsBedroom' => $_POST['NumberOfSingleBedsBedroom'],
                                            ':AuxiliaryBedBedroom' => $_POST['AuxiliaryBedBedroom'],
                                            ':TrundleBedBedroom' => $_POST['TrundleBedBedroom'],
                                            ':WidthTrundleBedBedroom' => $_POST['WidthTrundleBedBedroom'],
                                            ':HeightTrundleBedBedroom' => $_POST['HeightTrundleBedBedroom'],
                                            ':CapacityTrundleBed' => $_POST['CapacityTrundleBed'],
                                            ':CotBedroom' => $_POST['CotBedroom'],
                                            ':BunkBedBedroom' => $_POST['BunkBedBedroom'],
                                            ':WidthBunkBedBedroom' => $_POST['WidthBunkBedBedroom'],
                                            ':HeightBunkBedBedroom' => $_POST['HeightBunkBedBedroom'],
                                            ':BalconyBedroom' => $_POST['BalconyBedroom'],
                                            ':WindowlessBedroom' => $_POST['WindowlessBedroom'],
                                            ':ACBedRoom' => $_POST['ACBedRoom'],
                                            ':FanBedRoom' => $_POST['FanBedRoom'],
                                            ':DieselCentralHeatingBedRoom' => $_POST['DieselCentralHeatingBedRoom'],
                                            ':ChimneyBedRoom' => $_POST['ChimneyBedRoom'],
                                            ':RadiatorsBedRoom' => $_POST['RadiatorsBedRoom'],
                                            ':GasBedRoom' => $_POST['GasBedRoom'],
                                            ':TypeOfCotBedRoom' => $_POST['TypeOfCotBedRoom'],
                                            ':WardrobeBedroom' => $_POST['WardrobeBedroom'],
                                            ':ExitToTerraceBedroom' => $_POST['ExitToTerraceBedroom'],
                                            ':HallwayBedroom' => $_POST['HallwayBedroom'],
                                            ':ViewsBedroom' => $_POST['ViewsBedroom'],
                                            ':NotesBedroom' => $_POST['NotesBedroom'],
                                            ':iddormitori' => $_POST['iddormitori']
                                        ));
                                    }


                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM DORMITORI D WHERE D.DOR_codiCasa = :casaid "
                                    );
                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

//
// ' . checkbox($sal->SofasHall, "  SofasHall  ", "  Sofàs  ") . '
                                    echo '<form action="#dropdown4" method="post">';
                                    echo '<input type= "hidden" name= "coddor" value = "' . $codicasa . '">';
                                    echo '<hr/>';
                                    echo '<input type="submit" value="Afegir Dormitori" name = "novdor" class="btn btn-primary">';
                                    echo '</form>';


                                    $result = $stmt->fetchAll();
                                    foreach ($result as $n => $dor) {


                                        echo '<form action="#dropdown4" method="post" id="dormitori' . $dor->DOR_id . '">';
                                        echo '<table class="table">';
                                        echo '<th colspan ="5" class="success">Dormitori</th>';
                                        echo '<input type = "hidden" name = "iddormitori" value = "' . $dor->DOR_id . '">';
                                        echo '<tr><td colspan="2">Dimensions  m²: <input type="text" name="BedroomDimensions" class = "form-control2l" min="0" max="1000" value="' . $dor->BedroomDimensions . '" data-toggle="tooltip" data-placement="right" title="En metres"></td>'
                                        . '<td>Planta : <select name="FloorBedroom" class = "form-control2l">';
                                        echo '<option  value="" ' . ($dor->FloorBedroom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="-1" ' . ($dor->FloorBedroom == "-1" ? "selected='selected'" : " ") . '>-1</option>';
                                        echo '<option  value="PB" ' . ($dor->FloorBedroom == "PB" ? "selected='selected'" : " ") . '>PB</option>';
                                        echo '<option  value="1" ' . ($dor->FloorBedroom == "1" ? "selected='selected'" : " ") . '>1</option>';
                                        echo '<option value = "2" ' . ($dor->FloorBedroom == "2" ? "selected='selected'" : " ") . '>2</option>';
                                        echo '<option value = "3" ' . ($dor->FloorBedroom == "3" ? "selected='selected'" : " ") . '>3</option>';
                                        echo '</select></td>'
                                        . '<td>' . checkbox($dor->AttachedBedroom, "AttachedBedroom", "Anexada") . '</td></tr>';
                                        echo '<tr><td>' . checkbox($dor->DoubleBedBedroom, "DoubleBedBedroom", "  Llit matrimoni  ") . ''
                                        . '</td><td>Llarg: <select name="HeightDoubleBedBedroom" class = "form-control2l">';
                                        echo '<option  value="" ' . ($dor->HeightDoubleBedBedroom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="180" ' . ($dor->HeightDoubleBedBedroom == "180" ? "selected='selected'" : " ") . '>180</option>';
                                        echo '<option  value="185" ' . ($dor->HeightDoubleBedBedroom == "185" ? "selected='selected'" : " ") . '>185</option>';
                                        echo '<option  value="190" ' . ($dor->HeightDoubleBedBedroom == "190" ? "selected='selected'" : " ") . '>190</option>';
                                        echo '<option  value="200" ' . ($dor->HeightDoubleBedBedroom == "200" ? "selected='selected'" : " ") . '>200</option>';
                                        echo '<option value = "210" ' . ($dor->HeightDoubleBedBedroom == "210" ? "selected='selected'" : " ") . '>210</option>';
                                        echo '</select>'
                                        . '</td><td>Ampla: <select name="WidthDoubleBedBedroom" class = "form-control2l">';
                                        echo '<option  value="" ' . ($dor->WidthDoubleBedBedroom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="135" ' . ($dor->WidthDoubleBedBedroom == "135" ? "selected='selected'" : " ") . '>135</option>';
                                        echo '<option  value="150" ' . ($dor->WidthDoubleBedBedroom == "150" ? "selected='selected'" : " ") . '>150</option>';
                                        echo '<option  value="160" ' . ($dor->WidthDoubleBedBedroom == "160" ? "selected='selected'" : " ") . '>160</option>';
                                        echo '<option  value="180" ' . ($dor->WidthDoubleBedBedroom == "180" ? "selected='selected'" : " ") . '>180</option>';
                                        echo '<option value = "200" ' . ($dor->WidthDoubleBedBedroom == "200" ? "selected='selected'" : " ") . '>200</option>';
                                        echo '</select></td>'
                                        . '<td> nº :<input type="number" name="NumberOfDoubleBedsBedroom" class = "form-control2l" min="0" max="5" value="' . $dor->NumberOfDoubleBedsBedroom . '"></td>'
                                        . '<td>' . checkbox($dor->EnsuiteBedroom, "EnsuiteBedroom", "  Bany en suite  ") . '</td></tr>';
                                        echo '<tr><td>' . checkbox($dor->SingleBedNumberBedroom, "SingleBedNumberBedroom", "  Llit individual  ") . ''
                                        . '</td><td>Llarg: <select name="HeightSingleBedBedroom" class = "form-control2l">';
                                        echo '<option  value="" ' . ($dor->HeightDoubleBedBedroom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="180" ' . ($dor->HeightSingleBedBedroom == "180" ? "selected='selected'" : " ") . '>180</option>';
                                        echo '<option  value="185" ' . ($dor->HeightSingleBedBedroom == "185" ? "selected='selected'" : " ") . '>185</option>';
                                        echo '<option  value="190" ' . ($dor->HeightSingleBedBedroom == "190" ? "selected='selected'" : " ") . '>190</option>';
                                        echo '<option  value="200" ' . ($dor->HeightSingleBedBedroom == "200" ? "selected='selected'" : " ") . '>200</option>';
                                        echo '<option value = "210" ' . ($dor->HeightSingleBedBedroom == "210" ? "selected='selected'" : " ") . '>210</option>';
                                        echo '</select>'
                                        . '</td><td>Ampla: <select name="WidthSingleBedBedroom" class = "form-control2l">';
                                        echo '<option  value="" ' . ($dor->WidthSingleBedBedroom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="80" ' . ($dor->WidthSingleBedBedroom == "80" ? "selected='selected'" : " ") . '>80</option>';
                                        echo '<option  value="90" ' . ($dor->WidthSingleBedBedroom == "90" ? "selected='selected'" : " ") . '>90</option>';
                                        echo '<option  value="100" ' . ($dor->WidthSingleBedBedroom == "100" ? "selected='selected'" : " ") . '>100</option>';
                                        echo '<option value = "120" ' . ($dor->WidthSingleBedBedroom == "120" ? "selected='selected'" : " ") . '>120</option>';
                                        echo '</select></td>'
                                        . '<td> nº :<input type="number" name="NumberOfSingleBedsBedroom" class = "form-control2l" min="0" max="5" value="' . $dor->NumberOfSingleBedsBedroom . '"></td>'
                                        . '<td>' . checkbox($dor->AuxiliaryBedBedroom, "AuxiliaryBedBedroom", "  Lloc per llit auxiliar  ") . '</td></tr>';
                                        echo '<tr><td>' . checkbox($dor->TrundleBedBedroom, "TrundleBedBedroom", "  Nido  ") . ''
                                        . '</td><td>Llarg: <select name="HeightTrundleBedBedroom" class = "form-control2l">';
                                        echo '<option  value="" ' . ($dor->HeightTrundleBedBedroom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="180" ' . ($dor->HeightTrundleBedBedroom == "180" ? "selected='selected'" : " ") . '>180</option>';
                                        echo '<option  value="185" ' . ($dor->HeightTrundleBedBedroom == "185" ? "selected='selected'" : " ") . '>185</option>';
                                        echo '<option  value="190" ' . ($dor->HeightTrundleBedBedroom == "190" ? "selected='selected'" : " ") . '>190</option>';
                                        echo '<option  value="200" ' . ($dor->HeightTrundleBedBedroom == "200" ? "selected='selected'" : " ") . '>200</option>';
                                        echo '<option value = "210" ' . ($dor->HeightTrundleBedBedroom == "210" ? "selected='selected'" : " ") . '>210</option>';
                                        echo '</select>'
                                        . '</td><td>Ampla: <select name="WidthTrundleBedBedroom" class = "form-control2l">';
                                        echo '<option  value="" ' . ($dor->WidthTrundleBedBedroom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="80" ' . ($dor->WidthTrundleBedBedroom == "80" ? "selected='selected'" : " ") . '>80</option>';
                                        echo '<option  value="90" ' . ($dor->WidthTrundleBedBedroom == "90" ? "selected='selected'" : " ") . '>90</option>';
                                        echo '<option  value="100" ' . ($dor->WidthTrundleBedBedroom == "100" ? "selected='selected'" : " ") . '>100</option>';
                                        echo '</select></td>'
                                        . '<td> Capacitat :<input type="number" name="CapacityTrundleBed" class = "form-control2l" min="0" max="5" value="' . $dor->CapacityTrundleBed . '"></td>'
                                        . '<td>' . checkbox($dor->CotBedroom, "CotBedroom", "  Lloc cuna  ") . '</td></tr>';
                                        echo '<tr><td>' . checkbox($dor->BunkBedBedroom, "BunkBedBedroom", "  Llitera  ") . ''
                                        . '</td><td>Llarg: <select name="HeightBunkBedBedroom" class = "form-control2l">';
                                        echo '<option  value="" ' . ($dor->HeightBunkBedBedroom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="180" ' . ($dor->HeightBunkBedBedroom == "180" ? "selected='selected'" : " ") . '>180</option>';
                                        echo '<option  value="185" ' . ($dor->HeightBunkBedBedroom == "185" ? "selected='selected'" : " ") . '>185</option>';
                                        echo '<option  value="190" ' . ($dor->HeightBunkBedBedroom == "190" ? "selected='selected'" : " ") . '>190</option>';
                                        echo '<option  value="200" ' . ($dor->HeightBunkBedBedroom == "200" ? "selected='selected'" : " ") . '>200</option>';
                                        echo '<option value = "210" ' . ($dor->HeightBunkBedBedroom == "210" ? "selected='selected'" : " ") . '>210</option>';
                                        echo '</select>'
                                        . '</td><td>Ampla: <select name="WidthBunkBedBedroom" class = "form-control2l">';
                                        echo '<option  value="" ' . ($dor->WidthBunkBedBedroom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="80" ' . ($dor->WidthBunkBedBedroom == "80" ? "selected='selected'" : " ") . '>80</option>';
                                        echo '<option  value="90" ' . ($dor->WidthBunkBedBedroom == "90" ? "selected='selected'" : " ") . '>90</option>';
                                        echo '<option  value="100" ' . ($dor->WidthBunkBedBedroom == "100" ? "selected='selected'" : " ") . '>100</option>';
                                        echo '</select></td>'
                                        . '<td>' . checkbox($dor->BalconyBedroom, "BalconyBedroom", "Balco") . '</td>'
                                        . '<td>' . checkbox($dor->WindowlessBedroom, "WindowlessBedroom", "  Sense finestres  ") . '</td></tr>';
                                        echo '<tr><td colspan = "3"><ins>Tipus climatització</ins></td><td colspan="3">Tipus cuna: <select name="TypeOfCotBedRoom" class = "form-control3l">';
                                        echo '<option  value="" ' . ($dor->TypeOfCotBedRoom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="Fixa" ' . ($dor->TypeOfCotBedRoom == "Fixa" ? "selected='selected'" : " ") . '>Fixa</option>';
                                        echo '<option value = "Movil" ' . ($dor->TypeOfCotBedRoom == "Movil" ? "selected='selected'" : " ") . '>Movil</option>';
                                        echo '</select></td></tr>';
                                        echo '<tr><td colspan="5"><table class="table-condensed">';
                                        echo '<tr><td>' . checkbox($dor->ACBedRoom, "ACBedRoom", "  AC  ") . '</td><td>' . checkbox($dor->FanBedRoom, "  FanBedRoom  ", "  Ventilador  ") . '</td><td></td></tr>';
                                        echo '<tr><td>' . checkbox($dor->DieselCentralHeatingBedRoom, "DieselCentralHeatingBedRoom", "  Calefacció gasoil  ") . '</td><td>' . checkbox($dor->ChimneyBedRoom, "  ChimneyBedRoom  ", "  Ximeneia  ") . '</td><td></td></tr>';
                                        echo '<tr><td>' . checkbox($dor->RadiatorsBedRoom, "RadiatorsBedRoom", "  Radiadors  ") . '</td><td>' . checkbox($dor->GasBedRoom, "  GasBedRoom  ", "  Calefacció de Gas  ") . '</td><td></td></tr>';
                                        echo '</table></td></tr>';
// ExitToTerraceBedroom
                                        echo '<tr><td>' . checkbox($dor->WardrobeBedroom, "WardrobeBedroom", "  Armaris  ") . '</td>'
                                        . '<td>Sortida: <select name="ExitToTerraceBedroom" class = "form-control3l">';
                                        echo '<option  value="" ' . ($dor->ExitToTerraceBedroom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="Terrace" ' . ($dor->ExitToTerraceBedroom == "Terrace" ? "selected='selected'" : " ") . '>Terrasa</option>';
                                        echo '<option value = "Jardí" ' . ($dor->ExitToTerraceBedroom == "Jardí" ? "selected='selected'" : " ") . '>Jardí</option>';
                                        echo '</select></td>'
                                        . '<td colspan="3">' . checkbox($dor->HallwayBedroom, "HallwayBedroom", "  Vestidor  ") . '</td></tr>';
                                        echo '<tr><td colspan="5">Vistes:<textarea name="ViewsBedroom" class = "form-control1" rows="2" cols="90" style="width:100%">' . $dor->ViewsBedroom . '</textarea></td></tr>';
                                        echo '<tr><td colspan="5">Notes:<textarea name="NotesBedroom" class = "form-control1" rows="2" cols="90" style="width:100%">' . $dor->NotesBedroom . '</textarea></td></tr>';

                                        echo'<tr><td colspan = "5"><input type="submit" value="Modificar Dormitori" name = "moddormitori" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Segur que vol canviar?">'
                                        . '<input type="submit" '
                                        . 'value="Eliminar Dormitori" name = "borrardor" class="btn btn-danger" data-toggle="tooltip" data-placement="right" title="¡¡Alerta estau apunt de borrar!!"></td></tr>';

                                        echo '</table>';
                                        echo '</form>';
                                    }
                                    ?>
                                </div>
                                <div id="dropdown5" class="tab-pane fade">
                                    <?php
// ----------------------------------- Bany ----------------------------------------
//
//
// Insertar Bany

                                    if (isset($_POST['novban'])) {

                                        $codicasa = $_POST['codban'];

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO BANY (BAN_codiCasa) VALUES ('$codicasa')"
                                        );
                                        $stmt->execute();
                                    }

// Borrar Bany

                                    if (isset($_POST['borrban'])) {

                                        $codiban = $_POST['idban'];

                                        $stmt = $pdoCore->db->prepare(
                                                "DELETE FROM BANY WHERE BAN_id = $codiban"
                                        );
                                        $stmt->execute();
                                    }

// Modificar Bany

                                    if (isset($_POST['modbany'])) {


                                        canvi('AttachedBathroom');
                                        canvi('CompleteBathroom');
                                        canvi('SinkBathroom');
                                        canvi('ToiletBathRoom');
                                        canvi('BidetBathroom');
                                        canvi('ShowerBathroom');
                                        canvi('BathBathroom');
                                        canvi('EnsuiteBathroom');
                                        canvi('JacuzziBathroom');
                                        canvi('SaunaBathroom');
                                        canvi('HairdryerBathRoom');
                                        canvi('OutsideBathroom');

                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE BANY SET "
                                                . "BathroomDimensions = :BathroomDimensions, "
                                                . "FloorBathroom = :FloorBathroom, "
                                                . "AttachedBathroom = :AttachedBathroom, "
                                                . "CompleteBathroom = :CompleteBathroom, "
                                                . "SinkBathroom = :SinkBathroom, "
                                                . "ToiletBathRoom = :ToiletBathRoom, "
                                                . "BidetBathroom = :BidetBathroom, "
                                                . "ShowerBathroom = :ShowerBathroom, "
                                                . "TypeOfShoweBathRoom = :TypeOfShoweBathRoom, "
                                                . "BathBathroom = :BathBathroom, "
                                                . "TypeOfBathBathRoom = :TypeOfBathBathRoom, "
                                                . "EnsuiteBathroom = :EnsuiteBathroom, "
                                                . "JacuzziBathroom = :JacuzziBathroom, "
                                                . "SaunaBathroom = :SaunaBathroom, "
                                                . "HairdryerBathRoom = :HairdryerBathRoom, "
                                                . "OutsideBathroom = :OutsideBathroom, "
                                                . "EnsuiteBathroomLocation = :EnsuiteBathroomLocation, "
                                                . "HeatingBathRoom = :HeatingBathRoom "
                                                . "WHERE BAN_id = :idban "
                                        );




                                        $stmt->execute(array(
                                            ':BathroomDimensions' => $_POST['BathroomDimensions'],
                                            ':FloorBathroom' => $_POST['FloorBathroom'],
                                            ':AttachedBathroom' => $_POST['AttachedBathroom'],
                                            ':CompleteBathroom' => $_POST['CompleteBathroom'],
                                            ':SinkBathroom' => $_POST['SinkBathroom'],
                                            ':ToiletBathRoom' => $_POST['ToiletBathRoom'],
                                            ':BidetBathroom' => $_POST['BidetBathroom'],
                                            ':ShowerBathroom' => $_POST['ShowerBathroom'],
                                            ':TypeOfShoweBathRoom' => $_POST['TypeOfShoweBathRoom'],
                                            ':BathBathroom' => $_POST['BathBathroom'],
                                            ':TypeOfBathBathRoom' => $_POST['TypeOfBathBathRoom'],
                                            ':EnsuiteBathroom' => $_POST['EnsuiteBathroom'],
                                            ':JacuzziBathroom' => $_POST['JacuzziBathroom'],
                                            ':SaunaBathroom' => $_POST['SaunaBathroom'],
                                            ':HairdryerBathRoom' => $_POST['HairdryerBathRoom'],
                                            ':OutsideBathroom' => $_POST['OutsideBathroom'],
                                            ':EnsuiteBathroomLocation' => $_POST['EnsuiteBathroomLocation'],
                                            ':HeatingBathRoom' => $_POST['HeatingBathRoom'],
                                            ':idban' => $_POST['idban']
                                        ));
                                    }


                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM BANY  WHERE BAN_codiCasa = :casaid "
                                    );
                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

                                    echo '<form action="#dropdown5" method="post">';
                                    echo '<input type= "hidden" name= "codban" value = "' . $codicasa . '">';
                                    echo '<hr/>';
                                    echo '<input type="submit" value="Afegir Bany" name = "novban" class="btn btn-primary">';
                                    echo '</form>';

                                    $result = $stmt->fetchAll();
                                    foreach ($result as $n => $ban) {


                                        echo '<form action="#dropdown5" method="post" id="bany' . $ban->BAN_id . '">';
                                        echo '<table class="table">';
                                        echo '<th colspan ="5" class="success">Bany</th>';
                                        echo '<input type = "hidden" name = "idban" value = "' . $ban->BAN_id . '">';
                                        echo '<tr><td>Dimensions m² <input type="text" name="BathroomDimensions" class = "form-control2l" min="0" max="1000" value="' . $ban->BathroomDimensions . '" data-toggle="tooltip" data-placement="right" title="En metres"></td>'
                                        . '<td>Planta <select name="FloorBathroom" class = "form-control2l">';
                                        echo '<option  value="" ' . ($ban->FloorBathroom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="-1" ' . ($ban->FloorBathroom == "-1" ? "selected='selected'" : " ") . '>-1</option>';
                                        echo '<option  value="PB" ' . ($ban->FloorBathroom == "PB" ? "selected='selected'" : " ") . '>PB</option>';
                                        echo '<option  value="1" ' . ($ban->FloorBathroom == "1" ? "selected='selected'" : " ") . '>1</option>';
                                        echo '<option value = "2" ' . ($ban->FloorBathroom == "2" ? "selected='selected'" : " ") . '>2</option>';
                                        echo '<option value = "3" ' . ($ban->FloorBathroom == "3" ? "selected='selected'" : " ") . '>3</option>';
                                        echo '</select></td>'
                                        . '<td>' . checkbox($ban->AttachedBathroom, "AttachedBathroom", "  Anexada  ") . '</td></tr>';
                                        echo '<tr><td>' . checkbox($ban->CompleteBathroom, "CompleteBathroom", "  Complet  ") . '</td>'
                                        . '<td>' . checkbox($ban->SinkBathroom, "SinkBathroom", "  Lavabo  ") . '</td>'
                                        . '<td>' . checkbox($ban->ToiletBathRoom, "ToiletBathRoom", "  Vater  ") . '</td></tr>';
                                        echo '<tr><td>' . checkbox($ban->BidetBathroom, "BidetBathroom", "  Bidet  ") . '</td>'
                                        . '<td>' . checkbox($ban->ShowerBathroom, "ShowerBathroom", "  Dutxa:   ") . ''
                                        . ' ';

                                        echo '<select name="TypeOfShoweBathRoom" class = "form-control1">';
                                        echo '<option  value="" ' . ($ban->TypeOfShoweBathRoom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="Normal" ' . ($ban->TypeOfShoweBathRoom == "Normal" ? "selected='selected'" : " ") . '>Normal</option>';
                                        echo '<option value = "Hydromassage" ' . ($ban->TypeOfShoweBathRoom == "Hydromassage" ? "selected='selected'" : " ") . '>Hidromassatge</option>';
                                        echo '</select></td>'
                                        . '<td>' . checkbox($ban->BathBathroom, "BathBathroom", "  Banyera  ") . ''
                                        . 'Tipus: <select name="TypeOfBathBathRoom" class = "form-control1">';
                                        echo '<option  value="" ' . ($ban->TypeOfBathBathRoom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="Normal" ' . ($ban->TypeOfBathBathRoom == "Normal" ? "selected='selected'" : " ") . '>Normal</option>';
                                        echo '<option value = "Hydromassage" ' . ($ban->TypeOfBathBathRoom == "Hydromassage" ? "selected='selected'" : " ") . '>Hidromassatge</option>';
                                        echo '</select></td></tr>';
                                        echo '<tr><td>' . checkbox($ban->EnsuiteBathroom, "EnsuiteBathroom", "  Bany en suite  ") . '</td><td>Localització: <input type="text" name = "EnsuiteBathroomLocation" value = "' . $ban->EnsuiteBathroomLocation . '" class = "form-control4l"></td>'
                                        . '<td>' . checkbox($ban->JacuzziBathroom, "JacuzziBathroom", "  Jacuzzi  ") . '</td></tr>'
                                        . '<tr><td>' . checkbox($ban->SaunaBathroom, "SaunaBathroom", "  Sauna  ") . '</td>';
                                        echo '<td>' . checkbox($ban->HairdryerBathRoom, "HairdryerBathRoom", "  Secador de cabells  ") . '</td>'
                                        . '<td>' . checkbox($ban->OutsideBathroom, "OutsideBathroom", "  Exterior  ") . '</td></tr>'
                                        . '<tr><td>Calefacció <select name="HeatingBathRoom" class = "form-control1">';
                                        echo '<option  value="" ' . ($ban->HeatingBathRoom == " " ? "selected='selected'" : " ") . '></option>';
                                        echo '<option  value="DieselOil" ' . ($ban->HeatingBathRoom == "DieselOil" ? "selected='selected'" : " ") . '>Gasoil/gas</option>';
                                        echo '<option value = "Electric" ' . ($ban->HeatingBathRoom == "Electric" ? "selected='selected'" : " ") . '>Electrica</option>';
                                        echo '</select></td></tr>';

                                        echo'<tr><td colspan = "3"><input type="submit" value="Modificar Bany" name = "modbany" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Segur que vol canviar?">'
                                        . '<input type="submit" '
                                        . 'value="Eliminar Bany" name = "borrban" class="btn btn-danger" data-toggle="tooltip" data-placement="right" title="¡¡Alerta estau apunt de borrar!!"></td></tr>';
                                        echo '</table>';
                                        echo '</form>';
                                    }
                                    ?>


                                </div>
                                <div id="dropdown6" class="tab-pane fade">

                                    <?php
// ------------------------------------ Bugaderia ---------------------------------------


                                    if (isset($_POST['buga'])) {
                                        canvi('WashingMachine');
                                        canvi('Dryer');
                                        canvi('Iron');
                                        canvi('Vacuum');


                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE BUGADERIA SET WashingMachine = :WashingMachine, Dryer = :Dryer, "
                                                . "Iron = :Iron, Vacuum = :Vacuum, LitersElectricBoiler = :LitersElectricBoiler, "
                                                . "Laundry = :Laundry "
                                                . "WHERE BUG_id = :idbug"
                                        );


                                        $stmt->execute(array(':WashingMachine' => $_POST['WashingMachine'], ':Dryer' => $_POST['Dryer'], ':Iron' => $_POST['Iron'],
                                            ':Vacuum' => $_POST['Vacuum'], ':LitersElectricBoiler' => $_POST['LitersElectricBoiler'],
                                            ':Laundry' => $_POST['Laundry'], ':idbug' => $_POST['idbug']));
                                    }

                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM BUGADERIA WHERE BUG_codiCasa = :casaid "
                                    );
                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

                                    $bug = $stmt->fetchObject();



                                    echo '<form action="#dropdown6" method="post">';
                                    echo '<table class="table">';
                                    echo '<th colspan ="4" class="success">Bugaderia</th>';
                                    echo '<input type = "hidden" name = "idbug" value = ' . $bug->BUG_id . '>';
                                    echo '<tr><td>';
                                    echo '<td>' . checkbox($bug->WashingMachine, "WashingMachine", "Rentadora") . '</td>';
                                    echo '<td>' . checkbox($bug->Dryer, "Dryer", "Secadora") . '</td>';
                                    echo '<td>' . checkbox($bug->Iron, "Iron", "Planxa i taula de planxar") . '</td></tr>';
                                    echo '<tr><td>' . checkbox($bug->Vacuum, "Vacuum", "Aspirador") . '</td>';
                                    echo '<td>Nº litres termo: <input type = "number" value = "' . $bug->LitersElectricBoiler . '" '
                                    . 'name="LitersElectricBoiler" class = "form-control2l" min="0" max="300"</td>';
                                    echo '<td colspan = "2">Situació: <select name="Laundry" class = "form-control1">';
                                    echo '<option  value="" ' . ($bug->Laundry == " " ? "selected='selected'" : " ") . '></option>';
                                    echo '<option  value="Independent" ' . ($bug->Laundry == "Independent" ? "selected='selected'" : " ") . '>Independent</option>';
                                    echo '<option value = "Cuine" ' . ($bug->Laundry == "Cuine" ? "selected='selected'" : " ") . '>Cuine</option>';
                                    echo '<option value = "Bany" ' . ($bug->Laundry == "Bany" ? "selected='selected'" : " ") . '>Bany</option>';
                                    echo '<option value = "Terrasa" ' . ($bug->Laundry == "Terrasa" ? "selected='selected'" : " ") . '>Terrasa</option>';
                                    echo '</select></td></tr>';

                                    echo '<tr><td colspan ="4"><input type="submit" name ="buga" value="Modificar Bugaderia" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"></td></tr>';

                                    echo '</table>';
                                    echo '</form>';
                                    ?>



                                </div>



                                <div id="dropdown7" class="tab-pane fade">
                                    <?php
// -------------------------------- GENERAL -----------------------------------

                                    if (isset($_POST['gen'])) {
                                        canvi('OtherFurniture');


                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE GENERAL SET OtherFurniture = :OtherFurniture, "
                                                . "OtherTypeOfFurniture = :OtherTypeOfFurniture, "
                                                . "kitchenNotes = :kitchenNotes "
                                                . "WHERE GEN_id = :genid "
                                        );


                                        $stmt->execute(array(':OtherFurniture' => $_POST['OtherFurniture'],
                                            ':OtherTypeOfFurniture' => $_POST['OtherTypeOfFurniture'],
                                            ':kitchenNotes' => $_POST['kitchenNotes'],
                                            ':genid' => $_POST['idgen']));
                                    }

                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM GENERAL WHERE GEN_codiCasa = :casaid "
                                    );
                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

                                    $gen = $stmt->fetchObject();

                                    echo '<form action="#dropdown7" method="post">';
                                    echo '<table class="table">';
                                    echo '<th colspan ="3" class="success">General</th>';
                                    echo '<input type = "hidden" name = "idgen" value= "' . $gen->GEN_id . '" >';
                                    echo '<tr><td colspan="3">' . checkbox($gen->OtherFurniture, "OtherFurniture", "Altres mobles") . '</td></tr>';
                                    echo '<tr><td colspan="3">Tipus mobles: <textarea name="OtherTypeOfFurniture" class = "form-control" rows="2" cols="90" style="width:100%">' . $gen->OtherTypeOfFurniture . '</textarea></td></tr>';
                                    echo '<tr><td colspan="3">Notes: <textarea name="kitchenNotes" class = "form-control" rows="2" cols="90" style="width:100%">' . $gen->kitchenNotes . '</textarea></td></tr>';

//kitchenNotes.
                                    echo '<tr><td><input type="submit" name ="gen" value="Modificar general" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"></td></tr>';

                                    echo '</table>';
                                    echo '</form>';
                                    ?>


                                </div>


                                <div id = "sectionG" class = "tab-pane fade">

                                    <?php
// ----------------------------------- Informacio adicional ----------------------------------------
                                    if (isset($_POST['info'])) {

                                        canvi('AllowPets');
                                        canvi('PetsOnRequest');
                                        canvi('NoAllowPets');
                                        canvi('SolarEnergy');
                                        canvi('Parking');
                                        canvi('Garage');
                                        canvi('ParkingGarage');
                                        canvi('Lift');
                                        canvi('Noise');

                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE INFORMACIO_ADICIONAL SET "
                                                . "YearOfConstruction = :YearOfConstruction, "
                                                . "YearOfRemodel = :YearOfRemodel, "
                                                . "FloorsHouse = :FloorsHouse, "
                                                . "AllowPets = :AllowPets, "
                                                . "PetsOnRequest = :PetsOnRequest, "
                                                . "NoAllowPets = :NoAllowPets, "
                                                . "SolarEnergy = :SolarEnergy, "
                                                . "NumberOfNeighbors = :NumberOfNeighbors, "
                                                . "FloorHouse = :FloorHouse, "
                                                . "Parking = :Parking, "
                                                . "Noise = :Noise, "
                                                . "NoiseType = :NoiseType, "
                                                . "ParkingSpaces = :ParkingSpaces, "
                                                . "Garage = :Garage, "
                                                . "GarageSpaces = :GarageSpaces, "
                                                . "ParkingGarage = :ParkingGarage, "
                                                . "ParkingGarageSlots = :ParkingGarageSlots, "
                                                . "Lift = :Lift, "
                                                . "ParkingNotes = :ParkingNotes "
                                                . "WHERE INF_id = :idinfo "
                                        );




                                        $stmt->execute(array(
                                            ':YearOfConstruction' => $_POST['YearOfConstruction'],
                                            ':YearOfRemodel' => $_POST['YearOfRemodel'],
                                            ':FloorsHouse' => $_POST['FloorsHouse'],
                                            ':AllowPets' => $_POST['AllowPets'],
                                            ':PetsOnRequest' => $_POST['PetsOnRequest'],
                                            ':NoAllowPets' => $_POST['NoAllowPets'],
                                            ':SolarEnergy' => $_POST['SolarEnergy'],
                                            ':NumberOfNeighbors' => $_POST['NumberOfNeighbors'],
                                            ':FloorHouse' => $_POST['FloorHouse'],
                                            ':Parking' => $_POST['Parking'],
                                            ':ParkingSpaces' => $_POST['ParkingSpaces'],
                                            ':Garage' => $_POST['Garage'],
                                            ':GarageSpaces' => $_POST['GarageSpaces'],
                                            ':ParkingGarage' => $_POST['ParkingGarage'],
                                            ':ParkingGarageSlots' => $_POST['ParkingGarageSlots'],
                                            ':Lift' => $_POST['Lift'],
                                            ':Noise' => $_POST['Noise'],
                                            ':NoiseType' => $_POST['NoiseType'],
                                            ':ParkingNotes' => $_POST['ParkingNotes'],
                                            ':idinfo' => $_POST['idinfo']
                                        ));
                                    }

                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM INFORMACIO_ADICIONAL I WHERE I.INF_codiCasa = :casaid "
                                    );




                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

                                    $adi = $stmt->fetchObject();
                                    echo '<form action="#sectionG" method="post" id="infoadi' . $adi->INF_id . '">';
                                    echo '<table class="table">';
                                    echo '<th colspan ="3" class="success">INFORMACIO ADICIONAL DE LA VIVENDA</th>';
                                    echo '<input type = "hidden" name = "idinfo" value = "' . $adi->INF_id . '">';
                                    echo '<tr><td>Any construcció: <input type="number" name="YearOfConstruction" class = "form-control2l" min="0" max="2050" value="' . $adi->YearOfConstruction . '"></td>'
                                    . '<td>Any Remodelació: <input type="number" name="YearOfRemodel" class = "form-control2l" min="0" max="2050" value="' . $adi->YearOfRemodel . '"></td>'
                                    . '<td>Nº Plantes Casa: <input type="number" name="FloorsHouse" class = "form-control2l" min="0" max="20" value="' . $adi->FloorsHouse . '"></td></tr>';
                                    echo '<tr><td>' . checkbox($adi->AllowPets, "AllowPets", "  Es permeten mascotes  ") . '</td>'
                                    . '<td>' . checkbox($adi->PetsOnRequest, "PetsOnRequest", "  Mascotes a peticio  ") . '</td>'
                                    . '<td>' . checkbox($adi->NoAllowPets, "NoAllowPets", "  No es permeten mascotes  ") . '</td></tr>';
                                    echo '<tr><td>' . checkbox($adi->SolarEnergy, "SolarEnergy", "  Energia solar ") . '</td>'
                                    . '<td>Nº veins comunicats: <input type="number" name="NumberOfNeighbors" class = "form-control2l" min="0" max="10" value="' . $adi->NumberOfNeighbors . '"></td>'
                                    . '<td>Nºpis: <input type="number" name="FloorHouse" min="0" max="40" value="' . $adi->FloorHouse . '"></td></tr>';
                                    echo '<tr><td>' . checkbox($adi->Parking, "Parking", " Aparcament exterior ") . ''
                                    . '<input type="number" name="ParkingSpaces" class = "form-control2l" min="0" max="40" value="' . $adi->ParkingSpaces . '"> Places</td>'
                                    . '<td>' . checkbox($adi->Garage, "Garage", " Aparcament cobert ") . ''
                                    . '<input type="number" name="GarageSpaces" class = "form-control2l" min="0" max="30" value="' . $adi->GarageSpaces . '"> Places</td>'
                                    . '<td>' . checkbox($adi->ParkingGarage, "ParkingGarage", "  Garatge  ") . ''
                                    . '<input type="number" name="ParkingGarageSlots" class = "form-control2l" min="0" max="30" value="' . $adi->ParkingGarageSlots . '">Places</td></tr>';
                                    echo '<tr><td>' . checkbox($adi->Lift, "Lift", "  Ascensor  ") . '</td><td colspan = "2">' . checkbox($adi->Noise, "Noise", "  Renou  ") . ' Tipus : '
                                    . '<select name="NoiseType" class = "form-control1">';
                                    echo '<option  value="" ' . ($ban->NoiseType == " " ? "selected='selected'" : " ") . '></option>';
                                    echo '<option  value="Renou continuat" ' . ($ban->NoiseType == "Renou continuat" ? "selected='selected'" : " ") . '>Renou continuat</option>';
                                    echo '<option value = "Renou possiblement molest" ' . ($ban->NoiseType == "Renou possiblement molest" ? "selected='selected'" : " ") . '>Possiblement molest</option>';
                                    echo '</select></td></tr>';
                                    echo '<tr><td colspan="3">Notes:<textarea name="ParkingNotes" class = "form-control" rows="4" cols="90" style="width:100%">' . $adi->ParkingNotes . '</textarea></td></tr>';

                                    echo'<tr><td colspan="3"><input type="submit" value="Modificar Informació" name = "info" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"></td></tr>';
                                    echo '</table>';
                                    echo '</form>';
                                    ?>


                                </div>
                                <div id="sectionH" class="tab-pane fade">
                                    <?php
// ----------------------------------- Distancies ----------------------------------------

                                    if (isset($_POST['distancies'])) {


                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE DISTANCIES SET "
                                                . "NumberDistanceToBank = :NumberDistanceToBank, "
                                                . "NameDistanceToBank = :NameDistanceToBank, "
                                                . "NumberDistanceToSupermarket = :NumberDistanceToSupermarket, "
                                                . "NameDistanceToSupermarket = :NameDistanceToSupermarket, "
                                                . "NumberDistanceToBeach = :NumberDistanceToBeach, "
                                                . "NameDistanceToBeach = :NameDistanceToBeach, "
                                                . "NumberDistanceToAirport = :NumberDistanceToAirport, "
                                                . "NameDistanceToAirport = :NameDistanceToAirport, "
                                                . "NumberDistanceToGolf = :NumberDistanceToGolf, "
                                                . "NameDistanceToGolf = :NameDistanceToGolf, "
                                                . "NumberDistanceToVillage = :NumberDistanceToVillage, "
                                                . "NameDistanceToVillage = :NameDistanceToVillage, "
                                                . "NumberDistanceToTrain = :NumberDistanceToTrain, "
                                                . "NameDistanceToTrain = :NameDistanceToTrain, "
                                                . "NumberDistanceToBus = :NumberDistanceToBus, "
                                                . "NameDistanceToBus = :NameDistanceToBus, "
                                                . "NumberDistanceToFerry = :NumberDistanceToFerry, "
                                                . "NameDistanceToFerry = :NameDistanceToFerry, "
                                                . "NumberDistanceToHospital = :NumberDistanceToHospital, "
                                                . "NameDistanceToHospital = :NameDistanceToHospital, "
                                                . "NumberDistanceToPharmacy = :NumberDistanceToPharmacy, "
                                                . "NameDistanceToPharmacy = :NameDistanceToPharmacy, "
                                                . "NumberDistanceToRestaurant = :NumberDistanceToRestaurant, "
                                                . "NameDistanceToRestaurant = :NameDistanceToRestaurant, "
                                                . "DistanceNotes = :DistanceNotes "
                                                . "WHERE DIS_id = :iddis "
                                        );




                                        $stmt->execute(array(
                                            ':NumberDistanceToBank' => $_POST['NumberDistanceToBank'],
                                            ':NameDistanceToBank' => $_POST['NameDistanceToBank'],
                                            ':NumberDistanceToSupermarket' => $_POST['NumberDistanceToSupermarket'],
                                            ':NameDistanceToSupermarket' => $_POST['NameDistanceToSupermarket'],
                                            ':NumberDistanceToBeach' => $_POST['NumberDistanceToBeach'],
                                            ':NameDistanceToBeach' => $_POST['NameDistanceToBeach'],
                                            ':NumberDistanceToAirport' => $_POST['NumberDistanceToAirport'],
                                            ':NameDistanceToAirport' => $_POST['NameDistanceToAirport'],
                                            ':NumberDistanceToGolf' => $_POST['NumberDistanceToGolf'],
                                            ':NameDistanceToGolf' => $_POST['NameDistanceToGolf'],
                                            ':NumberDistanceToVillage' => $_POST['NumberDistanceToVillage'],
                                            ':NameDistanceToVillage' => $_POST['NameDistanceToVillage'],
                                            ':NumberDistanceToTrain' => $_POST['NumberDistanceToTrain'],
                                            ':NameDistanceToTrain' => $_POST['NameDistanceToTrain'],
                                            ':NumberDistanceToBus' => $_POST['NumberDistanceToBus'],
                                            ':NameDistanceToBus' => $_POST['NameDistanceToBus'],
                                            ':NumberDistanceToFerry' => $_POST['NumberDistanceToFerry'],
                                            ':NameDistanceToFerry' => $_POST['NameDistanceToFerry'],
                                            ':NumberDistanceToHospital' => $_POST['NumberDistanceToHospital'],
                                            ':NameDistanceToHospital' => $_POST['NameDistanceToHospital'],
                                            ':NumberDistanceToPharmacy' => $_POST['NumberDistanceToPharmacy'],
                                            ':NameDistanceToPharmacy' => $_POST['NameDistanceToPharmacy'],
                                            ':NumberDistanceToRestaurant' => $_POST['NumberDistanceToRestaurant'],
                                            ':NameDistanceToRestaurant' => $_POST['NameDistanceToRestaurant'],
                                            ':DistanceNotes' => $_POST['DistanceNotes'],
                                            ':iddis' => $_POST['iddis']
                                        ));
                                    }



                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * FROM DISTANCIES D WHERE D.DIS_codiCasa = :casaid "
                                    );




                                    $stmt->execute(
                                            array(
                                                ':casaid' => $codicasa
                                            )
                                    );

                                    $dis = $stmt->fetchObject();

                                    echo '<form action="#sectionH" method="post" id="distancia' . $dis->DIS_id . '">';
                                    echo '<table class="table table-condensed">';
                                    echo '<th colspan ="4" class="success">DISTANCIES</th>';
                                    echo '<input type = "hidden" name = "iddis" value = "' . $dis->DIS_id . '">';
                                    echo '<tr class="warning"><td colspan="2">Banc</td><td colspan="2">Supermercat</td></tr>';
                                    echo '<tr><td>Distància(km)</td><td>Nom</td><td>Distància(km)</td><td>Nom</td></tr>';
                                    echo '<tr><td><input type="number" name="NumberDistanceToBank" class = "form-control2b" min = "0" step = "0.05" value="' . $dis->NumberDistanceToBank . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToBank" class = "form-control4l" size="60" value="' . $dis->NameDistanceToBank . '"></td>'
                                    . '<td><input type="number" name="NumberDistanceToSupermarket" class = "form-control2b" min = "0" step = "0.05" value="' . $dis->NumberDistanceToSupermarket . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToSupermarket" class = "form-control4l" size="60" value="' . $dis->NameDistanceToSupermarket . '"></td></tr>';

                                    echo '<tr class="warning"><td colspan="2">Platja</td><td colspan="2">Aeroport</td></tr>';
                                    echo '<tr><td>Distància(km)</td><td>Nom</td><td>Distància(km)</td><td>Nom</td></tr>';
                                    echo '<tr><td><input type="number" name="NumberDistanceToBeach" class = "form-control2b" min = "0" step = "0.05" value="' . $dis->NumberDistanceToBeach . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToBeach" class = "form-control4l" size="60" value="' . $dis->NameDistanceToBeach . '"></td>'
                                    . '<td><input type="number" name="NumberDistanceToAirport" class = "form-control2b" min = "0" step = "0.05" value="' . $dis->NumberDistanceToAirport . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToAirport" class = "form-control4l" size="60" value="' . $dis->NameDistanceToAirport . '"></td></tr>';

                                    echo '<tr class="warning"><td colspan="2">Camp de golf</td><td colspan="2">Poble</td></tr>';
                                    echo '<tr><td>Distància(km)</td><td>Nom</td><td>Distància(km)</td><td>Nom</td></tr>';
                                    echo '<tr><td><input type="number" name="NumberDistanceToGolf" class = "form-control2b" min = "0" step = "0.05" value="' . $dis->NumberDistanceToGolf . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToGolf" class = "form-control4l" size="60" value="' . $dis->NameDistanceToGolf . '"></td>'
                                    . '<td><input type="number" name="NumberDistanceToVillage" class = "form-control2l" size="10" value="' . $dis->NumberDistanceToVillage . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToVillage" class = "form-control4l" size="60" value="' . $dis->NameDistanceToVillage . '"></td></tr>';

                                    echo '<tr class="warning"><td colspan="2">Aturada tren</td><td colspan="2">Aturada bus</td></tr>';
                                    echo '<tr><td>Distància(km)</td><td>Nom</td><td>Distància(km)</td><td>Nom</td></tr>';
                                    echo '<tr><td><input type="number" name="NumberDistanceToTrain" class = "form-control2b" min = "0" step = "0.05" value="' . $dis->NumberDistanceToTrain . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToTrain" class = "form-control4l" size="60" value="' . $dis->NameDistanceToTrain . '"></td>'
                                    . '<td><input type="number" name="NumberDistanceToBus" class = "form-control2b" min = "0" step = "0.05" value="' . $dis->NumberDistanceToBus . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToBus" class = "form-control4l" size="60" value="' . $dis->NameDistanceToBus . '"></td></tr>';

                                    echo '<tr class="warning"><td colspan="2">Ferry</td><td colspan="2">Hospital</td></tr>';
                                    echo '<tr><td>Distància(km)</td><td>Nom</td><td>Distància(km)</td><td>Nom</td></tr>';
                                    echo '<tr><td><input type="number" name="NumberDistanceToFerry" class = "form-control2b" min = "0" step = "0.05" value="' . $dis->NumberDistanceToFerry . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToFerry" class = "form-control4l" size="60" value="' . $dis->NameDistanceToFerry . '"></td>'
                                    . '<td><input type="number" name="NumberDistanceToHospital" class = "form-control2b" min = "0" step = "0.05" value="' . $dis->NumberDistanceToHospital . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToHospital" class = "form-control4l" size="60" value="' . $dis->NameDistanceToHospital . '"></td></tr>';

                                    echo '<tr class="warning"><td colspan="2">Farmàcia</td><td colspan="2">Bar/Restaurant</td></tr>';
                                    echo '<tr><td>Distància(km)</td><td>Nom</td><td>Distància(km)</td><td>Nom</td></tr>';
                                    echo '<tr><td><input type="number" name="NumberDistanceToPharmacy" class = "form-control2b" min = "0" step = "0.05" value="' . $dis->NumberDistanceToPharmacy . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToPharmacy" class = "form-control4l" size="60" value="' . $dis->NameDistanceToPharmacy . '"></td>'
                                    . '<td><input type="number" name="NumberDistanceToRestaurant" class = "form-control2b" min = "0" step = "0.05" value="' . $dis->NumberDistanceToRestaurant . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToRestaurant" class = "form-control4l" size="60" value="' . $dis->NameDistanceToRestaurant . '"></td></tr>';

                                    echo '<tr><td colspan="3">Notes:<textarea name="DistanceNotes" class = "form-control2l" rows="2" cols="90" style="width:100%">' . $dis->DistanceNotes . '</textarea></td></tr>';



                                    echo'<tr><td colspan="4"><input type="submit" value="Modificar Distàncies" name = "distancies" class="btn btn-primary"></td></tr>';
                                    echo '</table>';
                                    echo '</form>';
                                    ?>



                                </div>

                            </div>


                        </div>


                    </div>


                </div>

            </div>





        </div>


    </body>

</html>
