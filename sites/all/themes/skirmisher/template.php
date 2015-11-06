<?php

// Copy from here Template for Content type

function skirmisher_preprocess_page(&$vars) {
    // - page--example.tpl.php  

	 if (isset($vars['node'])) {
		$vars['theme_hook_suggestion'] = 'page__'.$vars['node']->type; //
	}
}
// Finish copy Template for Content type

?>