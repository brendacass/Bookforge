<?php
// $Id: page-node-lightbox2.tpl.php,v 1.1.2.2 2008/06/11 22:16:38 snpower Exp $

/**
 * @file
 * Template file for displaying the node content, associated with an image, in
 * the lightbox.  It displays it without any sidebars, etc.
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>


  </head>
  <body>
<script>
	jQuery(document).ready(function(){
		jQuery("#imageDataContainer",window.parent.document).css("display","none");
		jQuery("#outerImageContainer",window.parent.document).css("height","80%");
		jQuery("#lightboxFrame",window.parent.document).css("height","80%");
		jQuery("#edit-cancel").click(function(){
			window.parent.Lightbox.end();
		});
	});
</script>
    <?php print render($page['content']) ?>
    
</body>
</html>
