<?php

    include "include/ConnModule.php";
    include "include/funcModule.php";
    $sqlStr = "SELECT tb_orders.i_EmployeeID AS EmpID ,tb_employees.c_FirstName AS EmpName,tb_employees.c_LastName AS EmpSName,
                SUM(tb_products.i_Price * tb_orderdetails.i_Quantity) AS EmpNet from tb_orders 
                INNER JOIN tb_orderdetails
                ON tb_orders.i_OrderID = tb_orderdetails.i_OrderID
                INNER JOIN tb_products 
                ON tb_orderdetails.i_ProductID = tb_products.i_ProductID
                INNER JOIN tb_employees 
                ON tb_orders.i_EmployeeID = tb_employees.i_EmployeeID 
                GROUP BY tb_orders.i_EmployeeID";
    $ResultSet = mysqli_query($ConnDB,$sqlStr);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Baloo+2&display=swap" rel="stylesheet">
    <style>
    body , input[type=text], select  {
        font-family: 'Kanit', serif;
        font-size: 24px;
    }

    #tbList {
    font-family: "Kanit", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    #tbList td, #tbList th {
    border: 1px solid #ddd;
    padding: 8px;
    }

    #tbList tr:nth-child(even){background-color: #f2f2f2;}

    #tbList tr:hover {background-color: #ddd;}

    #tbList th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
    }
    input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    }

    input[type=submit] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    }

    input[type=submit]:hover {
    background-color: #45a049;
    }

    input[type=reset]:hover {
    background-color: #45a049;
    }

    div {
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
    }
    </style>
</head>
<body>
    <div>
        <table id="tbList">
            <tr>
                <th>รหัสพนักงาน</th>
                <th>ชื่อ-นามสกุลพนักงาน</th>
                <th>ยอดขายรวม</th>
            </tr>
            <?php
                $GraphDara = '';
                while($Row=mysqli_fetch_array($ResultSet))
                {
                    echo "<tr>";
                        echo "<td><A href='formEachEmp.php?EmpID=".$Row['EmpID']."' >".$Row['EmpID']."</A></td>";
                        echo"<td>".$Row['EmpName']." ".$Row['EmpSName']."</td>";
                        echo"<td>".number_format($Row['EmpNet'],2)."</td>";
                    echo "</tr>";
                    $GraphDara .= "['".$Row['EmpName']." ".$Row['EmpSName']."',".$Row['EmpNet']."],";

                }
            ?>

        </table>
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = google.visualization.arrayToDataTable([
                ['EmpName', 'Employee Net'],
                <?php
                    echo $GraphDara;
                ?>
                ]);

                var options = {
                title: 'My Daily Activities'
                };

                var chart = new google.visualization.PieChart(document.getElementById('EmpChart'));
                var Barchart = new google.visualization.ColumnChart(document.getElementById('EmpBarChart'));
                chart.draw(data, options);
                Barchart.draw(data, options);
            }
        </script>
    </div>
    <div id="EmpChart" style="width: 900px; height: 500px;"></div>
    <div id="EmpBarChart" style="width: 100%; height: 500px;"></div>

</body>
</html>