<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Gateway Supply Chain Services</title>
    <!-- Favicon -->

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
        
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Main Style Sheet -->
    <link rel="stylesheet" type='text/css' href="<?= secure_url('css/gateway.min.css'); ?>">

    <!-- For prod-->

    <!-- Vendor Styles Sheet -->
    <link rel="stylesheet" type='text/css' href="<?= secure_url('vendors/intlTelInput.css'); ?>">
    <link rel="stylesheet" type='text/css' href="<?= secure_url('vendors/styling.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= secure_url('vendors/dd.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= secure_url('vendors/flags.css'); ?>">

    <!-- FontAwesome -->
    <link rel="stylesheet" type='text/css' href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Fonts -->
    <link rel="stylesheet" type='text/css' href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.9.0/loading-bar.css" rel="stylesheet" type="text/css"/>

    <script>
        window.logoutUrl='<?= getenv("OAUTH_LOGOUT_URL") ?>';
        window.clientId='<?= getenv("OAUTH_BASE_CLIENT_ID") ?>';
    </script>
    </head>
    <body ng-class="{'bgwhite' : !user.contractSigned}">


      <!-- Navigation -->
      <toolbar></toolbar>
      <!-- Navigation Ends-->


        <main ng-view="" class="bd-gateway" resize id="mainViewId" style="min-height: 575px;">

        </main>
        <footertmpl></footertmpl>



        <!-- Including jQuery  -->
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
<!--        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
        <!-- Including jQuery Cycle  -->


        <!-- <script type="text/javascript" src="https://cdn.auth0.com/js/lock/10.8/lock.min.js"></script> -->
        <script data-main="<?= secure_url('js/bd/require-config'); ?>" src="<?= secure_url('node_modules/require/require.js'); ?>"></script>

        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.cycle2/2.1.6/jquery.cycle2.js"></script>-->
<!--        <script type="text/javascript" src="js/gateway.js"></script>
        <script type="text/javascript" src="js/jquery.dd.min.js"></script>-->
        <script>


          function signout() {
                localStorage.removeItem('token');
                localStorage.removeItem('profile');
                window.location.href = window.logoutUrl+"?returnTo=" + window.location.origin + "/logout&client_id="+window.clientId;                        
              }
      </script>
    </body>
</html>
