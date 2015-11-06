<?php

$path = $_SERVER['DOCUMENT_ROOT'];
chdir($path);
define('DRUPAL_ROOT', getcwd()); //the most important line
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$query = "SELECT ttd.name, ti.nid, n.title
FROM taxonomy_term_data ttd, taxonomy_index ti, node n
WHERE ttd.vid = 2
AND ti.tid = :tid
AND ttd.tid = ti.tid
AND ti.nid = n.nid
AND n.type = 'source_material'
AND n.nid in (SELECT an.nid
FROM acl_node an, acl_user al
WHERE an.acl_id = al.acl_id
AND an.grant_view = 1
AND uid = :uid
UNION
SELECT nid 
FROM node_access, users_roles
WHERE uid = :uid
AND gid = rid
AND grant_view = 1);";

$result = db_query($query, array(':tid' => $_POST["tid"], ':uid' => $_POST["uid"]));
$html = "<ul>";

foreach($result as $record)
{
	$html.="<li class='draggable'><a href='/node/".$record->nid."'>".$record->title."</a>";
	$html.="<span class='hidden nid'>".$record->nid."</span>";
	$html.="</li>";
}
$html.="</ul>";
echo json_encode(array('tid'=>$_POST["tid"],'html'=>$html));
?>