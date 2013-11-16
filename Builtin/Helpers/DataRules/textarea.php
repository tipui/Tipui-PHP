<?php
$rs = array (
	'type'           => 'textarea',
	'MinLength'      => 3,
	'MaxLength'      => 150,
	'cols'           => 25,
	'rows'           => 3,
	'validation'     => 'text',
	'value'          => '',
	'default'        => '',
	'pre-filter'     => array( array( '\Tipui\Builtin\Libs\Strings', 'Escape' ), array( false ) ),
);