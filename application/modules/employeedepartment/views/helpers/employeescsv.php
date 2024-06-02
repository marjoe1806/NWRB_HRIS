<table border="1">
        <?php 
        if(isset($list['Data']['details']) && sizeof($list['Data']['details']) > 0): ?>
            <tr>
                <td style='font-weight: bold;'>EMPLOYEE ID NUMBER</td>
                <?php 
                foreach ($list['Data']['details'][0] as $k => $v) {
                    $isId = strpos($k,"id");
                    $isWith = strpos($k,"with");
                    //var_dump($isWith);
                    if((!is_numeric($isId) && !is_numeric($isWith)) && $k != "id" && $k != "employee_number" && $k != "uses_biometrics" && $k != "regular_shift" && $k != "tax" && $k != "gmp" && $k != "0th" && $k != "modified_by" && $k != "is_active" && $k != "date_created"){
                ?>
                        <td style='font-weight: bold;'>
                            <?php 
                                $replace = str_replace('_', ' ', $k);
                                echo strtoupper($replace); 
                            ?>
                        </td>    
                <?php 
                    }
                }
                ?>
            </tr>
        <?php endif; ?>
        <?php 
        if(isset($list['Data']['details']) && sizeof($list['Data']['details']) > 0): 
            foreach ($list['Data']['details'] as $index1 => $value1) { 
        ?>
            <tr>    
                <td><?php echo $value1['employee_id_number'] ?></td>
                <?php 
                foreach($value1 as $k1=>$v1){ 
                    $isId2 = strpos($k1,"id");
                     $isWith2 = strpos($k1,"with");
                    if((!is_numeric($isId2) && !is_numeric($isWith2)) && $k1 != "id" && $k1 != "employee_number" && $k1 != "uses_biometrics" && $k1 != "regular_shift" && $k1 != "tax" && $k1 != "gmp" && $k1 != "0th" && $k1 != "modified_by" && $k1 != "is_active" && $k1 != "date_created"){
                ?>
                    <td>
                        <?php  
                            if($v1 == "1")
                                echo "YES";
                            else if($v1 == "0")
                                echo "NO";
                            else
                                echo $v1; 
                        ?>
                            
                    </td>   
                 <?php
                    } 
                } 
                ?>
            </tr>
        <?php }
        endif; ?>
</table>