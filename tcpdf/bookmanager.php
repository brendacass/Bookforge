<style>
#articles li{
	cursor: move;
	list-style-type:none;
	padding: 10px;
	margin: 5px;
	margin-left: -40px;
	border-style: dashed;
	border-width: 1px;
	border-color: #2b416b;
	background-color: #EDF0F7;
}

#articles li a, span.button{
	background-color: #aa3131;
	color:white;
	border-radius: 5px;
	border-style: none;
	font-weight: bolder;
	padding: 5px;
	padding-left: 1px;
	margin-left: 5px;
}

#articles li a:hover, span.button:hover{
	color: white;
	text-decoration: none;
	border-style: solid;
	border-color: #df8e8e
	border-width: 2px;
	padding: 2px;
	padding-left: 0px;
}

span.button{
	padding-left: 5px;
	cursor: pointer;
}

span.button:hover{
	padding-left: 3px;
}

select{
	background-color: white;
	border-color: #aa3131;
	border-width: 2px;
}
</style>
<?php global $user; ?>

<select name="book">

</select>
<span class="button" style="background-color: #2b416b;" onclick="pdf()">Download PDF</span>
<ul id="articles" class="sortable">
    
</ul>
<span class="button" onclick="deleteBook()">Delete Book</span><br/><br/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
<script src="http://d-infinity.net/bookmanager/jquery.sortable.js"></script>
<script>
jQuery(document).ready(function(){
    user_id = <?php echo $user->uid; ?>;
    
	url = 'http://d-infinity.net/bookmanager/userbooks.php';
	$.post(url, {uid: user_id}, function(data){
			
			if(data!="False")
			{
				$('select[name="book"]').html(data);
				url = 'http://d-infinity.net/bookmanager/getbook.php';
				$.post(url, {bid:$('select[name="book"]').val()}, function(data) {
					$("#articles").html(data);
					$('.sortable').sortable();
					$('.sortable').sortable().bind('sortupdate', function() {updateBook();});
				});
			}
			else
			{
				$('select[name="book"]').html("")
				$("#articles").html("<li>No Books</li>");
			}
		});
	
	$('select[name="book"]').change(function(){
		url = 'http://d-infinity.net/bookmanager/getbook.php';
		$.post(url, {bid:$('select[name="book"]').val()}, function(data) {
			if(data!='False')
			{
				$("#articles").html(data);
				$('.sortable').sortable();
				$('.sortable').sortable().bind('sortupdate', function() {updateBook();});
			}
		});
		
	});
	
});

function remove(nid)
{
	choice = $('select[name="book"]').val();
	
	
	url = 'http://d-infinity.net/bookmanager/removebook.php';
	$.post(url, {bid:choice, nid:nid}, function(data) {console.log(data); });
	
	url = 'http://d-infinity.net/bookmanager/getbook.php';
	$.post(url, {bid: $('select[name="book"]').val()}, function(data) {
		$("#articles").html(data);
		$('.sortable').sortable();
		$('.sortable').sortable().bind('sortupdate', function() {updateBook();});
	});
}

function updateBook()
{
	contentList = [];
	$("#articles").children().each(function(){
		contentList.push(parseInt($(this).attr("id")));
	});

	url = 'http://d-infinity.net/bookmanager/updatebook.php';
	$.post(url, {bid:$('select[name="book"]').val(), 'content[]': contentList}, function(data){});
}

function deleteBook()
{
	url = 'http://d-infinity.net/bookmanager/deletebook.php';
	$.post(url, {bid:$('select[name="book"]').val()}, function(data){});
	
	url = 'http://d-infinity.net/bookmanager/userbooks.php';
	$.post(url, {uid:user_id}, function(data){
			console.log(data);
			if(data!="False")
			{
				$('select[name="book"]').html(data);
				url = 'http://d-infinity.net/bookmanager/getbook.php';
				$.post(url, {bid:$('select[name="book"]').val()}, function(data) {
					if(data!="False")
					{
						$("#articles").html(data);
						$('.sortable').sortable();
						$('.sortable').sortable().bind('sortupdate', function() {updateBook();});
					}
					else
					{
						$("#articles").html("<li>No Books</li>");
					}
				});
			}
			else
			{
				$('select[name="book"]').html("")
				$("#articles").html("<li>No Books</li>");
			}
		});
	
	
	
}

function pdf()
{
	url = 'http://d-infinity.net/tcpdf/pdfgen.php';
	$.post(url, {bid:$('select[name="book"]').val()}, function(data) {
			console.log(data);
	});
}

</script>
