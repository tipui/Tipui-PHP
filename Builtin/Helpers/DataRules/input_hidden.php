<?php
$rs = array (
	'type'           => 'hidden',
	'min_length'     => 5,
	'max_length'     => 25,
	'value'          => '',
	'default'        => '',
	'pre_filter'     => array( array( '\Tipui\Builtin\Libs\Strings', 'Escape' ), array( false ) ),
);