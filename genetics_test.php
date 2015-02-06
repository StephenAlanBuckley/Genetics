<?php
require 'Genetics.php';

if (test_basic_mating_produces_correct_length()){
    print_r("Basic Mating produces correct length.\n");
} else {
    print_r("FAIL: Basic Mating Failed to produce correct length.\n");
}

function test_basic_mating_produces_correct_length() {
    $mom = '0472669210828649250769557889033171337358341215131107163841854261305041975106610731557932028545785299';
    $dad = '3879076696437085928046591277567854852421165973575370039267013708284536743240598135249165976256175528';

    $kid = Genetics::mate($mom, $dad);

    if (strlen($kid) == strlen($mom)) {
        return true;
    }
    return false;
}
