<?php
$rs = array (
	'type'           => 'select',
	'ExactLength'    => 1,
	'size'           => 1,
	'validation'     => 'options',
	'value'          => '',
	'default'        => '1',
	'options'        => array(1 => 'option 1', 2 => 'option 2'),
	'pre-filter'     => array( array( '\Tipui\Builtin\Libs\Strings', 'NumbersOnly' ) ),
);