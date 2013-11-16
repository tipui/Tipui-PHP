<?php
$rs = array (
	'type'           => 'select',
	'ExactLength'    => 1,
	'size'           => 1,
	'validation'     => 'options',
	'value'          => '',
	'default'        => '2',
	'options'        => array( 
							'g1' => array( 'optgroup' => 'group 1', 'options' => array( 1 => 'g1 option 1', 2 => 'g1 option 2') ),
							'g2' => array( 'optgroup' => 'group 2', 'options' => array( 3 => 'g2 option 1', 4 => 'g2 option 2') )
						),
	'pre-filter'     => array( array( '\Tipui\Builtin\Libs\Strings', 'NumbersOnly' ) ),
);