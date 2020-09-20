<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-lg-4 offset-lg-1 col-md-6 offset-md-2 col-sm-6 offset-sm-3">

            <?php echo (isset($error)) ? '<div class="alert alert-danger" role="alert"><b>Error registering:</b> ' . $error . '</div>' : null; ?>

            <?php echo(isset($notification) ? '<div class="alert alert-info" role="alert">' . $notification . '</div>' : NULL); ?>

            <h1>Initial Setup of Your ModFarm Unit</h1>
            <div id="password_result"></div>
            <form action="" id="register_form" method="POST">
                <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token']; ?>">
                <input type="hidden" name="register-account"/>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <i class="fa fa-user fa-align"></i>
                            <input id="user" type="text" placeholder="username" class="form-control form-control-align"
                                   placeholder="" name="username" required autofocus>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <i class="fa fa-lock fa-align"></i>
                            <input id="password" type="password" name="password" placeholder="password"
                                   class="form-control form-control-align" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <i class="fa fa-lock fa-align"></i>
                            <input id="password_confirm" type="password" name="password_confirm"
                                   placeholder="confirm password" class="form-control form-control-align" required>
                        </div>
                    </div>
                </div>
                <div id="result"></div>
                <button class="btn" type="submit">
                    <span>Register</span>
                </button>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        function checkPasswordMatch() {
            var password = $("#password").val();
            var confirmPassword = $("#password_confirm").val();

            if (password !== confirmPassword)
                $("#password_result").html("<div class='alert alert-info' role='alert'>Passwords aren't identical</div>");
            else
                $("#password_result").html('');
        }

        $('#password, #password_confirm').keyup(function () {

            checkPasswordMatch();

        });

        /*
        assigning keyup event to password field
        so everytime user type code will execute
        */

        $('#password').keyup(function () {
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
            if (password.length > 7) {
                strength += 1
            }

            //if password contains both lower and uppercase characters, increase strength value
            if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
                strength += 1
            }

            //if it has numbers and characters, increase strength value
            if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) {
                strength += 1
            }

            //if it has one special character, increase strength value
            if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
                strength += 1
            }

            //if it has two special characters, increase strength value
            if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) {
                strength += 1
            }

            //now we have calculated strength value, we can return messages

            //if value is less than 2
            if (strength < 2) {
                $('#result').removeClass();
                $('#result').addClass('weak');
                return '<span class="label label-warning">Password is very weak</span>';
            } else if (strength === 2) {
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