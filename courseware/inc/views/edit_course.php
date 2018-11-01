<?
$edit_type = ( $req['chapter'] ) ? 'chapter': 'course';
include 'courseware/inc/modules/edit.' . $edit_type . '.php';
?>