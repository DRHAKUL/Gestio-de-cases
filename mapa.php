<?php
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


        <title>MAPA</title>

        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">


        <style type="text/css">

        </style>
        <script type="text/javascript" src="js/jquery-1.11.3.js"></script>
        <script src="js/bootstrap.js"></script>

        <title>Places search box</title>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
        <script type="text/javascript">
            //Variables que necesitam
            var lat = null;
            var lng = null;
            var map = null;
            var geocoder = null;
            var marker = null;

            jQuery(document).ready(function () {
                //Recollim les coordenades
                lat = jQuery('#lat').val();
                lng = jQuery('#long').val();
                //Funcio click
                jQuery('#pasar').click(function () {
                    codeAddress();
                    return false;
                });
                //Iniciam funcio que te el google maps
                initialize();
            });

            function initialize() { // Funcio

                geocoder = new google.maps.Geocoder();

                //Si hi ha ja coordenades
                if (lat != '' && lng != '')
                {
                    var latLng = new google.maps.LatLng(lat, lng);
                }
                else
                {
                    var latLng = new google.maps.LatLng(39.5696005, 2.6501603000000387);
                }
                //Opcions del mapa
                var myOptions = {
                    center: latLng, //centre
                    zoom: 15, //zoom
                    mapTypeId: google.maps.MapTypeId.ROADMAP //tipus mapa
                };
                //Enviam el mapa al div.
                map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

                //Es fa el marcador
                marker = new google.maps.Marker({
                    map: map, //mapa
                    animation: google.maps.Animation.DROP,
                    position: latLng, //objecte coordenades
                    draggable: true //Fer el marcador movil
                });

                // Funcio per moure el marcador
                updatePosition(latLng);


            }

            //Traduccio de direccio
            function codeAddress() {

                //Formulari
                var address = document.getElementById("direccion").value;
                //Cridada geodecoder
                geocoder.geocode({'address': address}, function (results, status) {

                    //comprobacio estat
                    if (status == google.maps.GeocoderStatus.OK) {
                        //centre el mapa
                        map.setCenter(results[0].geometry.location);
                        //Posa el marcador
                        marker.setPosition(results[0].geometry.location);
                        //actualitza el formulari.
                        updatePosition(results[0].geometry.location);

                        // Event per actualitzar el formulari quan es mou el marcador.
                        google.maps.event.addListener(marker, 'dragend', function () {
                            updatePosition(marker.getPosition());
                        });
                    } else {
                        //Error
                        alert("No podemos encontrar la direcci&oacute;n, error: " + status);
                    }
                });
            }

            //funcio per actulitzar el camps del formulari.
            function updatePosition(latLng)
            {

                jQuery('#lat').val(latLng.lat());
                jQuery('#long').val(latLng.lng());

            }
        </script>
        <style>
            #target {
                width: 345px;
            }
        </style>
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
                    <a class="navbar-brand" href="index.php">Ca S'amitger</a>
                </div>

            </nav>

            <div id="page-wrapper">
                <div class="container-fluid">

                    <form id="google" name="google" action="#">

                        <p><label>Direcci&oacute;: </label>
                            <input style="width:500px" type="text" id="direccion" name="Direccion" value=""/>
                            <button id="pasar">Enviar coordenades</button>
                        </p>

                        <div id="map_canvas" style="width:100%;height:700px;"></div>
                        <br>
                        <!--campos ocultos donde guardamos los datos-->
                        <p><label>Latitud: </label><input type="text" readonly name="lat" id="lat"/></p>
                        <p><label> Longitud:</label> <input type="text" readonly name="lng" id="long"/></p>

                    </form>
                </div>

            </div>
    </body>
</html>