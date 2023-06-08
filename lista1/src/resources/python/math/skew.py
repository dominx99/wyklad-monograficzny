import sys
from scipy import stats
import json

data_string = sys.argv[1]
data = json.loads(data_string)

skew = stats.skew(data)

result = {"skew": skew}

result_string = json.dumps(result)

print(result_string)
