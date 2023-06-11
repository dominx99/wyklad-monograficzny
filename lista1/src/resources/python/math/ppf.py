import sys
from scipy.stats import dixon, t
import json

data_string = sys.argv[1]
data = json.loads(data_string)

alpha = 0.95
n = len(data)
Q_critical = t.ppf(alpha, n - 1)
ppf = Q_critical

result = {"ppf": ppf}

result_string = json.dumps(result)

print(result_string)
