<?php
// 'lang':
$rs = array (
	'type'           => 'select',
	'MinLength'      => 2,
	'MaxLength'      => 6,
	'size'           => 4,
	'validation'     => 'options',
	'value'          => '',
	'default'        => LANG_DEFAULT,
	'options'        => LanguagesLabels::Get(),
	'pre-filter'     => array( array( 'Strings', 'Escape' ), array( false ) ),
);
?>