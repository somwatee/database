<?php
    include "include/ConnModule.php";
    $CondPrice = $_POST["cond_price"];
    $sqlStr ="SELECT * FROM tb_products WHERE i_Price > $CondPrice";
    echo $sqlStr;
    //$sql = "SELECT * FROM tb_products\n"
      //  . "WHERE i_Price > 50";
    
    $ResultSet = mysqli_query($ConnDB,$sqlStr);

    // while($Row=mysqli_fetch_array($ResultSet))
    // {
    //     echo $Row["c_ProductName"];
    //     echo $Row["i_Price"];
    //     echo "<BR>";
    // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css?family=Baloo+2&display=swap" rel="stylesheet">
    <style>
        body {
              font-family: 'Kanit', serif;
              font-size: 48px;
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
        <form  method="POST" action="formdb.php">
        <label for="fname">Product Price</label>
            <input type="text" name="cond_price" value=<?=$CondPrice?>>
            <input type="submit" value="Search">
            <input type="reset" value="Clera">
        </form>
        <table id="tbList" >
            <tr>
            <th>ชื่อสิ้นค้า</th>
            <th>ราคาสิ้นค้า</th>
            </tr>
        <?php
            $Sum = 0;
            while($Row=mysqli_fetch_array($ResultSet))
            {
                echo "<tr>";
                    echo "<td>".$Row["c_ProductName"]."</td>";
                    echo "<td>".$Row["i_Price"]."</td>";
                echo "</tr>";
                $Sum +=$Row["i_Price"];
            }
        ?>
        <tr>
            <th>ราคารวมทั้งหมด</th>
            <th><?= $Sum ?></th>
        </tr>
        </table>
    </div>
</body>
</html>