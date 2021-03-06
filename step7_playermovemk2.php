<?php
    function cls() {
        print("\033[2J\033[;H");
    }

    function display($field) {
        for ($i = 0; $i < count($field); $i++) {
            for ($n = 0; $n < count($field[$i]); $n++) {
                echo $field[$i][$n];
            }
            echo "\n";
        } 
    }

    function setup_ground($x,$y) {
        $field = [];
        for ($i = 0; $i <= $x; $i++) {
            for ($n = 0; $n <= $y; $n++) {
                $field[$i][$n] = "\033[0;33m.\033[0m";
            }
        }
        return $field;
    }

    function set_player($x,$y,$field) {
        $GLOBALS["nowp"] = array($x, $y, $field[$x][$y]);
        $field[$x][$y] = "\033[1;37m@\033[0m";
        return $field;
    }

    function player_move($way, $field) {
        if ($way == "w" && array_key_exists($GLOBALS["nowp"][0] - 1, $field)) {
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = $GLOBALS["nowp"][2];
            $GLOBALS["nowp"][0] = $GLOBALS["nowp"][0] - 1;
            $GLOBALS["nowp"][2] = $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]];
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = "\033[1;37m@\033[0m";
        } else if ($way == "d" && array_key_exists($GLOBALS["nowp"][1] + 1, $field[$GLOBALS["nowp"][0]])) {
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = $GLOBALS["nowp"][2];
            $GLOBALS["nowp"][1] = $GLOBALS["nowp"][1] + 1;
            $GLOBALS["nowp"][2] = $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]];
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = "\033[1;37m@\033[0m";
        } else if ($way == "s" && array_key_exists($GLOBALS["nowp"][0] + 1, $field)) {
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = $GLOBALS["nowp"][2];
            $GLOBALS["nowp"][0] = $GLOBALS["nowp"][0] + 1;
            $GLOBALS["nowp"][2] = $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]];
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = "\033[1;37m@\033[0m";
        } else if ($way == "a" && array_key_exists($GLOBALS["nowp"][1] - 1, $field[$GLOBALS["nowp"][0]])) {
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = $GLOBALS["nowp"][2];
            $GLOBALS["nowp"][1] = $GLOBALS["nowp"][1] - 1;
            $GLOBALS["nowp"][2] = $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]];
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = "\033[1;37m@\033[0m";
        }
        return $field;
    }

    function glassgrow($field) {
        for ($i = 0; $i < count($field); $i++) {
            for ($n = 0; $n < count($field[$i]); $n++) {
                $prob = rand(1,100);
                if ($prob == 100) {
                    $field[$i][$n] = "\033[1;32mW\033[0m";
                    $growed[0][] = $i . "," . $n;
                }
            }
        } 
        $roop = 1;
        do {
            $growed[$roop] = [];
            for ($i = 0; $i < count($field); $i++) {
                for ($n = 0; $n < count($field[$i]); $n++) {
                    if (array_key_exists($i - 1, $field) && $field[$i - 1][$n] == "\033[1;32mW\033[0m") {
                        $prob = rand(1,2);
                        if ($prob == 2) {
                            $field[$i][$n] = "\033[1;32mW\033[0m";
                            $growed[$roop][] = $i . "," . $n;
                        }
                    } else if (array_key_exists($n + 1, $field[$i]) && $field[$i][$n + 1] == "\033[1;32mW\033[0m") {
                        $prob = rand(1,2);
                        if ($prob == 2) {
                            $field[$i][$n] = "\033[1;32mW\033[0m";
                            $growed[$roop][] = $i . "," . $n;
                        }
                    } else if (array_key_exists($i + 1, $field) && $field[$i + 1][$n] == "\033[1;32mW\033[0m") {
                        $prob = rand(1,2);
                        if ($prob == 2) {
                            $field[$i][$n] = "\033[1;32mW\033[0m";
                            $growed[$roop][] = $i . "," . $n;
                        }
                    } else if (array_key_exists($n - 1, $field[$i]) && $field[$i][$n - 1] == "\033[1;32mW\033[0m") {
                        $prob = rand(1,2);
                        if ($prob == 2) {
                            $field[$i][$n] = "\033[1;32mW\033[0m";
                            $growed[$roop][] = $i . "," . $n;
                        }
                    }
                }
            } 
            $roop++;
        } while ($growed[$roop - 1] == $growed[$roop - 2]); 
        return $field;
    }

    cls();

    $field = setup_ground(20,50);
    $field_g = glassgrow($field);
    $field_p = set_player(10, 30, $field_g);
    display($field_p);
    $i = true;

    while ($i == true) {
        echo $nowp[0] . "," . $nowp[1];
        if ($nowp[2] == "\033[0;33m.\033[0m") {
            echo " dirt\n";
        } else if ($nowp[2] == "\033[1;32mW\033[0m") {
            echo " glass\n";
        }
        $ctrl = trim(fgets(STDIN));
        if ($ctrl == "w" || $ctrl == "d" || $ctrl == "s" || $ctrl == "a") {
            $field_p = player_move($ctrl, $field_p);
            display($field_p);
        } else if ($ctrl == "e") {
            echo "終了します。\n";
        break;
        }
    }


?>