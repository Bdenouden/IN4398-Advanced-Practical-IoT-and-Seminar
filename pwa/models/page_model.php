<?php

class page_model extends Model {

	public function searchPage($page_uri) {
		
		return Database::select("SELECT * FROM pages WHERE page_uri=:uri UNION SELECT * FROM pages WHERE page_uri='/404'", array(':uri' => $page_uri));
	}

}

?>