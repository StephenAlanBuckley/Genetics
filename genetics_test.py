import genetics_class

something = genetics_class.Genetics()

chromey = something.create_chromosome(5, 2)
other_guy = something.create_chromosome(5, 2)
kid = something.mate(chromey, other_guy)

print chromey 
print other_guy
print kid