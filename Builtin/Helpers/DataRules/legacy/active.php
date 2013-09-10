<?php
// 'active':
$rs = array (
	'type'           => 'select',
	'ExactLength'    => 1,
	'size'           => 1,
	'validation'     => 'options',
	'value'          => '',
	'default'        => '1',
	'options'        => ActiveLabels::Get(),
	'pre-filter'     => array( array( 'Strings', 'NumbersOnly' ) ),
);
?>