<?php
header('HTTP/1.0 500 Internal Server Error');

if ($message == ''){
?>
<section style="padding:90px 0;">
	<div class="container">
		<div class="text-center">
			500 - internal server error
		</div>
	</div>
</section>

<?php

}
else {
    echo json_encode($message);
}