<!DOCTYPE html>
<html lang="en">
<head>
    <title>ModFarm</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <link rel="shortcut icon" type="image/png" href="../images/logo.png"/>

    <link rel="manifest" href="manifest.webmanifest">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://use.fontawesome.com/58a3479a64.js"></script>
    <script src='../js/ajax_custom.js'></script>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/">
        <img src="../images/logo.png" alt="Logo" style="width:40px;">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link disabled"><?php echo ucfirst(User::g('user_name')); ?></a>
            </li>
            <?php
            if (User::userMinimalAccessLevel('admin')) { ?>
                <li class="nav-item active">
                    <a class="nav-link" href="/admin">Admin</a>
                </li>
            <?php } ?>
            <?php
            if (User::userMinimalAccessLevel('user')) { ?>
                <li class="nav-item active">
                    <a class="nav-link" href="/download">Download</a>
                </li>
            <?php } ?>
            <?php
            if (User::session_exists()) { ?>
                <li class="nav-item active">
                    <a class="nav-link" href="/logout">Logout</a>
                </li>
            <?php } else { ?>
                <li class="nav-item active">
                    <a class="nav-link" href="/login">Login</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/register">Register</a>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>