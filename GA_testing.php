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
$my_shit->from_scratch(3500, 30);

$other_seed = new Genetics;
$other_seed->from_scratch(3500, 30);

$generation_durations = array();
$average_genome_length = array();
for ($generations = 1; $generations < 1000; $generations++) {
    $current_gen = array();
    $gen_start = microtime(true);
    for ($i=0; $i<2; $i++) {
        $current_gen[] = $my_shit->mate($other_seed, 30, 29, 1);
    }
    $gen_end = microtime(true);
    $this_generation_duration = ($gen_end - $gen_start) * 1000;
    $generation_durations[] = $this_generation_duration;
    $this_generation_total_length = 0;
    $this_generation_population = 0;
    foreach ($current_gen as $creature) {
        $this_generation_total_length += strlen($creature->chromosome);
        $this_generation_population += 1;
    }
    $this_generation_average_length = $this_generation_total_length / $this_generation_population;
    $average_genome_length[] = $this_generation_average_length;

    print_r("$this_generation_average_length || $this_generation_duration\n");

    $my_shit = $current_gen[0];
    $other_seed = $current_gen[1];
}


print_r("\n");
?>
