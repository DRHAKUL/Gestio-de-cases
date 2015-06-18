<?php
// Les descripcions de les cases.

session_start();
// Si no hi ha sesio envia a login.php.
if (!$_SESSION['meva']) { // Comprobaci� de sessi�.
    header("Location: login.php"); //Si no hi ha sessio "meva", envia a login..
}
require_once 'class/Core.php';
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


        <title>DESCRIPCIONS</title>

        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">


        <script src="js/bootstrap.js"></script>
        <script>
//            Jquery que fa que no canvii de pestanya quan s'enviael formulari.
            $(document).ready(function () {
                var hash = window.location.hash;
                hash && $('ul.nav a[href="' + hash + '"]').tab('show');
                $('[data-toggle="tooltip"]').tooltip(); //funcio per els avisos dels botons.
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
                        <?php
                        $pdoCore = Core::getInstance();



                        // Update catala

                        if (isset($_POST['modcatala'])) {
                            $stmt = $pdoCore->db->prepare(
                                    "UPDATE DESCRIPCIONS SET DES_catala = :DES_catala WHERE DES_codiCasa = :idcasa"
                            );


                            $stmt->execute(array(':DES_catala' => $_POST['DES_catala'], ':idcasa' => $_POST['idcasa']
                            ));
                        }

                        // Update castella

                        if (isset($_POST['modcastella'])) {
                            $stmt = $pdoCore->db->prepare(
                                    "UPDATE DESCRIPCIONS SET DES_castella = :DES_castella WHERE DES_codiCasa = :idcasa"
                            );


                            $stmt->execute(array(':DES_castella' => $_POST['DES_castella'], ':idcasa' => $_POST['idcasa']
                            ));
                        }

                        // Update angles

                        if (isset($_POST['modangles'])) {
                            $stmt = $pdoCore->db->prepare(
                                    "UPDATE DESCRIPCIONS SET DES_angles = :DES_angles WHERE DES_codiCasa = :idcasa"
                            );


                            $stmt->execute(array(':DES_angles' => $_POST['DES_angles'], ':idcasa' => $_POST['idcasa']
                            ));
                        }

                        // Update alemany

                        if (isset($_POST['modalemany'])) {
                            $stmt = $pdoCore->db->prepare(
                                    "UPDATE DESCRIPCIONS SET DES_alemany = :DES_alemany WHERE DES_codiCasa = :idcasa"
                            );


                            $stmt->execute(array(':DES_alemany' => $_POST['DES_alemany'], ':idcasa' => $_POST['idcasa']
                            ));
                        }

                        // Update Catala curt

                        if (isset($_POST['modcatcurt'])) {
                            $stmt = $pdoCore->db->prepare(
                                    "UPDATE DESCRIPCIONS SET DES_catalaCurta = :DES_catalaCurta WHERE DES_codiCasa = :idcasa"
                            );


                            $stmt->execute(array(':DES_catalaCurta' => $_POST['DES_catalaCurta'], ':idcasa' => $_POST['idcasa']
                            ));
                        }

                        // Update Castella curt

                        if (isset($_POST['modcascurt'])) {
                            $stmt = $pdoCore->db->prepare(
                                    "UPDATE DESCRIPCIONS SET DES_castellaCurta = :DES_castellaCurta WHERE DES_codiCasa = :idcasa"
                            );


                            $stmt->execute(array(':DES_castellaCurta' => $_POST['DES_castellaCurta'], ':idcasa' => $_POST['idcasa']
                            ));
                        }

                        // Update Angles curt

                        if (isset($_POST['modangcurt'])) {
                            $stmt = $pdoCore->db->prepare(
                                    "UPDATE DESCRIPCIONS SET DES_anglesCurta = :DES_anglesCurta WHERE DES_codiCasa = :idcasa"
                            );


                            $stmt->execute(array(':DES_anglesCurta' => $_POST['DES_anglesCurta'], ':idcasa' => $_POST['idcasa']
                            ));
                        }

                        // Update Alemany curt

                        if (isset($_POST['modalecurt'])) {
                            $stmt = $pdoCore->db->prepare(
                                    "UPDATE DESCRIPCIONS SET DES_alemanyCurta = :DES_alemanyCurta WHERE DES_codiCasa = :idcasa"
                            );


                            $stmt->execute(array(':DES_alemanyCurta' => $_POST['DES_alemanyCurta'], ':idcasa' => $_POST['idcasa']
                            ));
                        }

                        // Seleccio de descripcions i cases

                        $codicasa = $_GET['codicasa'];

                        $stmt = $pdoCore->db->prepare(
                                "SELECT * FROM DESCRIPCIONS D "
                                . "INNER JOIN CASA C ON D.DES_codiCasa = C.CAS_id "
                                . "WHERE D.DES_codiCasa = :casaid "
                        );
                        $stmt->execute(
                                array(
                                    ':casaid' => $codicasa
                                )
                        );


                        $des = $stmt->fetchObject();
                        echo '<table class="table">';
                        echo '<th colspan ="4" class="success">Descripció de la casa "' . $des->CAS_nom . '" amb codi: ' . $des->CAS_id . '</th>';
                        echo '</table>';
                        ?>

                        <ul class="nav nav-tabs nav-justified">
                            <li class="active"><a data-toggle="tab" href="#sectionA">CATALA &nbsp<img alt="Brand" src="images/cat.png" width="17%"></a></li>
                            <li><a data-toggle="tab" href="#sectionB">CASTELLA &nbsp<img alt="Brand" src="images/es.png" width="15%"></a></li>
                            <li><a data-toggle="tab" href="#sectionC">ANGLES &nbsp<img alt="Brand" src="images/gb.png" width="17%"></a></li>
                            <li><a data-toggle="tab" href="#sectionD">ALEMANY &nbsp<img alt="Brand" src="images/de.png" width="15%"></a></li>


                        </ul>
                        <div class="tab-content">
                            <div id="sectionA" class="tab-pane fade in active">




                                <?php
// ------------------------------------ Descripcio Catala ---------------------------------------



                                echo '<form action="#sectionA" method="post">';
                                echo '<table class="table">';
                                echo '<input type = "hidden" name = "idcasa" value = "' . $des->DES_codiCasa . '">';
                                echo '<tr><td><textarea name="DES_catala" rows="20" cols="90" style="width:100%">' . $des->DES_catala . '</textarea></td></tr>';
                                echo '<tr><td><input type = "submit" value = "Modificar" name = "modcatala" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"></td></tr>';
                                echo '</table>';
                                echo '</form>';
                                echo '<ul class="nav nav-tabs"><li><a data-toggle="tab" href="#sectionA">CATALA CURTA '
                                . '&nbsp<img alt="Brand" src="images/cat.png" width="13%"></a></li></ul>';
                                echo '<form action="#sectionA" method="post">';
                                echo '<table class="table">';
                                echo '<input type = "hidden" name = "idcasa" value = "' . $des->DES_codiCasa . '">';
                                echo '<tr><td><textarea name="DES_catalaCurta" rows="20" cols="90" style="width:100%">' . $des->DES_catalaCurta . '</textarea></td></tr>';
                                echo '<tr><td><input type = "submit" value = "Modificar" name = "modcatcurt" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"></td></tr>';
                                echo '</table>';
                                echo '</form>';
                                ?>

                            </div>

                            <div id="sectionB" class="tab-pane fade">
                                <?php
// ------------------------------------ Descripcio Castella ---------------------------------------



                                echo '<form action="#sectionB" method="post">';
                                echo '<table class="table">';
                                echo '<input type = "hidden" name = "idcasa" value = "' . $des->DES_codiCasa . '">';
                                echo '<tr><td><textarea name="DES_castella" rows="20" cols="90" style="width:100%">' . $des->DES_castella . '</textarea></td></tr>';
                                echo '<tr><td><input type = "submit" value = "Modificar" name = "modcastella" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"></td></tr>';
                                echo '</table>';
                                echo '</form>';
                                echo '<ul class="nav nav-tabs"><li><a data-toggle="tab" href="#sectionB">CASTELLA CURTA '
                                . '&nbsp<img alt="Brand" src="images/es.png" width="9%"></a></li></ul>';
                                echo '<form action="#sectionB" method="post">';
                                echo '<table class="table">';
                                echo '<input type = "hidden" name = "idcasa" value = "' . $des->DES_codiCasa . '">';
                                echo '<tr><td><textarea name="DES_castellaCurta" rows="20" cols="90" style="width:100%">' . $des->DES_castellaCurta . '</textarea></td></tr>';
                                echo '<tr><td><input type = "submit" value = "Modificar" name = "modcascurt" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"></td></tr>';
                                echo '</table>';
                                echo '</form>';
                                ?>


                            </div>
                            <div id="sectionC" class="tab-pane fade">
                                <?php
// ------------------------------------ Descripcio Angles ---------------------------------------



                                echo '<form action="#sectionC" method="post">';
                                echo '<table class="table">';
                                echo '<input type = "hidden" name = "idcasa" value = "' . $des->DES_codiCasa . '">';
                                echo '<tr><td><textarea name="DES_angles" rows="20" cols="90" style="width:100%">' . $des->DES_angles . '</textarea></td></tr>';
                                echo '<tr><td><input type = "submit" value = "Modificar" name = "modangles" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"></td></tr>';
                                echo '</table>';
                                echo '</form>';
                                echo '<ul class="nav nav-tabs"><li><a data-toggle="tab" href="#sectionC">ANGLES CURTA '
                                . '&nbsp<img alt="Brand" src="images/gb.png" width="15%"></a></li></ul>';
                                echo '<form action="#sectionC" method="post">';
                                echo '<table class="table">';
                                echo '<input type = "hidden" name = "idcasa" value = "' . $des->DES_codiCasa . '">';
                                echo '<tr><td><textarea name="DES_anglesCurta" rows="20" cols="90" style="width:100%">' . $des->DES_anglesCurta . '</textarea></td></tr>';
                                echo '<tr><td><input type = "submit" value = "Modificar" name = "modangcurt" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"></td></tr>';
                                echo '</table>';
                                echo '</form>';
                                ?>
                            </div>

                            <div id="sectionD" class="tab-pane fade">
                                <?php
// ------------------------------------ Descripcio Alemany ---------------------------------------



                                echo '<form action="#sectionD" method="post">';
                                echo '<table class="table">';
                                echo '<input type = "hidden" name = "idcasa" value = "' . $des->DES_codiCasa . '">';
                                echo '<tr><td><textarea name="DES_alemany" rows="20" cols="90" style="width:100%">' . $des->DES_alemany . '</textarea></td></tr>';
                                echo '<tr><td><input type = "submit" value = "Modificar" name = "modalemany" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"></td></tr>';
                                echo '</table>';
                                echo '</form>';
                                echo '<ul class="nav nav-tabs"><li><a data-toggle="tab" href="#sectionD">ALEMANY CURTA '
                                . '&nbsp<img alt="Brand" src="images/de.png" width="9%"></a></li></ul>';
                                echo '<form action="#sectionD" method="post">';
                                echo '<table class="table">';
                                echo '<input type = "hidden" name = "idcasa" value = "' . $des->DES_codiCasa . '">';
                                echo '<tr><td><textarea name="DES_alemanyCurta" rows="20" cols="90" style="width:100%">' . $des->DES_alemanyCurta . '</textarea></td></tr>';
                                echo '<tr><td><input type = "submit" value = "Modificar" name = "modalecurt" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol canviar?"></td></tr>';
                                echo '</table>';
                                echo '</form>';
                                ?>
                            </div>


                        </div>


                    </div>


                </div>


            </div>


        </div>


    </body>

</html>
