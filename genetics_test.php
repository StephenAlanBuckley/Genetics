<?php
require 'Genetics_class.php';

$mom = Genetics::createChromosome();
print_r("Mom: $mom\n");

$dad = Genetics::createChromosome();
print_r("Dad: $dad\n");

$kid = Genetics::mate($mom, $dad);

print_r("Kid: $kid\n");
