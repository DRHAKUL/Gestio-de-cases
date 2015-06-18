<?php
// Reb l'arxiu excel i el posa al formularis.

require_once 'class/Core.php';
$pdoCore = Core::getInstance();
session_start();
if (!$_SESSION['meva']) { // Comprobaci� de sessi�.
    header("Location: login.php"); //Si no hi ha sessio "meva", envia a login..
}
//Recollim nom usuari.
$usuari = $_SESSION['meva'];

// Si es pitja sortir es tanca la sesio i torna a login.php.
if (isset($_GET['sortir'])) {
    session_destroy();
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="es">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <script type="text/javascript" src="js/jquery-ui.js"></script>
        <script type="text/javascript" src="js/jquery-1.11.3.js"></script>
        <script src="js/bootstrap.js"></script>

        <title>Editor desde excel</title>


        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">




        <script>

//            Jquery que fa que no canvii de pestanya quan s'envia el formulari.




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
                var hash = window.location.hash;
                hash && $('ul.nav a[href="' + hash + '"]').tab('show');

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
                <ul class="nav navbar-right top-nav">



                    <h4><a class="warning"><i class="fa fa-user"></i> <?php echo $usuari; ?> </a>

                        <a href="index.php?sortir=si" ><i class="fa fa-fw fa-power-off"></i> Sortir</a></h4>


                </ul>


                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav side-nav">
                        <li>
                            <a href="index.php"><i class="glyphicon glyphicon-home"></i> Cases</a>
                        </li>
                        <li>
                            <a href="propietaris.php"><i class="glyphicon glyphicon-user"></i> Propietaris</a>
                        </li>
                        <li>
                            <a href="newcasa.php"><i class="glyphicon glyphicon-plus"></i> Afegir Casa</a>
                        </li>
                        <li>
                            <a href="newpropietari.php"><i class="glyphicon glyphicon-plus"></i> Afegir propietari</a>
                        </li>
                        <li>
                            <a href="fotoscasa.php"><i class="glyphicon glyphicon-camera"></i> Fotografies Casa</a>
                        </li>
                        <li>
                            <a href="pujadaarxius.php"><i class="glyphicon glyphicon-file"></i> Pujada d'arxius</a>
                        </li>
                        <li class="active">
                            <a href="pujarexcel.php"><i class="glyphicon glyphicon-file"></i> Pujada i edicio Excel</a>
                        </li>
                    </ul>
                </div>

            </nav>

            <div id="page-wrapper">

                <div class="container-fluid">


                    <div class="row">


                    </div>

                    <div class="breadcrumb">
                        <div class="bs-example">

                            <ul class="nav nav-tabs">
                                <li  class="active"><a data-toggle="tab" href="#sectionA">PROPIETARI</a></li>
                                <li><a data-toggle="tab" href="#sectionB">FITXA</a></li>
                                <li><a data-toggle="tab" href="#sectionC">DADES</a></li>
                                <li><a data-toggle="tab" href="#sectionD">GESTIÓ</a></li>
                                <li><a data-toggle="tab" href="#sectionE">INFORMACIÓ</a></li>
                                <li><a data-toggle="tab" href="#sectionF">ENTORN-VISTES</a></li>
                                <li><a data-toggle="tab" href="#sectionG">PISCINA-JARDI</a></li>
                                <li><a data-toggle="tab" href="#sectionH">EQUIPAMENT</a></li>
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
                                <li><a data-toggle="tab" href="#sectionI">INFORMACIÓ ADICIONAL</a></li>
                                <li><a data-toggle="tab" href="#sectionJ">DISTÀNCIES</a></li>


                            </ul>
                            <div class="tab-content">
                                <div id="sectionA" class="tab-pane fade in active">
                                    <?php
                                    // Consulta bbdd
                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT PRO_id FROM PROPIETARI ORDER BY PRO_id DESC LIMIT 1 "
                                    );
                                    $stmt->execute();
                                    $pro = $stmt->fetchObject();
                                    //Darrer id propietari.
                                    $darrerpro = ($pro->PRO_id) + 1;

                                    // Funcions  ..............
                                    // Per si del checkbox ens arriba "on" en lloc de 1
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

                                    $post = '';

                                    if (isset($_POST[$post])) {
                                        if ($_POST[$post] == 'on') {
                                            $_POST[$post] = '1';
                                            RETURN $_POST[$post];
                                        }
                                    } else {
                                        $_POST[$post] = '0';
                                    }

                                    // Funcio per omplir camps check box.
                                    function checkbox($camp_bd, $name, $texte) {
                                        if ($camp_bd == NULL) {
                                            $camp_bd = '0';
                                        }
                                        return '<input name="' . $name . '"  type="checkbox"  ' . ($camp_bd == 'SI' ? "checked" : "" ) . '  />' . $texte;
                                    }

                                    // Arriba l'excel


                                    if (!file_exists("excel")) {
                                        mkdir("excel", 0700);
                                    }
//
                                    if (isset($_POST['pujararxiu'])) {
                                        move_uploaded_file($_FILES["arxiu"]["tmp_name"], "excel/dades.xlsx");
                                    }
                                    // La classe de PHPExcel
                                    require_once 'Classes/PHPExcel/IOFactory.php';
                                    $objPHPExcel = PHPExcel_IOFactory::load('excel/dades.xlsx');


                                    // ----------------------------- Propietari --------------------------------
                                    // Insert del propietari nou.
                                    If (isset($_POST['newpro'])) {

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO PROPIETARI (PRO_id)VALUES(:id)");
                                        $stmt->execute(array(
                                            ':id' => $darrerpro
                                        ));


                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE PROPIETARI SET "
                                                . "PRO_nom = :nom, "
                                                . "PRO_dni = :dni, "
                                                . "PRO_personaContacte = :percont, "
                                                . "PRO_personaContacte2 = :percont2, "
                                                . "PRO_personaContacte3 = :percont3, "
                                                . "PRO_personaContacte4 = :percont4, "
                                                . "PRO_email = :mail, "
                                                . "PRO_email2 = :mail2, "
                                                . "PRO_email3 = :mail3, "
                                                . "PRO_email4 = :mail4, "
                                                . "PRO_telefon = :tel, "
                                                . "PRO_telefon2 = :tel2, "
                                                . "PRO_telefon3 = :tel3, "
                                                . "PRO_telefon4 = :tel4, "
                                                . "PRO_mobil = :mobil, "
                                                . "PRO_mobil2 = :mobil2, "
                                                . "PRO_mobil3 = :mobil3, "
                                                . "PRO_mobil4 = :mobil4, "
                                                . "PRO_fax = :fax, "
                                                . "PRO_fax2 = :fax2, "
                                                . "PRO_fax3 = :fax3, "
                                                . "PRO_fax4 = :fax4, "
                                                . "PRO_adreca = :adreca, "
                                                . "PRO_ciutat = :ciutat, "
                                                . "PRO_observacions = :observ, "
                                                . "PRO_codiPostal = :codipost, "
                                                . "PRO_provincia = :provin, "
                                                . "PRO_pais = :pais, "
                                                . "PRO_ccc = :ccc, "
                                                . "PRO_iban = :iban, "
                                                . "PRO_swift = :swift, "
                                                . "PRO_activitat = :activitat, "
                                                . "PRO_idGestor = :idgestor, "
                                                . "PRO_pagament = :paga, "
                                                . "PRO_pagamentBanc = :pagabanc, "
                                                . "PRO_rutaDni = :rutadni, "
                                                . "PRO_factura = :factura "
                                                . "WHERE PRO_id = '$darrerpro' "
                                        );

                                        $stmt->execute(array(
                                            ':nom' => $_POST['PRO_nom'],
                                            ':dni' => $_POST['PRO_dni'],
                                            ':percont' => $_POST['PRO_personaContacte'],
                                            ':percont2' => $_POST['PRO_personaContacte2'],
                                            ':percont3' => $_POST['PRO_personaContacte3'],
                                            ':percont4' => $_POST['PRO_personaContacte4'],
                                            ':mail' => $_POST['PRO_email'],
                                            ':mail2' => $_POST['PRO_email2'],
                                            ':mail3' => $_POST['PRO_email3'],
                                            ':mail4' => $_POST['PRO_email4'],
                                            ':tel' => $_POST['PRO_telefon'],
                                            ':tel2' => $_POST['PRO_telefon2'],
                                            ':tel3' => $_POST['PRO_telefon3'],
                                            ':tel4' => $_POST['PRO_telefon4'],
                                            ':mobil' => $_POST['PRO_mobil'],
                                            ':mobil2' => $_POST['PRO_mobil2'],
                                            ':mobil3' => $_POST['PRO_mobil3'],
                                            ':mobil4' => $_POST['PRO_mobil4'],
                                            ':fax' => $_POST['PRO_fax'],
                                            ':fax2' => $_POST['PRO_fax2'],
                                            ':fax3' => $_POST['PRO_fax3'],
                                            ':fax4' => $_POST['PRO_fax4'],
                                            ':adreca' => $_POST['PRO_adreca'],
                                            ':ciutat' => $_POST['PRO_ciutat'],
                                            ':observ' => $_POST['PRO_observacions'],
                                            ':codipost' => $_POST['PRO_codiPostal'],
                                            ':provin' => $_POST['PRO_provincia'],
                                            ':pais' => $_POST['PRO_pais'],
                                            ':ccc' => $_POST['PRO_ccc'],
                                            ':iban' => $_POST['PRO_iban'],
                                            ':swift' => $_POST['PRO_swift'],
                                            ':activitat' => $_POST['PRO_activitat'],
                                            ':idgestor' => $_POST['GES_nom'],
                                            ':paga' => $_POST['PRO_pagament'],
                                            ':pagabanc' => $_POST['PRO_pagamentBanc'],
                                            ':rutadni' => $_POST['PRO_rutaDni'],
                                            ':factura' => $_POST['PRO_factura']));
                                    }


                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('propietari')->toArray(null, true, true, true);

                                    //Recorrem les files obtingudes i les ordenam a un array "$infogen".

                                    $infogen = [];
                                    foreach ($objHoja as $iIndice => $objCelda) {
                                        //Posam el contingut de cada columna a una variable. I recorrem les files.
                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
                                    //var_dump($infogen);


                                    echo '<form action="#sectionB" method="post">';
                                    echo '<table class="table table-condensed">';
                                    echo '<th colspan ="4" class="success">Dades propietari</th>';
                                    echo '<tr><td>';
                                    echo '<label for = "nompro"> Nom Propietari </label>';
                                    echo '<input type = "text" pattern= "[A-Za-z\sàèòñáéíóú]*" class = "form-control" id = "nompro" class = "form-control3l" name="PRO_nom" value = "' . $infogen['Nom Propietari'] . ' ">';
                                    echo '</td><td>';
                                    echo '<label for = "dnipro"> Dni Propietari </label>';
                                    echo "<input type = 'text' class = 'form-control' id = 'dnipro' name='PRO_dni' value = " . $infogen['Dni'] . ">";
                                    echo '</td></tr>';
                                    echo '<tr><td>';
                                    echo '<label for = "contpro"> Persona contacte </label>';
                                    echo "<input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'contpro' "
                                    . "name='PRO_personaContacte' value = " . $infogen['Persona Contacte'] . ">";
                                    echo '</td><td>';
                                    echo '<label for = "cont2pro"> Segona persona contacte </label>';
                                    echo "<input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'cont2pro' "
                                    . "name='PRO_personaContacte2' value = " . $infogen['Persona Contacte2'] . ">";
                                    echo '</td><td>';
                                    echo '<label for = "cont3pro"> Tercera persona contacte </label>';
                                    echo "<input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'cont3pro' "
                                    . "name='PRO_personaContacte3' value = " . $infogen['Persona Contacte3'] . ">";
                                    echo '</td><td>';
                                    echo '<label for = "cont4pro"> Cuarta persona contacte </label>';
                                    echo "<input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'cont4pro' "
                                    . "name='PRO_personaContacte4' value = " . $infogen['Persona Contacte4'] . ">";
                                    echo '</td></tr>';
                                    echo '<tr><td>';
                                    echo '<label for = "emailpro"> Email </label>';
                                    echo "<input type = 'email' class = 'form-control' id = 'emailpro' name='PRO_email' value = " . $infogen['Email'] . ">";
                                    echo '</td><td>';
                                    echo '<label for = "email2pro"> Email2 </label>';
                                    echo "<input type = 'email' class = 'form-control' id = 'emailpro2' name='PRO_email2' value = " . $infogen['Email2'] . ">";
                                    echo '</td><td>';
                                    echo '<label for = "email3pro"> Email3 </label>';
                                    echo "<input type = 'email' class = 'form-control' id = 'emailpro3' name='PRO_email3' value = " . $infogen['Email3'] . ">";
                                    echo '</td><td>';
                                    echo '<label for = "email4pro"> Email4 </label>';
                                    echo "<input type = 'email' class = 'form-control' id = 'emailpro4' name='PRO_email4' value = " . $infogen['Email4'] . ">";
                                    echo '</td><td>';
                                    echo '<tr><td>';
                                    echo '<label for = "telfpro"> telefon pro </label>';
                                    echo "<input type = 'text' pattern= '[\d]{9}'  class = 'form-control' id = 'telfpro' name='PRO_telefon' value = " . $infogen['Telefon'] . " data-toggle='tooltip' '
                                    . 'data-placement='right' title='Nou numeros cap espai'>";
                                    echo '</td><td>';
                                    echo '<label for = "telfpro2"> telefon pro2 </label>';
                                    echo "<input type = 'text' pattern= '[\d]{9}' class = 'form-control' id = 'telfpro2' name='PRO_telefon2' value = " . $infogen['Telefon2'] . " data-toggle='tooltip' '
                                    . 'data-placement='right' title='Nou numeros cap espai'>";
                                    echo '</td><td>';
                                    echo '<label for = "telfpro3"> telefon pro3 </label>';
                                    echo "<input type = 'text' pattern= '[\d]{9}' class = 'form-control' id = 'telfpro3' name='PRO_telefon3' value = " . $infogen['Telefon3'] . " data-toggle='tooltip' '
                                    . 'data-placement='right' title='Nou numeros cap espai'>";
                                    echo '</td><td>';
                                    echo '<label for = "telfpro4"> telefon pro4 </label>';
                                    echo "<input type = 'text' pattern= '[\d]{9}' class = 'form-control' id = 'telfpro4' name='PRO_telefon4' value = '" . $infogen['Telefon4'] . "' data-toggle='tooltip' '
                                    . 'data-placement='right' title='Nou numeros cap espai'>";
                                    echo '</td><td>';
                                    echo '<tr><td>';
                                    echo '<label for = "movilpro"> telefon movil pro </label>';
                                    echo "<input type = 'text' pattern= '[\d]{9}' class = 'form-control' id = 'telfpro' name='PRO_mobil' value = " . $infogen['Movil'] . " data-toggle='tooltip' '
                                    . 'data-placement='right' title='Nou numeros cap espai'>";
                                    echo '</td><td>';
                                    echo '<label for = "movilpro2"> telefon movil2 pro </label>';
                                    echo "<input type = 'text' pattern= '[\d]{9}' class = 'form-control' id = 'telfpro2' name='PRO_mobil2' value = " . $infogen['Movil2'] . " data-toggle='tooltip' '
                                    . 'data-placement='right' title='Nou numeros cap espai'>";
                                    echo '</td><td>';
                                    echo '<label for = "movilpro3"> telefon movil3 pro </label>';
                                    echo "<input type = 'text' pattern= '[\d]{9}' class = 'form-control' id = 'telfpro3' name='PRO_mobil3' value = " . $infogen['Movil3'] . " data-toggle='tooltip' '
                                    . 'data-placement='right' title='Nou numeros cap espai'>";
                                    echo '</td><td>';
                                    echo '<label for = "movilpro4"> telefon movil4 pro </label>';
                                    echo "<input type = 'text' pattern= '[\d]{9}' class = 'form-control' id = 'telfpro4' name='PRO_mobil4' value = " . $infogen['Movil4'] . " data-toggle='tooltip' '
                                    . 'data-placement='right' title='Nou numeros cap espai'>";
                                    echo '</td><td>';
                                    echo '<tr><td>';
                                    echo '<label for = "faxpro"> Fax pro </label>';
                                    echo "<input type = 'text' pattern= '[\d]{9}' class = 'form-control' id = 'telfpro' name='PRO_fax' value = " . $infogen['Fax'] . " data-toggle='tooltip' '
                                    . 'data-placement='right' title='Nou numeros cap espai'>";
                                    echo '</td><td>';
                                    echo '<label for = "faxpro2"> Fax2 pro </label>';
                                    echo "<input type = 'text' pattern= '[\d]{9}' class = 'form-control' id = 'telfpro2' name='PRO_fax2' value = " . $infogen['Fax2'] . " data-toggle='tooltip' '
                                    . 'data-placement='right' title='Nou numeros cap espai'>";
                                    echo '</td><td>';
                                    echo '<label for = "faxpro3"> Fax3 pro </label>';
                                    echo "<input type = 'text' pattern= '[\d]{9}' class = 'form-control' id = 'telfpro3' name='PRO_fax3' value = " . $infogen['Fax3'] . " data-toggle='tooltip' '
                                    . 'data-placement='right' title='Nou numeros cap espai'>";
                                    echo '</td><td>';
                                    echo '<label for = "faxpro4"> Fax4 pro </label>';
                                    echo "<input type = 'text' pattern= '[\d]{9}' class = 'form-control' id = 'telfpro4' name='PRO_fax4' value = " . $infogen['Fax4'] . " data-toggle='tooltip' '
                                    . 'data-placement='right' title='Nou numeros cap espai'>";
                                    echo '</td></tr><tr><td>';
                                    echo '<label for = "adrecapro"> Adreça </label>';
                                    echo "<input type = 'text' class = 'form-control' id = 'adrecapro' name='PRO_adreca' value = " . $infogen['Adreça'] . ">";
                                    echo '</td><td>';
                                    echo '<label for = "ciutat"> Adreça </label>';
                                    echo "<input type = 'text' class = 'form-control' id = 'ciutat' name='PRO_ciutat' value = " . $infogen['Ciutat'] . ">";
                                    echo '</td><td>';
                                    echo '<label for = "observa"> Observacions </label>';
                                    echo "<textarea class='form-control' rows='3' name='PRO_observacions' >" . $infogen['Observacions'] . "</textarea>";
                                    echo '</td></tr><td>';
                                    echo '<label for = "codipostal"> Codi Postal </label>';
                                    echo "<input type = 'number' class = 'form-control' id = 'codipostal' name='PRO_codiPostal' value = " . $infogen['Codipostal'] . ">";
                                    echo '</td><td>';
                                    echo '<label for = "provincia"> Provincia </label>';
                                    echo "<input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'provincia' name='PRO_provincia' value = " . $infogen['Provincia'] . ">";
                                    echo '</td><td>';
                                    echo '<label for = "pais"> Pais </label>';
                                    echo "<input type = 'text' pattern= '[A-Za-z\ssàèòñáéíóú]*' class = 'form-control' id = 'pais' name='PRO_pais' value = " . $infogen['Pais'] . ">";
                                    echo '</tr>';
                                    echo '<th colspan ="4" class="success">Pagament</th>';
                                    echo '<tr><td>';
                                    echo '<label for = "ccc"> CCC </label>';
                                    echo "<input type = 'text' class = 'form-control' id = 'ccc' name='PRO_ccc' value = " . $infogen['CCC'] . ">";
                                    echo '</td><td>';
                                    echo '<label for = "iban"> IBAN </label>';
                                    echo "<input type = 'text' class = 'form-control' id = 'iban' name='PRO_iban' value = " . $infogen['IBAN'] . ">";
                                    echo '</td><td>';
                                    echo '<label for = "swift"> Swift </label>';
                                    echo "<input type = 'text' class = 'form-control' id = 'swift' name='PRO_swift' value = " . $infogen['Swift'] . ">";
                                    echo '</td><td>';
                                    echo '<label for = "activitat"> Activitat </label>';
                                    echo '<input type = "text" class = "form-control" id = "activitat" name="PRO_activitat" value = "' . $infogen['Activitat'] . '">';
                                    echo '</td></tr><tr><td>';
                                    echo '<label for = "gestor"> Gestor </label>';
                                    echo '<input type="text" name = "GES_nom" class="form-control" value="' . $infogen['Gestor'] . '">';

                                    echo '</td><td>';
                                    echo '<label for = "pagament"> Forma Pagament </label>';
                                    echo '<input type = "text" class = "form-control" id = "pagament" name="PRO_pagament" value ="' . $infogen['Forma pagament'] . '">';
                                    echo '</td><td>';
                                    echo '<label for = "pagamentbanc"> Banc Pagament </label>';
                                    echo '<input type = "text" class = "form-control" id = "pagamentbanc" name="PRO_pagamentBanc" value ="' . $infogen['Banc'] . '">';
                                    echo '</td><td>';
                                    echo '<label for = "rutadni"> Arxiu dni </label>';
                                    echo '<input type = "text" class = "form-control" id = "rutadni" name="PRO_rutaDni" value ="">';
                                    echo '</td></tr><tr><td>';
                                    echo '<label for = "factura"> Factura </label>';
                                    echo '<select name = "PRO_factura" class="form-control">';
                                    echo '<option  value="" ' . ($infogen['Factura'] == " " ? "selected='selected'" : " ") . '></option>';
                                    echo '<option  value="1" ' . ($infogen['Factura'] == "SI" ? "selected='selected'" : " ") . '>SI</option>';
                                    echo '<option  value="0" ' . ($infogen['Factura'] == "NO" ? "selected='selected'" : " ") . '>NO</option>';
                                    echo "<option value = '1'>Si</option>";
                                    echo '</select></td></tr>';
                                    echo '<tr><td colspan ="4" class="success">';
                                    echo '<input type = "submit" name="newpro" value = "Continuar >>" class = "btn btn-primary" data-toggle="tooltip" '
                                    . 'data-placement="right" title="Segur que vol guardar i continuar?">';
                                    echo '</td></tr>';
                                    echo '</table></form>';
                                    ?>

                                </div>


                                <div id="sectionB" class="tab-pane fade">

                                    <?php
// --------------------------------- Nova casa -------------------------------

                                    If (isset($_POST['newcas'])) {
                                        canvi('CAS_capsa');
                                        canvi('CAS_apunt');
                                        canvi('CAS_asseguranca');
                                        canvi('CAS_gestioIntegral');



                                        $stmt = $pdoCore->db->prepare(
                                                "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                        );
                                        $stmt->execute();
                                        $cas = $stmt->fetchObject();
                                        $darrer = $cas->CAS_id + 1;
                                        $casaid = $darrer;

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO CASA ( "
                                                . "CAS_codi,"
                                                . "CAS_nom,"
                                                . "CAS_idPropietari,"
                                                . "CAS_notesInternes,"
                                                . "CAS_notesGestors,"
                                                . "CAS_portals,"
                                                . "CAS_places,"
                                                . "CAS_codiTipus,"
                                                . "CAS_nomNorm,"
                                                . "CAS_codiLlicencia,"
                                                . "CAS_codiControl,"
                                                . "CAS_codiIlla,"
                                                . "CAS_codiZona,"
                                                . "CAS_segonNom,"
                                                . "CAS_activada,"
                                                . "CAS_dataCaducitatContracte,"
                                                . "CAS_capsa,"
                                                . "CAS_apunt,"
                                                . "CAS_notesTarifaBooking,"
                                                . "CAS_notesTarifaGestio,"
                                                . "CAS_asseguranca,"
                                                . "CAS_gestioIntegral,"
                                                . "CAS_link ) "
                                                . "VALUES ( "
                                                . ":CAS_codi,"
                                                . ":CAS_nom,"
                                                . ":CAS_idPropietari,"
                                                . ":CAS_notesInternes,"
                                                . ":CAS_notesGestors,"
                                                . ":CAS_portals,"
                                                . ":CAS_places,"
                                                . ":CAS_codiTipus,"
                                                . ":CAS_nomNorm,"
                                                . ":CAS_codiLlicencia,"
                                                . ":CAS_codiControl,"
                                                . ":CAS_codiIlla,"
                                                . ":CAS_codiZona,"
                                                . ":CAS_segonNom,"
                                                . ":CAS_activada,"
                                                . ":CAS_dataCaducitatContracte,"
                                                . ":CAS_capsa,"
                                                . ":CAS_apunt,"
                                                . ":CAS_notesTarifaBooking,"
                                                . ":CAS_notesTarifaGestio,"
                                                . ":CAS_asseguranca,"
                                                . ":CAS_gestioIntegral,"
                                                . ":CAS_link)"
                                        );
                                        $stmt->execute(
                                                array(
                                                    ':CAS_codi' => $darrer,
                                                    ':CAS_nom' => $_POST['CAS_nom'],
                                                    ':CAS_idPropietari' => $darrerpro - 1,
                                                    ':CAS_notesInternes' => $_POST['CAS_notesInternes'],
                                                    ':CAS_notesGestors' => $_POST['CAS_notesGestors'],
                                                    ':CAS_portals' => $_POST['CAS_portals'],
                                                    ':CAS_places' => $_POST['CAS_places'],
                                                    ':CAS_codiTipus' => $_POST['CAS_codiTipus'],
                                                    ':CAS_codiLlicencia' => $_POST['CAS_codiLlicencia'],
                                                    ':CAS_codiControl' => $_POST['CAS_codiControl'],
                                                    ':CAS_codiIlla' => $_POST['CAS_codiIlla'],
                                                    ':CAS_codiZona' => $_POST['CAS_codiZona'],
                                                    ':CAS_nomNorm' => $_POST['CAS_nomNorm'],
                                                    ':CAS_segonNom' => $_POST['CAS_segonNom'],
                                                    ':CAS_activada' => $_POST['CAS_activada'],
                                                    ':CAS_dataCaducitatContracte' => $_POST['CAS_dataCaducitatContracte'],
                                                    ':CAS_capsa' => $_POST['CAS_capsa'],
                                                    ':CAS_apunt' => $_POST['CAS_apunt'],
                                                    ':CAS_notesTarifaBooking' => $_POST['CAS_notesTarifaBooking'],
                                                    ':CAS_notesTarifaGestio' => $_POST['CAS_notesTarifaGestio'],
                                                    ':CAS_asseguranca' => $_POST['CAS_asseguranca'],
                                                    ':CAS_gestioIntegral' => $_POST['CAS_gestioIntegral'],
                                                    ':CAS_link' => $_POST['CAS_link']
                                                )
                                        );
                                    }


//$objHoja = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Casa')->toArray(null, true, true, true);
//recorremos las filas obtenidas

                                    $infogen = [];
                                    foreach ($objHoja as $iIndice => $objCelda) {
                                        //imprimimos el contenido de la celda utilizando la letra de cada columna
                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
//echo $infogen['nom casa'];
//var_dump($infogen);

                                    $casanom = $infogen['Nom casa'];

                                    echo '<table class = "table">';
                                    echo '<th colspan ="3" class="success">Fitxa casa</th>';
                                    echo '</table>';
                                    echo '<form action = "#sectionC" method = "post">';
                                    echo '<table class = "table">';
                                    echo '<tbody>';
                                    echo '<tr><td><label>Nom de la casa: </label>'
                                    . '<input type="text" name = "CAS_nom" value = "' . $infogen['Nom casa'] . '" class = "form-control4"></td>'
                                    . '<td><label> Plaçes: </label><input type="number" name="CAS_places" min="0" max="50" value = "' . $infogen['Plaçes'] . '" class = "form-control2l">';
                                    echo '&nbsp<label>Tipus: </label><input type="text" name="CAS_codiTipus" value = "' . $infogen['Tipus'] . '" class="form-control2l">';
                                    echo '&nbsp<label>Codi llicencia: </label><input type="text" name="CAS_codiLlicencia" value = "' . $infogen['Llicencia'] . '" class="form-control2l">';
                                    echo '<label>Codi Control: </label><input type="text" name="CAS_codiControl" value = "' . $infogen['Control'] . '" class="form-control2l">';
                                    echo '&nbsp<label>Codi Illa: </label><input type="text" name="CAS_codiIlla" value = "' . $infogen['Illa'] . '" class="form-control2l"></td></tr>';
                                    echo '<tr><td><label>Nom normal: </label><input type="text" class="form-control4" name="CAS_nomNorm" '
                                    . 'value = "' . $infogen['Nom normal'] . '"></td>';
                                    echo '<td><label>Segon nom: </label><input type="text" class="form-control4" name="CAS_segonNom" '
                                    . 'value = "' . $infogen['Segon nom'] . '"></td></tr>'
                                    . '<tr><td><label>Codi zona: </label><input type="number" min="0" max="15" class="form-control2" '
                                    . 'name="CAS_codiZona" class="form-control2l" value = "' . $infogen['Codi zona'] . '"></td><td><label>Activada: </label><select name="CAS_activada" class="form-control2l" class="form-control2">';
                                    echo '<option  value="" ' . ($infogen['Activada'] == " " ? "selected='selected'" : " ") . '></option>';
                                    echo '<option  value="1" ' . ($infogen['Activada'] == "SI" ? "selected='selected'" : " ") . '>SI</option>';
                                    echo '<option  value="0" ' . ($infogen['Activada'] == "NO" ? "selected='selected'" : " ") . '>NO</option>';
                                    echo '</select></td>';
                                    echo '<tr><td colspan="3"><label>Portals: </label><textarea name="CAS_portals" class = "form-control">' . $infogen['Portals'] . '</textarea></td></tr>';
                                    echo '<tr><td colspan="3"><label>Notes internes: </label><textarea name="CAS_notesInternes" class = "form-control">' . $infogen['Notes internes'] . '</textarea></td></tr>';
                                    echo '<tr><td colspan="3"><label>Notes gestors: </label><textarea name="CAS_notesGestors" class = "form-control">' . $infogen['Notes gestors'] . '</textarea></td></tr>';
                                    $fecha = $infogen['Caducitat contracte'];
                                    $dia = substr($fecha, 0, 2);

                                    $mes = substr($fecha, 3, 2);
                                    $ano = substr($fecha, -2);
                                    $fecha = '20' . $ano . '-' . $mes . '-' . $dia;
                                    echo '<tr><td><label>Data caducitat contracte: </label><input type="text" class = "form-control1" '
                                    . 'name="CAS_dataCaducitatContracte" value="' . $fecha . '"></td>'
                                    . '<td colspan="2">' . checkbox($infogen['Capsa'], "CAS_capsa", "Capsa") . ''
                                    . '&nbsp&nbsp' . checkbox($infogen['Apunt'], "CAS_apunt", "Apunt") . '&nbsp&nbsp'
                                    . '' . checkbox($infogen['Assegurança'], "CAS_asseguranca", "Assegurança") . '&nbsp&nbsp'
                                    . '' . checkbox($infogen['Gestió integral'], "CAS_gestioIntegral", "Gestió integral") . '</td></tr>';
                                    echo '<tr><td colspan ="3"><label>Notes Tarifa booking: </label><textarea class = "form-control" name="CAS_notesTarifaBooking">' . $infogen['Notes tarifa booking'] . '</textarea></td></tr>';
                                    echo '<tr><td colspan ="3"><label>Notes Tarifa gestió: </label><textarea class = "form-control" name="CAS_notesTarifaGestio">' . $infogen['Notes tarifa gestió'] . '</textarea></td></tr>';
                                    echo '<tr><td colspan ="3"><label>Link: </label><textarea class = "form-control" name="CAS_link">' . $infogen['Link web'] . '</textarea></td></tr>';
                                    $fecha = $infogen['Data alta'];
                                    $dia = substr($fecha, 0, 2);
                                    $mes = substr($fecha, 3, 2);
                                    $ano = substr($fecha, -2);
                                    $fecha = '20' . $ano . '-' . $mes . '-' . $dia;

                                    echo '<tr><td colspan ="3"><label>Data Alta: </label><input type ="text" class = "form-control" name="data_alta" value="' . $fecha . '"</td></tr>';

                                    echo '</tbody>';
                                    echo '</table>';
                                    echo '<input type="submit" name="newcas" class = "btn btn-primary " value = "Continuar >>" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar i continuar?">';
                                    echo '</form>';
                                    ?>
                                </div>





                                <div id="sectionC" class="tab-pane fade">

                                    <?php
// ------------------------------------ Dades casa ---------------------------------------
//
//                                    $stmt = $pdoCore->db->prepare(
//                                            "SELECT * FROM CASA WHERE CAS_nom = :nom "
//                                    );
//                                    $stmt->execute(array(':nom' => $casanom));
//
//                                    $casa = $stmt->fetchObject();
//
//Modificació dades

                                    if (isset($_POST['dades'])) {
                                        $stmt = $pdoCore->db->prepare(
                                                "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                        );
                                        $stmt->execute();
                                        $cas = $stmt->fetchObject();
                                        $darrer = $cas->CAS_id;

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO DADES_CASA (DAD_codiCasa)VALUES(:codi)");
                                        $stmt->execute(array(
                                            ':codi' => $darrer
                                        ));


                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE DADES_CASA SET Address = :Address, "
                                                . "GeograficCoordinates = :geo, City = :city, PostCode = :post "
                                                . "WHERE DAD_codicasa = :iddad"
                                        );

//
                                        $stmt->execute(array(':Address' => $_POST['Address'], ':geo' => $_POST['GeograficCoordinates'],
                                            ':city' => $_POST['City'], ':post' => $_POST['PostCode'], ':iddad' => $darrer));
                                    }
// Selecció de  dades


                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Dades')->toArray(null, true, true, true);

//recorremos las filas obtenidas

                                    $infogen = [];
                                    foreach ($objHoja as $iIndice => $objCelda) {
                                        //imprimimos el contenido de la celda utilizando la letra de cada columna
                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }

// Formulari dades

                                    echo '<form action="#sectionD" method="post">';
                                    echo '<table class="table">';
                                    echo '<th colspan ="3" class="success">Dades Casa</th>';
                                    echo '<tr>';

                                    echo '<td>';
                                    echo '<label for = "direccio"> DIRECCIÓ </label>';
                                    echo '<input type = "text" class = "form-control" name = "Address" value = "' . $infogen['Direcció'] . '" size = "10">';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<label for = "contpro"> COORDENADES </label>';
                                    echo "<input type = 'text' pattern='^([-]?\d{1,2}[.]\d+),\s*([-]?\d{1,3}[.]\d+)$' "
                                    . "class = 'form-control' name = 'GeograficCoordinates' value = " . $infogen['Coordenades'] . " size = '10'>";
                                    echo '</td></tr>';
                                    echo '<tr><td><label for = "cont2pro"> CIUTAT </label>';
                                    echo '<input type = "text" pattern= "[a-zA-Z\sàèòñáéíóú]*" class = "form-control" name = "City" '
                                    . 'value = "' . $infogen['Ciutat'] . '" size = "10">';
                                    echo '</td><td>';
                                    echo '<label for = "cont3pro"> CODI POSTAL </label>';
                                    echo "<input type = 'text' pattern='\d{5}' class = 'form-control' name = 'PostCode' "
                                    . "value = " . $infogen['Codipostal'] . " size = '10'><td></tr>";

                                    echo '<tr><td colspan = "3"><input type="submit" name ="dades" class="btn btn-primary" value = "Continuar >>" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar i continuar?"></td></tr>';

                                    echo '</table>';
                                    echo '</form>';
                                    ?>
                                </div>

                                <div id="sectionD" class="tab-pane fade">
                                    <?php
// --------------------------------------------- Gestio -------------------------------------
//
// funcio per quan el check es igual a 'on' i el canvia per 1.
//                                    function canvi($post) {
//                                        if (isset($_POST[$post])) {
//                                            if ($_POST[$post] == 'on') {
//                                                $_POST[$post] = '1';
//                                                RETURN $_POST[$post];
//                                            }
//                                        } else {
//                                            $_POST[$post] = '0';
//                                        }
//                                    }
//
//                                    // Modificació, formulari
                                    if (isset($_POST['gestio'])) {
//
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
//Modificació gestio

                                        $stmt = $pdoCore->db->prepare(
                                                "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                        );
                                        $stmt->execute();
                                        $cas = $stmt->fetchObject();
                                        $darrer = $cas->CAS_id;
                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO GESTIO (GES_codiCasa)VALUES(:codi)");
                                        $stmt->execute(array(
                                            ':codi' => $darrer
                                        ));


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
                                                . "WHERE GES_codiCasa = :iddad"
                                        );


                                        $stmt->execute(array(':netetgesgesprop' => $_POST['netetges_ges_prop'], ':netetgesgescsm' => $_POST['netetges_ges_csm'], ':netetgesgescol' => $_POST['netetges_ges_col'],
                                            ':netetges_desp_prop' => $_POST['netetges_desp_prop'], ':netetges_desp_csm' => $_POST['netetges_desp_csm'],
                                            ':bugaderia_ges_prop' => $_POST['bugaderia_ges_prop'], ':bugaderia_ges_csm' => $_POST['bugaderia_ges_csm'], ':bugaderia_ges_col' => $_POST['bugaderia_ges_col'],
                                            ':bugaderia_desp_prop' => $_POST['bugaderia_desp_prop'], ':bugaderia_desp_csm' => $_POST['bugaderia_desp_csm'], ':iddad' => $darrer,
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
//                                    $stmt = $pdoCore->db->prepare(
//                                            "SELECT * FROM GESTIO G WHERE G.GES_codiCasa = :casaid "
//                                    );
//                                    $stmt->execute(
//                                            array(
//                                                ':casaid' => $codicasa
//                                            )
//                                    );
//
//                                    $ges = $stmt->fetchObject();

                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Gestio')->toArray(null, true, true, true);



                                    $infogen = [];
                                    foreach ($objHoja as $iIndice => $objCelda) {
                                        //imprimimos el contenido de la celda utilizando la letra de cada columna
                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
// $infogen['Coordenades']
// Formulari per GESTIO
                                    echo '<table class="table">';
                                    echo '<th colspan ="4" class="success">Gestio</th>';
                                    echo '</td></tr></table>';

                                    echo '<form action="#sectionE" method="post">';

                                    echo '<table class="table table-bordered" style="text-align:center">';
                                    echo '<tr><td style="border: none"></td><td colspan="3" class="warning">GESTIÓ a càrrec de:</td><td colspan="2" class="warning">DESPESES a càrrec de:</td></tr>';
                                    echo '<tr><td style="border: none"></td><td>Propietari</td><td>Casamitger</td><td>col·laborador</td><td>Propietari</td><td>Casamitger</td></tr>';



                                    echo '<tr><td class="warning">Netetges</td>'
                                    . '<td><input type="checkbox" name="netetges_ges_prop"  ' . ($infogen['netetges_ges_prop'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="netetges_ges_csm"  ' . ($infogen['netetges_ges_csm'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="netetges_ges_col"  ' . ($infogen['netetges_ges_col'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="netetges_desp_prop" ' . ($infogen['netetges_desp_prop'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="netetges_desp_csm" ' . ($infogen['netetges_desp_csm'] ? "checked" : "" ) . '></td></tr>';



                                    echo '<tr><td class="warning">Bugaderia</td><td><input type="checkbox" '
                                    . 'name="bugaderia_ges_prop" value="' . $infogen['bugaderia_ges_prop'] . ' " ' . ($infogen['bugaderia_ges_prop'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="bugaderia_ges_csm" ' . ($infogen['bugaderia_ges_csm'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="bugaderia_ges_col" ' . ($infogen['bugaderia_ges_col'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="bugaderia_desp_prop" ' . ($infogen['bugaderia_desp_prop'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="bugaderia_desp_csm" ' . ($infogen['bugaderia_desp_csm'] ? "checked" : "" ) . '></td></tr>';


                                    echo '<tr><td class="warning">Posada a punt</td><td><input type="checkbox" name="posada_ges_prop" '
                                    . 'value="' . $infogen['posada_ges_prop'] . ' " ' . ($infogen['posada_ges_prop'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="posada_ges_csm" ' . ($infogen['posada_ges_csm'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="posada_ges_col" ' . ($infogen['posada_ges_col'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="posada_desp_prop" ' . ($infogen['posada_desp_prop'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="posada_desp_csm" ' . ($infogen['posada_desp_csm'] ? "checked" : "" ) . '></td></tr>';



                                    echo '<tr><td class="warning">piscina</td><td><input type="checkbox" '
                                    . 'name="piscina_ges_prop"  ' . ($infogen['piscina_ges_prop'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="piscina_ges_csm" ' . ($infogen['piscina_ges_csm'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="piscina_ges_col" ' . ($infogen['piscina_ges_col'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="piscina_desp_prop" ' . ($infogen['piscina_desp_prop'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="piscina_desp_csm" ' . ($infogen['piscina_desp_csm'] ? "checked" : "" ) . '></td></tr>';


                                    echo '<tr><td class="warning">Jardí</td><td><input type="checkbox" '
                                    . 'name="jardi_ges_prop"  ' . ($infogen['jardi_ges_prop'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="jardi_ges_csm" ' . ($infogen['jardi_ges_csm'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="jardi_ges_col" ' . ($infogen['jardi_ges_col'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="jardi_desp_prop" ' . ($infogen['jardi_desp_prop'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="jardi_desp_csm" ' . ($infogen['jardi_desp_csm'] ? "checked" : "" ) . '></td></tr>';


                                    echo '<tr><td class="warning">Entrades en persona</td><td><input type="checkbox" '
                                    . 'name="entrades_ges_prop"  ' . ($infogen['entrades_ges_prop'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="entrades_ges_csm" ' . ($infogen['entrades_ges_csm'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="entrades_ges_col" ' . ($infogen['entrades_ges_col'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="entrades_desp_prop" ' . ($infogen['entrades_desp_prop'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="entrades_desp_csm" ' . ($infogen['entrades_desp_csm'] ? "checked" : "" ) . '></td></tr>';

                                    echo '<tr><td class="warning">Sortides en persona</td><td><input type="checkbox" '
                                    . 'name="sortides_ges_prop"  ' . ($infogen['sortides_ges_prop'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="sortides_ges_csm" ' . ($infogen['sortides_ges_csm'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="sortides_ges_col" ' . ($infogen['sortides_ges_col'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="sortides_desp_prop" ' . ($infogen['sortides_desp_prop'] ? "checked" : "" ) . '></td>'
                                    . '<td><input type="checkbox" name="sortides_desp_csm" ' . ($infogen['sortides_desp_csm'] ? "checked" : "" ) . '></td></tr>';

                                    echo '</table>';

                                    echo '<table class="table">';
                                    echo '<tr><td>Condicions Especials de Pagament</td><td><input type="text" '
                                    . 'name ="SpecialConditionsPayment" class = "form-control1" size="90" value="' . $infogen['Condicions pagament'] . '" style="width:100%"></td></tr>';
                                    echo '<tr><td><input type="checkbox" name="capsa"  ' . ($infogen['Entrades capsa'] ? "checked" : "" ) . '> Entrades i sortides amb capsa</td></tr>';
                                    echo '<tr><td colspan="5"><input type="checkbox" name="KeyStore"  ' . ($infogen['Capsa clau'] ? "checked" : "") . '>Caixa claus + contrasenya: '
                                    . '<input type="text" name="KeyStoreCode" class = "form-control1" value="' . $infogen['Contrasenya'] . '" size="20"></td></tr>';
                                    echo '<tr><td>Lloc capseta:</td><td><textarea name="UbicationKeyStore" class = "form-control1" rows="2" cols="90" style="width:100%">' . $infogen['Ubicacio capsa'] . '</textarea></td></tr>';
                                    echo '<tr><td>Canvi de roba:</td><td><input type="checkbox"
                                        name="ChangeOfClothesAutomatic"  ' . ($infogen['Canvi roba automatic'] ? "checked" : "" ) . '>'
                                    . ' Automatic <input type="checkbox" name="ChangeOfClothesOwner"  ' . ($infogen['Canvi propietari'] ? "checked" : "" ) . '> Propietari</td></tr>';

                                    echo '<tr><td><input type="submit" name= "gestio" class="btn btn-primary" id="gestio" value = "Continuar >>" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar i continuar?"><td><tr>';

                                    echo '</table>';
                                    echo '</form>';
                                    ?>


                                </div>
                                <div id="sectionE" class="tab-pane fade">
                                    <?php
// ---------------------------------- Informacio Basica ---------------------------------
// Modificació
                                    if (isset($_POST['infobas'])) {


                                        $stmt = $pdoCore->db->prepare(
                                                "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                        );
                                        $stmt->execute();
                                        $cas = $stmt->fetchObject();
                                        $darrer = $cas->CAS_id;

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO INFORMACIO_BASICA (INF_codiCasa)VALUES(:codi)");
                                        $stmt->execute(array(
                                            ':codi' => $darrer
                                        ));


                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE INFORMACIO_BASICA SET MaximumCapacity = :MaximumCapacity, ExtraCapacity = :ExtraCapacity, "
                                                . "capacitat_total = :capacitat_total, Plot = :Plot, House = :House, NumberBedrooms = :NumberBedrooms, "
                                                . "NumberBathrooms = :NumberBathrooms,ExtraCapacityType = :ExtraCapacityType "
                                                . "WHERE INF_codicasa = :idinf"
                                        );


                                        $stmt->execute(array(':MaximumCapacity' => $_POST['MaximumCapacity'], ':ExtraCapacity' => $_POST['ExtraCapacity'],
                                            ':capacitat_total' => $_POST['capacitat_total'],
                                            ':Plot' => $_POST['Plot'], ':House' => $_POST['House'], ':NumberBedrooms' => $_POST['NumberBedrooms'],
                                            ':NumberBathrooms' => $_POST['NumberBathrooms'], ':ExtraCapacityType' => $_POST['ExtraCapacityType'], ':idinf' => $darrer));
                                    }

// Consulta taula.
                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Informació')->toArray(null, true, true, true);

//recorremos las filas obtenidas

                                    $infogen = [];
                                    foreach ($objHoja as $iIndice => $objCelda) {
                                        //imprimimos el contenido de la celda utilizando la letra de cada columna
                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
//Formulari
                                    echo '<form action="#sectionF" method="post">';

                                    echo '<table class="table">';
                                    echo '<th colspan ="6" class="success">INFORMACIO BASICA</th>';
                                    echo '<tr><td>Capacitat Pax: </td><td><input type="number" name="MaximumCapacity" min="0" max="50" class = "form-control2" '
                                    . 'value="' . $infogen['Capacitat pax'] . '"></td>'
                                    . '<td>Extra Pax: </td><td><input type="number" name="ExtraCapacity" min="0" max="50" class = "form-control2"  value="' . $infogen['Extra pax'] . '"></td>';
                                    echo '<td>Tipus Plaça Extra: <select name="ExtraCapacityType" class = "form-control3l">';
                                    echo '<option  value="" ' . ($infogen['Tipus extra'] == " " ? "selected='selected'" : " ") . '></option>';
                                    echo '<option  value="sofà-llit" ' . ($infogen['Tipus extra'] == "sofà-llit" ? "selected='selected'" : " ") . '>sofà-llit</option>';
                                    echo '<option  value="sofà-llit individual" ' . ($infogen['Tipus extra'] == "sofà-llit individual" ? "selected='selected'" : " ") . '>sofà-llit individual</option>';
                                    echo '<option  value="plegatin doble" ' . ($infogen['Tipus extra'] == "plegatin doble" ? "selected='selected'" : " ") . '>plegatin doble</option>';
                                    echo '<option value = "plegatin individual" ' . ($infogen['Tipus extra'] == "plegatin individual" ? "selected='selected'" : " ") . '>plegatin individual</option>';
                                    echo '</select></td></tr>';
                                    echo '<tr><td>Capacitat Total Pax: </td><td><input type="number" name="capacitat_total" class = "form-control2" min="0" max="50" value="' . $infogen['Total pax'] . '"></td>';
                                    echo '<td>Parcel-la m²: </td><td><input type="number" step="0.10"min="0" max="100000" name="Plot" class = "form-control2" value="' . $infogen['Parcela'] . '"></td><td>Vivienda m²: '
                                    . '<input type="number" min="0" max="10000" step="0.10" name="House" class = "form-control2l" value="' . $infogen['Vivenda'] . '"></td></tr>';
                                    echo '<tr><td>Dormitoris nº: </td><td><input type="number" name="NumberBedrooms" class = "form-control2" min="0" max="20" value="' . $infogen['Dormitoris'] . '"></td>'
                                    . '<td>Banys nº:  </td><td colspan="3"><input type="number" class = "form-control2" name="NumberBathrooms" min="0" max="20" value="' . $infogen['Banys'] . '"></td></tr>';
                                    echo '<tr><td colspan = "3"><input type="submit" name="infobas" class="btn btn-primary" value = "Continuar >>" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar i continuar?"><td><tr>';
                                    echo '</table>';


                                    echo '</form>';
                                    ?>
                                </div>

                                <div id="sectionF" class="tab-pane fade">
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
                                                "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                        );
                                        $stmt->execute();
                                        $cas = $stmt->fetchObject();
                                        $darrer = $cas->CAS_id;

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO ENTORN_VISTES (ENT_codiCasa)VALUES(:codi)");
                                        $stmt->execute(array(
                                            ':codi' => $darrer
                                        ));


                                        $stmt = $pdoCore->db->prepare(
                                                "UPDATE ENTORN_VISTES SET FirstLineToBeach = :FirstLineToBeach, NoViews = :NoViews, "
                                                . "PrivacyPartial = :PrivacyPartial, Mountain = :Mountain, SeaView = :SeaView, "
                                                . "PrivacyTotal = :PrivacyTotal, Countryside = :Countryside, TownView = :TownView, "
                                                . "Village = :Village, ForestView = :ForestView, "
                                                . "ResidentialArea = :ResidentialArea, CountrysideView = :CountrysideView, CoastZone = :CoastZone, "
                                                . "MountainView = :MountainView, Neighbors = :Neighbors, "
                                                . "GolfView = :GolfView "
                                                . "WHERE ENT_codicasa = :ident"
                                        );


                                        $stmt->execute(array(':FirstLineToBeach' => $_POST['FirstLineToBeach'], ':NoViews' => $_POST['NoViews'], ':PrivacyPartial' => $_POST['PrivacyPartial'],
                                            ':Mountain' => $_POST['Mountain'], ':SeaView' => $_POST['SeaView'],
                                            ':PrivacyTotal' => $_POST['PrivacyTotal'], ':Countryside' => $_POST['Countryside'], ':TownView' => $_POST['TownView'],
                                            ':Village' => $_POST['Village'], ':ForestView' => $_POST['ForestView'], ':ResidentialArea' => $_POST['ResidentialArea'],
                                            ':CountrysideView' => $_POST['CountrysideView'], ':CoastZone' => $_POST['CoastZone'], ':MountainView' => $_POST['MountainView'],
                                            ':Neighbors' => $_POST['Neighbors'], ':GolfView' => $_POST['GolfView'],
                                            ':ident' => $darrer
                                        ));
                                    }


                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Entorn')->toArray(null, true, true, true);


                                    $infogen = [];
                                    foreach ($objHoja as $iIndice => $objCelda) {

                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
//var_dump($infogen);

                                    echo '<form action="#sectionG" method="post">';
                                    echo '<table class="table">';
                                    echo '<th colspan ="4" class="success">ENTORN I VISTES</th>';
                                    echo '<tr><td>';
                                    echo '<table class="table">';
                                    echo '<th style="border:solid 1px grey">Situacio</th>';

                                    echo '<tr><td><input type="checkbox" name="FirstLineToBeach"  ' . ($infogen['Primera linea'] ? "checked" : "" ) . '>1ª linia de mar</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="Mountain"  ' . ($infogen['Zona montanya'] ? "checked" : "") . '>Zona montanya</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="Countryside"  ' . ($infogen['Camp'] ? "checked" : "") . '>Camp</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="Village"  ' . ($infogen['Poble'] ? "checked" : "") . '>Poble</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="ResidentialArea"  ' . ($infogen['Urbanització'] ? "checked" : "") . '>Urbanització</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="CoastZone"  ' . ($infogen['Zona costanera'] ? "checked" : "") . '>Zona Costanera</td></tr>';

                                    echo '</table>';
                                    echo '</td>';


                                    echo '<td>';
                                    echo '<table class="table">';
                                    echo '<th style="border:solid 1px grey">Vistes</th>';
                                    echo '<tr><td><input type="checkbox" name="NoViews"  ' . ($infogen['Sense vistes'] ? "checked" : "") . '>Sense vistes</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="SeaView"  ' . ($infogen['Mar'] ? "checked" : "") . '>Mar</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="TownView"  ' . ($infogen['Al poble'] ? "checked" : "") . '>Poble</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="ForestView"  ' . ($infogen['Bosc'] ? "checked" : "") . '>Bosc</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="CountrysideView"  ' . ($infogen['Al Camp'] ? "checked" : "") . '>Camp</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="MountainView"  ' . ($infogen['Muntanyes'] ? "checked" : "") . '>Muntanyes</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="Neighbors"  ' . ($infogen['Veins aprop'] ? "checked" : "") . '>Veïns a menys de 50m</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="GolfView"  ' . ($infogen['Vistes golf'] ? "checked" : "") . '>Vistes a camp de golf</td></tr>';


                                    echo '</table>';
                                    echo '</td>';



                                    echo '<td>';
                                    echo '<table class="table">';
                                    echo '<th style="border:solid 1px grey">Privacitat</th>';
                                    echo '<tr><td><input type="checkbox" name="PrivacyTotal"  ' . ($infogen['Total'] ? "checked" : "") . '>Total</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="PrivacyPartial"  ' . ($infogen['Parcial'] ? "checked" : "") . '>Parcial</td></tr>';
                                    echo '</table>';
                                    echo '</td></tr>';

                                    echo '<tr><td colspan = "3"><input type="submit" name = "entorn" class="btn btn-primary" value = "Continuar >>" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar i continuar?"></td></tr>';

                                    echo '</table>';
                                    echo '</form>';
                                    ?>
                                </div>


                                <div id="sectionG" class="tab-pane fade">
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
                                                "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                        );
                                        $stmt->execute();
                                        $cas = $stmt->fetchObject();
                                        $darrer = $cas->CAS_id;

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO PISCINA_JARDI (PIS_codiCasa)VALUES(:codi)");
                                        $stmt->execute(array(
                                            ':codi' => $darrer
                                        ));



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
                                                . "WHERE PIS_codicasa = :idpis"
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
                                            ':idpis' => $darrer
                                        ));
                                    }







                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Piscina')->toArray(null, true, true, true);


                                    $infogen = [];
                                    foreach ($objHoja as $iIndice => $objCelda) {

                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
//var_dump($infogen);

                                    echo '<form action="#sectionH" method="post">';

                                    echo '<table class="table table-responsive table-condensed">';
                                    echo '<th colspan ="7" class="success">PISCINA</th>';

                                    echo '<tr><td colspan="2"><input type="checkbox" name ="PrivatePool"  ' . ($infogen['Privada'] ? "checked" : "") . '>Privada</td>'
                                    . '<td><input type="checkbox" name="SharedSwimmingPool"  ' . ($infogen['Compartida'] ? "checked" : "") . '>Compartida</td>'
                                    . '<td><td colspan="3"><input type="checkbox" name="FencedPool"  ' . ($infogen['Vallada'] ? "checked" : "") . '>Protecció per a nins(Vallada)</td></tr>';


                                    echo '<tr><td>Dimensions: </td><td><input type="text" name="PoolDimensions" class = "form-control3" size="10" value="' . $infogen['Dimensions'] . '" data-toggle="tooltip" data-placement="top" title="En metres"></td>'
                                    . '<td>Profunditat: </td>'
                                    . '<td><input type="text" name="PoolDepth" class = "form-control2l" size="6" value="' . $infogen['Profunditat'] . '"></td>'
                                    . '<td colspan="2"><input type="checkbox" name="AboveGroundPool"  ' . ($infogen['Elevada'] ? "checked" : "") . '>Elevada</td></tr>';
                                    echo '<tr><td>Horari llums: </td><td><input type="text" name="ProgrammeLightsPool" class = "form-control3" size="10" value="' . $infogen['Horari llums'] . '"></td>'
                                    . '<td colspan="2"><input type="checkbox" name="SwimmingPoolWithSalt"  ' . ($infogen['Sal'] ? "checked" : "") . '>Sal</td>'
                                    . '<td colspan="3"><input type="checkbox" name="SwimmingPoolWithChlorine"  ' . ($infogen['Clor'] ? "checked" : "") . '>Clor</td></tr>';
                                    echo '<tr><td colspan="2"><input type="checkbox" name="HeatedSwimmingPool"  ' . ($infogen['Climatizada'] ? "checked" : "") . '>Cimatizada</td>'
                                    . '<td><input type="checkbox" name="IntegratedJacuzzi"  ' . ($infogen['Jacuzzi integrat'] ? "checked" : "") . '>Jacuzzi integrat</td>'
                                    . '<td colspan="4"><input type="checkbox" name="SeparateJacuzzi"  ' . ($infogen['Jacuzzi separat'] ? "checked" : "") . '>Jacuzzi separat</td></tr>';
                                    echo '<tr><td colspan="2"><input type="checkbox" name="ChildrenPool"  ' . ($infogen['Piscina infantil'] ? "checked" : "") . '>Piscina infantil</td>'
                                    . '<td><input type="checkbox" name="FixedBarbecue"  ' . ($infogen['Barbacoa fixa'] ? "checked" : "") . '>Barbacoa fixa</td>'
                                    . '<td colspan="4"><input type="checkbox" name="MobileBarbecue"  ' . ($infogen['Barbacoa mobil'] ? "checked" : "") . '>Barbacoa móbil</td></tr>';
                                    echo '<tr><td colspan="2"><input type="checkbox" name="OutdoorShower"  ' . ($infogen['Dutxa exterior'] ? "checked" : "") . '>Ducha exterior</td>'
                                    . '<td><input type="checkbox" name="Umbrellas"  ' . ($infogen['Sombrilles'] ? "checked" : "") . '>Sombrilles nº:</td>'
                                    . '<td><input type="number" min="0" max="30" name="NumberOfUmbrellas" class = "form-control2l" value="' . $infogen['Numero sombrilles'] . '"></td>'
                                    . '<td><input type="checkbox" name="DeckChair"  ' . ($infogen['Tumbones'] ? "checked" : "") . '>Tumbones  nº: </td>'
                                    . '<td><input type="number" name="NumberOfDeckChair" class = "form-control2l" min="0" max="30" value="' . $infogen['Numero tumbones'] . '"></td></tr>';
                                    echo '<td><input type="checkbox" name="ThereTerrace1"   ' . ($infogen['Terrassa1'] ? "checked" : "") . '>Terrassa1: </td>'
                                    . '<td><input type="number" name="TerraceDimensions1" class = "form-control2l" min="0" max="1000" value="' . $infogen['Metres terrassa1'] . '"> m² </td>'
                                    . '<td colspan="2"><input type="checkbox" name=""  ' . ($infogen['Terrassa amoblada1'] ? "checked" : "") . '>Terrassa amoblada1</td>'
                                    . '<td><input type="checkbox" name="ThereTerrace2"  ' . ($infogen['Terrassa2'] ? "checked" : "") . '>Terrassa2: </td>'
                                    . '<td><input type="number" name="TerraceDimensions2" class = "form-control2l" min="0" max="1000" value="' . $infogen['Metres terrassa2'] . '"> m²</td>'
                                    . '<td colspan="2"><input type="checkbox" name=""  ' . ($infogen['Terrassa amoblada2'] ? "checked" : "") . '>Terrassa amoblada2</td><tr>';
                                    echo '<td><input type="checkbox" name="ThereTerrace3"  ' . ($infogen['Terrassa3'] ? "checked" : "") . '>Terrassa3: </td>'
                                    . '<td><input type="number" name="TerraceDimensions3" class = "form-control2l" min="0" max="1000" value="' . $infogen['Metres terrassa3'] . '"> m²</td>'
                                    . '<td colspan="2"><input type="checkbox" name=""  ' . ($infogen['Terrassa amoblada3'] ? "checked" : "") . '>Terrassa amoblada3</td>'
                                    . '<td><input type="checkbox" name="ThereBalcony"  ' . ($infogen['Balco'] ? "checked" : "") . '>Balcó: </td>'
                                    . '<td><input type="number" name="BalconyDimensions" class = "form-control2l" min="0" max="1000" value="' . $infogen['Balco metres'] . '"> m</td>'
                                    . '<td colspan="2"><input type="checkbox" name="FurnishedBalcony"  ' . ($infogen['Balco amoblat'] ? "checked" : "") . '>Balco amoblat</td><tr>';

                                    echo '<tr><td><input type="checkbox" name="FurnishedPorche1"  ' . ($infogen['Porxo1'] ? "checked" : "") . '>Porxo1</td>'
                                    . '<td><input type="number" name="PorchDimensions1" class = "form-control2l" min="0" max="1000" value="' . $infogen['Porxo1 metres'] . '"> m²</td>';
                                    echo '<td><input type="checkbox" name="FurnishedPorche2"  ' . ($infogen['Porxo2'] ? "checked" : "") . '>Porxo2</td>'
                                    . '<td><input type="number" name="PorchDimensions2" class = "form-control2l" min="0" max="1000" value="' . $infogen['Porxo2 metres'] . '"> m²</td>';
                                    echo '<td><input type="checkbox" name="FurnishedPorche3"  ' . ($infogen['Porxo3'] ? "checked" : "") . '>Porxo3'
                                    . '</td><td><input type="number" name="PorchDimensions3" class = "form-control2l" min="0" max="1000" value="' . $infogen['Porxo3 metres'] . '"> m² </td></tr>';
                                    echo '<tr><td colspan="2"><input type="checkbox" name="TableTennis"  ' . ($infogen['Pingpong'] ? "checked" : "") . '>Ping-Pong</td>'
                                    . '<td colspan="5"><input type="checkbox" name="IndoorPool"  ' . ($infogen['Piscina interior'] ? "checked" : "") . '>Piscina interior</td></tr>';

                                    echo '<th colspan ="7" class="success">Jardi Privat</th>';
                                    echo '<tr><td><input type="checkbox" name="Floor"  ' . ($infogen['Trispol'] ? "checked" : "") . '>Trispol: </td>'
                                    . '<td><input type="number" min="0" max="1000" name="FloorSurface" class = "form-control2l" value="' . $infogen['Metres trispol'] . '"> m²</td>'
                                    . '<td><input type="checkbox" name="Grass"  ' . ($infogen['gespa'] ? "checked" : "") . '>Gespa</td>'
                                    . '<td  colspan="4"><input type="number" min="0" max="1000" name="GrassSurface" class = "form-control2l" value="' . $infogen['Metres gespa'] . '"> m²</td></tr>';
                                    echo '<tr><td colspan="2"><input type="checkbox" name="VegetableGarden"  ' . ($infogen['Hort'] ? "checked" : "") . '>Hort</td>'
                                    . '<td><input type="checkbox" name="Fence" ' . ($infogen['vallat'] ? "checked" : "") . '>Vallat</td><td>'
                                    . '<td colspan="2"><input type="checkbox" name="FruitTree"  ' . ($infogen['Arbres fruiters'] ? "checked" : "") . '>Arbres fruiters</td></tr>';
                                    echo '<tr><td>Accés a l`hort</td><td colspan="6"><textarea rows=2 name="GardenAcces" class = "form-control" cols=125 style="width:100%">' . $infogen['Acces hort'] . '</textarea></td></tr>';
                                    echo '<th colspan ="7" class="success">Jardi Comunitari</th>';
                                    echo '<tr><td><input type="checkbox" name="FloorCommunity"  ' . ($infogen['Trispol comunitari'] ? "checked" : "") . '>Trispol: </td>'
                                    . '<td><input type="number" min="0" max="1000" name="FloorSurfaceCommunity" class = "form-control2l" value="' . $infogen['Metres Trispol'] . '"> m²</td>'
                                    . '<td><input type="checkbox" name="GrassCommunity"  ' . ($infogen['Gespa'] ? "checked" : "") . '>Gespa</td>'
                                    . '<td  colspan="2"><input type="number" min="0" max="1000" name="GrassSurfaceCommunity" class = "form-control2l" value="' . $infogen['Metres Gespa'] . '"> m²</td>'
                                    . '<td><input type="checkbox" name="FenceCommunity"  ' . ($infogen['Vallat'] ? "checked" : "") . '>Vallat</td></td></tr>';
                                    echo '<tr><td>Notes: </td><td colspan="6"><textarea name="GardenNotes" class = "form-control" rows=2 cols=125 style="width:100%">' . $infogen['Notes'] . '</textarea></td></tr>';

                                    echo '<tr><td><input type="submit"  name = "piscina" class="btn btn-primary" value = "Continuar >>" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar i continuar?"></td></tr>';

                                    echo '</table>';
                                    echo '</form>';
                                    ?>

                                </div>
                                <div id="sectionH" class="tab-pane fade">
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
                                                "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                        );
                                        $stmt->execute();
                                        $cas = $stmt->fetchObject();
                                        $darrer = $cas->CAS_id;

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO EQUIPAMENT (EQU_codiCasa)VALUES(:codi)");
                                        $stmt->execute(array(
                                            ':codi' => $darrer
                                        ));


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
                                                . "WHERE EQU_codicasa = :idequ"
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
                                            ':idequ' => $darrer
                                        ));
                                    }


                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Equipament')->toArray(null, true, true, true);


                                    $infogen = [];
                                    foreach ($objHoja as $iIndice => $objCelda) {

                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
//var_dump($infogen);

                                    echo '<form action="#dropdown1" method="post">';

                                    echo '<table class="table table-responsive">';
                                    echo '<th colspan ="6" class="success">Climatització</th>';

                                    echo '<tr><td><input type="checkbox" name="AirConditioning"  ' . ($infogen['Aire acondicionat'] ? "checked" : "") . '>Aire acondicionat: </td>'
                                    . '<td>on i quans <input type="number" name="AirConditioningNumber" class = "form-control2l" size = "20" value="' . $infogen['Quans'] . '"></td>'
                                    . '<td colspan="4">Situació: <input type="text" name="AirConditioningLocation" class = "form-control1" value="' . $infogen['Situació'] . '"></td></tr>';
                                    echo '<tr><td>Notes aire acondicionat: </td><td colspan="5"><textarea name="AirConditioningNotes" class = "form-control" rows=2 cols=80 style="width:100%">' . $infogen['Notes aire'] . '</textarea></td></tr>';
                                    echo '<tr><td colspan = "2"><input type="checkbox" name="GasStove"  ' . ($infogen['Estufa gas'] ? "checked" : "") . '>Estufa de Gas </td>'
                                    . '<td colspan="4"><input type="checkbox" name="WoodStove"  ' . ($infogen['Estufa llenya'] ? "checked" : "") . '>Estufa de llenya </td>';
                                    echo '<tr><td colspan = "2"></td><td><input type="checkbox" name="CentralDieselHeating"  ' . ($infogen['Calefacció gasoil'] ? "checked" : "") . '>Calefacció central gasoil</td>'
                                    . '<td colspan="3"><input type="checkbox" name="CentralGasHeating"  ' . ($infogen['Calefacció gas'] ? "checked" : "") . '>Calefacció central gas </td></tr>';
                                    echo '<tr><td><input type="checkbox" name="ElectricRadiators"  ' . ($infogen['Radiadors elèctrics'] ? "checked" : "") . '>Radiadors Elèctrics </td>'
                                    . '<td>nº <input type="number" name="NumberOfElectricRadiators" class = "form-control2l" min="0" max="30" value="' . $infogen['Numero radiadors'] . '"></td>'
                                    . '<td><input type="checkbox" name="Chimney"  ' . ($infogen['Xemeneia'] ? "checked" : "") . '>Xemeneia</td>'
                                    . '<td>nº <input type="number" name="NumberOfFireplaces" class = "form-control2l" min="0" max="10" value="' . $infogen['Numero xemeneies'] . '"></td><'
                                    . 'td><input type="checkbox" name="Fans"  ' . ($infogen['Ventiladors'] ? "checked" : "") . '>Ventiladors </td>'
                                    . '<td>nº <input type="number" name="NumberOfFans" class = "form-control2l" min="0" max="30" value="' . $infogen['Numero ventiladors'] . '"></td></tr>';
                                    echo '<tr><td colspan="3">Potencia contractada: <input type="text" name="ContractedPower" class = "form-control3l" size="50" value="' . $infogen['Potencia contractada'] . '"></td>'
                                    . '<td colspan="3"><input type="checkbox" name="UnderfloorHeating"  ' . ($infogen['Sol radiant'] ? "checked" : "") . '>Sol radiant</td></tr>';
                                    echo '<th colspan ="6" class="success">Electronica i entreteniment</th>';
                                    echo '<tr><td><input type="checkbox" name="Wifi"  ' . ($infogen['Wifi'] ? "checked" : "") . '>Wifi </td>'
                                    . '<td colspan = "5">Clau <input type="text" name="WifiCode" class = "form-control1" size="14" value="' . $infogen['Clau wifi'] . '"></td></tr>';
                                    echo '<tr><td colspan = "6">Quadre elèctric: <input type="text" name="ElectricalBox" class = "form-control3l" size="46" value="' . $infogen['Quadre elèctric'] . '" data-toggle="tooltip" data-placement="right" title="Situacio del quadre"></td></tr>';
                                    echo '<tr><td><input type="checkbox" name="Television"  ' . ($infogen['Tv'] ? "checked" : "") . '>TV</td>'
                                    . '<td><input type="checkbox" name="SatelliteTelevision"  ' . ($infogen['Tv satèlit'] ? "checked" : "") . '>TV Satèl-lit</td>'
                                    . '<td><input type="checkbox" name="EnglishChannel"  ' . ($infogen['Angles'] ? "checked" : "") . '>Angles</td>'
                                    . '<td><input type="checkbox" name="GermanChannel"  ' . ($infogen['Alemany'] ? "checked" : "") . '>Alemany</td>'
                                    . '<td colspan="2"><input type="checkbox" name="ItalianChannel"  ' . ($infogen['Italia'] ? "checked" : "") . '>Italia</td></tr>'
                                    . '<tr><td></td><td></td><td><input type="checkbox" name="FrenchChannel"  ' . ($infogen['Francès'] ? "checked" : "") . '>Francès</td>'
                                    . '<td><input type="checkbox" name="DutchChannel"  ' . ($infogen['Holandes'] ? "checked" : "") . '>Holandes</td>'
                                    . '<td colspan="2"><input type="checkbox" name="RussianChannel"  ' . ($infogen['Rus'] ? "checked" : "") . '>Rus</td></tr>'
                                    . '<tr><td></td><td></td><td colspan="4"><input type="checkbox" name="ArabianChannel"  ' . ($infogen['Arab'] ? "checked" : "") . '>Arab</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="DvdPlayer"  ' . ($infogen['Reproductor dvd'] ? "checked" : "") . '>Reproductor DVD</td>'
                                    . '<td><input type="checkbox" name="DVD"  ' . ($infogen['Dvds'] ? "checked" : "") . '>DVD`S</td><td></td>'
                                    . '<td><input type="checkbox" name="CdPlayer"  ' . ($infogen['Reproductor cd'] ? "checked" : "") . '>Reproductor CD</td>'
                                    . '<td colspan="2"><input type="checkbox" name="CD"  ' . ($infogen['Cds'] ? "checked" : "") . '>CD`S</td></tr>';
                                    echo '<tr><td><input type="checkbox" name="ChildrensGames"  ' . ($infogen['Jocs nins'] ? "checked" : "") . '>Jocs per nins</td>'
                                    . '<td><input type="checkbox" name="Films"  ' . ($infogen['Pelicules'] ? "checked" : "") . '>Pel·lícules </td>'
                                    . '<td><input type="checkbox" name="VideoGames"  ' . ($infogen['Consola videojocs'] ? "checked" : "") . '>Consola videojocs</td>'
                                    . '<td>Tipus Videojocs: <input type="text" name="TypeOfVideoGames" class = "form-control1" size="15" value="' . $infogen['Tipus videojocs'] . '"></td>'
                                    . '<td><input type="checkbox" name="Books"  ' . ($infogen['Llibres'] ? "checked" : "") . '>Llibres</td>'
                                    . '<td>Tipus: <input type="text" name="TypeOfBooks" class = "form-control1" size="20" value="' . $infogen['Tipus llibres'] . '"></td></tr>';
                                    echo '<th colspan ="6" class="success">Seguretat</th>';
                                    echo '<tr><td colspan = "2"><input type="checkbox" name="Strongbox"  ' . ($infogen['Caixa forta'] ? "checked" : "") . '>Caixa forta </td>'
                                    . '<td colspan="4"><input type="checkbox" name="AlarmSystem"  ' . ($infogen['Alarma'] ? "checked" : "") . '>Alarma </td>';


                                    echo '<tr><td colspan = "5"><input type="submit" class="btn btn-primary" name = "equipament" value = "Continuar >>" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar i continuar?"></td></tr>';

                                    echo '</table>';
                                    echo '</form>';
                                    ?>

                                </div>




                                <div id="dropdown1" class="tab-pane fade">

                                    <?php
// ----------------------------------------------------- Cuina -------------------------------------------------------



                                    if (isset($_POST['modcuina1']) || isset($_POST['modcuina2'])) {
                                        //var_dump($_POST);

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
                                                "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                        );
                                        $stmt->execute();
                                        $cas = $stmt->fetchObject();
                                        $darrer = $cas->CAS_id;

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO CUINA (CUI_codiCasa, SeparateKitchen,LivingDiningRoom,KitchenDiningRoom,DimensionsKitchen,HighChairKitchen,BarWithStoolsKitchen,BlenderKitchen,ToasterKitchen,ExtractorKitchen,MicrowaveKitchen,DishwasherKitchen,SqueezerKitchen,FridgeWithFreezerKitchen,FreezerKitchen,ElectricFurnaceKitchen,FridgeKitchen,KettleKitchen,GasKitchen,VitroceramicKitchen,GasFurnaceKitchen,InductionKitchen,ElectricCoffeeMakerKitchen,ItalianCoffeeMakerKitchen,TablesKitchen,NumberOfTablesKitchen,ChairsKitchen,NumberOfChairsKitchen,LarderKitchen)
                                                    VALUES
                                                    (:codi, :SeparateKitchen,:LivingDiningRoom,:KitchenDiningRoom,:DimensionsKitchen,
                                                        :HighChairKitchen, :BarWithStoolsKitchen,
                                                        :BlenderKitchen,
                                                        :ToasterKitchen,
                                                        :ExtractorKitchen,
                                                        :MicrowaveKitchen,
                                                        :DishwasherKitchen,
                                                        :SqueezerKitchen,
                                                        :FridgeWithFreezerKitchen,
                                                        :FreezerKitchen,
                                                        :ElectricFurnaceKitchen,
                                                        :FridgeKitchen,
                                                        :KettleKitchen,
                                                        :GasKitchen,
                                                        :VitroceramicKitchen,
                                                        :GasFurnaceKitchen,
                                                        :InductionKitchen,
                                                        :ElectricCoffeeMakerKitchen,
                                                        :ItalianCoffeeMakerKitchen,
                                                        :TablesKitchen,
                                                        :NumberOfTablesKitchen,
                                                        :ChairsKitchen,
                                                        :NumberOfChairsKitchen,
                                                        :LarderKitchen)");

                                        $stmt->execute(array(
                                            ':codi' => $darrer,
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
                                        ));


//
                                    }


                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Cuina')->toArray(null, true, true, true);


                                    $infogen = [];


                                    foreach ($objHoja as $iIndice => $objCelda) {

                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
                                    $numero = $infogen['NUMERO CUINES'];
                                    echo '<form action = "#dropdown2" method = "post">';
                                    echo '<input type="submit" class="btn btn-success" name="canviar" value = "Continuar >>" data-toggle="tooltip" data-placement="right" title="Confirmar cuines abans de guardar">';
                                    echo '</form>';
//var_dump($infogen);
                                    $a = 3;
                                    $b = 30;
                                    for ($n = 1; $n < $numero + 1; $n++) {

                                        $objHoja = $objPHPExcel->setActiveSheetIndexByName('Cuina')->rangeToArray("A$a:B$b", null, true, true, true);

                                        $infogen = [];


                                        foreach ($objHoja as $iIndice => $objCelda) {

                                            $infogen[$objCelda['A']] = $objCelda['B'];
                                        }
                                        //var_dump($_POST);
                                        echo '<form action = "#dropdown1" method = "post">';
                                        echo '<table class = "table table-responsive">';
                                        echo '<th colspan = "3" class = "success">Cuina</th>';
//                                    echo '<input type = "hidden" name = "idcui" value = "' . $cui->CUI_id . '">';
                                        echo '<tr><td><input type = "checkbox" name = "SeparateKitchen" ' . ($infogen['Cuina independent'] ? "checked" : "" ) . ' > Cuina independent </td>'
                                        . '<td><input type = "checkbox" name = "LivingDiningRoom" ' . ($infogen['Cuina sala'] ? "checked" : "" ) . '>Cuina sala-menjador </td>'
                                        . '<td><input type = "checkbox" name = "KitchenDiningRoom" ' . ($infogen['Cuina menjador'] ? "checked" : "" ) . '>Cuina menjador </td></tr>';
                                        echo '<tr><td>Dimensions cuina: <input type = "text" min = "0" max = "1000" name = "DimensionsKitchen" class = "form-control2l" '
                                        . 'value = "' . $infogen['Dimensions cuina'] . '" data-toggle="tooltip" data-placement="right" title="En metres"> m²</td>'
                                        . '<td><input type = "checkbox" name = "HighChairKitchen" ' . ($infogen['Trona'] ? "checked" : "" ) . '>Trona </td>'
                                        . '<td><input type = "checkbox" name = "BarWithStoolsKitchen" ' . ($infogen['Barra taburets'] ? "checked" : "" ) . '>Barra amb taburets </td></tr>';
                                        echo '<tr><td><input type = "checkbox" name = "BlenderKitchen" ' . ($infogen['Batedora'] ? "checked" : "" ) . '>Batedora </td>'
                                        . '<td><input type = "checkbox" name = "ToasterKitchen" ' . ($infogen['Torradora'] ? "checked" : "" ) . '>Torradora </td>'
                                        . '<td><input type = "checkbox" name = "ExtractorKitchen" ' . ($infogen['Extractor fum'] ? "checked" : "" ) . '>Extractor fum </td></tr>';

                                        echo '<tr><td><input type = "checkbox" name = "MicrowaveKitchen" ' . ($infogen['Microones'] ? "checked" : "" ) . '>Microones </td>'
                                        . '<td><input type = "checkbox" name = "DishwasherKitchen" ' . ($infogen['Rentavaixelles'] ? "checked" : "" ) . '>Rentavaixelles </td>'
                                        . '<td><input type = "checkbox" name = "SqueezerKitchen" ' . ($infogen['Exprimidor'] ? "checked" : "" ) . '>Exprimidor </td></tr>';

                                        echo '<tr><td><input type = "checkbox" name = "FridgeWithFreezerKitchen" ' . ($infogen['Gelera congelador'] ? "checked" : "" ) . '>Gelera amb congelador </td>'
                                        . '<td><input type = "checkbox" name = "FreezerKitchen" ' . ($infogen['Congelador'] ? "checked" : "" ) . '>Congelador </td>'
                                        . '<td><input type = "checkbox" name = "FridgeKitchen" ' . ($infogen['Gelera'] ? "checked" : "" ) . '>Gelera </td></tr>';

                                        echo '<tr><td><input type = "checkbox" name = "GasFurnaceKitchen" ' . ($infogen['Forn gas'] ? "checked" : "") . '>Forn de gas </td>'
                                        . '<td><input type = "checkbox" name = "ElectricFurnaceKitchen" ' . ($infogen['Forn elèctric'] ? "checked" : "") . '>Forn electric </td>'
                                        . '<td><input type = "checkbox" name = "KettleKitchen" ' . ($infogen['Bollidora aigua'] ? "checked" : "") . '>Bollidora aigua </td></tr>';
                                        echo '<tr><td><input type = "checkbox" name = "GasKitchen" ' . ($infogen['Cuina gas'] ? "checked" : "") . '>Cuina de gas </td>'
                                        . '<td><input type = "checkbox" name = "VitroceramicKitchen" ' . ($infogen['Cuina vitro'] ? "checked" : "") . '>Cuina de vitro ceràmica </td>'
                                        . '<td><input type = "checkbox" name = "InductionKitchen" ' . ($infogen['Cuina inducció'] ? "checked" : "") . '>Cuina d`inducció </td></tr>';
                                        echo '<tr><td><input type="checkbox" name="ElectricCoffeeMakerKitchen"  ' . ($infogen['Cafetera elèctrica'] ? "checked" : "") . '>Cafetera electrica </td>'
                                        . '<td colspan="2"><input type="checkbox" name="ItalianCoffeeMakerKitchen"  ' . ($infogen['Cafetera italiana'] ? "checked" : "") . '>Cafetera italiana </td></tr>';
                                        echo '<tr><td><input type="checkbox" name="TablesKitchen"  ' . ($infogen['Taules'] ? "checked" : "") . '>Taules nº '
                                        . '<input type="number" name="NumberOfTablesKitchen" class = "form-control2l" min="0" max="20" value="' . $infogen['Numero taules'] . '"></td>'
                                        . '<td><input type="checkbox" name="ChairsKitchen"  ' . ($infogen['Cadires'] ? "checked" : "") . '>Cadires nº '
                                        . '<input type="number" name="NumberOfChairsKitchen" class = "form-control2l" min="0" max="50" value="' . $infogen['Numero cadires'] . '"></td>';

                                        echo '<td><input type="checkbox" name="LarderKitchen"  ' . ($infogen['Rebost'] ? " checked" : "") . '>Rebost </td></tr>';

                                        echo'<tr><td colspan = "6"><input type="submit" name = "modcuina' . $n . '" '
                                        . 'class="btn btn-primary" id= "guardacuina' . $n . '" value = "Confirmar Cuina" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar?"></td></tr>';

                                        echo '</table>';
                                        echo '</form>';
                                        $a = $a + 29;
                                        $b = $b + 29;
                                    }
                                    ?>

                                </div>

                                <div id="dropdown2" class="tab-pane fade">
                                    <?php
// --------------------------------------------------Sala ------------------------------------





                                    if (isset($_POST['modsala1']) || isset($_POST['modsala2'])) {

                                        var_dump($_POST);

                                        canvi('SofasHall');
                                        canvi('SofaBedHall');
                                        canvi('ArmChairHall');
                                        canvi('HallDiningroom');
                                        $stmt = $pdoCore->db->prepare(
                                                "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                        );
                                        $stmt->execute();
                                        $cas = $stmt->fetchObject();
                                        $darrer = $cas->CAS_id;
                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO SALA (
                                                    SAL_codiCasa,
                                                    DimensionsHall,
                                                    SofasHall,
                                                    ArmChairHall,
                                                    ArmChairSeatsHall,
                                                    NumberOfArmChairHall,
                                                    SofaSeatsHall,
                                                    NumberOfSofasHall,
                                                    SofaBedHall,
                                                    SofaBedSeatsHall,
                                                    TypeOfSofaBedHall,
                                                    NumberOfSofasBedHall,
                                                    AirConditioningHall,
                                                    HallDiningroom)
                                                VALUES(
                                                :SAL_codiCasa,
                                                :DimensionsHall,
                                                :SofasHall,
                                                :ArmChairHall,
                                                :ArmChairSeatsHall,
                                                :NumberOfArmChairHall,
                                                :SofaSeatsHall,
                                                :NumberOfSofasHall,
                                                :SofaBedHall,
                                                :SofaBedSeatsHall,
                                                :TypeOfSofaBedHall,
                                                :NumberOfSofasBedHall,
                                                :AirConditioningHall,
                                                :HallDiningroom)");


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
                                            ':SAL_codiCasa' => $darrer
                                        ));
                                    }


                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Sala')->toArray(null, true, true, true);


                                    $infogen = [];


                                    foreach ($objHoja as $iIndice => $objCelda) {

                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
                                    $numero = $infogen['NUMERO SALES'];

                                    $a = 3;
                                    $b = 15;
                                    echo '<form action = "#dropdown3" method = "post">';
                                    echo '<input type="submit" class="btn btn-success" name="canviar" value = "Continuar >>" data-toggle="tooltip" data-placement="right" title="Confirmar cuines abans de guardar">';

                                    for ($n = 1; $n < $numero + 1; $n++) {

                                        $objHoja = $objPHPExcel->setActiveSheetIndexByName('Sala')->rangeToArray("A$a:B$b", null, true, true, true);

                                        $infogen = [];


                                        foreach ($objHoja as $iIndice => $objCelda) {

                                            $infogen[$objCelda['A']] = $objCelda['B'];
                                        }
                                        //var_dump($infogen);
                                        echo '<form action = "#dropdown2" method = "post">';

                                        echo '<table class="table">';
                                        echo '<tr>';
                                        echo '<th colspan ="5" class="success">Sala' . $n . '</th>';
                                        echo '</tr>';
//                                        echo '<input type = "hidden" name="idsala" value = "' . $sal->SAL_id . '">';
                                        echo '<tr>'
                                        . '<td>Dimensions m²: <input type="text" name="DimensionsHall" class = "form-control2l" min="0" max="1000" value="' . $infogen['Dimensions'] . '"></td>'
                                        . '</td><td>' . checkbox($infogen['Sofà'], "SofasHall", "Sofàs") . ' nº:</td><td> '
                                        . '<input type="number" name="NumberOfSofasHall" class = "form-control2l" min="0" max="15" value="' . $infogen['Numero sofàs'] . '"></td>'
                                        . '<td colspan="3">Seients: <input type="number" name="SofaSeatsHall" class = "form-control2l" min="0" max="50" value="' . $infogen['Seients sofàs'] . '"></td></tr>';
                                        echo '<tr>'
                                        . '<td>' . checkbox($infogen['Sala menjador'], "HallDiningroom", "Sala-Menjador") . ' </td>'
                                        . '<td>' . checkbox($infogen['Butaques'], "ArmChairHall", "Butaques") . ' nº:</td><td> '
                                        . '<input type="number" name="NumberOfArmChairHall" class = "form-control2l" min="0" max="15" '
                                        . 'value="' . $infogen['Numero butaques'] . '"></td>'
                                        . '<td colspan="3">Seients: <input type="number" name="ArmChairSeatsHall" class = "form-control2l" min="0" max="50" '
                                        . 'value="' . $infogen['Seients butaques'] . '"></td></tr>';
                                        echo '<tr><td>' . checkbox($infogen['Sofas llit'], "SofaBedHall", "Sofàs-llits ") . ''
                                        . 'nº <input type="number" name="NumberOfSofasBedHall" class = "form-control2l" min="0" max="50" '
                                        . 'value="' . $infogen['Numero sofasllit'] . '">'
                                        . '</td><td>Tipus</td><td> <input type="text" name="TypeOfSofaBedHall" class = "form-control2l" size="5" '
                                        . 'value="' . $infogen['Tipus'] . '">'
                                        . '</td><td>Seients: <input type="number" name="SofaBedSeatsHall" class = "form-control2l" min="0" max="50" '
                                        . 'value="' . $infogen['Seients sofasllit'] . '"></td>'
                                        . '<td>Climatització: <input type="text" name="AirConditioningHall" class = "form-control3l" size="5" '
                                        . 'value="' . $infogen['Climatització'] . '"></tr>';



                                        echo'<tr><td colspan="6"><input type="submit"  name = "modsala' . $n . '" class="btn btn-primary" value = "Confimar sala" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar i continuar?">'
                                        . '</td></tr>';
                                        echo '</table>';
                                        echo '</form>';
                                        $a = $a + 14;
                                        $b = $b + 14;
                                    }
                                    ?>
                                </div>
                                <div id="dropdown3" class="tab-pane fade">

                                    <?php
// --------------------------------- Menjador --------------------------------------------
// Modificar Menjador

                                    if (isset($_POST['modmenjador1']) || isset($_POST['modmenjador2'])) {

                                        canvi('SideTableDiningRoom');
                                        $stmt = $pdoCore->db->prepare(
                                                "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                        );
                                        $stmt->execute();
                                        $cas = $stmt->fetchObject();
                                        $darrer = $cas->CAS_id;

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO MENJADOR (MEN_codicasa,DimensionsLivingroom ,SideTableDiningRoom ,SideDiningTableSeatsDining, SeatsDining)"
                                                . "VALUES(:MEN_codicasa,
                                                    :DimensionsLivingroom,
                                                    :SideTableDiningRoom,
                                                    :SideDiningTableSeatsDining,
                                                    :SeatsDining)"
                                        );




                                        $stmt->execute(array(
                                            ':DimensionsLivingroom' => $_POST['DimensionsLivingroom'],
                                            ':SideTableDiningRoom' => $_POST['SideTableDiningRoom'],
                                            ':SideDiningTableSeatsDining' => $_POST['SideDiningTableSeatsDining'],
                                            ':SeatsDining' => $_POST['SeatsDining'],
                                            ':MEN_codicasa' => $darrer
                                        ));
                                    }

                                    echo '<form action = "#dropdown4" method = "post">';
                                    echo '<input type="submit" class="btn btn-success" name="canviar" value = "Continuar >>" data-toggle="tooltip" data-placement="right" title="Confirmar menjadors abans de guardar">';
                                    echo '</form>';
                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Menjador')->toArray(null, true, true, true);


                                    $infogen = [];


                                    foreach ($objHoja as $iIndice => $objCelda) {

                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
                                    $numero = $infogen['NUMERO MENJADORS'];

                                    $a = 2;
                                    $b = 7;
                                    for ($n = 1; $n < $numero + 1; $n++) {

                                        $objHoja = $objPHPExcel->setActiveSheetIndexByName('Menjador')->rangeToArray("A$a:B$b", null, true, true, true);

                                        $infogen = [];


                                        foreach ($objHoja as $iIndice => $objCelda) {

                                            $infogen[$objCelda['A']] = $objCelda['B'];
                                        }
                                        //var_dump($infogen);
//
// ' . checkbox($sal->SofasHall, "  SofasHall  ", "  Sofàs  ") . '
                                        echo '<form action="#dropdown3" method="post">';
                                        echo '<table class="table">';
//                                            echo '<input type = "hidden" name = "menid" value = "' . $men->MEN_id . '" >';
                                        echo '<th colspan ="2" class="success">Menjador' . $n . '</th>';
                                        echo '<tr><td> Dimensions m²: <input type="text" name="DimensionsLivingroom" class = "form-control2l" min="0" max="1000" value="' . $infogen['Dimensions'] . '" data-toggle="tooltip" data-placement="right" title="En metres"></td>'
//
                                        . '<td><input type="checkbox" name="SideTableDiningRoom"  ' . ($infogen['Taules auxiliars'] ? "checked" : "") . '>Taules auxiliars '
                                        . 'nº: <input type="number" name="SideDiningTableSeatsDining" class = "form-control2l" min="0" max="15" value="' . $infogen['Numero taules'] . '"></td></tr>';
                                        echo '<tr><td colspan = "2">nº llocs taula menjador: <input type="number" name="SeatsDining" class = "form-control2l" min="0" max="50" value="' . $infogen['Llocs menjador'] . '"></td></tr>';

                                        echo'<tr><td colspan = "2"><input type="submit" name = "modmenjador' . $n . '" class="btn btn-primary" value = "Confimar menjador" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar?">'
                                        . '</td></tr>';
                                        echo '</table>';
                                        echo '</form>';
                                        $a = $a + 5;
                                        $b = $b + 5;
                                    }
                                    ?>



                                </div>
                                <div id="dropdown4" class="tab-pane fade">
                                    <?php
// ----------------------------------- Dormitori ----------------------------------------
// Modificar Dormitori
                                    for ($n = 1; $n < 13; $n++) {
                                        if (isset($_POST['moddormitori' . $n . ''])) {

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
                                                    "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                            );
                                            $stmt->execute();
                                            $cas = $stmt->fetchObject();
                                            $darrer = $cas->CAS_id;

                                            $stmt = $pdoCore->db->prepare(
                                                    "INSERT INTO DORMITORI (BedroomDimensions,FloorBedroom,AttachedBedroom,DoubleBedBedroom,WidthDoubleBedBedroom,HeightDoubleBedBedroom,NumberOfDoubleBedsBedroom,EnsuiteBedroom,SingleBedNumberBedroom,WidthSingleBedBedroom,HeightSingleBedBedroom,NumberOfSingleBedsBedroom,AuxiliaryBedBedroom,TrundleBedBedroom,WidthTrundleBedBedroom,HeightTrundleBedBedroom,CapacityTrundleBed,CotBedroom,BunkBedBedroom,WidthBunkBedBedroom,HeightBunkBedBedroom,BalconyBedroom,WindowlessBedroom,ACBedRoom,FanBedRoom,DieselCentralHeatingBedRoom,ChimneyBedRoom,RadiatorsBedRoom,GasBedRoom,TypeOfCotBedRoom,WardrobeBedroom,ExitToTerraceBedroom,HallwayBedroom,ViewsBedroom,NotesBedroom,DOR_codiCasa ) "
                                                    . "VALUES (
                                                        :BedroomDimensions,
                                                    :FloorBedroom,
                                                    :AttachedBedroom,
                                                    :DoubleBedBedroom,
                                                    :WidthDoubleBedBedroom,
                                                    :HeightDoubleBedBedroom,
                                                    :NumberOfDoubleBedsBedroom,
                                                    :EnsuiteBedroom,
                                                    :SingleBedNumberBedroom,
                                                    :WidthSingleBedBedroom,
                                                    :HeightSingleBedBedroom,
                                                    :NumberOfSingleBedsBedroom,
                                                    :AuxiliaryBedBedroom,
                                                    :TrundleBedBedroom,
                                                    :WidthTrundleBedBedroom,
                                                    :HeightTrundleBedBedroom,
                                                    :CapacityTrundleBed,
                                                    :CotBedroom,
                                                    :BunkBedBedroom,
                                                    :WidthBunkBedBedroom,
                                                    :HeightBunkBedBedroom,
                                                    :BalconyBedroom,
                                                    :WindowlessBedroom,
                                                    :ACBedRoom,
                                                    :FanBedRoom,
                                                    :DieselCentralHeatingBedRoom,
                                                    :ChimneyBedRoom,
                                                    :RadiatorsBedRoom,
                                                    :GasBedRoom,
                                                    :TypeOfCotBedRoom,
                                                    :WardrobeBedroom,
                                                    :ExitToTerraceBedroom,
                                                    :HallwayBedroom,
                                                    :ViewsBedroom,
                                                    :NotesBedroom,
                                                    :DOR_codiCasa
                                                    )"
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
                                                ':DOR_codiCasa' => $darrer
                                            ));
                                        }
                                    }


                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Dormitori')->toArray(null, true, true, true);

                                    echo '<form action="#dropdown5" method="post">';

                                    echo '<input type="submit" value="Continuar >>" name = "afegir" class="btn btn-success" data-toggle="tooltip" data-placement="right" title="Confirmar tots es dormitoris abans de guardar i continuar">';
                                    echo '</form>';



                                    $infogen = [];


                                    foreach ($objHoja as $iIndice => $objCelda) {

                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
                                    $numero = $infogen['NUMERO DORMITORIS'];

                                    $a = 3;
                                    $b = 37;
                                    for ($n = 1; $n < $numero + 1; $n++) {

                                        $objHoja = $objPHPExcel->setActiveSheetIndexByName('Dormitori')->rangeToArray("A$a:B$b", null, true, true, true);

                                        $infogen = [];


                                        foreach ($objHoja as $iIndice => $objCelda) {

                                            $infogen[$objCelda['A']] = $objCelda['B'];
                                        }



                                        echo '<form action="#dropdown4" method="post">';
                                        echo '<table class="table">';
                                        echo '<th colspan ="5" class="success">Dormitori' . $n . '</th>';
//                                        echo '<input type = "hidden" name = "iddormitori" value = "' . $dor->DOR_id . '">';
                                        echo '<tr><td colspan="2">Dimensions  m²: <input type="text" name="BedroomDimensions" '
                                        . 'class = "form-control2l" min="0" max="1000" value="' . $infogen['Dimensions'] . '" data-toggle="tooltip" data-placement="right" title="En metres"></td>'
                                        . '<td>Planta : <input type ="text" name="FloorBedroom" value="' . $infogen['Planta'] . '" ></td>'
                                        . '<td>' . checkbox($infogen['Anexada'], "AttachedBedroom", "Anexada") . '</td></tr>';
                                        echo '<tr><td>' . checkbox($infogen['Llit matrimoni'], "DoubleBedBedroom", "  Llit matrimoni  ") . ''
                                        . '</td><td>Llarg: <input type="text" name="HeightDoubleBedBedroom" class = "form-control2l" value ="' . $infogen['Llarg m'] . '">'
                                        . '</td><td>Ampla: <input type ="text" name="WidthDoubleBedBedroom" class = "form-control2l" value ="' . $infogen['Ampla m'] . '"></td>'
                                        . '<td> nº :<input type="number" name="NumberOfDoubleBedsBedroom" '
                                        . 'class = "form-control2l" min="0" max="5" value="' . $infogen['Numero m'] . '"></td>'
                                        . '<td>' . checkbox($infogen['Bany suite'], "EnsuiteBedroom", "  Bany en suite  ") . '</td></tr>';
                                        echo '<tr><td>' . checkbox($infogen['Llit individual'], "SingleBedNumberBedroom", "  Llit individual  ") . ''
                                        . '</td><td>Llarg: <input type = "text" name="HeightSingleBedBedroom" class = "form-control2l" value ="' . $infogen['Llarg i'] . '">'
                                        . '</td><td>Ampla: <input type="text" name="WidthSingleBedBedroom" class = "form-control2l" value="' . $infogen['Ampla i'] . '"></td>'
                                        . '<td> nº :<input type="number" name="NumberOfSingleBedsBedroom" class = "form-control2l" min="0" max="5" '
                                        . 'value="' . $infogen['Numero i'] . '"></td>'
                                        . '<td>' . checkbox($infogen['Lloc llitaux'], "AuxiliaryBedBedroom", "  Lloc per llit auxiliar  ") . '</td></tr>';
                                        echo '<tr><td>' . checkbox($infogen['Nido'], "TrundleBedBedroom", "  Nido  ") . ''
                                        . '</td><td>Llarg: <input type="text" name="HeightTrundleBedBedroom" class = "form-control2l" value="' . $infogen['Llarg n'] . '">'
                                        . '</td><td>Ampla: <input type ="text" name="WidthTrundleBedBedroom" class = "form-control2l" value ="' . $infogen['Ampla n'] . '"></td>'
                                        . '<td> Capacitat :<input type="number" name="CapacityTrundleBed" class = "form-control2l" min="0" max="5" '
                                        . 'value="' . $infogen['Capacitat'] . '"></td>'
                                        . '<td>' . checkbox($infogen['Lloc cuna'], "CotBedroom", "  Lloc cuna  ") . '</td></tr>';
                                        echo '<tr><td>' . checkbox($infogen['Llitera'], "BunkBedBedroom", "  Llitera  ") . ''
                                        . '</td><td>Llarg: <input type="text" name="HeightBunkBedBedroom" class = "form-control2l" value="' . $infogen['Llarg l'] . '">'
                                        . '</td><td>Ampla: <input type="text" name="WidthBunkBedBedroom" class = "form-control2l" value="' . $infogen['Ampla l'] . '"></td>'
                                        . '<td>' . checkbox($infogen['Balco'], "BalconyBedroom", "Balco") . '</td>'
                                        . '<td>' . checkbox($infogen['Sense finestres'], "WindowlessBedroom", "  Sense finestres  ") . '</td></tr>';
                                        echo '<tr><td colspan = "3"><ins>Tipus climatització</ins></td><td colspan="3">Tipus cuna: '
                                        . '<input type="text" name="TypeOfCotBedRoom" class = "form-control3l" value="' . $infogen['Tipus cuna'] . '"></td></tr>';
                                        echo '<tr><td colspan="5"><table class="table-condensed">';
                                        echo '<tr><td>' . checkbox($infogen['Ac'], "ACBedRoom", "  AC  ") . '</td>'
                                        . '<td>' . checkbox($infogen['Ventilador'], "  FanBedRoom  ", "  Ventilador  ") . '</td><td></td></tr>';
                                        echo '<tr><td>' . checkbox($infogen['Calefacció gasoil'], "DieselCentralHeatingBedRoom", "  Calefacció gasoil  ") . '</td>'
                                        . '<td>' . checkbox($infogen['Ximeneia'], "  ChimneyBedRoom  ", "  Ximeneia  ") . '</td><td></td></tr>';
                                        echo '<tr><td>' . checkbox($infogen['Radiadors'], "RadiatorsBedRoom", "  Radiadors  ") . '</td>'
                                        . '<td>' . checkbox($infogen['Calefacció gas'], "  GasBedRoom  ", "  Calefacció de Gas  ") . '</td><td></td></tr>';
                                        echo '</table></td></tr>';
// ExitToTerraceBedroom
                                        echo '<tr><td>' . checkbox($infogen['Armaris'], "WardrobeBedroom", "  Armaris  ") . '</td>'
                                        . '<td>Sortida: <input type="text" name="ExitToTerraceBedroom" class = "form-control3l" value="' . $infogen['Sortida'] . '"></td>'
                                        . '<td colspan="3">' . checkbox($infogen['Vestidor'], "HallwayBedroom", "  Vestidor  ") . '</td></tr>';
                                        echo '<tr><td colspan="5">Vistes:<textarea name="ViewsBedroom" '
                                        . 'class = "form-control1" rows="2" cols="90" style="width:100%">' . $infogen['Vistes'] . '</textarea></td></tr>';
                                        echo '<tr><td colspan="5">Notes:<textarea name="NotesBedroom" class = "form-control1" '
                                        . 'rows="2" cols="90" style="width:100%">' . $infogen['Notes'] . '</textarea></td></tr>';

                                        echo'<tr><td colspan = "5"><input type="submit"  name = "moddormitori' . $n . '" class="btn btn-primary" value = "Confimar dormitori" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar?">'
                                        . '</td></tr>';

                                        echo '</table>';
                                        echo '</form>';
                                        $a = $a + 36;
                                        $b = $b + 36;
                                    }
                                    ?>
                                </div>
                                <div id="dropdown5" class="tab-pane fade">
                                    <?php
// ----------------------------------- Bany ----------------------------------------
//
//
// Modificar Bany

                                    for ($n = 1; $n < 13; $n++) {
                                        if (isset($_POST['modbany' . $n . ''])) {


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
                                                    "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                            );
                                            $stmt->execute();
                                            $cas = $stmt->fetchObject();
                                            $darrer = $cas->CAS_id;

                                            $stmt = $pdoCore->db->prepare(
                                                    "INSERT INTO BANY (BAN_codiCasa,BathroomDimensions,FloorBathroom,AttachedBathroom,CompleteBathroom,SinkBathroom,ToiletBathRoom,BidetBathroom,ShowerBathroom,TypeOfShoweBathRoom,BathBathroom,TypeOfBathBathRoom,EnsuiteBathroom,JacuzziBathroom,SaunaBathroom,HairdryerBathRoom,OutsideBathroom,EnsuiteBathroomLocation,HeatingBathRoom) "
                                                    . "VALUES (:BAN_codiCasa,
                                                        :BathroomDimensions,
                                                        :FloorBathroom,
                                                        :AttachedBathroom,
                                                        :CompleteBathroom,
                                                        :SinkBathroom,
                                                        :ToiletBathRoom,
                                                        :BidetBathroom,
                                                        :ShowerBathroom,
                                                        :TypeOfShoweBathRoom,
                                                        :BathBathroom,
                                                        :TypeOfBathBathRoom,
                                                        :EnsuiteBathroom,
                                                        :JacuzziBathroom,
                                                        :SaunaBathroom,
                                                        :HairdryerBathRoom,
                                                        :OutsideBathroom,
                                                        :EnsuiteBathroomLocation,
                                                        :HeatingBathRoom)"
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
                                                ':BAN_codiCasa' => $darrer
                                            ));
                                        }
                                    }

                                    echo '<form action="#dropdown6" method="post">';
                                    echo '<hr/>';
                                    echo '<input type="submit" value="Afegir Bany" name = "novban" class="btn btn-success" data-toggle="tooltip" data-placement="right" title="Confirmar tots es banys abans de guardar i continuar">';
                                    echo '</form>';


                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Bany')->toArray(null, true, true, true);


                                    $infogen = [];


                                    foreach ($objHoja as $iIndice => $objCelda) {

                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
                                    $numero = $infogen['NUMERO BANYS'];

                                    $a = 3;
                                    $b = 20;
                                    for ($n = 1; $n < $numero + 1; $n++) {

                                        $objHoja = $objPHPExcel->setActiveSheetIndexByName('Bany')->rangeToArray("A$a:B$b", null, true, true, true);

                                        $infogen = [];


                                        foreach ($objHoja as $iIndice => $objCelda) {

                                            $infogen[$objCelda['A']] = $objCelda['B'];
                                        }




                                        echo '<form action="#dropdown5" method="post">';

                                        echo '<table class="table">';
                                        echo '<th colspan ="5" class="success">Bany' . $n . '</th>';
//                                        echo '<input type = "hidden" name = "idban" value = "' . $ban->BAN_id . '">';
                                        echo '<tr><td>Dimensions m² <input type="text" name="BathroomDimensions" '
                                        . 'class = "form-control2l" min="0" max="1000" value="' . $infogen['Dimensions'] . '" data-toggle="tooltip" data-placement="right" title="En metres"></td>'
                                        . '<td>Planta <input type="text" name="FloorBathroom" class = "form-control2l" value="' . $infogen['Planta'] . '"></td>'
                                        . '<td>' . checkbox($infogen['Anexada'], "AttachedBathroom", "  Anexada  ") . '</td></tr>';
                                        echo '<tr><td>' . checkbox($infogen['Complet'], "CompleteBathroom", "  Complet  ") . '</td>'
                                        . '<td>' . checkbox($infogen['Lavabo'], "SinkBathroom", "  Lavabo  ") . '</td>'
                                        . '<td>' . checkbox($infogen['Vater'], "ToiletBathRoom", "  Vater  ") . '</td></tr>';
                                        echo '<tr><td>' . checkbox($infogen['Bidet'], "BidetBathroom", "  Bidet  ") . '</td>'
                                        . '<td>' . checkbox($infogen['Dutxa'], "ShowerBathroom", "  Dutxa:   ") . ''
                                        . ' ';

                                        echo '<input type="text" name="TypeOfShoweBathRoom" class = "form-control1" value="' . $infogen['Dutxa tipus'] . '"></td>'
                                        . '<td>' . checkbox($infogen['Banyera'], "BathBathroom", "  Banyera  ") . ''
                                        . 'Tipus: <input type="text" name="TypeOfBathBathRoom" class = "form-control1" value="' . $infogen['Banyera tipus'] . '"></td></tr>';
                                        echo '<tr><td>' . checkbox($infogen['Bany suite'], "EnsuiteBathroom", "  Bany en suite  ") . '</td>'
                                        . '<td>Localització: <input type="text" name = "EnsuiteBathroomLocation" value = "' . $infogen['Localització'] . '" class = "form-control4l"></td>'
                                        . '<td>' . checkbox($infogen['Jacuzzi'], "JacuzziBathroom", "  Jacuzzi  ") . '</td></tr>'
                                        . '<tr><td>' . checkbox($infogen['Sauna'], "SaunaBathroom", "  Sauna  ") . '</td>';
                                        echo '<td>' . checkbox($infogen['Secador cabells'], "HairdryerBathRoom", "  Secador de cabells  ") . '</td>'
                                        . '<td>' . checkbox($infogen['Exterior'], "OutsideBathroom", "  Exterior  ") . '</td></tr>'
                                        . '<tr><td>Calefacció <input type="text" name="HeatingBathRoom" class = "form-control1" value="' . $infogen['Calefacció'] . '"></td></tr>';

                                        echo'<tr><td colspan = "3"><input type="submit" name = "modbany' . $n . '" class="btn btn-primary" value = "Confirmar bany" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar?">'
                                        . '</td></tr>';
                                        echo '</table>';
                                        echo '</form>';
                                        $a = $a + 19;
                                        $b = $b + 19;
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
                                                "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                        );
                                        $stmt->execute();
                                        $cas = $stmt->fetchObject();
                                        $darrer = $cas->CAS_id;

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO BUGADERIA (BUG_codicasa,WashingMachine,Dryer,Iron,Vacuum,LitersElectricBoiler,Laundry)
                                            VALUES(:BUG_codicasa,
                                                :WashingMachine,
                                                :Dryer,
                                                :Iron,
                                                :Vacuum,
                                                :LitersElectricBoiler,
                                                :Laundry)");


                                        $stmt->execute(array(':WashingMachine' => $_POST['WashingMachine'], ':Dryer' => $_POST['Dryer'], ':Iron' => $_POST['Iron'],
                                            ':Vacuum' => $_POST['Vacuum'], ':LitersElectricBoiler' => $_POST['LitersElectricBoiler'],
                                            ':Laundry' => $_POST['Laundry'], ':BUG_codicasa' => $darrer));
                                    }



                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Bugaderia')->toArray(null, true, true, true);

//recorremos las filas obtenidas

                                    $infogen = [];
                                    foreach ($objHoja as $iIndice => $objCelda) {
                                        //imprimimos el contenido de la celda utilizando la letra de cada columna
                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
//var_dump($infogen);



                                    echo '<form action="#dropdown7" method="post">';
                                    echo '<table class="table">';
                                    echo '<th colspan ="4" class="success">Bugaderia</th>';
                                    echo '<tr><td>';
                                    echo '<td>' . checkbox($infogen['Rentadora'], "WashingMachine", "Rentadora") . '</td>';
                                    echo '<td>' . checkbox($infogen['Secadora'], "Dryer", "Secadora") . '</td>';
                                    echo '<td>' . checkbox($infogen['Planxa'], "Iron", "Planxa i taula de planxar") . '</td></tr>';
                                    echo '<tr><td>' . checkbox($infogen['Aspirador'], "Vacuum", "Aspirador") . '</td>';
                                    echo '<td>Nº litres termo: <input type = "number" value = "' . $infogen['Litres termo'] . '" '
                                    . 'name="LitersElectricBoiler" class = "form-control2l" min="0" max="300"</td>';
                                    echo '<td colspan = "2">Situació: <input type ="text" name="Laundry" class = "form-control1" value="' . $infogen['Situació'] . '"></td></tr>';

                                    echo '<tr><td colspan ="4"><input type="submit" name ="buga" class="btn btn-primary" value = "Continuar >>" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar?"></td></tr>';

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
                                                "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                        );
                                        $stmt->execute();
                                        $cas = $stmt->fetchObject();
                                        $darrer = $cas->CAS_id;
                                        $stmt = $pdoCore->db->prepare("INSERT INTO GENERAL (GEN_codicasa,OtherFurniture,OtherTypeOfFurniture,kitchenNotes)"
                                                . "VALUES(:GEN_codicasa,
                                            :OtherFurniture,
                                            :OtherTypeOfFurniture,
                                            :kitchenNotes)");


                                        $stmt->execute(array(':OtherFurniture' => $_POST['OtherFurniture'],
                                            ':OtherTypeOfFurniture' => $_POST['OtherTypeOfFurniture'],
                                            ':kitchenNotes' => $_POST['kitchenNotes'],
                                            ':GEN_codicasa' => $darrer));
                                    }


                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('General')->toArray(null, true, true, true);

//recorremos las filas obtenidas

                                    $infogen = [];
                                    foreach ($objHoja as $iIndice => $objCelda) {
                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
//var_dump($infogen);

                                    echo '<form action="#sectionI" method="post">';
                                    echo '<table class="table">';
                                    echo '<th colspan ="3" class="success">General</th>';
//                                    echo '<input type = "hidden" name = "idgen" value= "' . $gen->GEN_id . '" >';
                                    echo '<tr><td colspan="3">' . checkbox($infogen['Altres mobles'], "OtherFurniture", "Altres mobles") . '</td></tr>';
                                    echo '<tr><td colspan="3">Tipus mobles: <textarea name="OtherTypeOfFurniture" class = "form-control" '
                                    . 'rows="2" cols="90" style="width:100%">' . $infogen['Tipus mobles'] . '</textarea></td></tr>';
                                    echo '<tr><td colspan="3">Notes: <textarea name="kitchenNotes" class = "form-control" rows="2" '
                                    . 'cols="90" style="width:100%">' . $infogen['Notes'] . '</textarea></td></tr>';

//kitchenNotes.
                                    echo '<tr><td><input type="submit" name ="gen" class="btn btn-primary" value = "Continuar >>" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar?"></td></tr>';

                                    echo '</table>';
                                    echo '</form>';
                                    ?>


                                </div>


                                <div id = "sectionI" class = "tab-pane fade">

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
                                                "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                        );
                                        $stmt->execute();
                                        $cas = $stmt->fetchObject();
                                        $darrer = $cas->CAS_id;

                                        $stmt = $pdoCore->db->prepare("INSERT INTO INFORMACIO_ADICIONAL(INF_codicasa,YearOfConstruction,YearOfRemodel,FloorsHouse,AllowPets,PetsOnRequest,NoAllowPets,SolarEnergy,NumberOfNeighbors,FloorHouse,Parking,Noise,NoiseType,ParkingSpaces,Garage,GarageSpaces,ParkingGarage,ParkingGarageSlots,Lift,ParkingNotes)"
                                                . "VALUES(:INF_codicasa,
                                                    :YearOfConstruction,
                                                    :YearOfRemodel,
                                                    :FloorsHouse,
                                                    :AllowPets,
                                                    :PetsOnRequest,
                                                    :NoAllowPets,
                                                    :SolarEnergy,
                                                    :NumberOfNeighbors,
                                                    :FloorHouse,
                                                    :Parking,
                                                    :Noise,
                                                    :NoiseType,
                                                    :ParkingSpaces,
                                                    :Garage,
                                                    :GarageSpaces,
                                                    :ParkingGarage,
                                                    :ParkingGarageSlots,
                                                    :Lift,
                                                    :ParkingNotes)");

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
                                            ':INF_codicasa' => $darrer
                                        ));
                                    }



                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Adicional')->toArray(null, true, true, true);

//recorremos las filas obtenidas

                                    $infogen = [];
                                    foreach ($objHoja as $iIndice => $objCelda) {
                                        //imprimimos el contenido de la celda utilizando la letra de cada columna
                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }
//var_dump($infogen);


                                    echo '<form action="#sectionJ" method="post" id="infoadi">';
                                    echo '<table class="table">';
                                    echo '<th colspan ="3" class="success">INFORMACIO ADICIONAL DE LA VIVENDA</th>';
//                                    echo '<input type = "hidden" name = "idinfo" value = "' . $adi->INF_id . '">';
                                    echo '<tr><td>Any construcció: <input type="number" name="YearOfConstruction" '
                                    . 'class = "form-control2l" min="0" max="2050" value="' . $infogen['Any construcció'] . '"></td>'
                                    . '<td>Any Remodelació: <input type="number" name="YearOfRemodel" '
                                    . 'class = "form-control2l" min="0" max="2050" value="' . $infogen['Any remodelació'] . '"></td>'
                                    . '<td>Nº Plantes Casa: <input type="number" name="FloorsHouse" '
                                    . 'class = "form-control2l" min="0" max="20" value="' . $infogen['Plantes casa'] . '"></td></tr>';
                                    echo '<tr><td>' . checkbox($infogen['Permeten mascotes'], "AllowPets", "  Es permeten mascotes  ") . '</td>'
                                    . '<td>' . checkbox($infogen['Mascotes petició'], "PetsOnRequest", "  Mascotes a peticio  ") . '</td>'
                                    . '<td>' . checkbox($infogen['No mascotes'], "NoAllowPets", "  No es permeten mascotes  ") . '</td></tr>';
                                    echo '<tr><td>' . checkbox($infogen['Energia solar'], "SolarEnergy", "  Energia solar ") . '</td>'
                                    . '<td>Nº veins comunicats: <input type="number" name="NumberOfNeighbors" '
                                    . 'class = "form-control2l" min="0" max="10" value="' . $infogen['Veins comunicats'] . '"></td>'
                                    . '<td>Nºpis: <input type="number" name="FloorHouse" min="0" max="40" '
                                    . 'value="' . $infogen['Numero pis'] . '"></td></tr>';
                                    echo '<tr><td>' . checkbox($infogen['Aparcament exterior'], "Parking", " Aparcament exterior ") . ''
                                    . '<input type="number" name="ParkingSpaces" class = "form-control2l" min="0" '
                                    . 'max="40" value="' . $infogen['Places exterior'] . '"> Places</td>'
                                    . '<td>' . checkbox($infogen['Aparcament cobert'], "Garage", " Aparcament cobert ") . ''
                                    . '<input type="number" name="GarageSpaces" class = "form-control2l" min="0" max="30" '
                                    . 'value="' . $infogen['Places cobert'] . '"> Places</td>'
                                    . '<td>' . checkbox($infogen['Garatge'], "ParkingGarage", "  Garatge  ") . ''
                                    . '<input type="number" name="ParkingGarageSlots" class = "form-control2l" min="0" '
                                    . 'max="30" value="' . $infogen['Places garatge'] . '">Places</td></tr>';
                                    echo '<tr><td>' . checkbox($infogen['Ascensor'], "Lift", "  Ascensor  ") . '</td><td colspan = "2">' . checkbox($infogen['Renou'], "Noise", "  Renou  ") . ' Tipus : '
                                    . '<input type="text" name="NoiseType" class = "form-control1" value="' . $infogen['Tipus renou'] . '"></td></tr>';
                                    echo '<tr><td colspan="3">Notes:<textarea name="ParkingNotes" class = "form-control" '
                                    . 'rows="4" cols="90" style="width:100%">' . $infogen['Notes'] . '</textarea></td></tr>';

                                    echo'<tr><td colspan="3"><input type="submit" name = "info" class="btn btn-primary" value = "Continuar >>" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar?"></td></tr>';
                                    echo '</table>';
                                    echo '</form>';
                                    ?>


                                </div>
                                <div id="sectionJ" class="tab-pane fade">
                                    <?php
// ----------------------------------- Distancies ----------------------------------------

                                    if (isset($_POST['distancies'])) {
                                        $stmt = $pdoCore->db->prepare(
                                                "SELECT CAS_id FROM CASA ORDER BY CAS_id DESC LIMIT 1 "
                                        );
                                        $stmt->execute();
                                        $cas = $stmt->fetchObject();
                                        $darrer = $cas->CAS_id;

                                        $stmt = $pdoCore->db->prepare(
                                                "INSERT INTO DISTANCIES (DIS_codicasa,NumberDistanceToBank,NameDistanceToBank,NumberDistanceToSupermarket,NameDistanceToSupermarket,NumberDistanceToBeach,NameDistanceToBeach,NumberDistanceToAirport,NameDistanceToAirport,NumberDistanceToGolf,NameDistanceToGolf,NumberDistanceToVillage,NameDistanceToVillage,NumberDistanceToTrain,NameDistanceToTrain,NumberDistanceToBus,NameDistanceToBus,NumberDistanceToFerry,NameDistanceToFerry,NumberDistanceToHospital,NameDistanceToHospital,NumberDistanceToPharmacy,NameDistanceToPharmacy,NumberDistanceToRestaurant,NameDistanceToRestaurant,DistanceNotes)"
                                                . "VALUES(:DIS_codicasa,
                                                    :NumberDistanceToBank,
                                                    :NameDistanceToBank,
                                                    :NumberDistanceToSupermarket,
                                                    :NameDistanceToSupermarket,
                                                    :NumberDistanceToBeach,
                                                    :NameDistanceToBeach,
                                                    :NumberDistanceToAirport,
                                                    :NameDistanceToAirport,
                                                    :NumberDistanceToGolf,
                                                    :NameDistanceToGolf,
                                                    :NumberDistanceToVillage,
                                                    :NameDistanceToVillage,
                                                    :NumberDistanceToTrain,
                                                    :NameDistanceToTrain,
                                                    :NumberDistanceToBus,
                                                    :NameDistanceToBus,
                                                    :NumberDistanceToFerry,
                                                    :NameDistanceToFerry,
                                                    :NumberDistanceToHospital,
                                                    :NameDistanceToHospital,
                                                    :NumberDistanceToPharmacy,
                                                    :NameDistanceToPharmacy,
                                                    :NumberDistanceToRestaurant,
                                                    :NameDistanceToRestaurant,
                                                    :DistanceNotes)"
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
                                            ':DIS_codicasa' => $darrer
                                        ));
                                    }


                                    $objHoja = $objPHPExcel->setActiveSheetIndexByName('Distancies')->toArray(null, true, true, true);


                                    $infogen = [];
                                    foreach ($objHoja as $iIndice => $objCelda) {
                                        //imprimimos el contenido de la celda utilizando la letra de cada columna
                                        $infogen[$objCelda['A']] = $objCelda['B'];
                                    }

                                    echo '<form action="#sectionJ" method="post" id="distancia">';
                                    echo '<table class="table table-condensed">';
                                    echo '<th colspan ="4" class="success">DISTANCIES</th>';
//                                    echo '<input type = "hidden" name = "iddis" value = "' . $dis->DIS_id . '">';
                                    echo '<tr class="warning"><td colspan="2">Banc</td><td colspan="2">Supermercat</td></tr>';
                                    echo '<tr><td>Distància(km)</td><td>Nom</td><td>Distància(km)</td><td>Nom</td></tr>';
                                    echo '<tr><td><input type="number" name="NumberDistanceToBank" '
                                    . 'class = "form-control2b" min = "0" step = "0.05" value="' . $infogen['Banc distancia'] . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToBank" class = "form-control4l" size="60" '
                                    . 'value="' . $infogen['Banc nom'] . '"></td>'
                                    . '<td><input type="number" name="NumberDistanceToSupermarket" class = "form-control2b" '
                                    . 'min = "0" step = "0.05" value="' . $infogen['Supermercat distancia'] . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToSupermarket" class = "form-control4l" size="60" '
                                    . 'value="' . $infogen['Supermercat nom'] . '"></td></tr>';

                                    echo '<tr class="warning"><td colspan="2">Platja</td><td colspan="2">Aeroport</td></tr>';
                                    echo '<tr><td>Distància(km)</td><td>Nom</td><td>Distància(km)</td><td>Nom</td></tr>';
                                    echo '<tr><td><input type="number" name="NumberDistanceToBeach" class = "form-control2b" min = "0"'
                                    . ' step = "0.05" value="' . $infogen['Platja distancia'] . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToBeach" class = "form-control4l" size="60" '
                                    . 'value="' . $infogen['Platja nom'] . '"></td>'
                                    . '<td><input type="number" name="NumberDistanceToAirport" class = "form-control2b" min = "0" '
                                    . 'step = "0.05" value="' . $infogen['Aeroport distancia'] . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToAirport" class = "form-control4l" size="60" '
                                    . 'value="' . $infogen['Aeroport nom'] . '"></td></tr>';

                                    echo '<tr class="warning"><td colspan="2">Camp de golf</td><td colspan="2">Poble</td></tr>';
                                    echo '<tr><td>Distància(km)</td><td>Nom</td><td>Distància(km)</td><td>Nom</td></tr>';
                                    echo '<tr><td><input type="number" name="NumberDistanceToGolf" class = "form-control2b" min = "0" '
                                    . 'step = "0.05" value="' . $infogen['Golf distancia'] . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToGolf" class = "form-control4l" size="60" '
                                    . 'value="' . $infogen['Golf nom'] . '"></td>'
                                    . '<td><input type="number" name="NumberDistanceToVillage" class = "form-control2l" size="10" '
                                    . 'value="' . $infogen['Poble distancia'] . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToVillage" class = "form-control4l" size="60" '
                                    . 'value="' . $infogen['Poble nom'] . '"></td></tr>';

                                    echo '<tr class="warning"><td colspan="2">Aturada tren</td><td colspan="2">Aturada bus</td></tr>';
                                    echo '<tr><td>Distància(km)</td><td>Nom</td><td>Distància(km)</td><td>Nom</td></tr>';
                                    echo '<tr><td><input type="number" name="NumberDistanceToTrain" class = "form-control2b" min = "0" '
                                    . 'step = "0.05" value="' . $infogen['Aturada tren'] . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToTrain" class = "form-control4l" size="60" '
                                    . 'value="' . $infogen['Tren nom'] . '"></td>'
                                    . '<td><input type="number" name="NumberDistanceToBus" class = "form-control2b" min = "0" step = "0.05" '
                                    . 'value="' . $infogen['Aturada bus'] . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToBus" class = "form-control4l" '
                                    . 'size="60" value="' . $infogen['Bus nom'] . '"></td></tr>';

                                    echo '<tr class="warning"><td colspan="2">Ferry</td><td colspan="2">Hospital</td></tr>';
                                    echo '<tr><td>Distància(km)</td><td>Nom</td><td>Distància(km)</td><td>Nom</td></tr>';
                                    echo '<tr><td><input type="number" name="NumberDistanceToFerry" '
                                    . 'class = "form-control2b" min = "0" step = "0.05" value="' . $infogen['Ferry distancia'] . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToFerry" class = "form-control4l" size="60" '
                                    . 'value="' . $infogen['Ferry nom'] . '"></td>'
                                    . '<td><input type="number" name="NumberDistanceToHospital" class = "form-control2b" '
                                    . 'min = "0" step = "0.05" value="' . $infogen['Hospital distancia'] . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToHospital" class = "form-control4l" size="60" '
                                    . 'value="' . $infogen['Hospital nom'] . '"></td></tr>';

                                    echo '<tr class="warning"><td colspan="2">Farmàcia</td><td colspan="2">Bar/Restaurant</td></tr>';
                                    echo '<tr><td>Distància(km)</td><td>Nom</td><td>Distància(km)</td><td>Nom</td></tr>';
                                    echo '<tr><td><input type="number" name="NumberDistanceToPharmacy" '
                                    . 'class = "form-control2b" min = "0" step = "0.05" value="' . $infogen['Farmacia distancia'] . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToPharmacy" class = "form-control4l" size="60" '
                                    . 'value="' . $infogen['Farmacia nom'] . '"></td>'
                                    . '<td><input type="number" name="NumberDistanceToRestaurant" class = "form-control2b" '
                                    . 'min = "0" step = "0.05" value="' . $infogen['Barrestaurant distancia'] . '"></td>'
                                    . '<td><input type="text" name="NameDistanceToRestaurant" class = "form-control4l" '
                                    . 'size="60" value="' . $infogen['Barrestaurant nom'] . '"></td></tr>';

                                    echo '<tr><td colspan="3">Notes:<textarea name="DistanceNotes" class = "form-control2l" rows="2" cols="90" style="width:100%">' . $infogen['Notes'] . '</textarea></td></tr>';



                                    echo'<tr><td colspan="4"><input type="submit"  name = "distancies" class="btn btn-primary" value = "FINALITZAR EDICIÓ" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar?"></td></tr>';
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
