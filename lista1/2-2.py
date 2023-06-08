import sys
from scipy import stats
import json

# Get the data from command-line argument passed by PHP
data_string = sys.argv[1]
data = json.loads(data_string)

# Perform Shapiro-Wilk test
statistic, p_value = stats.shapiro(data)

# Create a dictionary with the test results
result = {"test_statistic": statistic, "p_value": p_value}

# Convert the result to JSON string
result_string = json.dumps(result)

# Print the result
print(result_string)
