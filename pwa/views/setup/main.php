<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-lg-4 offset-lg-1 col-md-6 offset-md-2 col-sm-6 offset-sm-3">

            <?php echo (isset($error)) ? '<div class="alert alert-danger" role="alert"><b>Error registering:</b> ' . $error . '</div>' : null; ?>

            <?php echo(isset($notification) ? '<div class="alert alert-info" role="alert">' . $notification . '</div>' : NULL); ?>

            <h1>Initial Setup of Your <?= WEBSITE_NAME ?> Unit</h1>
            <div id="password_result"></div>
            <form action="" id="register_form" method="POST">
                <h3>Configure your admin account</h3>
                <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token']; ?>">
                <input type="hidden" name="register-account"/>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <i class="fa fa-user fa-align"></i> Admin Username
                            <input id="user" type="text" class="form-control form-control-align" name="username" required autofocus>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <i class="fa fa-lock fa-align"></i> Password
                            <input id="password" type="password" name="password" class="form-control form-control-align" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <i class="fa fa-lock fa-align"></i> Confirm Password
                            <input id="password_confirm" type="password" name="password_confirm" class="form-control form-control-align" required>
                        </div>
                    </div>
                </div>
                <h3>Configure your API account (used to connect to the controller)</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <i class="fa fa-user fa-align"></i> API Username
                            <input id="api_username" type="text" class="form-control form-control-align" name="api_username" required autofocus>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <i class="fa fa-lock fa-align"></i> API Password
                            <input id="api_password" type="password" name="api_password" class="form-control form-control-align" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <i class="fa fa-lock fa-align"></i> Confirm API Password
                            <input id="api_password_confirm" type="password" name="api_password_confirm" class="form-control form-control-align" required>
                        </div>
                    </div>
                </div>
                <div id="result"></div>
                <button class="btn btn-outline-success" type="submit">
                    Register
                </button>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        function checkPasswordMatch() {
            const password = $("#password").val();
            const confirmPassword = $("#password_confirm").val();

            if (password !== confirmPassword)
                $("#password_result").html("<div class='alert alert-info' role='alert'>Passwords aren't identical</div>");
            else
                $("#password_result").html('');
        }

        $('#password, #password_confirm, #api_password, #api_password_confirm').keyup(function () {
            checkPasswordMatch(this);
        });

        /*
        assigning keyup event to password field
        so everytime user type code will execute
        */

        $('#password, #api_password').keyup(function () {
            $('#password_result').html(checkStrength($(this).val()))
        });

        /*
        checkStrength is function which will do the
        main password strength checking for us
        */

        function checkStrength(password) {
            //initial strength
            let strength = 0;

            //if the password length is less than 6, return message.
            if (password.length < 6) {
                $('#result').removeClass();
                $('#result').addClass('short');
                return "<div class='alert alert-info' role='alert'>Password is too short</div>";
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
                return "<div class='alert alert-info' role='alert'>Password is very weak</div>";
            } else if (strength === 2) {
                $('#result').removeClass();
                $('#result').addClass('good');
                return "<div class='alert alert-info' role='alert'>Password is weak</div>";
            } else {
                $('#result').removeClass();
                $('#result').addClass('strong');
                return "<div class='alert alert-info' role='alert'>Password is strong</div>";
            }
        }
    });
</script>