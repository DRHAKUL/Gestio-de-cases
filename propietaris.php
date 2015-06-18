<?php
// Principal del llistat de propietaris.

session_start();
// Si no hi ha sesio envia a login.php.
if (!$_SESSION['meva']) { // Comprobacio de sessio.
    header("Location: login.php"); //Si no hi ha sessio "meva", envia a login..
}
require_once 'class/Core.php';
session_start();

if (!$_SESSION['meva']) {

    header("Location: login.php"); //Envia a login si no hi ha sessio.
}
$usuari = $_SESSION['meva'];

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



        <title>Propietaris</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="../css/styles.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->



<!--        <style>
            li {
                list-style-type: none;
                display: inline;
            }
            ul {
                margin-left: -2.75%;
            }
        </style>-->
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
                        <li>
                            <a href="index.php"><i class="glyphicon glyphicon-home"></i> Cases</a>
                        </li>
                        <li class="active">
                            <a href="propietaris.php"><i class="glyphicon glyphicon-user"></i> Propietaris</a>
                        </li>
                        <li>
                            <a href="newcasa.php"><i class="glyphicon glyphicon-plus"></i> Afegir casa</a>
                        </li>
                        <li>
                            <a href="newpropietari.php"><i class="glyphicon glyphicon-plus"></i> Afegir propietari</a>
                        </li>
                        <li>
                            <a href="fotoscasa.php"><i class="glyphicon glyphicon-camera"></i> Fotografies Casa</a>
                        </li>
                        <li>
                            <a href="bootstrap-grid.html"><i class="glyphicon glyphicon-file"></i> Pujada d'arxius</a>
                        </li>
                        <li>
                            <a href="pujarexcel.php"><i class="glyphicon glyphicon-file"></i> Pujada i edicio Excel</a>
                        </li>
                        <li>
                            <a href="mapa.php" target="_blank"><i class="glyphicon glyphicon-file"></i> Mapa coordenades</a>
                        </li>

                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </nav>

            <div id="page-wrapper">

                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col-lg-12 breadcrumb">
                            <h1 class="page-header">
                                Cerca Propietari <p class="glyphicon glyphicon-search"></p>

                            </h1>
                            <form class="form-inline">

                                <label for="pro">Nom Propietari</label>
                                <input type="text" class = "form-control1" id="busquedapro" />
                                <label for="casa">Gestor</label>
                                <input type="text" class = "form-control1" id="busquedagest" />
                                <input type="button" id="eliminar" class="btn btn-primary" value="Borrar cerca">


                                <div id="resultado"></div>


                            </form>
                        </div>

                    </div>
                    <!-- /.row -->
                    <div class="breadcrumb">
                        <div id="page_container">
                            <div class="page_navigation"></div>


                            <!--    script jquery que retorna el llistat generat per ajax --------------------------------------->


                            <script>

                                $(document).ready(function () {

                                    $("#pagination_div").load("pagination/pagination_propietaris.php", {'page': 0}, function () {

                                    }); //Pagina en el numero actual.


                                    $("#results").load("pagination/fetch_pages_prop.php", {'page': 0}, function () {
                                        $("#1-page").addClass('active');
                                    });
                                    //Pagina per veure i resalta el numero actual.



                                    $(".div_total").delegate(".paginate_click", "click", function () {



                                        $("#results").prepend('<div class="loading-indication"><img src="pagination/ajax-loader.gif" /> Loading...</div>');
                                        var clicked_id = $(this).attr("id").split("-"); //ID de l'element clicat, split() al numero de pagina.
                                        var page_num = parseInt(clicked_id[0]); //clicked_id[0] holds the page number we need

                                        $('.paginate_click').removeClass('active'); //llevam clases actives

                                        var data_form = $('#form_cerca').serializeArray();
                                        data_form.push({name: 'page', value: (page_num - 1)});
                                        // Tornara la taula a #result
                                        // Es resta 1 perque comença per 0
                                        $("#results").load("pagination/fetch_pages_prop.php", data_form, function () {

                                        });
                                        $(this).addClass('active'); // Li donam la clase activa.

                                        return false; // prevent going to herf link
                                    });
                                    // Cercador.......................................................................................................................

                                                                                                                  
//Feim un focus al camp nom casa
                                    $("#busquedapro").focus();
                                                                                                                                            
                                    // Funcio per els camps de texte.

                                    $("#busquedapro, #busquedagest").keyup(function () {
                                                                     
                                        //Agafam el texte a cercar


                                        var pro = $("#busquedapro").val();
                                        var gest = $("#busquedagest").val();
// Envia la cerca a l'arxiu cerca.php
                                                                                                                          
                                        $.ajax({
                                            type: "POST",
                                            url: "cercapro.php",
                                            data: {pro: pro, gest: gest},
                                            dataType: "html",
                                            beforeSend: function () {
                                            },
                                            error: function () {
                                                alert("error petición ajax");
                                            },
// Retorna el resultat de la cerca.
                                            success: function (data) { 
                                                console.log(data);                                                   
                                                $("#resultado").empty();
                                                $("#resultado").append(data);
                                            }
                                        });
                                    });
                                    $("#eliminar").click(function () {
                                        $('#busquedapro,#busquedagest').val('');
                                        $('#resultado').empty();
                                    });
                                }); // funcio document.


                            </script>


                            <link href="pagination/css/style.css" rel="stylesheet" type="text/css">

                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tbody>
                                    <tr>
                                        <td nowrap=""><h3><a href="newpropietari.php"><i class="glyphicon glyphicon-plus"></i> Afegir propietari</a></h3></td>
                                        <td colspan="10" width="100%"></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="div_total">
                                <div id="pagination_div"></div>  <!--   En aquest div es fara el menu de  paginacio-->
                                <div id="results" style="width:100%;"></div> <!--   Div de la taula paginada.-->
                            </div>


                        </div>

                    </div>

                </div>


            </div>


        </div>


    </body>

</html>
