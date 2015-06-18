<?php
// Formulari per afegir una casa nova.
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

        <title>Cases</title>


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
                $('[data-toggle="tooltip"]').tooltip();

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
                        <li class="active">
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
            </nav>

            <div id="page-wrapper">

                <div class="container-fluid">


                    <div class="breadcrumb">
                        <div id="page_container">

                            <?php

                            function canvi($post) {
                                if (isset($_POST[$post])) {
                                    if ($_POST[$post] == 'on') {
                                        $_POST[$post] = '1';
                                        RETURN $_POST[$post];
                                    }
                                } else {
                                    $_POST[$post] = '0';
                                }
                            }

                            // Fer insert
                            if (isset($_POST['enviar'])) {
                                $pronom = $_POST['CAS_idPropietari'];
                                $pronom = '"' . trim($pronom) . '"';
                                $stmt = $pdoCore->db->prepare(
                                        "SELECT * FROM PROPIETARI WHERE PRO_nom like :pronom "
                                );
                                $stmt->execute(
                                        array(
                                            ':pronom' => $_POST['CAS_idPropietari']
                                        )
                                );

                                $pro = $stmt->fetchObject();

                                canvi('CAS_capsa');
                                canvi('CAS_apunt');
                                canvi('CAS_asseguranca');
                                canvi('CAS_gestioIntegral');
                                // Insertar casa.

                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO CASA ( "
                                        . "CAS_codi,"
                                        . "CAS_nom,"
                                        . "CAS_idPropietari,"
                                        . "CAS_notesInternes,"
                                        . "CAS_notesGestors,"
                                        . "CAS_portals,"
                                        . "CAS_places,"
                                        . "CAS_codiTipus,"
                                        . "CAS_nomNorm,"
                                        . "CAS_codiLlicencia,"
                                        . "CAS_codiControl,"
                                        . "CAS_codiIlla,"
                                        . "CAS_codiZona,"
                                        . "CAS_segonNom,"
                                        . "CAS_activada,"
                                        . "CAS_idGestor,"
                                        . "CAS_dataCaducitatContracte,"
                                        . "CAS_capsa,"
                                        . "CAS_apunt,"
                                        . "CAS_notesTarifaBooking,"
                                        . "CAS_notesTarifaGestio,"
                                        . "CAS_asseguranca,"
                                        . "CAS_gestioIntegral,"
                                        . "CAS_link ) "
                                        . "VALUES ( "
                                        . ":CAS_codi,"
                                        . ":CAS_nom,"
                                        . ":CAS_idPropietari,"
                                        . ":CAS_notesInternes,"
                                        . ":CAS_notesGestors,"
                                        . ":CAS_portals,"
                                        . ":CAS_places,"
                                        . ":CAS_codiTipus,"
                                        . ":CAS_nomNorm,"
                                        . ":CAS_codiLlicencia,"
                                        . ":CAS_codiControl,"
                                        . ":CAS_codiIlla,"
                                        . ":CAS_codiZona,"
                                        . ":CAS_segonNom,"
                                        . ":CAS_activada,"
                                        . ":CAS_idGestor,"
                                        . ":CAS_dataCaducitatContracte,"
                                        . ":CAS_capsa,"
                                        . ":CAS_apunt,"
                                        . ":CAS_notesTarifaBooking,"
                                        . ":CAS_notesTarifaGestio,"
                                        . ":CAS_asseguranca,"
                                        . ":CAS_gestioIntegral,"
                                        . ":CAS_link)"
                                );
                                $stmt->execute(
                                        array(
                                            ':CAS_codi' => $_POST['CAS_codi'],
                                            ':CAS_nom' => $_POST['CAS_nom'],
                                            ':CAS_idPropietari' => $pro->PRO_id,
                                            ':CAS_notesInternes' => $_POST['CAS_notesInternes'],
                                            ':CAS_notesGestors' => $_POST['CAS_notesGestors'],
                                            ':CAS_portals' => $_POST['CAS_portals'],
                                            ':CAS_places' => $_POST['CAS_places'],
                                            ':CAS_codiTipus' => $_POST['CAS_codiTipus'],
                                            ':CAS_codiLlicencia' => $_POST['CAS_codiLlicencia'],
                                            ':CAS_codiControl' => $_POST['CAS_codiControl'],
                                            ':CAS_codiIlla' => $_POST['CAS_codiIlla'],
                                            ':CAS_codiZona' => $_POST['CAS_codiZona'],
                                            ':CAS_nomNorm' => $_POST['CAS_nomNorm'],
                                            ':CAS_segonNom' => $_POST['CAS_segonNom'],
                                            ':CAS_activada' => $_POST['CAS_activada'],
                                            ':CAS_idGestor' => $_POST['CAS_idGestor'],
                                            ':CAS_dataCaducitatContracte' => $_POST['CAS_dataCaducitatContracte'],
                                            ':CAS_capsa' => $_POST['CAS_capsa'],
                                            ':CAS_apunt' => $_POST['CAS_apunt'],
                                            ':CAS_notesTarifaBooking' => $_POST['CAS_notesTarifaBooking'],
                                            ':CAS_notesTarifaGestio' => $_POST['CAS_notesTarifaGestio'],
                                            ':CAS_asseguranca' => $_POST['CAS_asseguranca'],
                                            ':CAS_gestioIntegral' => $_POST['CAS_gestioIntegral'],
                                            ':CAS_link' => $_POST['CAS_link']
                                        )
                                );
                                echo '<script>alert("Formulari enviat correctament")</script>';
                                $codi = $_POST['CAS_codi'];
                                $stmt = $pdoCore->db->prepare(
                                        "SELECT * FROM CASA WHERE CAS_codi= '$codi'"
                                );
                                $stmt->execute();
                                $casa = $stmt->fetchObject();
                                $CODI = $casa->CAS_id;

                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO DADES_CASA (DAD_codicasa)VALUES($CODI)");

                                $stmt->execute();
                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO GESTIO (GES_codicasa)VALUES($CODI)");

                                $stmt->execute();

                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INFORMACIO_BASICA (INF_codicasa)VALUES($CODI)");

                                $stmt->execute();

                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO ENTORN_VISTES (ENT_codicasa)VALUES($CODI)");
                                $stmt->execute();

                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO PISCINA_JARDI (PIS_codicasa)VALUES($CODI)");
                                $stmt->execute();

                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO EQUIPAMENT (EQU_codicasa)VALUES($CODI)");
                                $stmt->execute();

                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO CUINA (CUI_codicasa)VALUES($CODI)");

                                $stmt->execute();
                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO SALA (SAL_codicasa)VALUES($CODI)");

                                $stmt->execute();
                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO MENJADOR (MEN_codicasa)VALUES($CODI)");

                                $stmt->execute();
                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO DORMITORI (DOR_codicasa)VALUES($CODI)");

                                $stmt->execute();
                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO BANY (BAN_codicasa)VALUES($CODI)");

                                $stmt->execute();
                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO BUGADERIA (BUG_codicasa)VALUES($CODI)");

                                $stmt->execute();
                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO GENERAL (GEN_codicasa)VALUES($CODI)");

                                $stmt->execute();

                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO INFORMACIO_ADICIONAL (INF_codicasa)VALUES($CODI)");

                                $stmt->execute();

                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO DISTANCIES (DIS_codicasa)VALUES($CODI)");

                                $stmt->execute();
                                $stmt = $pdoCore->db->prepare(
                                        "INSERT INTO DESCRIPCIONS (DES_codicasa)VALUES($CODI)");

                                $stmt->execute();
                            }
                            $stmt = $pdoCore->db->prepare(
                                    "SELECT * FROM CASA "
                            );
                            $stmt->execute();


                            $cas = $stmt->fetchObject();
                            ?>
                            <div id="formulari" class="col-lg-12">
                                <form action = "#" method = "post">
                                    <table class = "table table-responsive">
                                        <th colspan ="3" class="success">Afegir casa</th>
                                    </table>


                                    <div class="row">
                                        <div class= "col-xs-12 col-md-12 col-lg-4">
                                            <label>Codi de la casa: </label>
                                            <input type = "text" name = "CAS_codi" class = "form-control" pattern = "[ABCD]{1,1}[0-9]{4,4}[QIL]{1,1}[VGR]{1,1}[MSG]{1,1}[0-9]{2,2}">
                                        </div>

                                        <div class= "col-xs-12 col-md-6 col-lg-4">
                                            <label>Nom de la casa:</label>
                                            <input type="text" name = "CAS_nom" class = "form-control">
                                        </div>



                                        <div class= "col-xs-12 col-md-6 col-lg-4">
                                            <label>Propietari: </label>
                                            <input type="text" id="paraules" name="CAS_idPropietari" class = "form-control" required = "required">
                                        </div>

                                        <div class= "col-xs-4 col-md-2 col-lg-4 ">
                                            <label> Plaçes: </label>
                                            <input type="number" name="CAS_places" min="0" max="50" class = "form-control number">
                                        </div>


                                        <div class= "col-xs-4 col-md-2 col-lg-1">
                                            <label>Tipus: </label>
                                            <select name="CAS_codiTipus" class="form-control number">
                                                <option value="A">A</option>
                                                <option Value="B">B</option>
                                                <option value="C">C</option>
                                                <option Value="D">D</option>

                                            </select>
                                        </div>
                                        <div class= "col-xs-4 col-md-2 col-lg-1">
                                            <label>Llicencia: </label>
                                            <select name="CAS_codiLlicencia" class="form-control number">
                                                <option value="L">L</option>
                                                <option Value="I">I</option>
                                                <option value="Q">Q</option>
                                            </select>
                                        </div>




                                        <div class= "col-xs-4 col-md-2 col-lg-1">
                                            <label>Control: </label>
                                            <select name="CAS_codiControl" class="form-control number">
                                                <option value="V">V</option>
                                                <option Value="G">G</option>
                                                <option value="R">R</option>
                                            </select>

                                        </div>
                                        <div class= "col-xs-4 col-md-2 col-lg-1">
                                            <label>Codi Illa: </label>
                                            <select name="CAS_codiIlla" class="form-control number">
                                                <option value="M">M</option>
                                                <option Value="S">S</option>
                                                <option value="G">G</option>
                                            </select>

                                        </div>

                                        <div class= "col-xs-4 col-md-2 col-lg-4">
                                            <label>Codi zona: </label>
                                            <input type="number" min="0" max="15" class="form-control number" name="CAS_codiZona">
                                        </div>


                                        <div class= "col-xs-12  col-md-6 col-lg-4">
                                            <label>Nom normal: </label>
                                            <input type="text" class="form-control" name="CAS_nomNorm" title="Nom de la casa"/>
                                        </div>
                                        <div class= "col-xs-12 col-md-6 col-lg-3">
                                            <label>Segon nom: </label>
                                            <input type="text" class="form-control" name="CAS_segonNom">
                                        </div>

                                        <div class= "col-xs-12 col-md-4 col-lg-3">
                                            <label>Gestor: </label>
                                            <select name="CAS_idGestor" class="form-control numberb">
                                                <?php
                                                $stmt = $pdoCore->db->prepare(
                                                        "SELECT * FROM GESTOR  "
                                                );
                                                $stmt->execute();

                                                $result = $stmt->fetchAll();
                                                foreach ($result as $key => $gest) {
                                                    echo '<option value="' . $gest->GES_id . '">' . $gest->GES_nom . '</option>';
                                                }
                                                ?>

                                            </select>

                                        </div>
                                        <div class= "col-xs-12 col-md-8 col-lg-2">
                                            <label>Activada: </label><select name="CAS_activada" class="form-control number">
                                                <option value="0">No</option>
                                                <option Value="1">Si</option>
                                            </select>
                                        </div>
                                        <div class= "col-xs-12 col-md-12 col-lg-12">
                                            <label>Portals: </label><textarea name="CAS_portals" class = "form-control" title="Web"></textarea>
                                            </select>
                                        </div>
                                        <div class= "col-xs-12 col-md-12 col-lg-12">
                                            <label>Notes internes: </label><textarea name="CAS_notesInternes" class = "form-control"></textarea>
                                            </select>
                                        </div>
                                        <div class= "col-xs-12 col-md-12 col-lg-12">
                                            <label>Notes gestors: </label><textarea name="CAS_notesGestors" class = "form-control"></textarea>                                            </select>
                                        </div>
                                        <div class= "col-xs-12 col-md-12 col-lg-4">
                                            <label>Data caducitat contracte: &nbsp;</label>
                                            <input type="date" min="2010-01-02" class = "form-control1" name="CAS_dataCaducitatContracte"/>
                                        </div>
                                        <div class= "col-xs-12 col-md-12 col-lg-2">
                                            <input type="checkbox" name ="CAS_capsa" class = "form-control2l"><label> Capsa</label>
                                        </div>
                                        <div class= "col-xs-12 col-md-12 col-lg-2">
                                            <input type="checkbox" name ="CAS_asseguranca" class = "form-control2l" ><label> Assegurança</label>
                                        </div>

                                        <div class= "col-xs-12 col-md-12 col-lg-2">
                                            <input type="checkbox" name ="CAS_gestioIntegral" class = "form-control2l" ><label> Gestió integral</label>
                                        </div>
                                        <div class= "col-xs-12 col-md-12 col-lg-12">
                                            <label>Notes Tarifa booking: </label><textarea class = "form-control" name="CAS_notesTarifaBooking"></textarea>
                                        </div>
                                        <div class= "col-xs-12 col-md-12 col-lg-12">
                                            <label>Notes Tarifa gestió: </label><textarea class = "form-control" name="CAS_notesTarifaGestio"></textarea>
                                        </div>
                                        <div class= "col-xs-12 col-md-12 col-lg-12">
                                            <label>Link: </label><textarea class = "form-control" name="CAS_link"></textarea>
                                        </div>

                                    </div> <!-- ROW -->
                            </div> <!-- FORMULARI -->


                            <input type="submit" name="enviar" class = "btn btn-primary" value = "Guardar casa" data-toggle="tooltip" data-placement="right" title="Segur que vol Guardar?">
                            </form>
                        </div>


                    </div>

                </div>





            </div>


    </body>

</html>
