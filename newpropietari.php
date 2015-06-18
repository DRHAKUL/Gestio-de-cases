<?php
// Formulari per afegir un nou propietari.
session_start();
require_once 'class/Core.php';
// Si no hi ha sesio envia a login.php.
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

        <script type="text/javascript" src="js/jquery-1.11.3.js"></script>
        <script type="text/javascript" src="js/jquery-ui.js"></script>
        <script src="js/bootstrap.js"></script>

        <title>Nou propietari</title>


        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet">
        <link href="css/jquery-ui.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">



        <script>
<?php
$pdoCore = Core::getInstance();
$stmt = $pdoCore->db->prepare(
        "SELECT * FROM PROPIETARI "
);
$stmt->execute();

$pro = $stmt->fetchAll();
$arreglo_php = array();
foreach ($pro as $n => $pronom) {
    array_push($arreglo_php, $pronom->PRO_nom);
}
?>
            $(document).ready(function () {

                $(function () {
                    var autocompletar = new Array();
<?php
//php per obtenir el que necesitam
for ($p = 0; $p < count($arreglo_php); $p++) { //per coneixer el numero d'elements
    ?>
                        autocompletar.push('<?php echo $arreglo_php[$p]; ?>');
<?php } ?>
                    $("#paraules").autocomplete({//id de l'imput
                        source: autocompletar // origen l'array.
                    });
                });
                $('[data-toggle="tooltip"]').tooltip();
                var warn_on_unload = "";
                $('input:text,textarea,select').one('change', function () {
                    warn_on_unload = "Si surts ara no es guardaran els canvis fets.";

                    $('input:submit').click(function (e) {
                        warn_on_unload = "";
                    });

                    window.onbeforeunload = function () {
                        if (warn_on_unload !== '') {
                            return warn_on_unload;
                        }
                    };
                });
                var warn_on_unload = ""; // buida la variable
                $('input:text,input:checkbox,input:radio,textarea,select').one('change', function () // Detecta canvis input
                {
                    warn_on_unload = "Si surts ara no es guardaran els canvis fets."; // Avis

                    $('input:submit').click(function (e) { // No actua en cas de guardar
                        warn_on_unload = "";
                    });

                    window.onbeforeunload = function () { // Actua si es surt de la pagina.
                        if (warn_on_unload != '') {
                            return warn_on_unload;
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
                    <a class="navbar-brand" href="index.html">Ca S'amitger</a>
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
                        <li class="active">
                            <a href="newpropietari.php"><i class="glyphicon glyphicon-plus"></i> Afegir propietari</a>
                        </li>
                        <li>
                            <a href="fotoscasa.php"><i class="glyphicon glyphicon-camera"></i> Fotografies Casa</a>
                        </li>
                        <li>
                            <a href="pujadaarxius.php"><i class="glyphicon glyphicon-file"></i> Pujada d'arxius</a>
                        </li>
                        <li>
                            <a href="pujarexcel.php"><i class="glyphicon glyphicon-file"></i> Pujada i edicio Excel</a>
                        </li>
                        <li>
                            <a href="mapa.php" target="_blank"><i class="glyphicon glyphicon-file"></i> Mapa coordenades</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div id="page-wrapper">

                <div class="container-fluid">


                    <div class="breadcrumb">
                        <div id="page_container">

                            <?php
                            if (isset($_POST['enviar'])) {
                                $stmt = $pdoCore->db->prepare(
                                        "SELECT PRO_id FROM PROPIETARI ORDER BY PRO_id DESC LIMIT 1 "
                                );
                                $stmt->execute();
                                $pro = $stmt->fetchObject();
                                $darrer = ($pro->PRO_id) + 1;
                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO PROPIETARI (PRO_id,PRO_nom, PRO_dni, PRO_personaContacte, PRO_personaContacte2, PRO_personaContacte3, PRO_personaContacte4,PRO_email,PRO_email2, PRO_email3,PRO_email4,PRO_telefon,PRO_telefon2,PRO_telefon3,PRO_telefon4,PRO_mobil,PRO_mobil2,PRO_mobil3,PRO_mobil4,PRO_fax,PRO_fax2,PRO_fax3,PRO_fax4,PRO_adreca,PRO_ciutat,PRO_observacions,PRO_codiPostal,PRO_provincia, PRO_pais,PRO_ccc,PRO_iban,PRO_swift,PRO_activitat,PRO_idGestor,PRO_pagament,PRO_pagamentBanc,PRO_rutaDni,PRO_factura)
                                            VALUES(:id,:nom,:dni,:percont,:percont2,:percont3,:percont4,:mail,:mail2,:mail3,:mail4,:tel,:tel2,:tel3,:tel4,:mobil,:mobil2,:mobil3,:mobil4,:fax,:fax2,:fax3,:fax4,:adreca,:ciutat,:observ,:codipost,:provin,:pais,:ccc,:iban,:swift,:activitat,:idgestor,:paga,:pagabanc,:rutadni,:factura)");

                                $stmt->execute(array(
                                    ':id' => $darrer,
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
                                echo '<script>alert("Formulari enviat correctament")</script>';
                            }
                            ?>

                            <form action="#" method="post">
                                <table class="table table-responsive">
                                    <th colspan ="5" class="success">Afegir Propietari</th>

                                </table>
                                <div id="formulari" class= "col-xs-12 col-md-12 col-lg-12">
                                    <div class="row">
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "nompro"> Nom Propietari </label>
                                            <input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'nompro' class = 'form-control3l' name='PRO_nom'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "dnipro"> Dni Propietari </label>
                                            <input type = 'text' class = 'form-control' id = 'dnipro' name='PRO_dni'>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "contpro"> Persona contacte </label>
                                            <input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'contpro' name='PRO_personaContacte'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "cont2pro"> Segona persona contacte </label>
                                            <input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'cont2pro' name='PRO_personaContacte2'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "cont3pro"> Tercera persona contacte </label>
                                            <input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'cont3pro' name='PRO_personaContacte3'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "cont4pro"> Cuarta persona contacte </label>
                                            <input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'cont4pro' name='PRO_personaContacte4'>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "emailpro"> Email </label>
                                            <input type = 'email' class = 'form-control' id = 'emailpro' name='PRO_email' >
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "email2pro"> Email2 </label>
                                            <input type = 'email' class = 'form-control' id = 'emailpro2' name='PRO_email2'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "email3pro"> Email3 </label>
                                            <input type = 'email' class = 'form-control' id = 'emailpro3' name='PRO_email3'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "email4pro"> Email4 </label>
                                            <input type = 'email' class = 'form-control' id = 'emailpro4' name='PRO_email4'>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "telfpro"> telefon pro </label>
                                            <input type = 'text' pattern= '[1-9\s]*'  class = 'form-control3' id = 'telfpro' name='PRO_telefon'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "telfpro2"> telefon pro2 </label>
                                            <input type = 'text' pattern= '[1-9\s]*' class = 'form-control3' id = 'telfpro2' name='PRO_telefon2'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "telfpro3"> telefon pro3 </label>
                                            <input type = 'text' pattern= '[1-9\s]*' class = 'form-control3' id = 'telfpro3' name='PRO_telefon3'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "telfpro4"> telefon pro4 </label>
                                            <input type = 'text' pattern= '[1-9\s]*' class = 'form-control3' id = 'telfpro4' name='PRO_telefon4'>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "movilpro"> telefon movil pro </label>
                                            <input type = 'text' pattern= '[1-9\s]*' class = 'form-control3' id = 'telfpro' name='PRO_mobil'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "movilpro2"> telefon movil2 pro </label>
                                            <input type = 'text' pattern= '[1-9\s]*' class = 'form-control3' id = 'telfpro2' name='PRO_mobil2'>

                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "movilpro3"> telefon movil3 pro </label>
                                            <input type = 'text' pattern= '[1-9\s]*' class = 'form-control3' id = 'telfpro3' name='PRO_mobil3'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "movilpro4"> telefon movil4 pro </label>
                                            <input type = 'text' pattern= '[1-9\s]*' class = 'form-control3' id = 'telfpro4' name='PRO_mobil4'>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "faxpro"> Fax pro </label>
                                            <input type = 'text' pattern= '[1-9\s]*' class = 'form-control3' id = 'telfpro' name='PRO_fax'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "faxpro2"> Fax2 pro </label>
                                            <input type = 'text' pattern= '[1-9\s]*' class = 'form-control3' id = 'telfpro2' name='PRO_fax2'>

                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "faxpro3"> Fax3 pro </label>
                                            <input type = 'text' pattern= '[1-9\s]*' class = 'form-control3' id = 'telfpro3' name='PRO_fax3'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "faxpro4"> Fax4 pro </label>
                                            <input type = 'text' pattern= '[1-9\s]*' class = 'form-control3' id = 'telfpro4' name='PRO_fax4'>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "adrecapro"> Adreça </label>
                                            <input type = 'text' class = 'form-control' id = 'adrecapro' name='PRO_adreca'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "ciutat"> Ciutat </label>
                                            <input type = 'text' class = 'form-control' id = 'ciutat' name='PRO_ciutat'>

                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-6">
                                            <label for = "observa"> Observacions </label>
                                            <textarea class='form-control' rows='3' name='PRO_observacions' ></textarea>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "codipostal"> Codi Postal </label>
                                            <input type = 'number' class = 'form-control3' name='PRO_codiPostal'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "provincia"> Provincia </label>
                                            <input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' name='PRO_provincia'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "pais"> Pais </label>
                                            <input type = 'text' pattern= '[A-Za-z\ssàèòñáéíóú]*' class = 'form-control'  name='PRO_pais'>
                                        </div>

                                    </div>
                                    <table class ="table">
                                        <th colspan ="4" class="success">Pagament</th>
                                    </table>

                                    <div class="row">
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "ccc"> CCC </label>
                                            <input type = 'text' class = 'form-control' id = 'ccc' name='PRO_ccc'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "iban"> IBAN </label>
                                            <input type = 'text' class = 'form-control' id = 'iban' name='PRO_iban'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "swift"> Swift </label>
                                            <input type = 'text' class = 'form-control' id = 'swift' name='PRO_swift'>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "activitat"> Activitat </label>
                                            <input type = "text" class = "form-control" id = "activitat" name="PRO_activitat">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "gestor"> Gestor </label>
                                            <select name = "GES_nom" class="form-control">
                                                <?php
                                                $pdoCore = Core::getInstance();
                                                $stmt = $pdoCore->db->prepare(
                                                        "select GES_id, GES_nom from  GESTOR "
                                                );
                                                $stmt->execute();

                                                $result = $stmt->fetchAll();
                                                foreach ($result as $n => $ges) {
                                                    if ($ges->GES_id == $pro->PRO_idGestor) {
                                                        echo "<option value = '$pro->PRO_idGestor' selected>$ges->GES_nom</option>";
                                                    } else {
                                                        echo "<option value = '$pro->PRO_idGestor'>$ges->GES_nom</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "pagament"> Forma Pagament </label>
                                            <input type = "text" class = "form-control" id = "pagament" name="PRO_pagament">
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "pagamentbanc"> Banc Pagament </label>
                                            <input type = "text" class = "form-control" id = "pagamentbanc" name="PRO_pagamentBanc">
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label for = "rutadni"> Arxiu dni </label>
                                            <input type = "text" class = "form-control" id = "rutadni" name="PRO_rutaDni">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class= "col-xs-12 col-md-3 col-lg-3">
                                            <label for = "factura"> Factura </label>
                                            <select name = "PRO_factura" class="form-control">
                                                <option value = '1'>Si</option>
                                                <option value = '0'>No</option>
                                            </select>

                                        </div>
                                    </div>
                                    <input type = "submit" name="enviar" value = "Guardar" class = "btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol guardar?">



                                </div><!-- formulari -->

                            </form>


                        </div>

                    </div>

                </div>


            </div>


        </div>


    </body>

</html>
