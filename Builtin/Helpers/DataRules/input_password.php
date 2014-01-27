<?php
$rs = array (
	'type'           => 'password',
	'min_length'     => 5,
	'max_length'     => 25,
	'size'           => 25,
	'value'          => '',
	'default'        => '',
	'pre_filter'     => array( array( '\Tipui\Builtin\Libs\Strings', 'Escape' ), array( false ) ),
);