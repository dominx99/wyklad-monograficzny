import numpy as np

data = [1.3, 2.1, 2.7, 3.3, 3.5, 4.5, 4.7]
data = sorted(data)

# Obliczanie średniej
mean = np.mean(data)

# Obliczanie odchylenia standardowego
std = np.std(data)

# Obliczanie granic odstępstwa
lower_bound = mean - 3 * std
upper_bound = mean + 3 * std

# Znajdowanie pomiary odstające
outliers = [x for x in data if x < lower_bound or x > upper_bound]

print("Średnia: ", mean)
print("Odchylenie standardowe: ", std)
print("Dolna granica odstępstwa: ", lower_bound)
print("Górna granica odstępstwa: ", upper_bound)
print("Pomiary odstające:")
print(outliers)
