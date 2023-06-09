n = 34
mean = 78.44
S = 9.66

class_intervals = [60, 67.6, 75.2, 82.8, 90.4, 98]
class_counts = [0, 0, 0, 0, 0]

data = [60, 63, 64, 66, 67, 68, 69, 70, 70, 71, 72, 72, 74, 74, 80, 80, 81, 81, 81, 81, 81, 82, 83, 84, 84, 85, 85, 85, 89, 89, 90, 93, 95, 98]  # Umieść swoje dane w tej liście

# Liczenie liczebności dla każdej klasy
for value in data:
    if value >= class_intervals[0] and value < class_intervals[1]:
        class_counts[0] += 1
    elif value >= class_intervals[1] and value < class_intervals[2]:
        class_counts[1] += 1
    elif value >= class_intervals[2] and value < class_intervals[3]:
        class_counts[2] += 1
    elif value >= class_intervals[3] and value < class_intervals[4]:
        class_counts[3] += 1
    elif value >= class_intervals[4] and value <= class_intervals[5]:
        class_counts[4] += 1

# Obliczanie wartości p1, p2, p3, p4, p5
p_values = [class_count / n for class_count in class_counts]

# Wyświetlanie wartości p1, p2, p3, p4, p5
for i, p_value in enumerate(p_values):
    print(f"p{i+1}: {p_value}")
