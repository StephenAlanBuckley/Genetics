#Create the base Genetics class which will handle mating for GAs of all varieties

class Genetics:

	def __init__():
			#nothing here

	def create_base_pool(base):
		base_pool = ['0', '1']
		if base > 36: 
			base = 36

		for x in range(2,base):
			if x < 10:
				base_pool.extend([str(x)])
			else:
				base_pool.extend([chr(55+x)])

		return base_pool

