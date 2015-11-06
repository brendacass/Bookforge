<style>
#content_staging{
	/*display: none;*/
}



.page{
	height: 1510px;
	width: 1050px;

    /*margin-bottom: 20px;*/
    clear:both;
    /*border-bottom-style: solid;*/
	/*background-color: lightblue;*/
	/*page-break-after:always;*/
	margin-bottom: 40px;
}
.column{
	width: 49%;	
	/*background-color: lightblue;*/
	float: left;
	/*height: 1250px;*/
}
.left{
	margin-right: 20px;	
}



</style>

<?php

function createChapter($aNode){
	
	$chapter=new stdClass();
	$chapter->title=$aNode->title;
	$chapter->description=$aNode->body['und'][0]['value'];
	$chapter->content=array();
	return $chapter;
}

function createContent($aNode){
	
	$content=new stdClass();
	$content->title=$aNode->title;
	$content -> description = $aNode->body['und'][0]['value'];
	//echo json_encode($aNode->body['und'][0]['value']);
	// $html=htmlqp($aNode->body['und'][0]['value'], "body");
	// $content->images=htmlqp($html)->find('img')->html();
	// $content->tables=htmlqp($html)->find('table')->html();
	// htmlqp($html)->remove('table');
	// htmlqp($html)->remove('img');
	// $content->description=qp($html)->html();
	//$content -> description = ->remove('img')->text();
	return $content;
}

$book=new stdClass();
$book->title=$node->title;
$book->description=$node->body['und'][0]['value'];
$temparticles=$node->field_premium_node['und'];
$book->authors=array();
$tempauthors=array();
$book->chapters=array();
$chapter0=new stdClass();
$chapter0->title='';
$chapter0->description='';
$chapter0->content=array();
$currentchapter=$chapter0;
foreach($temparticles as $article){
	array_push($tempauthors,user_load($article['node']->uid)->name);
	//print_r($article['node']->type);
	if($article['node']->type=='chapter'){
		$currentchapter=createChapter($article['node']);
		array_push($book->chapters,$currentchapter);
	}
	else{
		array_push($currentchapter->content,createContent($article['node']));
	}
	//$printdata = new stdClass();
	//$printdata -> tables = array();
	//$parser = htmlqp($article['node']->body['und'][0][value]);
	//htmlqp($parser)->remove('table');
	//$parser->remove();
	//echo $parser->html();
}
$book->authors=array_unique($tempauthors);


?>
<body>

<div id="page1" class="page">
	<div class='column left'>
		<?php foreach($book->chapters as $chapter): ?>
			<h1><?php echo $chapter->title; ?></h1>
			<?php echo $chapter->description; ?>
			<?php foreach($chapter->content as $content): ?>
				<h2><?php echo $content->title; ?></h2>
				<?php echo $content->description; ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
	<div class='column right'></div>
</div>

<div id="content_staging">

</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>


<script>


$(document).ready(function(){
	page_count = 1;
	currentPage = $("#page1");
	currentColumn = currentPage.find(".right");
	
	function addPage()
	{
		page_count++;
		pageid = "page"+page_count;
		$("body").append("<div id = '"+pageid+"'class='page'><div class='column left'></div><div class='column right'></div></div>");
	    currentPage = $("#"+pageid);
	    currentColumn = currentPage.find(".left");
		//return "#"+pageid;
	}
	
	$("#page1 .left").children().each(function(){
		if ($(this)[0].offsetTop >= currentPage.height())
        {
     		if($(this).height()+currentColumn.height()<= currentPage.height())
	        {
	             currentColumn.append($(this));   
	        }
	        else
	        {
	            if(currentColumn.hasClass("right"))
	            {
	                addPage();
	                
	                if($(this).height()<=currentPage.height())
	                {
	                     currentColumn.append($(this));
	                    
	                }
	            }
	            else
	            {
	                
	                currentColumn = currentPage.find(".right");
	                if($(this).height()<=currentPage.height())
	                {
	                    currentColumn.append($(this));   
	                }
	            }
	        }
        }
	});
	
});
	$("#content_staging").remove();
</script>
</body>