<?php
header('Refresh: 3;url=../index.php');
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Sorry...!</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href='http://fonts.googleapis.com/css?family=Courgette' rel='stylesheet' type='text/css'>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap2-toggle.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap-datepicker3.min.css" rel="stylesheet" media="screen">

    <!-- Custom CSS -->
    <link href="customize-css.css" rel="stylesheet">
    <link href="custome-alignment.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        body {
            font-family: 'Courgette', cursive;
        }

        body {
            background: #ffffff;
        }

        .wrap {
            margin: 0 auto;
            width: 100%;
        }

        .logo {
            margin-top: 100px;
        }

        .logo h1 {
            font-size: 60px;
            color: #8F8E8C;
            text-align: center;
            margin-bottom: 1px;
            text-shadow: 1px 1px 6px #fff;
        }

        .logo p {
            color: rgb(228, 146, 162);
            font-size: 20px;
            margin-top: 1px;
            text-align: center;
        }

        .logo p span {
            color: lightgreen;
        }

        .sub a {
            color: white;
            background: #8F8E8C;
            text-decoration: none;
            padding: 7px 120px;
            font-size: 13px;
            font-family: arial, serif;
            font-weight: bold;
            -webkit-border-radius: 3em;
            -moz-border-radius: .1em;
            -border-radius: .1em;
        }
    </style>
</head>
<body>
<div class="container">

    <div id="top-banner" class="row page-banner">
        <!--header image-->
        <div class="col-lg-8" style="padding: 0px;">
            <!--<img src="../images/pictures/top_banner.jpg" width="876" height="200"> !-->
        </div>
        <!--logo -->
        <div class="col-lg-4 left">
            <img src="../images/w15/logo_w15.png" width="200" height="120" align="right">
        </div>
    </div>
    <!-- /.row -->


    <div class="wrap">
        <div class="row">
            <div class="logo">
                <h1>Sorry... Just sold out or exceed available room type which you choose.</h1>
                <br><br>
                <p style="color: #B6202C">Please choose again.</p>
                <div class="sub">
                    <p><a href="../index.php">Back To Availability</a></p>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>

    <br><br>
    <hr>


    <div id="bottom-banner" class="row page-footer">
        <div class="col-lg-12 text-center">
            <span>© 2015 Inhot Solutions™ Terms & Conditions</span>
        </div>
    </div>
    <!-- /.row -->

</div>
</body>

<?php

?>