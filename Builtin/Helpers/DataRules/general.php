<?php
// 'general':
$rs = array (
	'type'           => 'text',
	'MinLength'      => 1,
	'MaxLength'      => 50,
	'size'           => 20,
	'validation'     => 'text',
	'value'          => '',
	'default'        => '',
	'pre-filter'     => array( array( 'Strings', 'Escape' ), array( false ) ),					
);
?>