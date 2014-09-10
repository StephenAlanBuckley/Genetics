<?php

//Alright!  This seems as good a place as any to start writing some uses for this!

/*Things I want:
-Images
-RssEater
-Moving Things that can reproduce and feed in a 2d environment (cough cough CANVAS cough cough)
-Pets
*/
require 'Genetics_class.php';

$my_shit = new Genetics;
$create_start = microtime(true);
$my_shit->from_scratch(3500, 30);
$create_end = microtime(true);

$create_duration = ($create_end - $create_start) * 1000;

$other_seed = new Genetics;
$other_seed->from_scratch(3500, 30);

$generations = 1;
$generation_durations = array();
while($generations < 1000) {
	$current_gen = array();
  $gen_start = microtime(true);
	for ($i=0; $i<2; $i++) {
		$current_gen[] = $my_shit->mate($other_seed);
	}
  $gen_end = microtime(true);
  $generation_durations[] = ($gen_end - $gen_start) * 1000;
	$my_shit = $current_gen[0]; 
	$other_seed = $current_gen[1];
	$generations++;
}


print_r($generation_durations);

print_r("Creation duration: $create_duration seconds");

?>
