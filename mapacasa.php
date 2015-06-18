<?php
// Mapa de la casa

session_start();
// Si no hi ha sesio envia a login.php.
if (!$_SESSION['meva']) { // Comprobacio de sessio.
    header("Location: login.php"); //Si no hi ha sessio "meva", envia a login..
}
require_once 'class/Core.php';
if (isset($_GET['codicasa'])) {
    $codicasa = $_GET['codicasa'];
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


        <title>MAPA CASA</title>

        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">


        <style type="text/css">
            #mapa { height: 500px; }
        </style>
        <script type="text/javascript" src="js/jquery-1.11.3.js"></script>
        <script src="js/bootstrap.js"></script>

        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <script type="text/javascript">
            function initialize() {
                // Les dades de la bbdd que rep del hidden
                var dire = $('#direccio').val();
                dire = dire.split(',');
                var lat = dire[0];
                var lng = dire[1];

                var centre = new google.maps.LatLng({lat}, {lng});
                        var dire2 = "42.603, -5.577";
                var marcadores = [
                    ['La casa', lat, lng]

                ];
                var map = new google.maps.Map(document.getElementById('mapa'), {
                    zoom: 16,
                    center: new google.maps.LatLng(lat, lng),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });
                var infowindow = new google.maps.InfoWindow();
                var marker, i;
                for (i = 0; i < marcadores.length; i++) {
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(marcadores[i][1], marcadores[i][2]),
                        map: map
                    });
                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {
                            infowindow.setContent(marcadores[i][0]);
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                }
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>


        <style type="text/css">
            #mapa { height: 500px; }
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

                    <div class="row">


                    </div>

                    <div class="breadcrumb">
                        <div id="mapa"></div>
                        <?php
                        // Cercam a la bbdd les coordenades de la casa
                        $pdoCore = Core::getInstance();
                        $stmt = $pdoCore->db->prepare(
                                "SELECT * FROM DADES_CASA WHERE DAD_codiCasa = :casid "
                        );
                        $stmt->execute(
                                array(
                                    ':casid' => $codicasa
                                )
                        );
                        $cas = $stmt->fetchObject();
                        $dire = $cas->GeograficCoordinates;

                        echo $cas->GeograficCoordinates;
                        ?>
                        <input type="hidden" id="direccio" value="<?php echo $dire; ?>">
                        <P class="lead"><?php echo $cas->Address; ?></P>
                    </div>


                </div>


            </div>


        </div>


    </div>


</div>


</body>

</html>
