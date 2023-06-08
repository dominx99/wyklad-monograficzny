import sys
from scipy import stats
import json

data_string = sys.argv[1]
data = json.loads(data_string)

kurtosis = stats.kurtosis(data)

result = {"kurtosis": kurtosis}

result_string = json.dumps(result)

print(result_string)
