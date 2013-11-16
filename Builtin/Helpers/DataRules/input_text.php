<?php
$rs = array (
	'type'           => 'text',
	'MinLength'      => 5,
	'MaxLength'      => 25,
	'size'           => 25,
	'validation'     => 'text',
	'value'          => '',
	'default'        => '',
	'pre-filter'     => array( array( '\Tipui\Builtin\Libs\Strings', 'Escape' ), array( false ) ),
);