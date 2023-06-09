from decimal import Decimal
import numpy as np

a = Decimal('0.1')

result = pow(a, Decimal(0.5))

print(result)

def calculate_S(data):
    sorted_data = np.sort(data)
    n = len(sorted_data)
    ecdf = np.arange(1, n + 1) / n
    d_plus = np.max(np.abs(ecdf - np.arange(1, n + 1) / n))
    d_minus = np.max(np.abs(ecdf - np.arange(0, n) / n))
    S = np.max([d_plus, d_minus])
    return S

def calculate_S1(data):
    unique_data = np.unique(data)
    n = len(unique_data)
    ecdf = np.arange(1, n + 1) / n
    d = np.abs(ecdf - np.arange(1, n + 1) / n)
    S1 = np.max(d)
    return S1

# Przykładowa próba
data = np.array([11.1, 5.4, 18.0, 7.8, 15.2, 13.5, 15.2, 20.0, 7.8])

# Obliczenie statystyki testowej S
S = calculate_S(data)
print("Statystyka testowa S:", S)

# Obliczenie statystyki testowej S₁
S1 = calculate_S1(data)
print("Statystyka testowa S₁:", S1)
