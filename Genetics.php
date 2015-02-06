<?php
/*
	Genetics is a place to combine chromosomes
	-It doesn't care what mechanism chose the two chromosomes to mate
	-It can create random chromosomes of any base up to 36
	-It will have a public function mate() which will accept two chromosomes and perform crossover and mutation
	-A separate class should be made to interpret the genome
*/

class Genetics {

	/*
	Populates the chromosome with a string of length $length with $base different characters
	*/
	public static function createChromosome($length = 100, $base = 10) {
		$pool = Genetics::create_base_pool($base);

		for ($i=0; $i<$length; $i++) {
			$chromosome .= $pool[rand(0, count($pool)-1)];
		}
        return $chromosome;
	}

	public static function mate($base_partner, $sexy_partner, $mutation_pct = 0, $insertion_pct = 0, $deletion_pct = 0) {
		$new_chromosome = '';

		//First we find the minimum length of a parent's genome
		$min_length =  min(strlen($base_partner), strlen($sexy_partner));

        //Turn them into strings explicitly, just in case
		$parents_dna = array(
                "$base_partner",
                "$sexy_partner"
        );

		//It doesn't make sense to have a mutation rate greater than 100%
        //If that's the case, we simply make a ration of the two types of mutation
		if(($nonconservative_total = $insertion_pct + $deletion_pct) > 100) {
            $insertion_pct = floor((100/$nonconservative_total) * $insertion_pct);
            $deletion_pct = floor((100/$nonconservative_total) * $deletion_pct);
		}

		$insertions = array();
		$deletions = array();

        //We treat the parent dna's as tracks which we shift back and forth between
        $track = 0;

        //Is it a problem that when we shift tracks we could go out of the range of this for loop?
		for($i=0; $i < strlen($parents_dna[$track]); $i++) {
            //We only want to do crossover while we have both genomes available; mutations at the end are acceptable
            if($i < $min_length) {
                $new_chromosome .= substr($parents_dna[$track], $i, 1);

                //We don't want constant crossover, we want about 15-35% (According to my likely misreading of Stanhope and Daida)
                if(rand(1,100) < 25) {
                    //Switch reading from one to the other parent
                    $track = ($track === 0) ? 1 : 0;
                }
            }

            //If they specify a mutation rate, we wanna do some mutating!
            if(rand(1,100) <= $mutation_pct) {
                //I was previously using the mutation chance as seed, but then a mutation chance lower than insertion and deletion leads to problems!
                $seed = rand(1,100);
                if ($seed <= $insertion_pct) {
                    //Insertion Mutation
                    $random_parent = $parents_dna[rand(0,1)];
                    $random_parent_chunk = substr($random_parent, rand(0,strlen($random_parent)-1), 1);
                    $insertions[] = array(
                        'pos' => $i,
                        'val' => $random_parent_chunk
                    );
                } elseif ($seed <= ($insertion_pct + $deletion_pct)) {
                    //Deletion Mutation
                    $deletions[] = $i;
                } else {
                    //Point Mutation
                    //Since we don't know what base either of the parents' chromosomes are in and I refuse to make messy limitations, we have a different plan.
                    //Even if we don't know what base we're in, we know that the chromsomes must be bounded by SOME base
                    //So we just grab a random character in a random parent and then we use that!
                    $random_parent = $parents_dna[rand(0,1)];
                    $random_parent_chunk = substr($random_parent, rand(0,strlen($random_parent)-1), 1);
                    $new_chromosome = substr_replace($new_chromosome, $random_parent_chunk, $i, 1);
                }
            }
        }

		/*
		so in order to handle insertions and deletions we're gonna need to do some crazy shit:

		-Keep an array of insertions
		-Have it keep position and character to be inserted
		-After we're done with our standard crossover and mutations:
			-iterate over this insertion array and insert the characters in the positions in the $new_chrom
			-of course, they'll already be in the order that they should be inserted in, phew!
			-don't forget to keep an increment to add to where to insert
				-if I have to add in 1 and 3, then after I add the 1 the 3 should be in the 4 slot, not the 3 slot
				-you feel me, dawg?

		-Do the same for deletion!
		*/

        //changed position keeps track of how adding or subtracting characters changes each characters' index
		$changed_position = 0;
		foreach($insertions as $add) {
			//Since this replaces the 0 characters after that index, it just inserts it! PHP logic
			$position = (($add['pos'] + $changed_position) >= (strlen($new_chromosome)-1)) ? 1 : ($add['pos'] + $changed_position);
			$new_chromosome = substr_replace($new_chromosome, $add['val'], $position, 0);
			$changed_position++;
		}

		foreach($deletions as $del) {
			$new_chromosome = substr_replace($new_chromosome, '', $del + $changed_position, 1);
			$changed_position--;
		}

		return $new_chromosome;
	}

	private function create_base_pool($base){
		//At the minimum, the base pool is base 2
		$base_pool = array(
			'0',
			'1'
			);

		//Setting a hard limit of base 36.
		//Shouldn't affect functionality since any information can be encoded to any base.
		if ($base > 36){
			$base = 36;
		}

		for ($i = 2; $i<$base; $i++) {
			if ($i < 10) {
				$base_pool[] = "$i";
			} else {
				$base_pool[] = chr(55+$i);
			}
		}

		return $base_pool;
	}
}
?>
