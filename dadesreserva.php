<?php

// Fa una cerca per el dialeg de les dates del calendari.
//
//
// Fa una cerca de OCUPACIO i retorna una taula amb els resultats
require_once 'class/Core.php';

// Recull en variables les dades d'ajax.

session_start();


if (!$_POST['data']) {
    header("Location: login.php");
}
$fecha = $_POST['data'];
$idcasa = $_POST['idcasa'];


// Consulta taula  ocupacio
$pdoCore = Core::getInstance();
$stmt = $pdoCore->db->prepare(
        "SELECT * FROM OCUPACIO WHERE OCU_codiCasa = :id "
);
$stmt->execute(
        array(
            ':id' => $idcasa
        )
);

$result = $stmt->fetchAll();

// Recorrem el resultat per veure la data inici i la data fi.
foreach ($result as $key => $value) {
    $fechauno = $value->OCU_dataInici;
    $fechados = $value->OCU_dataFi;

    $fechaInicio = $value->OCU_dataInici;
    $fechaFin = $value->OCU_dataFi;
    for ($i = strtotime($fechaInicio); $i <= strtotime($fechaFin); $i+=86400) { //Recorrem dia a dia (timestamp)
        if (date("d/m/Y", $i) == $fecha) { //Si la data senyalada es la trobada, consultam la d'inici.
            $stmt = $pdoCore->db->prepare(
                    "SELECT * FROM OCUPACIO WHERE OCU_dataInici = :data and OCU_codiCasa = :casa "
            );
            $stmt->execute(
                    array(
                        ':data' => $fechaInicio,
                        ':casa' => $idcasa
                    )
            );
            $ocu = $stmt->fetchObject();
            echo '<table class="table">';
            echo '<tr><th>Inici Reserva</th><th>Fi de Reserva</th><th>Tipus de Reserva </th><th>Data Bolqueig</th></tr>';
            echo '<tr><td>' . $ocu->OCU_dataInici . '</td><td>' . $ocu->OCU_dataFi . '</td><td>' . $ocu->OCU_tipus . '</td>'
            . '<td>' . $ocu->OCU_dataBloqueig . '</td></tr>';
            echo '</table>';
        }
    }
}



