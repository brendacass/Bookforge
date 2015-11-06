<?php

$path = $_SERVER['DOCUMENT_ROOT'];
chdir($path);
define('DRUPAL_ROOT', getcwd()); //the most important line
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$query = "SELECT distinct n.title, n.nid, parent.name as parent, ttd.name as child
FROM taxonomy_term_data ttd,taxonomy_term_data parent, taxonomy_term_hierarchy tth, taxonomy_index ti, taxonomy_index themeindex, taxonomy_term_data theme, taxonomy_index genreindex, taxonomy_term_data genre, node n, field_data_body fdb
WHERE ttd.vid = 2
AND ti.tid = tth.tid
AND ttd.tid = tth.tid
AND parent.tid = tth.parent
AND ti.nid = n.nid
AND themeindex.nid = n.nid
AND theme.vid = 4
AND theme.tid = themeindex.tid
AND genreindex.nid = n.nid
AND genre.vid = 3
AND genre.tid = genreindex.tid
AND n.type = 'source_material'
AND n.nid = fdb.entity_id
AND
(		
n.title LIKE :search
OR fdb.body_value LIKE :search
OR ttd.name LIKE :search
OR theme.name LIKE :search
OR genre.name LIKE :search
)
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
AND grant_view = 1)
ORDER BY n.title ASC
;";

$search = '%'.str_replace(" ", "%",$_POST["search"]).'%';

$result = db_query($query, array(':search' => $search, ':uid' => $_POST["uid"]));

$html = "<ul>";

foreach($result as $record)
{
	$html.="<li class='draggable'><a href='/node/".$record->nid."'>".$record->title."</a>";
	$html.="<span class='hidden nid'>".$record->nid."</span>";
	$html.="<br/><span class='system-label'>".$record->parent." ".$record->child."</span>";
	$html.="</li>";
}
$html.="</ul>";
echo $html
?>