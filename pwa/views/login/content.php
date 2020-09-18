<section style="padding-top:5px;">
   <div class="container">
      <div class="row">
         <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">

            <?php echo (isset($error)) ? '<div class="alert alert-danger" role="alert"><b>Error logging in:</b> '.$error.'</div>' : null; ?>

            <?php echo (isset($notification) ? '<div class="alert alert-info" role="alert">' . $notification . '</div>' : NULL); ?>

            <h1>Log in</h1>
            <form action="" id="frmLogin" method="POST">
               <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token']; ?>">

               <div class="form-group">
                  <label>Username</label>
                  <input id="user" type="text" class="form-control" placeholder="Username ..." name="username" autofocus>
               </div>
               <div class="form-group">
                  <label>Password</label>
                  <input id="pass" type="password" class="form-control" placeholder="Password ..." name="password">
               </div>
               <button class="btn" type="submit">
                  <span>Log in</span>
               </button>
               <div class="clearfix"></div>
            </form>
            <p><a class="small pull-right" href="/forgotpassword">Forgot your password?</a></p>
         </div>
      </div>
   </div>
</section>