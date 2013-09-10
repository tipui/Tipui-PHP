<?php
// 'url-short':
$rs = array (
	'type'           => 'text',
	'MinLength'      => 12,
	'MaxLength'      => 220,
	'size'           => 40,
	'validation'     => 'text',
	'value'          => '',
	'default'        => '',
	'pre-filter'     => array( array( 'Strings', 'Escape' ), array( false ) ),
);
?>