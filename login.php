<?php
// Formulari de login.
session_start();
require_once 'class/Core.php';
$pdoCore = Core::getInstance();
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
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>


        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <title>Pagina de login</title>

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

            <!--            <div class="breadcrumb">-->
            <!--                <div class="container">-->
            <div class="row">
                <p></p>
                <br><br><br><br>
            </div>
            <div class="col-md-6 col-md-offset-2">
                <div class="login-panel panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">Entrada</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" action="login.php">
                            <fieldset>
                                <div class="form-group"  >
                                    Usuari: <input class="form-control" placeholder="Name" name="name" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    Contrasenya: <input class="form-control" placeholder="Password" name="pass" type="password" value="">
                                </div>
                                <?php
                                // Funcio per validar usuaris de la bbdd.

                                if (isset($_POST['entrar'])) {
                                    $user_name = $_POST['name']; //nom
                                    $user_pass = $_POST['pass']; //password


                                    $stmt = $pdoCore->db->prepare(
                                            "SELECT * from USERS WHERE user_name=:name "
                                            . "AND user_pass=:pass ");
                                    $stmt->execute(
                                            array(
                                                ':name' => $user_name,
                                                ':pass' => $user_pass
                                            )
                                    );

                                    $contar = $stmt->rowCount();
                                    // Si es troba algun resultat entra i crea variable sessió.
                                    // Si no hi ha resultats no son correctes les dades.
                                    if ($contar > 0) {
                                        echo "<script>window.open('index.php','_self')</script>";
                                        $_SESSION['meva'] = $user_name;
                                    } else {
                                        echo "<script>alert('EL Nom " . $user_name . " o la contrasenya son incorrectes!')</script>";
                                    }
                                }
                                ?>

                                <input class="btn btn-lg btn-success btn-block" type="submit" value="entrar" name="entrar" >


                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>


    </body>

</html>




