
function openPrevious()
{
	try
	{
		temp = jQuery.cookie("opened_array");
		for(item in temp){
			if(jQuery("#"+temp[item]).attr("class").split(" ")[1]=="parent")
			{
				jQuery("#"+temp[item]).children("ul").toggle();
			}
			else
			{
				poster(temp[item]);
			}
		}
	}
	catch(error)
	{
		
	}
}
function addToCookie(id)
{
	try
	{
		if(jQuery.cookie("opened_array"))
		{
			temp = jQuery.cookie("opened_array");
			temp.push(id);
			jQuery.cookie("opened_array", JSON.stringify(temp));
		}
		else
		{
			jQuery.cookie("opened_array", JSON.stringify(new Array(id)), {expires: 14, path: '/'});
		}
	}
	catch(error)
	{
		
	}
}

function removeFromCookie(id)
{
	try
	{
		temp = jQuery.cookie("opened_array");
		
		label_index = temp.indexOf(id)
		if(label_index!=-1)
		{
			temp.splice(label_index, 1);
		}
	
		jQuery.cookie("opened_array", JSON.stringify(temp));
	}
	catch(error)
	{
		
	}
}

function poster(tid)
{
	jQuery("#"+tid).toggleClass("clicked");

	url = '/sites/all/modules/library_navigation/get_library.php';
	jQuery.post(url, {
		uid:user,
		tid:tid
	},function(data) {
		data = jQuery.parseJSON(data)
		jQuery("#"+data.tid).children("ul").html(data.html)
		jQuery(".draggable").draggable({
					connectToSortable: "#sortable",
					helper: "clone",
					revert: "invalid",
					scroll: false,
					containment: 'window',
					appendTo: 'body',
				});
	});


}

jQuery(document).ready(function(){
	
	jQuery.cookie.json = true;
	
	if(jQuery.cookie("opened_array"))
	{
		openPrevious();
	}

	jQuery(".taxonomy_item.parent").children("ul").toggle();
	jQuery(".taxonomy_item.parent>h4").click(function(){
		if(jQuery(".parent").children("ul").is(":visible"))
		{
			removeFromCookie(jQuery(this).parent().attr("id"));
		}
		else
		{
			addToCookie(jQuery(this).parent().attr("id"));
		}

		jQuery(this).parent().children("ul").slideToggle();
	});

	jQuery(".taxonomy_item.child>h4").click(function(){
		
		if(jQuery(this).parent().children("ul").html()=="")
		{
			addToCookie(jQuery(this).parent().attr("id"));
			jQuery(this).parent().children("ul").html("<img style='display: block; margin-left:auto; margin-right:auto;' src='/sites/all/modules/library_navigation/ajax-loader.gif'/>");
			poster(jQuery(this).parent().attr("id"));
		}
		else{
			removeFromCookie(jQuery(this).parent().attr("id"));
			jQuery(this).parent().children("ul").html("");
			jQuery(this).parent().toggleClass("clicked");
		}
	});

	jQuery("#source_search").change(function(){
		if(jQuery("#source_search").val()=="")
		{
			jQuery("#source_results").html("");
			jQuery("#menu_results").css("display","inherit");
		}
		else
		{	
			jQuery("#source_results").html("<img style='display: block; margin-left:auto; margin-right:auto;' src='/sites/all/modules/library_navigation/ajax-loader.gif'/>");
			jQuery("#menu_results").css("display","none");
			url = '/sites/all/modules/library_navigation/search_library.php';
			jQuery.post(url, {
				uid:user,
				search:jQuery("#source_search").val()
			},function(data) {
				jQuery("#source_results").html(data);
				
				jQuery(".draggable").draggable({
					connectToSortable: "#sortable",
					helper: "clone",
					revert: "invalid",
					scroll: false,
					containment: 'window',
					appendTo: 'body',
				});
			});

		}
	});

});
