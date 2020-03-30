<?php
    include "include/ConnModule.php";
    include "include/funcModule.php";

    $CondPrice = $_POST["cond_price"];
    $CondCat = $_POST["cond_cat"];
    $CondSup = $_POST["cond_sup"];
    $sqlStr ="SELECT * FROM tb_products WHERE i_Price > $CondPrice";
    if (($CondCat>0) || ($CondSup>0))
    {
        $sqlStr .= " AND i_CategoryID = $CondCat OR i_SupplierID = $CondSup ";
    }
    
    echo $sqlStr;
    //$sql = "SELECT * FROM tb_products\n"
      //  . "WHERE i_Price > 50";
    
    $ResultSet = mysqli_query($ConnDB,$sqlStr);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
        <form  method="POST" action="formdb_search_Supplier.php">
            <label for="fname">Product Price</label>
            <input type="text" name="cond_price" value=<?=$CondPrice?>>

            <label for="cond_cat">Product Categories</label>
            <select name="cond_cat">
                <option value=0>=== All Categories ===</option>
            <?php
                $sqlCat = " select * from tb_categories " ;
                $resCat = mysqli_query($ConnDB,$sqlCat) ; 
                while($Row = mysqli_fetch_array($resCat))
                {
                    $sel = "";
                    if($CondCat == $Row["i_CategoryID"])
                    {
                        $sel = "selected";
                    }
                    echo "<option $sel value=".$Row["i_CategoryID"].">".$Row["c_CategoryName"]."</option>" ; 
                }
            ?>
            </select>

            <label for="cond_sup">Supplier</label>
            <select name="cond_sup">
                <option value=0>=== All Supplier ===</option>
            <?php
                $sqlSup = " select * from tb_suppliers " ;
                $resSup = mysqli_query($ConnDB,$sqlSup) ; 
                while($Row = mysqli_fetch_array($resSup))
                {
                    $selSup = "";
                    if($CondSup == $Row["i_SupplierID "])
                    {
                        $selSup = "selected";
                    }
                    echo "<option $selSup value=".$Row["i_SupplierID "].">".$Row["c_SupplierName"]."</option>" ; 
                }
            ?>
            </select>


            <input type="submit" value="Search">
            <input type="reset" value="Clera">
        </form>
        <table id="tbList" >
            <tr>
            <th>ชื่อสิ้นค้า</th>
            <th>หมวดหมู่สินค้า</th>
            <th>ผู้ส่งสินค้า</th>
            <th>ราคาสิ้นค้า</th>
            </tr>
        <?php
            $Sum = 0;
            while($Row=mysqli_fetch_array($ResultSet))
            {
                echo "<tr>";
                    echo "<td>".$Row["c_ProductName"]."</td>";
                    $CatName = GetCatName($ConnDB,$Row["i_CategoryID"]);
                    echo "<td>".$CatName."</td>";
                    $SupName = GetSupName($ConnDB,$Row["i_SupplierID"]);
                    echo "<td>".$SupName."</td>";
                    echo "<td>".$Row["i_Price"]."</td>";
                echo "</tr>";
                $Sum +=$Row["i_Price"];
            }
        ?>
        <tr>
            <th colspan=3>ราคารวมทั้งหมด</th>
            <th><?= $Sum ?></th>
        </tr>
        </table>
    </div>
</body>
</html>