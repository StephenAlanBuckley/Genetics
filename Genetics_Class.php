<?php
//Let's make a super simple little PHP GA for fun and more fun!

/*
In order to make this a usable class, I'm going to try to make it a simple idea.
	-Genetics is a place to store and combine chromosomes
	-It doesn't care what mechanism chose the two chromosomes to mate
	-It will be seeded with a random chromosome of base X
	-It will have a public function mate() which will accept another Genetics object which will crossover the two
	-A separate class should be made to interpret the chromosome
*/

class Genetics {
	public $chromosome;


	/*
	Populates the chromosome with a string of length $length with $base different characters
	*/
	public function from_scratch($length, $base) {
		$pool = $this->create_base_pool($base);
		for ($i=0; $i<$length; $i++) {
			$this->chromosome .= $pool[rand(0, count($pool)-1)];
		}
	}

	public function mate($sexy_partner, $mutation_pct=0, $insertion_pct=0, $deletion_pct=0){
		$new_chromosome = '';

		//First we find which is shorter
		$min_length = (strlen($this->chromosome) <= strlen($sexy_partner->chromosome)) ? strlen($this->chromosome) : strlen($sexy_partner->chromosome);
		$parents_dna = array(
			"$this->chromosome",
			"$sexy_partner->chromosome"
			);
		//Set the chromosome to be the longer of the two chromosomse, or the partner's if they're the same length
		$new_chromosome = $parents_dna[rand(0,1)];

		//If they put in 95 and 95, then we would have some weird problems below that we solve by making a ratio of the two
		if(($nonconservative_tot = $insertion_pct + $deletion_pct) > 100) {
			$insertion_pct = floor((100/$nonconservative_tot) * $insertion_pct);
			$deletion_pct = floor((100/$nonconservative_tot) * $deletion_pct);
		}

		$insertions = array();
		$deletions = array();

		for($i=0; $i < strlen($new_chromosome); $i++) {

			//We only want to do crossover while we have both genomes available- if one is longer than the other then the tail should be intact except for
			//mutations!
			if($i < $min_length) {
				//We don't want constant crossover, we want about 15-35% (According to my likely misreading of Stanhope and Daida)
				//The thing is, 50% of the time we're going to be pulling our random_parent_chunk from the parent that $new_chromosome is already set to!
				//So I chose 50% of 50%, which is the middle of the 15-35% range I was aiming for
				if(rand(1,100) < 50) {
					$random_parent_chunk = substr($parents_dna[rand(0,1)], $i, 1);
					$new_chromosome = substr_replace($new_chromosome, $random_parent_chunk, $i, 1);
				}
			}

			//If they specify a mutation rate, we wanna do some mutating!
			if(rand(1,100) <= $mutation_pct) {
				//I was previously using the mutation chance as seed, but then a mutation chance lower than insertion and deletion leads to problems!
				$seed = rand(1,100);
				//Since we don't know what base either of the parents' chromosomes are in and I refuse to make messy limitations, we have a different plan.
				//Even if we don't know what base we're in, we know that the chromsomes must be bounded by SOME base
				//So we just grab a random character in a random parent and then we use that!
				$random_parent = $parents_dna[rand(0,1)];
				$random_parent_chunk = substr($random_parent, rand(0,strlen($random_parent)-1), 1);
				$new_chromosome = substr_replace($new_chromosome, $random_parent_chunk, $i, 1);
				if ($seed <= $insertion_pct) {
					$random_parent = $parents_dna[rand(0,1)];
					$random_parent_chunk = substr($random_parent, rand(0,strlen($random_parent)-1), 1);
					$insertions[] = array(
						'pos' => $i,
						'val' => $random_parent_chunk
						);
				} elseif ($seed <= ($insertion_pct + $deletion_pct)) {
					$deletions[] = $i;
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

		$changed_position = 0;
		foreach($insertions as $add) {
			//Since this replaces the 0 characters after that index, it just inserts it!
			$position = (($add['pos'] + $changed_position) >= (strlen($new_chromosome)-1)) ? 1 : ($add['pos'] + $changed_position);
			$new_chromosome = substr_replace($new_chromosome, $add['val'], $position, 0);
			$changed_position++;
		}
		foreach($deletions as $del) {
			$new_chromosome = substr_replace($new_chromosome, '', $del + $changed_position, 1);
			$changed_position--;
		}

		$kid = new Genetics;
		$kid->chromosome = $new_chromosome;

		return $kid;	
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
