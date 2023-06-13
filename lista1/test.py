def calculate_variance(time_series):
    n = len(time_series)
    squared_diffs = []

    for i in range(1, n):
        diff = time_series[i] - time_series[i - 1]
        squared_diffs.append(diff ** 2)

    sum_squared_diffs = sum(squared_diffs)
    variance = sum_squared_diffs / (n - 1)

    return variance

# Example time series
time_series = [11.4, -4.4, 17.7, -7.1, -17.4, -1.4, 14.8, 8.9, -2.5, 6.9, -12.6, -21.4, 7.6, 16.9, 13.4, -30.5, 39.9, -34.7, -21.5, -17.0, 22.1, 11.4]

variance = calculate_variance(time_series)

print("Variance:", variance)
