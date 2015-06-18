<?php
// Formulari per canviar dades de propietari.

require_once 'class/Core.php';
$pdoCore = Core::getInstance();
session_start();

// Si no hi ha sesio envia a login.php.
if (!$_GET['id']) { // Comprobaci� de sessi�.
    header("Location: login.php"); //Si no hi ha sessio "meva", envia a login..
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
        <script src="js/bootstrap.min.js"></script>
        <title>Editor de Propietaris</title>
        <script src="js/bootstrap.js"></script>

        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <script>
            // Funcio per mostrar avisos en el boto de guardar.
            $(document).ready(function () {

                $('[data-toggle="tooltip"]').tooltip();
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

                    <!-- Page Heading -->
                    <div class="row">


                    </div>
                    <!-- /.row -->
                    <div class="breadcrumb">




                        <?php
                        // Modificacio de propietaris.

                        if (isset($_POST['enviar'])) {  // Fer modificació
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
                                    . "WHERE PRO_id = :idpro "
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
                                ':factura' => $_POST['PRO_factura'],
                                ':idpro' => $_POST['idpro']));
                        }


                        // Recull id de propietari
                        if (isset($_GET['id'])) {
                            $proid = $_GET['id'];
                        }

                        // Fa una consulta amb aquest id.
                        $stmt = $pdoCore->db->prepare(
                                "SELECT * FROM PROPIETARI C WHERE C.PRO_id = :proid "
                        );
                        $stmt->execute(
                                array(
                                    ':proid' => $proid
                                )
                        );

                        $pro = $stmt->fetchObject();

                        // formulari de propietaris.

                        echo '<form action="#" method="post">';
                        echo '<table class="table">';
                        echo '<th colspan ="4" class="success">Dades personals</th>';
                        echo '<tr><td>';
                        echo '<label for = "idpro">ID Propietari </label>';
                        echo "<input type = 'text' class = 'form-control input-small' name='idpro'  value = '$pro->PRO_id' readonly=readonly>";
                        echo '</td><td>';
                        echo '<label for = "nompro"> Nom Propietari </label>';
                        echo "<input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'nompro' class = 'form-control3l' name='PRO_nom' value = '$pro->PRO_nom'>";
                        echo '</td><td>';
                        echo '<label for = "dnipro"> Dni Propietari </label>';
                        echo "<input type = 'text' class = 'form-control' id = 'dnipro' name='PRO_dni' value = '$pro->PRO_dni' data-toggle='tooltip' data-placement='top' title='111111111A'>";
                        echo '</td></tr>';
                        echo '<tr><td>';
                        echo '<label for = "contpro"> Persona contacte </label>';
                        echo "<input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'contpro' name='PRO_personaContacte' value = '$pro->PRO_personaContacte'>";
                        echo '</td><td>';
                        echo '<label for = "cont2pro"> Segona persona contacte </label>';
                        echo "<input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'cont2pro' name='PRO_personaContacte2' value = '$pro->PRO_personaContacte2'>";
                        echo '</td><td>';
                        echo '<label for = "cont3pro"> Tercera persona contacte </label>';
                        echo "<input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'cont3pro' name='PRO_personaContacte3' value = '$pro->PRO_personaContacte3'>";
                        echo '</td><td>';
                        echo '<label for = "cont4pro"> Cuarta persona contacte </label>';
                        echo "<input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'cont4pro' name='PRO_personaContacte4' value = '$pro->PRO_personaContacte4'>";
                        echo '</td></tr>';
                        echo '<tr><td>';
                        echo '<label for = "emailpro"> Email </label>';
                        echo "<input type = 'email' class = 'form-control' id = 'emailpro' name='PRO_email' value = '$pro->PRO_email'>";
                        echo '</td><td>';
                        echo '<label for = "email2pro"> Email2 </label>';
                        echo "<input type = 'email' class = 'form-control' id = 'emailpro2' name='PRO_email2' value = '$pro->PRO_email2'>";
                        echo '</td><td>';
                        echo '<label for = "email3pro"> Email3 </label>';
                        echo "<input type = 'email' class = 'form-control' id = 'emailpro3' name='PRO_email3' value = '$pro->PRO_email3'>";
                        echo '</td><td>';
                        echo '<label for = "email4pro"> Email4 </label>';
                        echo "<input type = 'email' class = 'form-control' id = 'emailpro4' name='PRO_email4' value = '$pro->PRO_email4'>";
                        echo '</td><td>';
                        echo '<tr><td>';
                        echo '<label for = "telfpro"> telefon pro </label>';
                        echo "<input type = 'text' pattern= '[1-9\s]*'  class = 'form-control' id = 'telfpro' name='PRO_telefon' value = '$pro->PRO_telefon'>";
                        echo '</td><td>';
                        echo '<label for = "telfpro2"> telefon pro2 </label>';
                        echo "<input type = 'text' pattern= '[1-9\s]*' class = 'form-control' id = 'telfpro2' name='PRO_telefon2' value = '$pro->PRO_telefon2'>";
                        echo '</td><td>';
                        echo '<label for = "telfpro3"> telefon pro3 </label>';
                        echo "<input type = 'text' pattern= '[1-9\s]*' class = 'form-control' id = 'telfpro3' name='PRO_telefon3' value = '$pro->PRO_telefon3'>";
                        echo '</td><td>';
                        echo '<label for = "telfpro4"> telefon pro4 </label>';
                        echo "<input type = 'text' pattern= '[1-9\s]*' class = 'form-control' id = 'telfpro4' name='PRO_telefon4' value = '$pro->PRO_telefon4'>";
                        echo '</td><td>';
                        echo '<tr><td>';
                        echo '<label for = "movilpro"> telefon movil pro </label>';
                        echo "<input type = 'text' pattern= '[1-9\s]*' class = 'form-control' id = 'telfpro' name='PRO_mobil' value = '$pro->PRO_mobil'>";
                        echo '</td><td>';
                        echo '<label for = "movilpro2"> telefon movil2 pro </label>';
                        echo "<input type = 'text' pattern= '[1-9\s]*' class = 'form-control' id = 'telfpro2' name='PRO_mobil2' value = '$pro->PRO_mobil2'>";
                        echo '</td><td>';
                        echo '<label for = "movilpro3"> telefon movil3 pro </label>';
                        echo "<input type = 'text' pattern= '[1-9\s]*' class = 'form-control' id = 'telfpro3' name='PRO_mobil3' value = '$pro->PRO_mobil3'>";
                        echo '</td><td>';
                        echo '<label for = "movilpro4"> telefon movil4 pro </label>';
                        echo "<input type = 'text' pattern= '[1-9\s]*' class = 'form-control' id = 'telfpro4' name='PRO_mobil4' value = '$pro->PRO_mobil4'>";
                        echo '</td><td>';
                        echo '<tr><td>';
                        echo '<label for = "faxpro"> Fax pro </label>';
                        echo "<input type = 'text' pattern= '[1-9\s]*' class = 'form-control' id = 'telfpro' name='PRO_fax' value = '$pro->PRO_fax'>";
                        echo '</td><td>';
                        echo '<label for = "faxpro2"> Fax2 pro </label>';
                        echo "<input type = 'text' pattern= '[1-9\s]*' class = 'form-control' id = 'telfpro2' name='PRO_fax2' value = '$pro->PRO_fax2'>";
                        echo '</td><td>';
                        echo '<label for = "faxpro3"> Fax3 pro </label>';
                        echo "<input type = 'text' pattern= '[1-9\s]*' class = 'form-control' id = 'telfpro3' name='PRO_fax3' value = '$pro->PRO_fax3'>";
                        echo '</td><td>';
                        echo '<label for = "faxpro4"> Fax4 pro </label>';
                        echo "<input type = 'text' pattern= '[1-9\s]*' class = 'form-control' id = 'telfpro4' name='PRO_fax4' value = '$pro->PRO_fax4'>";
                        echo '</td></tr><tr><td>';
                        echo '<label for = "adrecapro"> Adreça </label>';
                        echo "<input type = 'text' class = 'form-control' id = 'adrecapro' name='PRO_adreca' value = '$pro->PRO_adreca'>";
                        echo '</td><td>';
                        echo '<label for = "ciutat"> Ciutat </label>';
                        echo "<input type = 'text' class = 'form-control' id = 'ciutat' name='PRO_ciutat' value = '$pro->PRO_ciutat'>";
                        echo '</td><td>';
                        echo '<label for = "observa"> Observacions </label>';
                        echo "<textarea class='form-control' rows='3' name='PRO_observacions' >$pro->PRO_observacions</textarea>";
                        echo '</td></tr><td>';
                        echo '<label for = "codipostal"> Codi Postal </label>';
                        echo "<input type = 'number' class = 'form-control' id = 'codipostal' name='PRO_codiPostal' value = '$pro->PRO_codiPostal' data-toggle='tooltip' data-placement='top' title='11111'>";
                        echo '</td><td>';
                        echo '<label for = "provincia"> Provincia </label>';
                        echo "<input type = 'text' pattern= '[A-Za-z\sàèòñáéíóú]*' class = 'form-control' id = 'provincia' name='PRO_provincia' value = '$pro->PRO_provincia'>";
                        echo '</td><td>';
                        echo '<label for = "pais"> Pais </label>';
                        echo "<input type = 'text' pattern= '[A-Za-z\ssàèòñáéíóú]*' class = 'form-control' id = 'pais' name='PRO_pais' value = '$pro->PRO_pais'>";
                        echo '</tr>';
                        echo '<th colspan ="4" class="success">Pagament</th>';
                        echo '<tr><td>';
                        echo '<label for = "ccc"> CCC </label>';
                        echo "<input type = 'text' class = 'form-control' id = 'ccc' name='PRO_ccc' value = '$pro->PRO_ccc'>";
                        echo '</td><td>';
                        echo '<label for = "iban"> IBAN </label>';
                        echo "<input type = 'text' class = 'form-control' id = 'iban' name='PRO_iban' value = '$pro->PRO_iban'>";
                        echo '</td><td>';
                        echo '<label for = "swift"> Swift </label>';
                        echo "<input type = 'text' class = 'form-control' id = 'swift' name='PRO_swift' value = '$pro->PRO_swift'>";
                        echo '</td><td>';
                        echo '<label for = "activitat"> Activitat </label>';
                        echo '<input type = "text" class = "form-control" id = "activitat" name="PRO_activitat" value = "' . $pro->PRO_activitat . '">';
                        echo '</td></tr><tr><td>';
                        echo '<label for = "gestor"> Gestor </label>';
                        echo '<select name = "GES_nom" class="form-control" data-toggle="tooltip" data-placement="right" title="Segur que vol guardar?">';

                        // Per obternir de la bbdd el nom dels gestors
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
                        echo '</select>';
                        echo '</td><td>';
                        echo '<label for = "pagament"> Forma Pagament </label>';
                        echo '<input type = "text" class = "form-control" id = "pagament" name="PRO_pagament" value ="' . $pro->PRO_pagament . '">';
                        echo '</td><td>';
                        echo '<label for = "pagamentbanc"> Banc Pagament </label>';
                        echo '<input type = "text" class = "form-control" id = "pagamentbanc" name="PRO_pagamentBanc" value ="' . $pro->PRO_pagamentBanc . '">';
                        echo '</td><td>';
                        echo '<label for = "rutadni"> Arxiu dni </label>';
                        echo '<input type = "text" class = "form-control" id = "rutadni" name="PRO_rutaDni" value ="' . $pro->PRO_rutaDni . '">';
                        echo '</td></tr><tr><td>';
                        echo '<label for = "factura"> Factura </label>';
                        echo '<select name = "PRO_factura" class="form-control">';
                        if ($pro->PRO_factura == '0') {
                            echo "<option value = '$pro->PRO_factura' selected>No</option>";
                        } else {
                            echo "<option value = '$pro->PRO_factura' selected>Si</option>";
                        }
                        echo "<option value = '1'>Si</option>";
                        echo '</select></td></tr>';
                        echo '<tr><td colspan ="4" class="success">';
                        echo '<input type = "submit" name="enviar" value = "Guardar" class = "btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol guardar?">';
                        echo '</td></tr>';
                        echo '</table>';
                        ?>


                    </div>

                </div>

            </div>





        </div>



    </body>

</html>
