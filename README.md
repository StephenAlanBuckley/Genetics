Genetics
========
The Genetics repo is to keep in one place all of the classes I'll need to create a good, dependable Genetic Algorithm.

It supports crossovers with or without mutations and allows the user to determine the percentage of mutations which are insertions, deletions and point.

As time goes on this repo may also come to hold examples of these classes in action!

The way that mutations are handled should be expounded upon:
	There is an optional 3rd, 4th, and 5th parameter to the Genetics::mate function.
	mutation_pct 	the number of mutations per 100 nucleotides
	insertion_pct 	the number of insertions per 100 mutations
	deletion_pct 	the number of deletions per 100 mutations

	While you can set the percentage, the absolute number of mutations will be:
		(mutation_pct * len(new_chromosome))/100

	After the number of mutations is calculated, the number of insertions and deletions are calculated in a similar way:
		(insertion_pct * number_of_mutations)/100

	This means that those numbers are deterministic- it is not actually a percentage chance for each nucleotide to mutate or be removed.  If there is any variance in the percentage of insertions or deletions, you will see a steady rise or fall in the length of the resulting genomes produced by mating.  The ratio will be very precisely followed. (Obviously if the length of a genome is less than 100 then certain small percentage differences may go unnoticed- if the length of each chromosome is 50 then a 25% insertion and 26% deletion rate will come out to the same number of insertions and deletions.)