import genetics_class
import timeit

something = genetics_class.Genetics()

chromey = something.create_chromosome(30, 5)
other_guy = something.create_chromosome(30, 5)
print timeit.timeit('kid = something.mate(chromey, other_guy, 50, 26, 25)', number=10000)