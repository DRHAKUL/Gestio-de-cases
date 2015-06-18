<?php
// Principal del calendari

session_start();
// Si no hi ha sesio envia a login.php.
if (!$_SESSION['meva']) { // Comprobacio de sessio.
    header("Location: login.php"); //Si no hi ha sessio "meva", envia a login..
}
require_once 'class/Core.php';
$pdoCore = Core::getInstance();


// Si no hi ha sesio envia a login.php.
if (!$_GET['codicasa']) {
    header("Location: login.php");
}

$codicasa = $_GET['codicasa']; // Codi de la casa de index.php
$dataavui = strftime("%Y-%m-%d", time()); // Obtenir la data d'avui per camp dataBloqueig.


If (isset($_POST['enviar'])) {

    // Afegir reserva a la base de dades.

    $stmt = $pdoCore->db->prepare(
            "INSERT INTO OCUPACIO (OCU_codiCasa, OCU_dataInici, OCU_dataFi, OCU_tipus, OCU_dataBloqueig ) "
            . "VALUES (:codicasa,:inici,:fi,:tipus,:block) "
    );
    $stmt->execute(array(
        ':codicasa' => $codicasa,
        ':inici' => $_POST['datainici'],
        ':fi' => $_POST['datafi'],
        ':tipus' => $_POST['tipus'],
        ':block' => $dataavui
    ));
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
        <script src="js/bootstrap.min.js"></script>

        <title>Calendari Reserves</title>


        <title>RESERVES</title>

        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/jquery-ui.css" rel="stylesheet">
        <link href="css/datepicker.css" rel="stylesheet">

        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <script>

<?php
$pdoCore = Core::getInstance();

// Consulta bbdd
$stmt = $pdoCore->db->prepare(
        "SELECT * FROM OCUPACIO WHERE OCU_codiCasa = :codicasa  "
);
$stmt->execute(array(
    ':codicasa' => $codicasa
));

$result = $stmt->fetchAll();
// Em de fer un array amb les dades pero arriben de manera "datainici,datafi"
// Feim un array per a cada tipus de reserva.
$Reserva = [];
$NoDisponible = [];
$Prepropietari = [];
$Prereserva = [];
$Propietari = [];

foreach ($result as $key => $value) {
    $fechaInicio = $value->OCU_dataInici;
    $fechaFin = $value->OCU_dataFi;
    $tipus = $value->OCU_tipus;


    // Calcula les dates entre els rangs que arriben de la base de dades. fechauno=datainici fechados=datafi i el ficam a un array.
    //$fechaaamostar = $fechauno; //la primera data

    for ($i = strtotime($fechaInicio); $i <= strtotime($fechaFin); $i+=86400) { //Suma els segons que te un dia.
        $fecha = (date("d-m-Y", $i));
        switch ($tipus) {

            case 'Reserva':
                array_push($Reserva, $fecha); // Cada tipus de reserva al seu array.
                break;
            case 'NoDisponible':
                array_push($NoDisponible, $fecha);
                break;
            case 'Prepropietari':
                array_push($Prepropietari, $fecha);
                break;

            case 'Prereserva':
                array_push($Prereserva, $fecha);
                break;
            case 'Propietari':
                array_push($Propietari, $fecha);
                break;
        }
    }//for
}//foreach
?>
            // Un event per a cada tipus de reserva

            var event1 = <?php echo json_encode($Reserva); ?>;
            var event2 = <?php echo json_encode($NoDisponible); ?>;
            var event3 = <?php echo json_encode($Prepropietari); ?>;
            var event4 = <?php echo json_encode($Prereserva); ?>;
            var event5 = <?php echo json_encode($Propietari); ?>;

            var idcasa = <?php echo json_encode($codicasa); ?>;
            $(document).ready(function () {

                //Jquery per el datepicker.
                //
                // Canviam la configuració del datepicker per posar en catalá
                $.datepicker.setDefaults({
                    numberOfMonths: [3, 4],
                    defaultDate: new Date(2015, 0, 1),
                    changeYear: true,
                    closeText: 'tancar',
                    prevText: '<Ant',
                    nextText: 'Seg>',
                    currentText: 'Avui',
                    monthNames: ['Gener', 'Febrer', 'Març', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Desembre'],
                    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    dayNamesShort: ['Dm', 'Dl', 'Dm', 'Dx', 'Dj', 'Dv', 'Ds'],
                    dayNamesMin: ['Dm', 'Dl', 'Dm', 'Dx', 'Dj', 'Dv', 'Ds'],
                    weekHeader: 'Sm',
                    dateFormat: 'dd/mm/yy',
                    firstDay: 1,
                    inline: true,
                    range: true,
                    isRTL: false,
                    showMonthAfterYear: false,
                    yearSuffix: ''
                });

                $("#datepicker").datepicker({
                    beforeShowDay: function (date) { // diferents events per a cada tipus de reserva amb el seu css.
                        var current = $.datepicker.formatDate('dd-mm-yy', date);
                        var index1 = $.inArray(current, event1);
                        if (index1 > -1) {
                            return [true, 'dp-highlightreserva', 'Reserva'];
                        }
                        var index2 = $.inArray(current, event2);
                        if (index2 > -1) {
                            return [true, 'dp-highlightnodis', 'No disponible'];
                        }
                        var index3 = $.inArray(current, event3);
                        if (index3 > -1) {
                            return [true, 'dp-highlightprepro', 'Pre-reserva propietari'];
                        }
                        var index4 = $.inArray(current, event4);
                        if (index4 > -1) {
                            return [true, 'dp-highlightpreres', 'Pre-reserva'];
                        }
                        var index5 = $.inArray(current, event5);
                        if (index5 > -1) {
                            return [true, 'dp-highlightprop', 'Propietari'];
                        }

                        return [index5 == -1];
                    },
                    onSelect: function (date) {

                        // Funcio ajax per enviar data a dadesreserva.php
                        $.ajax({
                            type: "POST",
                            url: "dadesreserva.php",
                            data: {data: date, idcasa: idcasa},
                            dataType: "html",
                            beforeSend: function () {
                                console.log('envia');
                            },
                            error: function () { // En cas d'error
                                alert("error petición ajax");
                            },
                            // Retorna el resultat de la cerca.
                            success: function (data) { 
                                console.log(data);                                                  
                                // mostra la resposta al div machacant cada vegada.
                                $("#dialeg").html(data);
                            }
                        }); //ajax
                        //fica el div dialeg a la capsa dialog.
                        $('#dialeg').dialog({
                            //modal: true,
                            width: 550,
                            resizable: true,
                            minWidth: 400,
                            maxWidth: 650,
                            show: "fold",
                            hide: "scale"
                        });
                    } //onSelect

                }); // Datepicker
                $("div.ui-datepicker-header a.ui-datepicker-prev,div.ui-datepicker-header a.ui-datepicker-next").hide();
            }); // Document



        </script>
        <style>
            .container-fluid{
                height: 790px;
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

            <div id="page-wrapper2">

                <div class="container-fluid">

                    <div id = "dialeg" title="Dades Reserva" style="display: none;"></div>

                    <?php
                    $stmt = $pdoCore->db->prepare(
                            "SELECT * FROM CASA  WHERE CAS_codi = :casaid "
                    );
                    $stmt->execute(
                            array(
                                ':casaid' => $codicasa
                            )
                    );
                    $casa = $stmt->fetchObject();
                    echo '<table class = "table">';
                    echo '<th colspan ="3" class="warning">Calendari de reserves de la Casa: ' . $casa->CAS_nom . ' amb codi: ' . $casa->CAS_id . ' </th>';
                    echo '</table>';
                    ?>
                    <!--                    Div per el datepicker                  -->
                    <div id="datepicker" class="col-md-12 col-lg-10"></div>


                    <!-- Llegenda dels colors -->
                    <div id = "formularidates"class="col-md-12 col-lg-2">
                        <ul class="list-groupb">
                            <li class="list-group-item list-group-item-successb">Reserva propietari</li>
                            <li class="list-group-item list-group-item-infob">Reserva</li>
                            <li class="list-group-item list-group-item-warningb">Pre-reserva</li>
                            <li class="list-group-item list-group-item-dangerb">No disponible</li>
                            <li class="list-group-item list-group-item-danger2">Pre-reserva propietari</li>
                        </ul>


                        <?php
                        // formulari noves reserves.


                        echo '<form action="#" method="post">';
                        echo '<table class="table table-responsive">';
                        echo '<thead>';
                        echo '<tr><th><h4>Nova Reserva</h4></th></tr>';
                        echo '</thead><tbody>';
                        echo '<tr><td>Data inici: </td></tr>';
                        echo '<tr><td><input type="date" name ="datainici" class = "form-control"></td></tr><br><br>';
                        echo '<tr><td>Data fi: </td></tr>';
                        echo '<tr><td><input type="date" name ="datafi" class = "form-control"></td></tr><br>';
                        echo '<tr><td>Tipus Reserva<select name ="tipus" class = "form-control">';
                        echo '<option value ="Reserva">Reserva</option>';
                        echo '<option value ="Prereserva">Pre-reserva</option>';
                        echo '<option value ="NoDisponible">No Disponible</option>';
                        echo '<option value ="Propietari">Propietari</option>';
                        echo '<option value ="Prepropietari">Pre-propietari</option>';
                        echo '</select></td></tr><br>';
                        echo '<tr><td><input type="submit" name="enviar" value="Guardar Reserva" '
                        . 'class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Segur que vol guardar la reserva?"></td></tr>';
                        echo '</tbody></table>';
                        echo '</form>';
                        ?>

                    </div>
                </div>
            </div>


        </div>

    </div>


</body>

</html>
