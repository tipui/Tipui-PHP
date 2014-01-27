<?php
$rs = array (
	'type'           => 'text',
	'min_length'     => 1,
	'max_length'     => 50,
	'size'           => 20,
	'value'          => '',
	'default'        => '',
	'pre_filter'     => array( array( '\Tipui\Builtin\Libs\Strings', 'Escape' ), array( false ) ),					
);