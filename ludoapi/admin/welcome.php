<?php
include 'config.php';
//include 'functions.php';
ob_start();
$session = session_start();
$uid = $_SESSION['uid'];
if ($uid == '') {
    echo "<script type='text/javascript'>window.location.href = ' index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Welcome </title>

    <!-- Tell the browser to be responsive to screen width -->

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.7 -->

    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->

    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">

    <!-- Ionicons -->

    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">

    <!-- Theme style -->

    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">

    <!-- AdminLTE Skins. Choose a skin from the css/skins

         folder instead of downloading all of them to reduce the load. -->

    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->



    <!-- Google Font -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>

<body class="hold-transition skin-blue sidebar-mini">

    <div class="wrapper">

        <?php include 'header.php'; ?>

        <!-- Content Wrapper. Contains page content -->

        <div class="content-wrapper" style="min-height: 837px;">

            <!-- Content Header (Page header) -->

            <section class="content-header">

                <h1>

                    Welcome Admin !
                </h1>

                <ol class="breadcrumb">

                    <li><a href="http://appdroidsolutions.com/ludomoney/admin/Welcome/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>

                    <li><a href="#">User</a></li>

                    <li class="active">UpdateUser</li>

                </ol>

            </section>



            <!-- Main content -->

            <section class="content">

             <div class="row text-center justify-content-center">
                    <div class="col-md-4 col-xl-3 mb-4">
                        <div class="card shadow border-left-primary py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col mr-2">
                                        <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>Transaction (today)</span></div>
                                        <div class="text-dark font-weight-bold h5 mb-0"><span style='color:green'><?php  $sqlt = mysqli_query($conn, "SELECT sum(amount) as total FROM payment where trnstype='desposite' and status='Success' and  DATE(datetime) = CURDATE()");
										$rest = mysqli_fetch_assoc($sqlt);										
										 echo $rest['total']; ?></span></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-rupee-sign fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xl-3 mb-4">
                        <div class="card shadow border-left-success py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col mr-2">
                                        <div class="text-uppercase text-success font-weight-bold text-xs mb-1"><span>
										<?php  $sql = mysqli_query($conn, "SELECT sum(amount) as total FROM payment where trnstype='withdrawal' and  DATE(datetime) = CURDATE()");
										$res = mysqli_fetch_assoc($sql);										
										?>
										
										
										withdrawal (today)</span></div>
                                        <div class="text-dark font-weight-bold h5 mb-0"><span style='color:red'><?php echo $res['total']; ?></span></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-rupee-sign fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xl-3 mb-4">
                        <div class="card shadow border-left-success py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col mr-2">
                                        <div class="text-uppercase text-success font-weight-bold text-xs mb-1"><span>new user(today)</span></div>
                                        <div class="text-dark font-weight-bold h5 mb-0"><span>
										<?php  $sqlu = mysqli_query($conn, "SELECT count(*) as total FROM users where DATE(registerd	) = CURDATE()");
										$resu = mysqli_fetch_assoc($sqlu);										
										?>
										<?php echo $resu['total']; ?></span></div>
                                    </div>
                                    <div class="col-auto"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- right column -->

                 <div class="row text-center justify-content-center" style="margin-top: 117px;">
                    <div class="col-sm-3 mb-4"><button class="btn btn-primary" type="button" style="width: 204px;height: 94px;" onclick="window.location.href='active_tournament.php'">Active Tournament</button></div>
                    <div class="col-sm-3 mb-4"><button class="btn btn-primary" type="button" style="width: 204px;height: 94px;" onclick="window.location.href='closed_tournament.php'">Closed Tournament</button></div>
                    <div class="col-sm-3 mb-4"><button class="btn btn-primary" type="button" style="width: 204px;height: 94px;" onclick="window.location.href='create_tournament.html'">Create Tournament</button></div>
                    <div class="col-sm-3 mb-4"><button class="btn btn-primary" type="button" style="width: 204px;height: 94px;" onclick="window.location.href='create_notification.html'">Set Notification</button></div>
                    <div class="col-sm-3 mb-4"><button class="btn btn-primary" type="button" style="width: 204px;height: 94px;" onclick="window.location.href='bot_setting.php'">BOT Setting</button></div>
                    <div class="col-sm-3 mb-4"><button class="btn btn-primary" type="button" style="width: 204px;height: 94px;" onclick="window.location.href='free_game.php'">Free Game Bonus</button></div>
                    <div class="col-sm-3 mb-4"><button class="btn btn-primary" type="button" style="width: 204px;height: 94px;" onclick="window.location.href='occasion_bonus.php'">Occasion Bonus</button></div>
                    <div class="col-sm-3 mb-4"><button class="btn btn-primary" type="button" style="width: 204px;height: 94px;" onclick="window.location.href='pop_not_set_page.php'">POP Notification</button></div>
                    <div class="col-sm-3 mb-4"><button class="btn btn-primary" type="button" style="width: 204px;height: 94px;" onclick="window.location.href='feedback_msg.php'">Feedback</button></div>
                    <div class="col-sm-3 mb-4"><button class="btn btn-primary" type="button" style="width: 204px;height: 94px;" onclick="window.location.href='image_pop_status.php'">Image/Text Status</button></div>
                    <div class="col-sm-3 mb-4"><button class="btn btn-primary" type="button" style="width: 204px;height: 94px;" onclick="window.location.href='image_upload.php'">Image Upload</button></div>
                    <div class="col-sm-3 mb-4"><button class="btn btn-primary" type="button" style="width: 204px;height: 94px;" onclick="window.location.href='t&amp;c.php'">Terms And Conditions</button></div>
                </div>

                    <!--/.col (right) -->

                

                <!-- /.row -->

            </section>

            <!-- /.content -->

        </div>

        <!-- /.content-wrapper -->

        <footer class="main-footer">

            <div class="pull-right hidden-xs">

                <b>Version</b> 2.0

            </div>

            <strong>Copyright &copy; 2017-2018 <a href="#">LudoGame</a>.</strong> All rights

            reserved.

        </footer>


        <!-- Control Sidebar -->



        <!-- /.control-sidebar -->

        <!-- Add the sidebar's background. This div must be placed

         immediately after the control sidebar -->

        <div class="control-sidebar-bg"></div>

    </div>

    <!-- ./wrapper -->



    <!-- jQuery 3 -->

    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap 3.3.7 -->

    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- FastClick -->

    <script src="bower_components/fastclick/lib/fastclick.js"></script>

    <!-- AdminLTE App -->

    <script src="dist/js/adminlte.min.js"></script>

    <!-- AdminLTE for demo purposes -->

    <script src="dist/js/demo.js"></script>

</body>



<!-- Mirrored from adminlte.io/themes/AdminLTE/pages/forms/general.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 23 Jan 2018 05:23:40 GMT -->

</html>