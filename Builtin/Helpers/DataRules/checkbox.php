<?php
$rs = array (
	'type'           => 'checkbox',
	'ExactValue'     => 1,
	'size'           => 1,
	'validation'     => 'number',
	'value'          => null,
	'default'        => null,
	'pre-filter'     => array( array( '\Tipui\Builtin\Libs\Strings', 'NumbersOnly' ) ),
);