<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>Gateway Supply Chain Services</title>
        <!-- Favicon -->
        <link rel="icon" href="<?= secure_url('img/favicon.gif') ?>">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?= secure_url('css/bootstrap.min.css') ?>">
        <!-- Main Style Sheet -->
        <link rel="stylesheet" type='text/css' href="<?= secure_url('css/gateway.min.css') ?>">
        <!-- Vendor Styles Sheet -->
        <link rel="stylesheet" type='text/css' href="<?= secure_url('vendors/intlTelInput.css') ?>">
        <link rel="stylesheet" type='text/css' href="<?= secure_url('vendors/styling.css') ?>">
        <!-- FontAwesome -->
        <link rel="stylesheet" type='text/css' href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Fonts -->
        <link rel="stylesheet" type='text/css' href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i">
    </head>
    <body class="bg-registration">
        <div class="loading--gateway" style="display: block;">
            <div class="icon-sent"></div>
        </div>
        <!-- Main Starts Herer -->
        <main class="bd-gateway" ng-app="activationApp">
            <div class="gateway--wrapper__full gateway--registration" ng-controller="validationController">
                <div class="container-fluid">

                    <div class="row">
                        <div class="mobile--logo__register"></div>

                        <!--About you Section Starts-->
                        <div class="create--account">

                            <?php if($hasError) {?>
                            <div class="create--account__padding">
                                <div class="col-md-12 error--two">
                                    <!--<div class="glyphicon glyphicon-remove-circle error--message__icon"></div>-->

                                    <h3 class="error--message"><?= $errorMessage; ?></h3>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <!--About you Section Ends-->

                    </div>

                </div>
            </div>
        </main>
        <!-- Main Ends Here -->
        <!-- Footer Starts Here -->
        @include('footer')
        <!-- Footer Ends Here -->
        <!-- Including jQuery  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>
            (function () {
                $('.loading--gateway').hide();
            }());
        </script>
        <?php if(!$hasError) { ?>
        <script>

            (function () {

                var gwauthToken = "<?= $authToken; ?>";
                localStorage.setItem('token', '"'+gwauthToken+'"');
                window.location.href = "/home";
            }());
        </script>
        <?php } ?>
    </body>
</html>
