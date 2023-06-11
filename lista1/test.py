import numpy as np
from scipy.stats import t

def dixon_test(data, alpha=0.05):
    data = np.array(data)
    n = len(data)
    sorted_data = np.sort(data)
    range_max = sorted_data[-1] - sorted_data[0]

    critical_value = t.ppf(1 - alpha / (2 * n), n - 2)
    test_statistic = range_max / (sorted_data[-1] - sorted_data[1])

    return test_statistic > critical_value

def grubbs_test(data, alpha=0.05):
    data = np.array(data)
    n = len(data)
    mean = np.mean(data)
    std = np.std(data, ddof=1)

    critical_value = t.ppf(1 - alpha / (2 * n), n - 2)

    test_statistics = np.abs(data - mean) / std
    max_statistic = np.max(test_statistics)

    return max_statistic > critical_value

# Przykładowa próba
sample = [2.86, 2.89, 2.9, 2.91, 2.99]

# Test Dixona
is_outlier_dixon = dixon_test(sample)
print("Czy istnieje odstająca wartość według testu Dixona:", is_outlier_dixon)

# Test Grubbsa
is_outlier_grubbs = grubbs_test(sample)
print("Czy istnieje odstająca wartość według testu Grubbsa:", is_outlier_grubbs)
