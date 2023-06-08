import numpy as np
from scipy.stats import skew, kurtosis, t

# Data
data = [60, 63, 64, 66, 67, 68, 69, 70, 70, 71, 72, 72, 74, 74, 80, 80, 81, 81, 81, 81, 81, 82, 83, 84, 84, 85, 85, 85, 89, 89, 90, 93, 95, 98]

# Calculate skewness and kurtosis
skewness = skew(data)
kurt = kurtosis(data)

# Sample size
n = len(data)

# Calculate test statistics
t_value_skewness = (skewness * np.sqrt(n)) / np.sqrt(6 * (n - 1))
t_value_kurt = (kurt * np.sqrt(n)) / np.sqrt(24 * (n - 1))

# Degrees of freedom for t-distribution
df = n - 1

# Critical value for 10% significance level (two-tailed test)
critical_value = t.ppf(0.95, df)
print('skewness: ', skewness)
print('kurt: ', kurt)
print('n: ', n)
print('t_value_skewness: ', t_value_skewness)
print('t_value_kurt: ', t_value_kurt)
print('df: ', df)

# Perform the test for skewness
if np.abs(t_value_skewness) > critical_value:
    print("Skewness is statistically significant at the 10% significance level.")
else:
    print("Skewness is not statistically significant at the 10% significance level.")

# Perform the test for kurtosis
if np.abs(t_value_kurt) > critical_value:
    print("Kurtosis is statistically significant at the 10% significance level.")
else:
    print("Kurtosis is not statistically significant at the 10% significance level.")

