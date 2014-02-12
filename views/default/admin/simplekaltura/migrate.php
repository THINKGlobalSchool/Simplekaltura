<?php

$count = simplekaltura_migration_check();

if ($count) {
	echo elgg_echo('simplekaltura:migration:possible', array($count));
	echo '<br><br><br>';
	echo elgg_view('output/url', array(
		'text' => elgg_echo('simplekaltura:migrate'),
		'href' => 'action/simplekaltura/migrate',
		'is_action' => true,
		'is_trusted' => true,
		'class' => 'elgg-button elgg-button-submit elgg-requires-confirmation'
	));
}
else {
	echo elgg_echo('simplekaltura:migration:notpossible');
}