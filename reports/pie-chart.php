<?php
header('Content-Type: text/html; charset=utf-8');

$conn = mysqli_connect("localhost", "root", "", "tst");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ==== Revenue Over Time Section ====
$group = isset($_GET['group']) ? $_GET['group'] : 'daily';

switch ($group) {
    case 'weekly':
        $select_field = "YEAR(order_date) AS year, WEEK(order_date) AS week";
        $group_field = "YEAR(order_date), WEEK(order_date)";
        $label_format = "CONCAT('Week ', WEEK(order_date), ' (', YEAR(order_date), ')')";
        break;
    case 'monthly':
        $select_field = "YEAR(order_date) AS year, MONTH(order_date) AS month";
        $group_field = "YEAR(order_date), MONTH(order_date)";
        $label_format = "DATE_FORMAT(order_date, '%b %Y')";
        break;
    default:
        $select_field = "DATE(order_date) AS day";
        $group_field = "DATE(order_date)";
        $label_format = "DATE(order_date)";
        break;
}

$sql_revenue = "
    SELECT $label_format AS label, SUM(oi.price * oi.quantity) AS total_revenue
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    GROUP BY $group_field
    ORDER BY MIN(order_date)
";
$result_revenue = mysqli_query($conn, $sql_revenue);
if (!$result_revenue) {
    die("Revenue query failed: " . mysqli_error($conn));
}

$revenue_data = [['Date', 'Revenue', ['role' => 'style']]];
while ($row = mysqli_fetch_assoc($result_revenue)) {
    $revenue_data[] = [$row['label'], (float)$row['total_revenue'], 'color: #4caf50'];
}
$chart_width = max(600, count($revenue_data) * 50);

// ==== Product Quantity Section ====
$sql_products = "
    SELECT p.product_name, SUM(oi.quantity) AS total_quantity
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    GROUP BY p.product_name
";
$result_products = mysqli_query($conn, $sql_products);
$product_data = [['Product', 'Quantity Sold']];
while ($row = mysqli_fetch_assoc($result_products)) {
    $product_data[] = [$row['product_name'], (int)$row['total_quantity']];
}

// ==== Revenue by Category Section ====
$sql_category = "
    SELECT c.category AS category_name, SUM(oi.price * oi.quantity) AS revenue
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    JOIN categories c ON p.category_id = c.category_id
    GROUP BY c.category
    ORDER BY revenue DESC
";
$result_category = mysqli_query($conn, $sql_category);
$category_data = [["Category", "Revenue", ["role" => "style"]]];
while ($row = mysqli_fetch_assoc($result_category)) {
    $category_data[] = [$row['category_name'], (float)$row['revenue'], '#4CAF50'];
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Dashboard</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", { packages: ["corechart"] });
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // Revenue Over Time
            var revenueData = google.visualization.arrayToDataTable(<?php echo json_encode($revenue_data); ?>);
            var revenueOptions = {
                title: 'Revenue Over Time (<?php echo ucfirst($group); ?>)',
                hAxis: { title: 'Date' },
                vAxis: { title: 'Revenue (in Rs)' },
                legend: 'none'
            };
            var revenueChart = new google.visualization.ColumnChart(document.getElementById("revenue_chart"));
            revenueChart.draw(revenueData, revenueOptions);

            // Product Sales Pie
            var pieData = google.visualization.arrayToDataTable(<?php echo json_encode($product_data); ?>);
            var pieOptions = {
                title: 'Top Products by Quantity Sold',
                pieHole: 0.4,
                backgroundColor: 'transparent'
            };
            var pieChart = new google.visualization.PieChart(document.getElementById('piechart'));
            pieChart.draw(pieData, pieOptions);

            // Revenue by Category
            var categoryData = google.visualization.arrayToDataTable(<?php echo json_encode($category_data); ?>);
            var view = new google.visualization.DataView(categoryData);
            view.setColumns([0, 1,
                {
                    calc: "stringify",
                    sourceColumn: 1,
                    type: "string",
                    role: "annotation"
                },
                2]);
            var categoryOptions = {
                title: "Revenue by Product Category",
                width: 600,
                height: 400,
                bar: {groupWidth: "95%"},
                legend: { position: "none" },
            };
            var categoryChart = new google.visualization.ColumnChart(document.getElementById("category_chart"));
            categoryChart.draw(view, categoryOptions);
        }

        function updateGrouping(select) {
            window.location.href = '?group=' + select.value;
        }
    </script>
    <style>
        body {
            background-color: rgba(247, 196, 87, 0.95);
            padding: 40px;
            font-family: sans-serif;
            color: #333;
        }
        .logout, .back {
            position: absolute;
            font-size: 20px;
            top: 10px;
            color: black;
            text-decoration: none;
        }
        .logout { right: 10px; }
        .back { left: 10px; font-size: 25px; color: rgb(165, 24, 92); }
        .logout:hover, .back:hover { color: white; }
        h2 { color: #333; }
    </style>
</head>
<body>
    <a href="http://localhost/php/aa.htm" class="back">&larr;</a>
    <a href="logout.php" class="logout" title="Logout">⏻</a>

    <center style="margin-top:25px;"><h2>Top Products by Quantity Sold</h2>
    <div style="background-color:rgba(165, 24, 92, 0.2); width:990px; height:430px; margin-top:25px; padding:20px; border-radius:4px; box-shadow: 10px 11px 10px rgba(139, 9, 9, 0.57);">
    <!--<div style="width: 100%; display: flex; justify-content: center; margin-top:25px; ">
        <div style="background-color: rgba(165, 24, 92, 0.2); width: 1000px; padding: 10px; border-radius: 4px;">-->
            <div id="piechart" style="width: 1000px; height: 500px; margin: auto;"></div>
        </div>
    </div></center>

    <center><h2 style = "margin-top:50px;">Revenue Over Time</h2>
    <div style="background-color:rgba(165, 24, 92, 0.36); width:990px; height:430px; margin-top:25px; padding:20px; border-radius:4px; box-shadow: 10px 11px 10px rgba(139, 9, 9, 0.57);">
        <label for="groupSelect"><strong>Group By:</strong></label>
        <select id="groupSelect" onchange="updateGrouping(this)" style="margin-bottom:25px;">
            <option value="daily" <?php if ($group == 'daily') echo 'selected'; ?>>Daily</option>
            <option value="weekly" <?php if ($group == 'weekly') echo 'selected'; ?>>Weekly</option>
            <option value="monthly" <?php if ($group == 'monthly') echo 'selected'; ?>>Monthly</option>
        </select>
        <div style="overflow-x: auto; overflow-y: hidden; width: 100%; margin: auto;">
            <div id="revenue_chart" style="width: <?php echo $chart_width; ?>px; height: 350px;"></div>
        </div>
    </div></center>

    <center><h2 style = "margin-top:50px;">Revenue by Product Category</h2>
      <!--<div style="background-color:rgba(165, 24, 92, 0.2); width:1030px; height:450px;">-->
    <div style="background-color:rgba(165, 24, 92, 0.2); width:990px; height:430px; margin-top:25px; padding:20px; border-radius:4px; box-shadow: 10px 11px 10px rgba(139, 9, 9, 0.57);">
        <label for="groupSelect"><strong>Group By:</strong></label>
        <select id="groupSelect" onchange="updateGrouping(this)" style="margin-bottom:25px;">
            <option value="daily" <?php if ($group == 'daily') echo 'selected'; ?>>Daily</option>
            <option value="weekly" <?php if ($group == 'weekly') echo 'selected'; ?>>Weekly</option>
            <option value="monthly" <?php if ($group == 'monthly') echo 'selected'; ?>>Monthly</option>
        </select>
        <div style="overflow-x: auto; overflow-y: hidden; width: 100%; margin: auto;">
            <div id="category_chart" style="width: <?php echo $chart_width; ?>px; height: 350px;"></div>
        </div>  
    </div></center>
</body>
</html>
