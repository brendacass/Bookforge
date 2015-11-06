<div id="book_manager">
<?php global $user; ?>
<?php if (!$_POST): ?>
  No direct access! Come here from a book edit button!
<?php endif; ?>
<?php if ($_POST): ?>
<?php if ($user->uid!=$_POST["uid"]): ?>
  You do not have permission to edit this book, edit your own!
<?php endif; ?>
<?php if ($user->uid==$_POST["uid"]): ?>
<script>
	
	/////////////////////////////////////////////////////////////////////////////////////
  	/*
  	* This is for all of the drag and drop functionality
  	*/
  	/////////////////////////////////////////////////////////////////////////////////////
  jQuery(function () {
  	editBind()
    removeThis = false;
  	jQuery("#sortable").sortable({
  		containment: 'window',
      appendTo: 'body',
      over: function() {
        removeThis = false;
      },
      out: function(){
        removeThis = true;
        jQuery("#delete_area").css("display","inherit");
      },
      start: function(){

      },
      stop: function(){
        jQuery("#delete_area").css("display","none");
        jQuery("#story_container").mCustomScrollbar("update");
      },
      change: function(){
        jQuery("#saved_area").css("display","none");
      },
      beforeStop: function(event, ui){
        ui.item.find("span.system-label").addClass("hidden");
        if(removeThis == true)
        {
          ui.item.remove();
          
        }

        jQuery("#delete_area").css("display","none");
      }
  	});
  	jQuery(".draggable").draggable({
  		connectToSortable: "#sortable",
  		helper: "clone",
  		revert: "invalid",
  		scroll: false,
  		containment: 'window',
  		appendTo: 'body',
  	});

    jQuery(".view-book-tree li").draggable({
      connectToSortable: "#sortable",
      helper: "clone",
      revert: "invalid",
      scroll: false,
      containment: 'window',
      appendTo: 'body',
    });
  	jQuery("ul, li").disableSelection();



  });
  	/////////////////////////////////////////////////////////////////////////////////////
  	/*
  	* This is for the book delete functionality
  	*/
  	/////////////////////////////////////////////////////////////////////////////////////
  jQuery("#delete_button").click(function(){
    url = 'bookmanager/delete_book.php';
      jQuery.post(url, {
        nid: <?php echo $_POST["nid"]; ?>
      },function(data) {
        document.location.href="library";
      });

  });
  	/////////////////////////////////////////////////////////////////////////////////////
  	/*
  	* This is for the book saving functionality
  	*/
  	/////////////////////////////////////////////////////////////////////////////////////
  jQuery("#save_button").click(function(){
  	
  	node_array = new Array();

  	jQuery("#sortable").children().each(function(){
  		node_array.push(jQuery("span.nid", this).text());
  	});

  	
  	url = 'bookmanager/edit_book.php';
  	jQuery.post(url, {
  		uid:<?php global $user; echo $user->uid; ?>,
  		title:jQuery("input[name='title']").val(),
  		description:jQuery("textarea[name='description']").val(),
  		nodes:node_array,
  		type:"source_book",
  		nid: <?php echo $_POST["nid"]; ?>
  	},function(data) {
  		
  	});
    jQuery("#saved_area").css("display","inherit");
  });

  jQuery("input[name='title'], textarea[name='description']").change(function(){
    jQuery("#saved_area").css("display","none");
  });

	/////////////////////////////////////////////////////////////////////////////////////
  	/*
  	* This is for all of the chapter management functionality
  	*/
  	/////////////////////////////////////////////////////////////////////////////////////
  	
  	
  	/////////////////////////////////////////
	////////       EDIT BUTTON     //////////
	/////////////////////////////////////////
  	function editBind()
  	{
  		jQuery(".edit_chapter").unbind("click").click(function(){
			jQuery(this).siblings('.chapter_form').children('.save_chapter').unbind('click');
			jQuery(this).siblings('.chapter_form').children('.save_chapter').click(function(){
				jQuery(this).parent().parent().children('.chapter_title').html(jQuery(this).siblings("input[name='chapter_title']").val());
				
				  	////////////////////////////////
			  		//  Save the Chapter
			  		////////////////////////////////
			  	  	url = 'bookmanager/edit_book.php';
				  	jQuery.ajax({
				  	type: 'POST',
				  	url: url, 
				  	data: {
				  		nid: jQuery(this).parent().parent().children('.nid').html(),
				  		uid:<?php global $user; echo $user->uid; ?>,
				  		title:jQuery(this).siblings("input[name='chapter_title']").val(),
				  		description:jQuery(this).siblings("textarea[name='chapter_description']").val(),
				  		type:"chapter",
				  	},
				  	success: function(data) {
				    	
				  	},
				  	async: false
				  	});
				  	////////////////////////////////////////
				
				
				jQuery(this).parent().parent().children('.chapter_title').toggle();
				jQuery(this).parent().parent().children('.edit_chapter').toggle();
				jQuery(this).parent().toggle();
				
			});
			
			jQuery(this).siblings('.chapter_form').children('.delete_chapter').unbind('click');
			jQuery(this).siblings('.chapter_form').children('.delete_chapter').click(function(){
				
				jQuery(this).parent().parent().children('.chapter_title').toggle();
				jQuery(this).parent().parent().children('.edit_chapter').toggle();
				jQuery(this).parent().toggle();
				
			});
			
			
			jQuery(this).siblings('.chapter_form').toggle();
			jQuery(this).siblings('.chapter_title').toggle();
			jQuery(this).toggle();
  		});
  	}
  
  function chapterBindings(){
  	
  	/////////////////////////////////////////
	////////     DELETE BUTTON     //////////
	/////////////////////////////////////////
  	jQuery(".delete_chapter").unbind("click").click(function(){
		jQuery(this).parent().parent().remove();
  	});
  	
  	
  	/////////////////////////////////////////
	////////       SAVE BUTTON     //////////
	/////////////////////////////////////////
  	jQuery(".save_chapter").unbind("click").click(function(){
  		//jQuery(this).parent().append("<span id='saving'>Saving <img src='/sites/all/modules/library_navigation/ajax-loader.gif'/></span>");
  		html = 'Chapter: <span class="chapter_title">'+jQuery(this).siblings("input[name='chapter_title']").val()+'</span>';
  		html += '<button class="edit_chapter manager_button">Edit</button>'
  		html += '<span class="hidden nid">';
  		
  		////////////////////////////////
  		//  Save the Chapter
  		////////////////////////////////
  	  	url = 'bookmanager/add_book.php';
	  	jQuery.ajax({
	  	type: 'POST',
	  	url: url, 
	  	data: {
	  		uid:<?php global $user; echo $user->uid; ?>,
	  		title:jQuery(this).siblings("input[name='chapter_title']").val(),
	  		description:jQuery(this).siblings("textarea[name='chapter_description']").val(),
	  		type:"chapter",
	  	},
	  	success: function(data) {
	    	html+= data+'</span>';
	  	},
	  	async: false
	  	});
	  	////////////////////////////////////////
  		jQuery(this).parent().parent().append(html);
		jQuery(this).parent().toggle();
		
		/////////////////////////////////////////
		////////       EDIT BUTTON     //////////
		/////////////////////////////////////////
		editBind()
		
  	});
  }
  
  function chapterForm(){
  	html = '<div class="chapter_form">';
  	html += '<span class="label">Chapter Title:</span><input type="text" name="chapter_title"><br/>';
  	html += '<h4>Description:</h4> <textarea name="chapter_description"> </textarea>';
  	html += '<button class="save_chapter manager_button">Save</button><button class="delete_chapter manager_button">Cancel</button>';
  	html += '</div>';
  	
  	return html;
  }
  
  
  jQuery("#create_chapter").click(function(){
  	html = '<li class="chapter">';
  	html += chapterForm();
  	html += '</li>';
  	jQuery("#sortable").append(html);
  	chapterBindings();
  });
///////////// END CHAPTER MANAGEMENT //////////////////////
///////////////////////////////////////////////////////////

  </script>

  <?php
    $book = node_load($_POST["nid"]);
  ?>

  <span class="label">Title:</span><input type="text" name="title" value="<?php print $book->title; ?>"><br/>
  <h4>Description:</h4> <textarea name="description"><?php print $book->body["und"][0]["value"]; ?></textarea>
  <div class="protip">Drag content from the right into the box below to add it to your book.</div>
  <div id="create_chapter"><img src="/sites/all/themes/skirmisher/add_button.png" height=20>Add Chapter</div>
  <ul id="sortable">
    <?php 
    foreach($book->field_premium_node["und"] as $source)
    {
    
	    $premium = node_load($source["nid"]);
	    
	    if($premium->type == 'source_material')
	    {
			print '<li style="display: list-item;">  
			<a href="/node/' . $source["nid"] . '">' . $premium->title . '</a>    
			<span class="hidden nid">' . $source["nid"] . '</span>  </li>';
		}
		else
		{
			print '<li class="chapter" style="display: list-item;">
			<div class="chapter_form" style="display: none;">
			<span class="label">Chapter Title:</span><input type="text" name="chapter_title" value =
			"' . $premium->title . '"><br><h4>Description:</h4>
			<textarea name="chapter_description">' . $premium->body['und'][0]['value'] . '</textarea>
			<button class="save_chapter manager_button">Save</button>
			<button class="delete_chapter manager_button">Cancel</button></div>';
			
			print 'Chapter: <span class="chapter_title">' . $premium->title .
			'</span><button class="edit_chapter manager_button">Edit</button>
			<span class="hidden nid">' . $source["nid"] . '</span></li>';
		}
    }
    
    ?>
  </ul>
  <div id="delete_area">Drag source here to delete it.</div>
  <button id="save_button">Save</button><button id="delete_button">Delete</button>
  <div id="saved_area">Changes to book have been saved.</div>
  <?php endif; ?>
  <?php endif; ?>
</div>