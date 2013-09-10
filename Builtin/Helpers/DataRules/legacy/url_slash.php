<?php
// 'url_slash':
$rs = array (
	'type'           => 'text',
	'MinLength'      => 5,
	'MaxLength'      => 220,
	'size'           => 80,
	'validation'     => 'text',
	'value'          => '',
	'default'        => '',
	'pre-filter'     => array( array( 'Strings', 'Escape' ), array( 'url_slash' ) ),
);
?>