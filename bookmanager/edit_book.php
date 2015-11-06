<?php
$path = $_SERVER['DOCUMENT_ROOT'];
chdir($path);
define('DRUPAL_ROOT', getcwd()); //the most important line
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

//display a node
//print '<pre>';
//print_r(node_load(23));
//print '</pre>';
$node = node_load($_POST["nid"]);

$body_text = $_POST["description"];

$node->title    = $_POST["title"];
$node->language = LANGUAGE_NONE;
$node->uid = $_POST["uid"];
$node->body[$node->language][0]['value']   = $body_text;
$node->body[$node->language][0]['summary'] = text_summary($body_text);
//$node->body[$node->language][0]['format']  = 'filtered_html';

if($_POST["type"] == 'source_book')
{
	$node->field_premium_node=array(und => array());
	
	foreach($_POST["nodes"] as $key => $reference)
	{
		array_push($node->field_premium_node['und'], array("nid" => $reference));
	}
}

node_save($node);
?>