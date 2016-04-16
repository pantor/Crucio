<?php
    error_reporting(0);
    require 'api/v1/helper.php';
    $token = $_GET['token'];

    $response = activate($token);

    $errorNoToken = (strlen($token) == 0);
    $errorUnknown = ($response['error'] == 'error_unknown');
    $error = $errorNoToken || $errorUnknown;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Account Aktivierung | Crucio</title>
        <?php include('parts/header.php'); ?>
    </head>

    <body class="body">
        <div class="wrap">
            <?php include('parts/container-top-bar.php'); ?>

            <?php
                $param = ["fa" => "fa-user", "h4" => "Account Aktivieren", "p" => ""];
                include('parts/container-title.php');
            ?>

            <div class="container">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <?php if ($error) { ?>
                        <div class="container-center-align-sm" style="padding: 60px;">
                            <h3>Fehler bei der Aktivierung.</h3>

                            <hr>

                            <div class="alert alert-danger">
                            <?php if ($errorNoToken) { ?>
                                Der Schl&uuml;ssel konnte deinen Account nicht aktivieren. Wir haben einfach keinen Schl&uuml;ssel gefunden.
                            <?php } else if ($errorUnknown) { ?>
                                Der Schl&uuml;ssel konnte deinen Account nicht aktivieren. <br> Entweder passt der Schl&uuml;ssel nicht oder dein Account ist bereits aktiviert.
                            <?php } ?>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if (!$error) { ?>
                        <div class="container-center-align-sm" style="padding: 60px;">
                            <div class="alert alert-success">
                                Dein Account ist aktiviert und deine E-Mail-Adresse best&auml;tigt. Willkommen bei Crucio!
                            </div>

                            <a class="btn btn-success" target="_self" href="/">Zur Anmeldung</a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <?php include('parts/footer.php'); ?>
    </body>
</html>
