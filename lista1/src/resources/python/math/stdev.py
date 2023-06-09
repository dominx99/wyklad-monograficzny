import sys
from statistics import stdev
import json

data_string = sys.argv[1]
data = json.loads(data_string)

stdev = stdev(data)

result = {"stdev": stdev}

result_string = json.dumps(result)

print(result_string)
