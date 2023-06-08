import numpy as np

def stats_kstest(data, distribution='norm'):
    # Rank the data
    N = len(data)
    data_sorted = np.sort(data)
    
    # Calculate max(i/N - Ri)
    plus_max = []
    for i in range(1, N + 1):
        x = i / N - data_sorted[i-1]
        plus_max.append(x)
    K_plus_max = np.sqrt(N) * np.max(plus_max)
    
    # Calculate max(Ri - ((i-1)/N))
    minus_max = []
    for i in range(1, N + 1):
        y = (i-1) / N
        y = data_sorted[i-1] - y
        minus_max.append(y)
    K_minus_max = np.sqrt(N) * np.max(minus_max)
    
    # Calculate KS Statistic
    K_max = max(K_plus_max, K_minus_max)
    
    return K_max

# Sample data
data = [50.4, 52.5, 54.6, 55.1, 55.3]

# Perform KS test
ks_statistic = stats_kstest(data, 'norm')

print("KS Statistic:", ks_statistic)
