<?php
//
// Pagina principal de la aplicacio
// Cridam la clase core per fer la conexio amb la base de dades
require_once 'class/Core.php';
// Arrancam sesió.
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

        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="pagination/css/style.css" rel="stylesheet" type="text/css">

        <title>Cases</title>

        <!--         script jquery que retorna el llistat generat per ajax -->


        <script>

            $(document).ready(function () {

                // #pagination_div es el div on es posa el llistat de pagines
                // #results es el div on es motra la taula de la base de dades.
                // .div_total es el div que inclou es altres dos.
                $("#pagination_div").load("pagination/pagination_reserves.php", {'page': 0}, function () {

                });  //Obre l'arxiu amb el numero de pagines i li envia el numero actual de pagina.

                // Mostram el llistat de cases en la pagina actual.
                $("#results").load("pagination/fetch_pages.php", {'page': 0}, function () {
                    $("#1-page").addClass('active'); // Resalta el nº de pagina actual.

                });

                //Pagina per veure

                $(".div_total").delegate(".paginate_click", "click", function () { // event click a tots els fills "llista pagines"



                    var clicked_id = $(this).attr("id").split("-"); //ID de l'element clicat, split() array al numero de pagina.
                    var page_num = parseInt(clicked_id[0]); //clicked_id[0] te el nº de pagine.

                    $('.paginate_click').removeClass('active'); //llevam clases actives, resaltades

                    var data_form = $('#form_cerca').serializeArray();
                    data_form.push({name: 'page', value: (page_num - 1)});
                    // Tornara la taula a #result
                    // Es resta 1 perque comença per 0
                    $("#results").load("pagination/fetch_pages.php", data_form, function () {

                    });

                    $(this).addClass('active'); // Li donam la clase activa.

                    return false; //prevent going to herf link
                });


// Cercador........................................................

                                                                          
//Posam el cursor inicialment al camp nom de la casa
                $("#busqueda").focus();
                                                                                                    
                // Funcio per els camps de texte.
                // Quan detecta tecles en algun dels camps.
                $("#busqueda, #busquedapro, #busquedagest").keyup(function (e) {
                                     
                    //Agafam el texte a cercar

                    var nom = $("#busqueda").val();
                    var pro = $("#busquedapro").val();
                    var gest = $("#busquedagest").val();
                    var ofi = $("#busquedaofi").val(); 
                    var act = $("#busquedaact").val(); 
                                                     
// Envia la cerca a l'arxiu cerca.php amb una cria ajax.
                                                                                  
                    $.ajax({
                        type: "POST", // get o post.
                        url: "cerca.php", // pagina per enviar les dades
                        data: {nom: nom, pro: pro, gest: gest, ofi: ofi, act: act}, // dades.
                        dataType: "html",
                        beforeSend: function () {
                        },
                        error: function () { // En cas d'error.
                            alert("error petición ajax");
                        },
// Retorna el resultat de la cerca.
                        success: function (data) { // Si va be.
                            console.log(data);                                                  
                            $("#results").empty(); // llevam el llistat principal
                            $("#pagin").hide();
                            $("#results").append(data); // Carregal el resultat.

                        }
                    });
                });

                // Funcio per el selects quan hi ha canvis al select

                $("#busquedaofi, #busquedaact").change(function (e) {
                                     
                    //Agafam el texte a cercar


                    var nom = $("#busqueda").val();
                    var pro = $("#busquedapro").val();
                    var gest = $("#busquedagest").val();
                    var ofi = $("#busquedaofi").val();
                    var act = $("#busquedaact").val();
                                                        
// Envia la cerca a l'arxiu cerca.php
                                                                                  
                    $.ajax({
                        type: "POST",
                        url: "cerca.php",
                        data: {nom: nom, pro: pro, gest: gest, ofi: ofi, act: act},
                        dataType: "html",
                        beforeSend: function () {
                        },
                        error: function () {
                            alert("error petición ajax");
                        },
// Retorna el resultat de la cerca.
                        success: function (data) { 
                            console.log(data);                                                  
                            $("#results").empty();
                            $("#results").append(data);

                        }
                    });
                });
                //Borrar cerca i tornar a llistat cases

                $("#eliminar").click(function () { // si es fa click a borrar.
                    $('#busqueda').val(''); // borram els camps del formlari.
                    $('#busquedapro,#busquedagest,#busquedaofi,#busquedaact').val('');

                    $('#results').empty(); // borram el resultat de la cerca

                    $("#results").load("pagination/fetch_pages.php", {'page': 0}, function () { // Tornam a carregar el llistat principal.
                        $("#1-page").addClass('active');// a la pagina 1
                        $("#pagin").fadeIn();

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
                        <li class="active">
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
                                Cerca Casa <p class="glyphicon glyphicon-search"></p>

                            </h1>
                            <div class="row">
                                <form class="form-inline">

                                    <div class="col-md-2 form-group"><label for="casa">Nom Casa</label><br>
                                        <input type="text" class = "form-control3l" id="busqueda" /></div>
                                    <div class="col-md-2 form-group"><label for="pro">Propietari</label><br>
                                        <input type="text" class = "form-control3l" id="busquedapro" /></div>
                                    <div class="col-md-2 form-group"><label for="casa">Gestor</label><br>
                                        <input type="text" class = "form-control3l" id="busquedagest" /></div>
                                    <div class="col-md-2 form-group"><label for="casa">Oficina</label><br>
                                        <select id="busquedaofi" class = "form-control3l">
                                            <option value="" selected=""></option>
                                            <option value="Sa Pobla">Sa Pobla</option>
                                            <option value="Campos">Campos</option>
                                            <option value="Gandia">Gandia</option>
                                        </select></div>
                                    <div class="col-md-2 form-group"><label for="casa">Estat </label><br>
                                        <select id="busquedaact" class = "form-control3l">
                                            <option value="" selected=""></option>
                                            <option value="1">Activada</option>
                                            <option value="0">No Activada</option>
                                        </select></div>
                                    <br><div class="col-md-2"><input type="button" id="eliminar" class="btn btn-primary" value="Borrar cerca"></div>

                                    <!-- Div per el resultat de la cerca -->
                                    <div id="resultado"></div>


                                </form>
                            </div>
                        </div>

                    </div>
                    <!-- /.row -->
                    <div class="breadcrumb">
                        <div id="page_container">
                            <div class="page_navigation"></div>




                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tbody>
                                    <tr>
                                        <td nowrap=""><h3><a href="newcasa.php"><i class="glyphicon glyphicon-plus"></i> Afegir Casa</a></h3></td>
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
                    <!-- /.container-fluid -->

                </div>
                <!-- /#page-wrapper -->


            </div>


        </div>
        <!-- /#wrapper -->


    </body>

</html>
