from scipy.stats import norm

n = 31  # rozmiar próbki
alfa = 0.01  # poziom istotności

# Obliczanie wartości krytycznej
k_critical = norm.ppf(1 - alfa / 2) / n**(1/2)

print(k_critical)
