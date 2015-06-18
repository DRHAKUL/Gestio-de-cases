<?php
// Principal de la pujada de fotos.

session_start();
// Si no hi ha sesio envia a login.php.
if (!$_SESSION['meva']) { // Comprobaci� de sessi�.
    header("Location: login.php"); //Si no hi ha sessio "meva", envia a login..
}
require_once 'class/Core.php';
$id_casa = $_GET['codicasa'];
?>
<!DOCTYPE html>
<html lang="es">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Fotos cases</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/dropzone.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/jquery-ui.css" rel="stylesheet">

        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <style>
            .linea {
                width: 270px;

            }
        </style>
        <script type="text/javascript" src="js/jquery-1.11.3.js"></script>
        <script type="text/javascript" src="js/jquery-ui.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/dropzone.js"></script>

        <script type="text/javascript">
        </script>

        <script>
            $(document).ready(function () {

                // Us el dropzone com a interficie per pujar les fotos

                Dropzone.autoDiscover = false;
                $("#dropzone").dropzone({
                    url: "uploads.php?id_casa=<?php echo $id_casa; ?>", //envia l'id de la casa al uploads.php
                    addRemoveLinks: true,
                    maxFileSize: 1000,
                    dictResponseError: "Hi ha agut un error en el servidor",
                    acceptedFiles: 'image/*,.jpeg,.jpg,.png,.gif,.JPEG,.JPG,.PNG,.GIF', //nomes imatges
                    beforeSend: function () { //Despres de enviar les dades
                    },
                    complete: function (file)
                    {
                        //Les dades de la casa s'envien a la cridada a l'arxiu que fa l'insert;
                        $("#contentLeft").load("uploads.php", {'codicasa': <?php echo $id_casa; ?>}, function () {
                            $(this).load("carrega_fotos.php", {'codicasa': <?php echo $id_casa; ?>}, function () {


                            });
                        });

                        console.log('asd'); // Comprobam el log.

                    }
                    ,
                    error: function (file) // En cas d'error.
                    {
                        alert("Error pujant l'arxiu  " + file.name);
                    }

                });

                setInterval(function () {
                    $("#contentLeft ul").sortable({opacity: 0.6, cursor: 'move', update: function () {
                            var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
                            $.post("ordrefoto.php", order, function () {
                                // Recarregam la llista de la base de dades.


                            });
                        }
                    });
                }, 1000);
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

                    <div class="breadcrumb">
                        <?php
                        echo '<table class="table">';
                        echo '<th colspan ="4" class="success">Pujar fotos </th>';
                        echo '</table>';
                        ?>
                        <!--                        Div per la zona de pujada de fotos           -->
                        <div id="dropzone" class="dropzone"></div>
                        <!--                        Div per la llista de fotos               -->
                        <div class="fotoscasa">
                            <?php
                            // Per obtenir el nom de la casa.

                            $pdoCore = Core::getInstance();
                            $stmt = $pdoCore->db->prepare(
                                    "SELECT * FROM CASA "
                                    . "WHERE CAS_id = :casaid "
                            );
                            $stmt->execute(
                                    array(
                                        ':casaid' => $id_casa
                                    )
                            );
                            $cas = $stmt->fetchObject();
                            // Missatge amb el nom de la casa.
                            echo '<table class="table">';
                            echo '<th colspan ="4" class="success">Fotos de la casa "' . $cas->CAS_nom . '" amb codi: ' . $cas->CAS_id . '</th>';
                            echo '</table>';

                            // Per borrar una foto.
                            if (isset($_GET['borrar'])) {
                                $IDCASA = $_GET['borrar'];
                                $stmt = $pdoCore->db->prepare(
                                        "DELETE FROM IMATGES WHERE IMA_nomImatge LIKE :IMAID "
                                );
                                $stmt->execute(
                                        array(
                                            ':IMAID' => $IDCASA
                                        )
                                );
                            }

                            // Veure les imatges de la base de dades.
                            $stmt = $pdoCore->db->prepare(
                                    "SELECT * FROM IMATGES WHERE IMA_codiCasa = :codicasa ORDER BY IMA_ordre"
                            );
                            $stmt->execute(
                                    array(
                                        ':codicasa' => $id_casa
                                    )
                            );


                            // div per a les fotos.
                            echo '<div id="fotoscasa">';
                            $result = $stmt->fetchAll();
                            echo '<div id="contentLeft">';
                            echo '<ul class="list-inline">';
                            foreach ($result as $n => $img) {

                                echo'<li class="linea" id="recordsArray_' . $img->IMA_id . '">';
                                echo '<img src="files/' . $cas->CAS_nom . '/' . $img->IMA_nomImatge . '" WIDTH="250" HEIGHT="200" class="img-thumbnail">'
                                . '<a href="fotos.php?codicasa=' . $id_casa . '&borrar=' . $img->IMA_nomImatge . '" id="borrar" >'
                                . '' . $img->IMA_nomImatge . ' nº ordre :' . $img->IMA_ordre . ' <button> Borrar Foto</button></a></li>';
                            }

                            echo '</ul>';
                            echo '</div>';
                            echo '</div>';
                            ?>

                        </div>

                    </div>

                </div>


            </div>


        </div>


    </body>

</html>
