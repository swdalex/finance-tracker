<!DOCTYPE html>
<html>
<head><title>Reports</title></head>
<body>
<h2>Financial Reports</h2>

<h3>Monthly Summary</h3>
<button onclick="fetchReport('monthly_summary', 'summary')">Load</button>
<pre id="summary"></pre>

<h3>Category Breakdown</h3>
<button onclick="fetchReport('category_breakdown', 'categories')">Load</button>
<pre id="categories"></pre>

<h3>Balance Trend</h3>
<button onclick="fetchReport('balance_trend', 'trend')">Load</button>
<pre id="trend"></pre>

<script>
    function fetchReport(route, elementId) {
        fetch(`/index.php?route=${route}`)
            .then(response => response.json())
            .then(data => document.getElementById(elementId).innerText = JSON.stringify(data, null, 2))
            .catch(error => console.error("Error fetching report:", error));
    }
</script>
</body>
</html>
