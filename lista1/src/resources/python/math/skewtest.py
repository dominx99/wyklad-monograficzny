import sys
from scipy import stats
import json

data_string = sys.argv[1]
data = json.loads(data_string)

statistic, p_value = stats.skewtest(data)

result = {"test_statistic": statistic, "p_value": p_value}

result_string = json.dumps(result)

print(result_string)
