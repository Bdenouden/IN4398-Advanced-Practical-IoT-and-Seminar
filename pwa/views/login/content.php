<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-lg-4 offset-lg-1 col-md-6 offset-md-2 col-sm-6 offset-sm-3">

            <?php echo (isset($error)) ? '<div class="alert alert-danger" role="alert"><b>Error logging in:</b> ' . $error . '</div>' : null; ?>

            <?php echo(isset($notification) && strlen($notification) > 0 ? '<div class="alert alert-info" role="alert">' . $notification . '</div>' : NULL); ?>

            <h1>Login</h1>
            <form action="" id="frmLogin" method="POST">
                <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token']; ?>">

                <div class="form-group">
                    <label>Username</label>
                    <input id="user" type="text" class="form-control" placeholder="username" name="username" autofocus required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input id="pass" type="password" class="form-control" placeholder="password" name="password" required>
                </div>
                <button class="btn" type="submit">
                    <span>Log in</span>
                </button>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>
