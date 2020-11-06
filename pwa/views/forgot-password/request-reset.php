<!--=== Content Part ===-->
<section style="padding-top:5px;">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
				<h1>Forgot your password?</h1>
				<?php echo (isset($error) ? '<div class="alert alert-info" role="alert">' . $error . '</div>' : NULL); ?>
				<form method="POST" accept-charset="UTF-8">
					<input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token'] ?>" />
					<input type="hidden" name="request-reset" />
					<br>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<i class="fa fa-envelope fa-align"></i>
								<input type="email" name="email" placeholder="<?php echo 'email'; ?>" class="form-control form-control-align" required autofocus>
							</div>
						</div>
					</div>
					<br>
					<p class="help-block">You will receive an email which you can use to set a new password. Don't forget to check your spam folder!</p>
					<br>
					<button class="btn btn-block" type="submit">Reset</button>
					<br>
				</form>
			</div>
		</div>
	</div>
</section>
