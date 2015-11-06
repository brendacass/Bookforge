<?php
//print $_SERVER['REQUEST_URI'];
$url = explode("/",$_SERVER['REQUEST_URI']);
if($url[1] == "print" || $url[1] == "printpdf" || $url[1] == "?q=print" || $url[1] == "?q=printpdf"){
    include ('print.tpl.php');
    return;
 }

$q = explode("/",$_GET["q"]);

if ($q[0] == "print") {
include ('print.tpl.php');
return;
}

?>

<body>
	<script>
		jQuery(document).ready(function(){

			dynamicPlacement();
				jQuery(".scrollzone").mCustomScrollbar({
					scrollInertia: 0,
					advanced:{
			        	updateOnContentResize: true
				    }
				});
				jQuery("#story_container").mCustomScrollbar({
					scrollInertia: 0,
					advanced:{
			        	updateOnContentResize: true
				    }
				});
			jQuery(window).resize(function(){
				dynamicPlacement();
			});

			jQuery("#body_container").scroll(function(){
				jQuery("#teaser_div").css({
					"top":jQuery(this).scrollTop()
				});
			});
			
			
			/////////////////////////////////////////////////////////////////////////////////
			/*
			* Shortcut Box
			*/
			/////////////////////////////////////////////////////////////////////////////////
			count = 0;
			html = "";
			
			jQuery(".field-name-field-premium-node .field-items div").find(".field-name-title").each(function(){
				count++;
				if(jQuery(this).parents(".chapter_node").length == 0)
				{
					html+="<li id='"+jQuery(this).offset().top+"'>"+jQuery(this).text()+"</li>";
				}
				else if(count>1){
					html+="</ul><li id='"+jQuery(this).offset().top+"'>"+jQuery(this).text()+"</li><ul>";	
				}
				else{
					html+="<li id='"+jQuery(this).offset().top+"'>"+jQuery(this).text()+"</li><ul>";		
				}
			});

			
			
			jQuery("#shortcut-box ol").append(html);
			jQuery("#shortcut-box").find("li").click(function(){
				jQuery("#story_container").mCustomScrollbar("scrollTo", parseInt(jQuery(this).attr('id')));
			});
			////////////////////////////////////////////////////
			/////////////  END SHORTCUT BOX ////////////////////
			////////////////////////////////////////////////////
			
		});

		function dynamicPlacement()
		{
			center_space = jQuery(window).height()-210;
			jQuery("#teaser_div").css("height",center_space-10);
			jQuery("#body_container").css("height",center_space);
			jQuery("#story_container").css("height",center_space);
			//left_distance = 50+jQuery("#story_container").offset().left+jQuery("#story_container").width()+"px";
			//jQuery("#teaser_div").css("left", left_distance);
		}
	</script>
	
	<div id="header_container">
		<div id="menu_container">
			<div id="menu_content">
				<?php if ($logo): ?>
	            <div id="logo">
					<a href="<?php print check_url($front_page); ?>" title="<?php print t('Home'); ?>"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" width="200" /></a>
	            </div>
	            <?php endif; ?>
				<?php if ($page['header']): ?>
				<div id="header_content">
				<?php if (!isset($user->roles[1])): ?>
						<div id="store_button">
							<a href="/store">Store</a>
							<div id="store_menu">
								<?php print render($page['store_menu_region']); ?>
							</div>
						</div>
					<?php endif; ?>	
				    <?php print render($page['header']); ?>
				</div>
			  	<?php endif; ?>	
				
			</div>
		</div>
		<div id="navigation_container">
			<div id="navigation_content">
				<?php print render($page['sidebar_second']); ?>
			</div>
		</div>
	</div>
	<div id="body_container">
		<div id="content_container">
			<div id="hider"></div>
			<div id="story_container">
				<div id="story_content">
					<?php if ($breadcrumb || $title|| $messages || $tabs || $action_links): ?>
						
						<?php print $breadcrumb; ?>
						<?php print $messages; ?>
						<?php print render($page['help']); ?>
						<?php if ($tabs): ?>
						  <div class="tabs"><?php print render($tabs); ?></div>
						<?php endif; ?>
						
						<?php if ($page['highlight']): ?>
					  		<div id="highlight"><?php print render($page['highlight']) ?></div>
						<?php endif; ?>
						
						<?php if ($title): ?>
						  <h1 class="title"><?php print $title; ?></h1>
						<?php endif; ?>

						<form method="post" action="/?q=content/edit-sourcebook">
							<input type="hidden" name="nid" value="<?php print_r($node->nid) ?>">
							<input type="hidden" name="uid" value="<?php print_r($user->uid) ?>">
							<input type="submit" id="edit_button" value="Edit Book"/>
						</form>

						<?php if ($action_links): ?>
						  <ul class="action-links"><?php print render($action_links); ?></ul>
						<?php endif; ?>
					<?php endif; ?>
					<div class="story">
						<div id="shortcut-box">
							<h4>Contents</h4>
							<ol></ol>
						</div>
						<?php print render($page['content']) ?>
					</div>
				</div>
			</div>

			<?php if ($page['sidebar_first']): ?>
				<div id="teaser_div" class="scrollzone">
					<?php print render ($page['sidebar_first']); ?>
				</div>
			<?php endif; ?>
		</div>
</div>
	<div id="footer_container">
		<?php if ($page['footer']): ?>
		    <div id="footer_content">
		      <?php print render($page['footer']); ?>
		    </div> <!-- /footer -->
		  <?php endif; ?>
	</div>

</body>