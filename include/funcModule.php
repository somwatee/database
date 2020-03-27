<?php
function GetCatName($DB,$Id)
{
    $CatName = "" ; 
    $sql = " SELECT c_CategoryName FROM tb_categories WHERE i_CategoryID = $Id " ; 
    $Res = mysqli_query($DB,$sql);
    while($Row = mysqli_fetch_array($Res)) 
        { $CatName = $Row["c_CategoryName"] ;}
    return  $CatName ; 
}

?>