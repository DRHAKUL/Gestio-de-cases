<?php
//Principal llistat de cases per veure les fotos.

session_start();
// Si no hi ha sesio envia a login.php.
if (!$_SESSION['meva']) { // Comprobacio de sessio.
    header("Location: login.php"); //Si no hi ha sessio "meva", envia a login..
}
// Cridam la clase core per fer la conexio amb la base de dades
require_once 'class/Core.php';
session_start();

// Si no hi ha sesio envia a login.php.
if (!$_SESSION['meva']) { // Comprobació de sessió.
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



        <title>Cases</title>

        <!-- CSS de bootstrap -->
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet">


        <!-- Fonts de bootstrap -->
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">




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
                        <li >
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
                        <li class="active">
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
                            <div class="page_navigation"></div>



                            <link href="pagination/css/style.css" rel="stylesheet" type="text/css">

                            <?php
                            $pdoCore = Core::getInstance();
                            echo '<p>Tria una casa per veure les seves fotos:</p>';
                            echo '<form action="fotos.php" method="get" target="_blank">';
                            echo '<select name = "codicasa" class = "form-control4l">';
                            $stmt = $pdoCore->db->prepare(
                                    "SELECT * FROM CASA "
                            );
                            $stmt->execute();

                            $result = $stmt->fetchAll();

                            foreach ($result as $key => $casa) {
                                echo '<option value = "' . $casa->CAS_id . '">' . $casa->CAS_nom . '</option>';
                            }
                            echo '</select>';
                            echo '<br><br><input type="submit" name ="enviar" value = "Enviar" class="btn btn-primary">';
                            echo '</form>';
                            ?>



                        </div>

                    </div>

                </div>


            </div>


        </div>


    </body>

</html>

