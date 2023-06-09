import sys
import pandas as pd
from scipy.stats import norm
import json

data_string = sys.argv[1]
data = json.loads(data_string)

n = len(data)
x_bar = sum(data) / n
s = (sum((x - x_bar) ** 2 for x in data) / (n-1)) ** 0.5
s_1 = s * (n / (n - 1)) ** 0.5

intermediate_results = pd.DataFrame([
    data,
    [(x - x_bar) / s for x in data],
    [norm.cdf((x - x_bar) / s) for x in data],
    [(i / n) for i in range(1, n + 1)],
    [abs(norm.cdf((x - x_bar) / s) - (i / n)) for i, x in enumerate(data, start=1)],
    [(i - 1) / n for i in range(1, n + 1)],
    [abs(norm.cdf((x - x_bar) / s) - ((i - 1) / n)) for i, x in enumerate(data, start=1)]
])

dmaxes = max(intermediate_results.iloc[4]), max(intermediate_results.iloc[6])
dmax = max(dmaxes)

data = {
    "maximum": dmax,
    "result": intermediate_results.to_dict(orient='list'),
    "s": s,
    "s1": s_1,
}

print(json.dumps(data, indent=4))
