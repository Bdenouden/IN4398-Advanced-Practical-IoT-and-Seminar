<!--=== Content Part ===-->
<script type="text/javascript">
   $(document).ready(function() {

      function checkPasswordMatch() {
         var password = $("#password").val();
         var confirmPassword = $("#password_confirm").val();

         if (password != confirmPassword)
            $("#password_result").html("<div class='alert alert-info' role='alert'>Passwords aren't identical</div>");
         else
            $("#password_result").html('');
      }

      $('#password, #password_confirm').keyup(function() {

         checkPasswordMatch();

      });

   /*
   assigning keyup event to password field
   so everytime user type code will execute
   */

   $('#password').keyup(function()
   {
      $('#result').html(checkStrength($('#password').val()))

   });

   /*
   checkStrength is function which will do the
   main password strength checking for us
   */

   function checkStrength(password) {
      //initial strength
      var strength = 0;

      //if the password length is less than 6, return message.
      if (password.length < 6) {
         $('#result').removeClass();
         $('#result').addClass('short');
         return '<span class="label label-danger">Password is too short</span>';
      }

      //length is ok, lets continue.

      //if length is 8 characters or more, increase strength value
      if (password.length > 7) {strength += 1}

      //if password contains both lower and uppercase characters, increase strength value
   if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) { strength += 1 }

      //if it has numbers and characters, increase strength value
   if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) { strength += 1 }

      //if it has one special character, increase strength value
   if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) { strength += 1 }

      //if it has two special characters, increase strength value
   if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) { strength += 1 }

      //now we have calculated strength value, we can return messages

      //if value is less than 2
      if (strength < 2) {
         $('#result').removeClass();
         $('#result').addClass('weak');
         return '<span class="label label-warning">Password is very weak</span>';
      } else if (strength == 2 ) {
         $('#result').removeClass();
         $('#result').addClass('good');
         return '<span class="label label-default">Password is weak</span>';
      } else {
         $('#result').removeClass();
         $('#result').addClass('strong');
         return '<span class="label label-success">Password is strong</span>';
      }
   }
});
</script>
<div class="container">
   <div class="row">
      <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 tag-box tag-box-v6">
         <?php echo (isset($error) ? '<div class="alert alert-info" role="alert">' . $error . '</div>' : NULL); ?>
         <form class="reg-page" method="POST" name="loginform" accept-charset="UTF-8">
            <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token'] ?>" />
            <input type="hidden" name="reset_password" />
            <div class="headline" style = "margin-top: 25px; margin-bottom: 25px;">
               <h2>Choose new password</h2>
            </div>
            <div id="password_result"></div>
            <div class="row">
               <div class="col-md-12">
                  <div class="form-group">
                     <i class="fa fa-lock fa-align"></i>
                     <input id="password" type="password" name="password" placeholder="new password" class="form-control form-control-align" required autofocus>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12">
                  <div class="form-group">
                     <i class="fa fa-lock fa-align"></i>
                     <input id="password_confirm" type="password" name="password_confirm" placeholder="confirm new password" class="form-control form-control-align" required>
                  </div>
               </div>
            </div>
            <div id="result"></div>
            <hr>
            <div class="row">
               <div class="col-md-12">
                  <button class="btn btn-xl no-print" style="width:100%;" type="submit">save new password</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- End Content Part -->
