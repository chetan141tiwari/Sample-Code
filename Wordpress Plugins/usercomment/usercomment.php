<?php
$action='';
if ( isset( $_POST['deleteucomment'] ) )
	$action = 'deletecomment';
	
echo $action.'--';

switch ( $action ) {
}