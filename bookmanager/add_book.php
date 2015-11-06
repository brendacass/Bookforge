<?php
$path = $_SERVER['DOCUMENT_ROOT'];
chdir($path);
define('DRUPAL_ROOT', getcwd()); //the most important line
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


  $body_text = $_POST["description"];
  
  
  $node = new stdClass();
  //$node->type = 'source_book';
  $node->type = $_POST["type"];
  node_object_prepare($node);
  
  $node->title    = $_POST["title"];
  $node->language = LANGUAGE_NONE;
  $node->uid = $_POST["uid"];
  $node->body[$node->language][0]['value']   = $body_text;
  $node->body[$node->language][0]['summary'] = text_summary($body_text);
  //$node->body[$node->language][0]['format']  = 'filtered_html';
  
  if($node->type == 'source_book')
  {
	  $node->field_premium_node=array(und => array());
	
	  foreach($_POST["nodes"] as $key => $reference)
	  {
	  	array_push($node->field_premium_node['und'], array("nid" => $reference));
	  }
  }
  

  $path = 'content/programmatically_created_node_' . date('YmdHis');
  $node->path = array('alias' => $path);

  node_save($node);

  echo $node->nid;
  
?>