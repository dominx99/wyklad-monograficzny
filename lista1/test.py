import numpy as np
import matplotlib.pyplot as plt

def moving_average(sequence, window_size):
    averages = []
    for i in range(len(sequence) - window_size + 1):
        window = sequence[i:i+window_size]
        average = np.mean(window)
        averages.append(average)
    return averages

def moving_median(sequence, window_size):
    medians = []
    for i in range(len(sequence) - window_size + 1):
        window = sequence[i:i+window_size]
        median = np.median(window)
        medians.append(median)
    return medians

def calculate_residuals(sequence, values):
    residuals = [x - y for x, y in zip(sequence, values)]
    return residuals

# Dane wejściowe
sequence = [6.5, 10.0, 24.7, 17.4, 14.4, -5.2, -14.1, 10.4, 24.0, 17.0, -26.6, 18.1, -15.2]
window_size = 5

# Obliczanie ciągu średnich ruchomych
moving_averages = moving_average(sequence, window_size)

# Obliczanie ciągu median ruchomych
moving_medians = moving_median(sequence, window_size)

# Obliczanie ciągu reszt dla średniej ruchomej
residuals_averages = calculate_residuals(sequence[window_size-1:], moving_averages)

# Obliczanie ciągu reszt dla mediany ruchomej
residuals_medians = calculate_residuals(sequence[window_size-1:], moving_medians)

# Tworzenie wykresu
plt.plot(sequence, label='Dane wejściowe')
plt.plot(range(window_size-1, len(sequence)), moving_averages, label='Średnia ruchoma (m={})'.format(window_size))
plt.plot(range(window_size-1, len(sequence)), moving_medians, label='Mediana ruchoma (m={})'.format(window_size))
plt.plot(range(window_size-1, len(sequence)), residuals_averages, label='Reszty (średnia)')
plt.plot(range(window_size-1, len(sequence)), residuals_medians, label='Reszty (mediana)')
plt.xlabel('Indeks')
plt.ylabel('Wartość')
plt.legend()
plt.title('Zmienna ruchoma i reszty')
plt.show()
