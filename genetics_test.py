import genetics_class

something = genetics_class.Genetics()

chromey = something.create_chromosome(30, 5)
other_guy = something.create_chromosome(30, 5)
kid = something.mate(chromey, other_guy, 50, 26, 25)

print chromey
print other_guy
print kid

print len(chromey)
print len(other_guy)
print len(kid)