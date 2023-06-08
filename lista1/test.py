from scipy.stats import kurtosis, skew

data = [60, 63, 64, 66, 67, 68, 69, 70, 70, 71, 72, 72, 74, 74, 80, 80, 81, 81, 81, 81, 81, 82, 83, 84, 84, 85, 85, 85, 89, 89, 90, 93, 95, 98]

kurtosis_coeff = abs(skew(data))

print("Kurtosis Coefficient:", kurtosis_coeff)

