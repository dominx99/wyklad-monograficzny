import sys
import statistics as stats
import json

data_string = sys.argv[1]
data = json.loads(data_string)

variance = stats.variance(data)

result = {"variance": variance}

result_string = json.dumps(result)

print(result_string)
