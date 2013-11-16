<?php
$rs = array (
	'type'           => 'radio',
	'ExactLength'    => 1,
	'size'           => 1,
	'validation'     => 'options',
	'options'        => array(1 => 'option 1', 2 => 'option 2'),
	'value'          => '',
	'default'        => 0,
	'pre-filter'     => array( array( '\Tipui\Builtin\Libs\Strings', 'NumbersOnly' ) ),
);