<?php
include "include/ConnModule.php";
include "include/funcModule.php";

$ID = $_GET["EmpID"];
$sqlStr = "SELECT tb_orders.i_order_YY AS yy ,tb_orders.i_order_MM AS mm,
                SUM(tb_products.i_Price * tb_orderdetails.i_Quantity) AS EmpNet from tb_orders 
                INNER JOIN tb_orderdetails
                ON tb_orders.i_OrderID = tb_orderdetails.i_OrderID
                INNER JOIN tb_products 
                ON tb_orderdetails.i_ProductID = tb_products.i_ProductID
                INNER JOIN tb_employees 
                ON tb_orders.i_EmployeeID = tb_employees.i_EmployeeID where tb_orders.i_EmployeeID = $ID
                GROUP BY tb_orders.i_order_MM
                order by tb_orders.i_order_YY,tb_orders.i_order_MM";
    $ResultSet = mysqli_query($ConnDB,$sqlStr);
    $nrow = mysqli_num_rows($ResultSet); 

    function func_conv_month( $input ){ 
        $arr_month = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน",
       "กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม") ;  
       return $arr_month[ $input ] ;  }
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
        <form  method="POST" action="formEachEmp.php">
        <?php
                $sqlemp = " select * from tb_employees " ;
                $resemp = mysqli_query($ConnDB,$sqlemp) ; 
                while($Row = mysqli_fetch_array($resemp))
                {
                    $sel = "";
                    if($ID == $Row["i_EmployeeID"])
                    {
                        echo "<h3 align = 'center'>ข้อมูลยอดขายของพนักงาน รหัส :".$Row["i_EmployeeID"]." ชื่อ-นามสกุล :".$Row["c_FirstName"]." ".$Row["c_LastName"]."</h3>";
                    } 
                }
            ?>
        
        <div>
        <table id="tbList">
            <tr>
                <th>ปี</th>
                <th>เดือนที่</th>
                <th>เดือน</th>
                <th>ยอดขายรวม</th>
            </tr>
            <?php
                $Sum = 0;
                $AVG = 0;
                $GraphDara = '';
                while($Row=mysqli_fetch_array($ResultSet))
                {
                    echo "<tr>";
                        echo "<td>".$Row["yy"]."</td>";
                        echo"<td>".$Row["mm"]. "</td>";
                        $month=func_conv_month($Row["mm"]);
                        echo"<td>".$month."</td>";
                        echo"<td>".number_format($Row['EmpNet'],2)."</td>";
                    echo "</tr>";
                    $Sum +=$Row["EmpNet"];
                    $AVG = $Sum/$nrow;
                    $GraphDara .= "['".$month." ".$Row['yy']."',".$Row['EmpNet']."],";
                }
            ?>
            <tr>
            <th></th>
            <th colspan=2>ยอดรวมทั้งหมด : <?= number_format($Sum,2) ?> บาท</th>
            <th>ยอดเฉลี่ย/เดือน : <?= number_format($AVG,2) ?> บาท</th>
            </tr>

        </table>
        <br>
        <A href='form_db_final1.php'>กลับไปข้อมูลยอดขายรวมของพนักงาน</a>
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
    <div id="EmpChart" style="width: 100%; height: 500px;"></div>
    <div id="EmpBarChart" style="width: 100%; height: 500px;"></div>
        </form>
    </div>

</body>
</html>