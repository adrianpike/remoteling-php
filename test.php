<?php

require 'remoteling.php';

$r = new Remoteling('2b0846cab17d80d2dae115bbedc3aa75cd732dccb5f412ea5e2451d6afd31fb9');

## Store
$rand_string = preg_replace('/([ ])/e', 'chr(rand(97,122))', '     ');
$r->set('foobar',$rand_string);
assert_equals($r->get('foobar'), $rand_string);

## Queues
$rand_string = preg_replace('/([ ])/e', 'chr(rand(97,122))', '     ');
$rand_q = preg_replace('/([ ])/e', 'chr(rand(97,122))', '     ');
$r->push($rand_q,$rand_string);
assert_equals($r->pop($rand_q), $rand_string);
assert_equals($r->pop($rand_q), ' ');

## Execution
$r->set('foobar_test_results',null);
$code = "Remoteling.store('foobar_test_results','yarr')";
$r->run_serialized($code, 'foobar');
sleep(2);
assert_equals('yarr', $r->get('foobar_test_results'));


## Test Helpers

function assert_equals($a,$b) {
	if ($a==$b) {
		echo ".";
	} else {
		echo "\nFAILURE, was looking for $a and got $b\n";	
	}
}
?>