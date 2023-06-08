import numpy as np
import matplotlib.pyplot as plt
import scipy.stats as stats

# Przykładowa próba
data = [50.4, 52.5, 54.6, 55.1, 55.3]

# Wyliczenie kwantyli dla próby
quantiles = np.arange(0.01, 1, 0.01)
sample_quantiles = np.quantile(data, quantiles)

# Wyliczenie kwantyli dla rozkładu normalnego
normal_quantiles = stats.norm.ppf(quantiles)

# Wykres kwantylowy
plt.scatter(normal_quantiles, sample_quantiles)
plt.plot(normal_quantiles, normal_quantiles, color='red')  # Linia oczekiwanej zgodności z rozkładem normalnym
plt.xlabel('Kwantyle rozkładu normalnego')
plt.ylabel('Kwantyle próby')
plt.title('Wykres Q-Q')
plt.show()
