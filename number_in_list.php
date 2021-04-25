<link rel="stylesheet" href="style.css">
<a href="index.php">Main</a>
<a href="message.php">Message</a>

<div class="container">
    <?php

    if (file_exists('input.json')) {
        $content = file_get_contents('input.json');
        $input = json_decode($content, true);

        if (is_array($input)) {

    ?>
            <table>
                <tr>
                    <th>firstConditionTest</th>
                    <th>secondConditionTest</th>
                    <th>thirdConditionTest</th>
                    <th>fourthdConditionTest</th>
                    <th>fifthConditionTest</th>
                </tr>
        <?php

            foreach ($input as $array) {
                echo "<tr>";

                echo "<td>";
                echo testCondition1($array);
                echo "</td>";

                echo "<td>";
                echo testCondition2($array);
                echo "</td>";

                echo "<td>";
                testCondition3($array);
                echo "</td>";

                echo "<td>";
                echo testCondition4($array);
                echo "</td>";

                echo "<td>";
                echo testCondition5($array);
                echo "</td>";

                echo "</tr>";
            }

            echo "</table>";
        }
    }


    function testCondition1($array)
    {
        $size = sizeof($array);
        for ($i = 0; $i < $size; $i++) {
            if ($array[$i] === $size) {
                return $array[$i];
            }
        }
        return "Pirmais nosacījums neizpildās";
    }

    //echo testCondition1($array1);


    /*
    Divu skaitļu summa ir vienāda ar citu skaitli;
    $array = [1, -2, 5, 10, 3, 1, 90, -3];
    (1 + (-3) === (-2)) == TRUE
    return -2;



    */
    function testCondition2($array)
    {
        $size = sizeof($array);
        for ($i = 0; $i < $size - 1; $i++) {
            for ($a = 1; $a < $size; $a++) {
                if (in_array($array[$i] + $array[$a], $array)) { //in_array funkcija šajā gadījumā pārbauda vai to summa ir vienāda ar jebkuru skaitli masīvā
                    return $array[$i] + $array[$a];
                }
            }
        }
        return "Otrais nosacījums neizpildās";
    }

    //echo testCondition2($array2);


    /* Vai skaitļu pieauguma solis ir vienāds;
    [-3, 0, 3, 6, 9] == TRUE
    [-3, 1, 3, 6, 9] == FALSE
    [-3, 9, -6, 0, 9, 3] == TRUE
    [-2, 0, 2, 4, 6] == TRUE

    $array = [-3, 9, -6, 0, 9, 3];
    sort($array);

    $array === [-6, -3, 0, 3, 6, 9];
    */

    function testCondition3($array)
    {
        sort($array);
        $incriment = $array[1] - $array[0];
        $counter = 0;
        $size = sizeof($array);
        for ($i = 0; $i < $size; $i++) {
            if ($array[$i + 1] - $array[$i]  === $incriment) {
                $counter++;
            }
        }
        if ($counter === $size - 1) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    }

    //echo testCondition3($array3);

    /* Vai trīs skaitļu summa ir vienāda ar skaitļu daudzmu
    $array = [1, 3, 4, 10, 3, 90, 2];
    $array[0] + $array[1] + $array[4] === count($array);
    1 + 3 + 3 === 7;
*/

    function testCondition4($array)
    {
        $size = sizeof($array);
        for ($a = 0; $a < $size - 2; $a++) {
            for ($b = 1; $b < $size - 1; $b++) {
                for ($c = 2; $c < $size; $c++) {
                    if ($array[$a] + $array[$b] + $array[$c] === $size) {
                        return $array[$a] + $array[$b] + $array[$c];
                    }
                }
            }
        }
        return "Ceturtais nosacījums neizpildās";
    }


    function testCondition5($array)
    {
        sort($array);
        $size = sizeof($array);
        $counter = 0;
        $a1 = 1;
        $a2 = 0;
        $a3 = 0;

        for ($i = 0; $i <= $array[0]; $i++) {
            $a3 = $a1 + $a2;
            $a1 = $a2;
            $a2 = $a3;
        }

        if (($array[0] === $a3 || $array[0] === 0) &&
            ($array[1] === 1 || $array[1] !== $array[0])
        ) {

            for ($i = 0; $i < $size - 2; $i++) {
                if ($array[$i] + $array[$i + 1] === $array[$i + 2]) {
                    $counter++;
                }
            }
            if ($counter === $size - 2) {
                return "Skaitļus var sarindot fibonači virknē";
            }
        }
        return "Piektais nosacījums neizpildās";
    }
        ?>
</div>