// JavaScript Document


	jQuery(".field-name-body table").wrap("<div class='table_wrapper' />");
	jQuery(".field-name-body .table_wrapper").mCustomScrollbar({
		horizontalScroll:true
	});
	
	jQuery("div.field-item").find("div.field").not(".field-name-title").toggle();
	jQuery("div.field-item").find("#comments").toggle();
	//jQuery("div.field-item").find(".contextual-links-region").toggle();
	
	
	
	jQuery( ".add_note" ).accordion({
		change: function(){
			jQuery("#story_container").mCustomScrollbar("update");
		},
		collapsible: true,
		active: false
	});
	//jQuery("#story_container").mCustomScrollbar("update");
	
	
	jQuery(".title-label").click(function(){
	jQuery(this).parents("div.field-item").find("div.field").not(".field-name-title").slideToggle(function(){
			jQuery("#story_container").mCustomScrollbar("update");
		});
	jQuery(this).parents("div.field-item").find("#comments").slideToggle(function(){
			jQuery("#story_container").mCustomScrollbar("update");
		});
	});