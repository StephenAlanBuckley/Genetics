#Create the base Genetics class which will handle mating for GAs of all varieties
import random
import math


class Genetics:

	def create_chromosome(self, length, base):
		pool = self.create_base_pool(base)
		chromosome = ''
		for x in range(0, length):
			chromosome += pool[random.randint(0, len(pool)-1)]
		return chromosome

	'''
	So, the mate function should:
		-find the length of the shortest of the two chromosomes
		-create x numbers between 0 and the shortest length where
			-x = floor(shortest length *.65)
		-sort those numbers
		-create substrings from each parent alternating on these crossover points

		length 10 chromosomes:
		AAAAAAAAAA
		BBBBBBBBBB

		crossover points at 2, 4, 6:
		AABBAABBBB  (50% of the time)
		BBAABBAAAA  (50% of the time)
	'''
	def mate(self, chromosome1, chromosome2):
		shortest = len(chromosome1) if (len(chromosome1) < len(chromosome2)) else len(chromosome2)
		parents = [chromosome1, chromosome2]

		crosses = []
		for x in range(0, int(round(shortest *0.65))):
			crosses.append(random.randint(0,shortest))
		crosses = sorted(crosses)

		rand_start = random.randint(0,1)
		new_chromosome = ''
		previous = 0
		for x in range(0, len(crosses)):
			new_chromosome += parents[(x+rand_start) % 2][previous:crosses[x]]
			previous = crosses[x]
			if x == len(crosses)-1:
				new_chromosome += parents[(x+rand_start) % 2][crosses[x]:]

		'''
		This is where mutations need to happen.
		-# of mutations = multiply length by mutation_pct, divide by 100
		-figure out the percentage of that number that insertions and deletions are
		-finish insertions
		-finish deletions
		-then do the point swaps by pulling from any point in either parent.
		'''
				
		return new_chromosome

	def create_base_pool(self, base):
		base_pool = ['0', '1']
		if base > 36: 
			base = 36

		for x in range(2,base):
			if x < 10:
				base_pool.append(str(x))
			else:
				base_pool.append(chr(55+x))
		return base_pool