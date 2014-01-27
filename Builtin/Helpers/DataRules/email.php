<?php
// 'email':
$rs = array (
	'type'           => 'text',
	'min_length'     => 6,
	'max_length'     => 130,
	'size'           => 25,
	'validation'     => 'Email',
	'value'          => '',
	'default'        => '',
	//'pre_filter'     => array( array( '\Tipui\Builtin\Libs\Strings', 'Escape' ), array( array( 'php', 'form', 'script', 'headers', 'trim' ) ) ),
	'pre_filter'     => 'Email',					
);
?>