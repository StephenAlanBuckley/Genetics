<?php
require 'Genetics.php';

if (test_basic_mating_produces_correct_length()){
    print_r("Basic Mating produces correct length.\n");
} else {
    print_r("FAIL: Basic Mating Failed to produce correct length.\n");
}

if (test_insertion_inserts_correct_amount()){
    print_r("Insertion Doubling produces correct length.\n");
} else {
    print_r("FAIL: Insertion Doubling Failed to produce correct length.\n");
}

if (test_deletion_deletes_correct_amount()){
    print_r("Full Deletion produces correct length.\n");
} else {
    print_r("FAIL: Full Deletion Failed to produce correct length.\n");
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

function test_insertion_inserts_correct_amount() {
    $mom = '0472669210828649250769557889033171337358341215131107163841854261305041975106610731557932028545785299';
    $dad = '3879076696437085928046591277567854852421165973575370039267013708284536743240598135249165976256175528';

    //100% mutation rate, 100% of which are insertions
    //Should double the length of the genome
    $kid = Genetics::mate($mom, $dad, 100, 100);

    if (strlen($kid) == (2 * strlen($mom))) {
        return true;
    } else {
        return false;
    }

}

function test_deletion_deletes_correct_amount() {
    $mom = '0472669210828649250769557889033171337358341215131107163841854261305041975106610731557932028545785299';
    $dad = '3879076696437085928046591277567854852421165973575370039267013708284536743240598135249165976256175528';

    //100% mutation rate, 100% of which are deletions
    //Should double the length of the genome
    $kid = Genetics::mate($mom, $dad, 100, 0, 100);

    if (strlen($kid) == 0) {
        return true;
    } else {
        return false;
    }

}
