<?php
// 'email':
$rs = array (
	'type'           => 'text',
	'MinLength'      => 6,
	'MaxLength'      => 130,
	'size'           => 25,
	'validation'     => 'email',
	'value'          => '',
	'default'        => '',
	'pre-filter'     => array( array( 'Strings', 'Escape' ), array( array( 'php', 'form', 'script', 'headers', 'trim' ) ) ),					
);
?>