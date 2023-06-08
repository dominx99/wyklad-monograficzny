import numpy as np
from scipy.stats import kstest
import statistics

# Próbka danych
data = [50.4, 52.5, 54.6, 55.1, 55.3]

# Wykonanie testu Kołmogorowa-Lillieforsa
var = statistics.variance(data)

# Wyświetlenie wyników
print("Wartość statystyki testowej: ", var)
