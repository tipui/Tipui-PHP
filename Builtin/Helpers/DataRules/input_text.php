<?php
$rs = array (
	'type'           => 'text',
	'min_length'     => 5,
	'max_length'     => 25,
	'size'           => 25,
	//'validation'     => 'Number',
	'value'          => '',
	'default'        => 12345,
	//'pre_filter'     => array( array( '\Tipui\Builtin\Libs\Strings', 'NumbersOnly' ) ),
	'pre_filter'     => 'Number',
);