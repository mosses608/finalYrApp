import os
import json
import pandas as pd
from sklearn.linear_model import LinearRegression
import numpy as np

# === Step 1: Load historical data ===
project_root = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
data_path = os.path.join(project_root, 'storage', 'app', 'waste_data.json')
output_path = os.path.join(project_root, 'storage', 'app', 'waste_data.json')

print(f"🔄 Reading data from: {data_path}")
data = pd.read_json(data_path)

# Ensure 'month' is datetime and sorted
data['month'] = pd.to_datetime(data['month'], errors='coerce')
data = data.dropna(subset=['month'])  # Drop invalid dates
data = data.sort_values('month')

if data.empty:
    print("❌ Error: No valid datetime data found in 'month' column.")
    exit()

# === Step 2: Prepare training data ===
data['month_num'] = range(len(data))
X = data[['month_num']]
y = data['total_weight']

# Train model
model = LinearRegression()
model.fit(X, y)

# Predict next 5 months
future_months = np.array(range(len(data), len(data) + 5)).reshape(-1, 1)
predictions = model.predict(future_months)

# === Step 3: Create prediction results ===
last_month = data['month'].max()
results = []

for i in range(5):
    future_date = last_month + pd.DateOffset(months=i + 1)
    results.append({
        'month': future_date.strftime('%Y-%m'),
        'predicted_weight': round(predictions[i], 2)
    })

# Save to JSON
os.makedirs(os.path.dirname(output_path), exist_ok=True)
with open(output_path, "w") as f:
    json.dump(results, f, indent=4)

print(f"✅ Prediction file created successfully at: {output_path}")
