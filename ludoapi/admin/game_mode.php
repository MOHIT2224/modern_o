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

    <title>Ludo Money | Add User</title>

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

                    Change Game Mode
                </h1>

                <ol class="breadcrumb">

                    <li><a href="http://appdroidsolutions.com/ludomoney/admin/Welcome/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>

                    <li><a href="#">User</a></li>

                    <li class="active">UpdateUser</li>

                </ol>

            </section>



            <!-- Main content -->

            <section class="content">

                <div class="row">

                    <!-- right column -->

                    <div class="col-md-12">

                        <!-- Horizontal Form -->

                        <div class="box box-info">

                            <div class="box-header with-border">

                                <h3 class="box-title"> Change Game Mode </h3>


                            </div>

                            <!-- /.box-header -->

                            <!-- form start -->
							
                            <?PHP

                            if (isset($_POST['submit'])) {
                                $game_mode = $_POST['game_mode'];
                                mysqli_query($conn, "update game_mode set is_active='0'");
                                $sql = mysqli_query($conn, "update game_mode set is_active='1' where id='" . $game_mode . "'");
                                $smsg = "Update sucessfully";
                            }
                             $sql = mysqli_query($conn, "SELECT * FROM game_mode");
                             
                            

                            ?>

                            <form action="" name="menu_form" method="post" enctype="multipart/form-data" accept-charset="utf-8">


                                <div class="form-horizontal">

                                    <div class="box-body">

                                        <?PHP if (@$emsg) : ?>
                                            <div class="alert alert-danger" role="alert">
                                                <?= $emsg ?>
                                            </div>
                                        <?php endif; ?>
                                        <?PHP if (@$smsg) : ?>
                                            <div class="alert alert-success" role="alert">
                                                <?= $smsg ?>
                                            </div>
                                        <?php endif; ?>

                                         <?PHP 
                                           while($res = mysqli_fetch_assoc($sql)){
                                         ?>
                                        <div class="form-group">
                                            <label for="category" class="col-sm-3 control-label"><?=$res['type']?></label>
                                            <div class="col-sm-6">
                                                <input type="radio" required  id="gmode" placeholder="gmode" <?=(($res['is_active'])?"checked":"")?> name="game_mode" value="<?=$res['id']?>">
                                            </div>
                                        </div>
                                           <?PHP 
                                           }
                                            ?>
                                       





                                    </div>

                                    <!-- /.box-body -->

                                </div>

                                <div class="box-footer">

                                    <button type="submit" id="submittrns" name="submit" value="submit" class="btn btn-info">Submit</button>


                                </div>

                                <!-- /.box-footer -->

                            </form>
                        </div>

                        <!-- /.box -->

                    </div>

                    <!--/.col (right) -->

                </div>

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