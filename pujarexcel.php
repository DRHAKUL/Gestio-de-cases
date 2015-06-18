<?php
// Principal per pujar excels

require_once 'class/Core.php';
session_start();

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



        <title>Arxius excel</title>

        <!-- CSS de bootstrap -->
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet">
        <link href="pagination/css/style.css" rel="stylesheet" type="text/css">

        <!-- Fonts de bootstrap -->
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">



        <script>

            $(document).ready(function () {



                $("#formulari").on("submit", function (e) {
                    e.preventDefault(); // cancela el submit.
                    //var f = $(this);
                    var formData = new FormData(document.getElementById("formulari")); // Posam el formulari a una variable.
                    console.log(formData);
                    $.ajax({// enviam per ajax
                        url: "ediexcel.php", // pagina de desti.
                        type: "post", // Metode
                        dataType: "html", // tipus de dades a tornar
                        data: formData, // el formulari
                        cache: false,
                        contentType: false,
                        processData: false
                    }).done(function (res) { // El que fara amb la resposta
                        //$("#avis").html("Resposta: " + res); // Posam resposta dins div.
                        $(location).attr('href', 'ediexcel.php');
                    });

                });
            });



        </script>



    </head>

    <body>

        <div id="wrapper">

            <!-- Navigation -->
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html">Ca S'amitger</a>
                </div>
                <!-- Top Menu Items -->
                <ul class="nav navbar-right top-nav">



                    <h4><a class="warning"><i class="fa fa-user"></i> <?php echo $usuari; ?> </a>

                        <a href="index.php?sortir=si" ><i class="fa fa-fw fa-power-off"></i> Sortir</a></h4>


                </ul>

                <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
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
                        <li>
                            <a href="fotoscasa.php"><i class="glyphicon glyphicon-camera"></i> Fotografies Casa</a>
                        </li>
                        <li>
                            <a href="pujadaarxius.php"><i class="glyphicon glyphicon-file"></i> Pujada d'arxius</a>
                        </li>
                        <li class="active">
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

                            <div class ="col-xs-12 col-md-12 col-lg-12">
                                <form enctype="multipart/form-data" id="formulari" method="post">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-4 col-lg-4">
                                            <label>Arxiu a pujar:</label><br>
                                            <input  type="file" id="arxiu" name="arxiu" class = "form-control"/>
                                        </div>
                                    </div>

                                    </select>
                                    <br><input type="submit" name ="pujararxiu" value="Pujar arxius" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Pujar arxiu."/>

                                </form>
                            </div>
                            <div id="avis"></div>


                        </div>

                    </div>

                </div>


            </div>


        </div>


    </body>

</html>

