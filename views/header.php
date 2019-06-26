<!DOCTYPE html>
<html>
    <head>
        <title>Yavneh Yeshiva</title>
        <link rel="stylesheet" href="<?php echo config::URL ?>/public/default.css">
        <script src="<?php echo config::URL ?>/libs/jquery.min.js"></script>
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" type="application/javascript"></script>-->
        <!--<script>-->
<!--            $(function(){
            $("#inp6").click(function(e){
            if(!confirm('Are you sure?')){
            e.preventDefault();
            return false;
            }
            return true;
            });
        });-->
    <!--</script>-->
    
    </head>
    <body>
        <div id="header">
        <img src = "<?php echo config::URL ?>/public/yavneh.jpg" alt="Yavneh" width="30">
                <?php if (Session::get('loggedIn')): ?>
                <a href="<?php echo config::URL ?>/Yeshiva">Yeshiva</a>
                   <?php if (Session::get('role') == 'Owner' || Session::get('role') == 'Manager'): ?>
                    <a href="<?php echo config::URL ?>/Administration">Users</a>
                   <?php endif ?>                 
                    <a href="<?php echo config::URL ?>/login/logout">Logout</a>
                <?php else: ?>
                    <a href="<?php echo config::URL ?>/login/login">Login</a>
                <?php endif ?>
                    <span style="float: right;"><?php echo Session::get('name'); ?>, <?php echo Session::get('role'); ?><img src="<?php echo Config::URL; ?>/<?php echo Session::get('image'); ?>" alt="" width="30" height="25"></span>
                    <!--<span style="float: right;"></span>-->
        </div>
        <hr>
        <!--<div id="content">-->
        <!--</div>-->

