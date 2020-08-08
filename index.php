<?php
			error_reporting(E_ALL & ~E_NOTICE);
			/*function error($str) {
				#foreach($_COOKIE as $key => $value) setcookie($key, '', time() - 3600, '/');
				exit($str);
			}*/
			function getInfantry() {
				if (!is_null($_COOKIE["infantry"])) {
					$arr = json_decode($_COOKIE["infantry"]);
					/*if (strcmp(gettype($arr), "array")) {
						error("Ошибка куки: неверный тип infantry");
					}
					else if (count($arr) == 0) {
						error("Ошибка куки: infantry не может быть пустым");
					}*/
					/*for ($i = 0; $i < count($arr); $i++) {
						$ret .= strval($arr[$i]);
						if ($i < count($arr) - 1) {
							$ret .= ",";
						}
					}*/
				}
				return $arr;
			}
			function printAllInfantry ($dbh, $side, $set) {
				$ids = getInfantry();
				$query = "select*from infantry where Сторона='";
			    if ($side) {
			    	$sideStr = "Торговый Протекторат";
			        $query .= $sideStr;
			    }
			    else {
			    	$sideStr = "Империя Полярис";
			        $query .= $sideStr;
			    }
			    if (!$set) {
			    	$query .= "' and Название in (select Название from infantry where id in (";
			    	$query .= str_repeat('?,', count($ids) - 1) . '?';
        			$query .= "))";
			    }
			    else {
			    	$query .= "'";
			    }
			    $query .= " order by Название;";
			    $stmt = $dbh->prepare($query);
			    try {$stmt->execute($ids);}
    			catch(PDOException $e){error($e->getMessage());}
    			$arr = $stmt->fetchAll();
    			#print_r($arr);
    			echo "<table border=\"0\" style=\"margin: auto;\">";
    			$numOfUnits = $stmt->rowCount();
    			#$numOfUnits = 13;
    			$ini_array = parse_ini_file("settings.ini");
    			$numOfUnits = $numOfUnits / 6;
    			$numOfRows = ($numOfUnits-($numOfUnits%$ini_array['sizerow']))/$ini_array['sizerow'];
    			if ($numOfUnits%$ini_array['sizerow'] > 0) {
    				$numOfRows += 1;
    			}
    			for ($i = 0; $i < $numOfRows; $i++) {
    				echo "<tr>";
    				#$k = 0;
    				if ($i == $numOfRows - 1) {
    					$jmax = $numOfUnits%$ini_array['sizerow'];
    					if ($jmax == 0) {
    						$jmax = $ini_array['sizerow'];
    					}
    				}
    				else {
    					$jmax = $ini_array['sizerow'];
    				}
    				for ($j = 0; $j < $jmax; $j++) {
    					/*if (!strcmp($arr[getInfantry()[$k]]['Сторона'],$sideStr)) {
    						$num = getInfantry()[$k];
    					}
    					else {
    						while(strcmp($arr[getInfantry()[$k]]['Сторона'],$sideStr)) {
	    						$k++;
	    						if (!strcmp($arr[getInfantry()[$k]]['Сторона'],$sideStr)) {
		    						$num = getInfantry()[$k];
		    						break;
		    					}
	    					}
    					}
    					$num = 0;*/
    					/*if (strcmp($arr[$k]['Сторона'],$sideStr)) {

    					}*/
    					echo "<td class=\"unit\" id=\"inf";
    					echo $arr[$i*$ini_array['sizerow']*6+$j*6]['id'];
    					if($set) {
    						echo "\" data-cost=\"";
    						echo $arr[$i*$ini_array['sizerow']*6+$j*6]['Стоим'];
    					}
    					echo "\">";
    					echo "<img title=\"";
    					if (!$set) {
			                echo "Дальн=";
			                echo $arr[$i*$ini_array['sizerow']*6+$j*6]['Дальн'];
			                echo " Мощн=";
			                echo $arr[$i*$ini_array['sizerow']*6+$j*6]['Мощн'];
			                echo " ББ=";
			                echo $arr[$i*$ini_array['sizerow']*6+$j*6]['ББ'];
			            }
			            else {
			                echo "Стоим=";
			                echo $arr[$i*$ini_array['sizerow']*6+$j*6]['Стоим'];
			            }
			            echo " Бр=";
			            echo $arr[$i*$ini_array['sizerow']*6+$j*6]['Бр'];
			            echo "\" src=\"/img/";
			            echo $arr[$i*$ini_array['sizerow']*6+$j*6]['image'];
			            echo "\" width=\"133\" height=\"143\" alt=\"error\">";
			            if ($set) {
			                echo "<br>";
			                echo "<div><span class=\"input-infantry\">0</span><button class=\"plus\">+</button><button class=\"minus\">-</button></div>";
			            }
			            echo "</td>";
			            #$k++;
    				}
    				echo "</tr>";
    			}
    			echo "</table>";
			    /*while($row = $queryDbh->fetch())
				{
					echo $row['Название'] . "<br>";
				}*/
				if (!$set) {
					for ($i = 0; $i < $numOfUnits*6; $i++) {
						#echo $arr[$i]['Название'];
						#echo "<br>";
						if ($i%6 == 0) {
							$i6 = $i;
							echo "<div style=\"display: none;\" class=\"spoiler\" id=\"spoiler";
			                echo $arr[$i]['id'];
			                echo "\" style=\"display: none;\">";
			                echo "<table>";
						}
						if ($i%3 == 0) {
							$i3 = $i;
							echo "<tr>";
						}
						#echo $arr[$i]['id'];
						echo "<td class=\"units";
						if (!strcmp($arr[$i]['Дальн'], '-') || !strcmp($arr[$i]['Дальн'], '0Д6')) {
							echo " melee";
						}
						echo "\" id=\"infs";
						echo $arr[$i]['id'];
						echo "\" data-num=\"";
						if (strcmp($arr[$i]['Мощн'], "-") != 0) {
							list($num, $side) = mb_split("Д", $arr[$i]['Мощн']);
							echo $num;
							echo "\" data-side=\"";
							echo $side;
						}
						else {
							echo "0\" data-side=\"6";
						}
						echo "\" data-armor=\"";
						echo $arr[$i]['Бр'];
						echo "\" data-cc=\"";
						echo $arr[$i]['ББ'];
						echo "\">";
						#echo "<td>";
						#echo "$i:$i3:$i6";
						echo "<img title=\"Дальн=";
			            echo $arr[$i]['Дальн'];
			            echo " Мощн=";
			            echo $arr[$i]['Мощн'];
			            echo " ББ=";
			            echo $arr[$i]['ББ'];
			            echo " Бр=";
			            echo $arr[$i]['Бр'];
			            echo "\" src=\"/img/";
			            echo $arr[$i]['image'];
			            echo "\" width=\"133\" height=\"143\" alt=\"error\">";
			            echo "</td>";
			            if ($i - $i3 == 2 || $i == $numOfUnits*6 - 1) {
			            	#echo "$i:$i3";
			                echo "</tr>";
			            }
			            if ($i - $i6 == 5 || $i == $numOfUnits*6 - 1) {
			            	#echo "$i:$i6";
			                echo "</table>";
			                echo "</div>";
			            }
					}
				}
			}
			function getMachines($side) {
				if ($side == 0) {
					$nums = json_decode($_COOKIE['polaris-machines']);
				}
				else if ($side == 1) {
					$nums = json_decode($_COOKIE['protectorat-machines']);
				}
				if (!is_null($_COOKIE["object-machines"]) && strcmp($_COOKIE['object-machines'], "[]")) {//ЗДЕСЬ МОЖЕТ БЫТЬ ОШИБКА - ЛУЧШЕ COUNT
					$obj = json_decode($_COOKIE["object-machines"]);
					if ($side == 0 || $side == 1) {
			            $size = count($nums);
			        }
			        #$num = 0;
			        $ret = array();
			        foreach ($obj as $key => $val) {
			        	$flag = false;
			        	if ($side == 0 || $side == 1) {
				        	for ($i = 0; $i < $size; $i++) {
				        		if (intval(substr($key, 3)) == $nums[$i]) {
				        			$flag = true;
				        		}
				        	}
				        }
				        else if ($side == 2) {
				        	$flag = true;
				        }
				        if ($flag) {
				        	array_push($ret, array('id'=>$val[0], 'ammunition'=>$val[1], 'strength'=>$val[2], 'speed'=>$val[3]));
				        }
				    }
				    return $ret;
			    }
			    else {
			    	return null;
			    }
			}
			function printLongRangeWeapons($dbh, $machine) {
				$query = "select Орудия from warmachine where id=";
				$query .= "?";
				$query .= ";";
				$stmt = $dbh->prepare($query);
			    try {$stmt->execute(array($machine['id']));}
    			catch(PDOException $e){error($e->getMessage());}
    			$row = $stmt->fetch();
    			//на test.php проверено что плейсхолдеры не пропускают ничего кроме значения а значит и ничего кроме адекватного значения по айдишнику не придет и в последующем запросе без плейсхолдера все будет гладка ЕСЛИ КОНЕЧНО НЕ БУДЕТ ПОДМЕНЫ АЙДИШНИКА НО ЕСЛИ БУДЕТ И У НЕГО ИЗ_ЗА ЭТОГО НЕ БУДЕТ РАБОТАТЬ ТО НИЧЕГО СТРАШНОГО
    			$query = "select*from longrangeweapon where Название in (";
    			$query .= $row[0];
    			$query .= ");";
    			$stmt = $dbh->prepare($query);
			    try {$stmt->execute();}
    			catch(PDOException $e){error($e->getMessage());}
    			$row = $stmt->fetchAll();
    			echo "<div style=\"display: none;\" class=\"spoiler-lrw\" id=\"spoiler-lrw";
    			echo $machine['id'];
    			echo "\" style=\"display: none;\">";
    			echo "<table>";
    			for ($i = 0; $i < count($row); $i++) {
    				echo "<tr>";
    				echo "<td id=\"lrw";
			        echo $row[$i]['id'];
			        echo "\" data-num=\"";
			        list($num, $side) = mb_split("Д", $row[$i]['Мощн']);
			        echo $num;
			        echo "\" data-side=\"";
			        echo $side;
			        echo "\">";
			        echo "<img title=\"Урон=";
			        echo $row[$i]['Урон'];
			        echo "\" src=\"/img/";
			        echo $row[$i]['image'];
			        echo "\" width=\"200\" height=\"99\" alt=\"error\">";
			        echo "</td>";
			        echo "</tr>";
    			}
    			echo "</table></div>";
			}
			function printAllBlowUps($dbh) {
				$query = "select*from blowup;";
				$stmt = $dbh->prepare($query);
			    try {$stmt->execute();}
    			catch(PDOException $e){error($e->getMessage());}
    			$row = $stmt->fetchAll();
    			echo "<table border=\"0\" style=\"margin: auto;\">";
    			$numOfBlows = $stmt->rowCount();
    			$ini_array = parse_ini_file("settings.ini");
    			$numOfRows = ($numOfBlows-($numOfBlows%$ini_array['sizerow']))/$ini_array['sizerow'];
    			if ($numOfBlows%$ini_array['sizerow'] > 0) {
    				$numOfRows += 1;
    			}
    			for ($i = 0; $i < $numOfRows; $i++) {
    				echo "<tr>";
    				#$k = 0;
    				if ($i == $numOfRows - 1) {
    					$jmax = $numOfBlows%$ini_array['sizerow'];
    					if ($jmax == 0) {
    						$jmax = $ini_array['sizerow'];
    					}
    				}
    				else {
    					$jmax = $ini_array['sizerow'];
    				}
    				for ($j = 0; $j < $jmax; $j++) {
    					echo "<td id=\"blu";
    					echo $row[$i*$ini_array['sizerow']+$j]['id'];
    					echo "\" class=\"blowup\" data-num=\"";
    					list($num, $side) = mb_split("Д", $row[$i*$ini_array['sizerow']+$j]['Попадание']);
			            echo $num;
			            echo "\" data-side=\"";
			            echo $side;
			            echo "\">";
			            echo "<img title=\"Урон=";
			            echo $row[$i*$ini_array['sizerow']+$j]['Урон'];
			            echo "\" src=\"/img/";
			            echo $row[$i*$ini_array['sizerow']+$j]['image'];
			            echo "\" width=\"100\" height=\"100\" alt=\"error\">";
			            echo "</td>";
    				}
    				echo "</tr>";
    			}
    			echo "</table>";
			}
			function printObjectsMachines($dbh, $machines, $hasPlusMinus, $side) {
				if ($side == 0) {
			        $iMachines = json_decode($_COOKIE['polaris-machines']);
			    }
			    else {
			        $iMachines = json_decode($_COOKIE['protectorat-machines']);
			    }
    			// Сортировка массива пузырьком
			    for ($i = 0; $i < count($machines) - 1; $i++) {
			        for ($j = 0; $j < count($machines) - $i - 1; $j++) {
			            if ($iMachines[$j] > $iMachines[$j + 1]) {
			                // меняем элементы местами
			                $temp = $iMachines[$j];
			                $iMachines[$j] = $iMachines[$j + 1];
			                $iMachines[$j + 1] = $temp;
			            }
			        }
			    }
				if (count($machines) != 0) {
					$rowi = 0;
					echo "<table border=\"0\" style=\"margin: auto;\">";
					echo "<tr>";
					$ini_array = parse_ini_file("settings.ini");
					for ($i = 0; $i < count($machines); $i++) {
						$rowi++;
						if ($rowi != 1 && $rowi%$ini_array['sizerowmachine'] == 1) {
							echo "</tr><tr>";
						}
						$query = "select*from warmachine where id=";
						$query .= "?";
						$query .= ";";
						$stmt = $dbh->prepare($query);
			    		try {$stmt->execute(array($machines[$i]['id']));}
    					catch(PDOException $e){error($e->getMessage());}
    					$row = $stmt->fetch();
    					echo "<td id=\"obj";
    					echo $iMachines[$i];
    					if (!$hasPlusMinus) {
			                if ($side) {
			                    echo "-protectorat";
			                }
			                else {
			                    echo "-polaris";
			                }
			            }
			            echo "\" class=\"obj";
			            echo $machines[$i]['id'];
			            echo " machine parent\" data-armor=\"";
			            echo stristr($row['Прочность'], '-', true);
			            echo "\">";
			            echo "<img title=\"Скорострельность=";
			            echo $row['Скорострельность'];
			            echo " Боезапас=";
			            echo $machines[$i]['ammunition'];
			            echo " Прочность=";
			            echo $row['Прочность'];
			            echo " Прочность=";
			            echo $machines[$i]['strength'];
			            echo " Скорость=";
			            echo $machines[$i]['speed'];
			            echo "\" src=\"/img/";
			            echo $row['image'];
			            echo "\" width=\"266\" height=\"200\" alt=\"error\">";
			            //display:none - arrows
			            echo "<img class=\"arrow\" src=\"/img/arrows.jpg\" width=\"20\" height=\"20\">";
			            if ($hasPlusMinus) {
			            	echo "<br>
<div class=\"strength\">Прочность<button class=\"plus\">+</button><button class=\"minus\">-</button></div>
<br>
<div class=\"ammunition\">Боезапас<button class=\"plus\">+</button><button class=\"minus\">-</button></div>";
			            }
			            echo "</td>";
					}
					echo "</tr>";
					echo "</table>";
				}
				
				if (!$hasPlusMinus) {
					$temp = array();
			        for ($i = 0; $i < count($machines); $i++) {
			        	array_push($temp, $machines[$i]['id']);
			        }
			        for ($i = 0; $i < count($machines); $i++) {
			            for ($j = 0; $j < count($machines); $j++) {
			                if ($temp[$i] == $temp[$j] && $i != $j) {
			                    $temp[$j] = 0;
			                }
			            }
			        }
			        for ($i = 0; $i < count($machines); $i++) {
			            if ($temp[$i] != 0) {
			                printLongRangeWeapons($dbh, $machines[$i]);
			            }
			        }
			    }

			}
			function createJsonMachines($machines) {
				$result = array();
				$num = 0;
				for ($i = 0; $i < count($machines); $i++) {
					if (intval($machines[$i]['id']) != 0) {
						$temp = array(intval($machines[$i]['id']), intval($machines[$i]['ammunition']), intval($machines[$i]['strength']), intval($machines[$i]['speed']));
						$result = array_merge($result, array('obj'.$num=>$temp));
						$num++;
					}
				}
				return json_encode($result);
				#return json_encode(array("obj0"=>array(1,2,3,4)));
			}
			function printAllWarMachines($dbh,$side) {
				$query = "select*from warmachine where Сторона='";
				if ($side) {
			        $query .= "Торговый Протекторат";
			    }
			    else {
			        $query .= "Империя Полярис";
			    }
			    $query .= "' OR Сторона='Нейтральный';";
			    $stmt = $dbh->prepare($query);
	    		try {$stmt->execute();}
				catch(PDOException $e){error($e->getMessage());}
				$row = $stmt->fetchAll();
				echo "<table border=\"0\" style=\"margin: auto;\">";
    			$numOfMachines = $stmt->rowCount();
    			$ini_array = parse_ini_file("settings.ini");
    			$numOfRows = ($numOfMachines-($numOfMachines%$ini_array['sizerowmachine']))/$ini_array['sizerowmachine'];
    			if ($numOfMachines%$ini_array['sizerowmachine'] > 0) {
    				$numOfRows += 1;
    			}
    			for ($i = 0; $i < $numOfRows; $i++) {
    				echo "<tr>";
    				if ($i == $numOfRows - 1) {
    					$jmax = $numOfMachines%$ini_array['sizerowmachine'];
    					if ($jmax == 0) {
    						$jmax = $ini_array['sizerowmachine'];
    					}
    				}
    				else {
    					$jmax = $ini_array['sizerowmachine'];
    				}
    				for ($j = 0; $j < $jmax; $j++) {
    					echo "<td id=\"";
			            echo $row[$i*$ini_array['sizerowmachine']+$j]['id'];
			            if (!strcmp($row[$i*$ini_array['sizerowmachine']+$j]['Сторона'], "Нейтральный")) {
			                echo "-";
			                if ($side) {
			                	echo 1;
			                }
			                else {
			                	echo 0;
			                }
			            }
			            echo "\" data-cost=\"";
			            echo $row[$i*$ini_array['sizerowmachine']+$j]['Стоимость'];
			            echo "\">";
			            echo "<img src=\"/img/";
			            echo $row[$i*$ini_array['sizerowmachine']+$j]['image'];
			            echo "\" width=\"266\" height=\"200\" alt=\"error\" title=\"Стоим=";
			            echo $row[$i*$ini_array['sizerowmachine']+$j]['Стоимость'];
			            echo " Скорострельность=";
			            echo $row[$i*$ini_array['sizerowmachine']+$j]['Скорострельность'];
			            echo " Боезапас=";
			            echo $row[$i*$ini_array['sizerowmachine']+$j]['Боезапас'];
			            echo " Прочность=";
			            echo $row[$i*$ini_array['sizerowmachine']+$j]['Прочность'];
			            echo " Скорость=";
			            echo $row[$i*$ini_array['sizerowmachine']+$j]['Скорость'];
			            echo "\">";
			            echo "<br>\r\n";
			            echo "<div><span class=\"input\">0</span><button class=\"plus\">+</button><button class=\"minus\">-</button></div>";
			            echo "</td>";
			        }
			        echo "</tr>";
			    }
			    echo "</table>";
			}
			function subDeleteFromSideMachines($sideMachines,$target) {
				$sizeSideMachines = count($sideMachines);
				$fixSizeSideMachines = $sizeSideMachines;
				for ($z = 0; $z < $fixSizeSideMachines; $z++) {
                    if ($sideMachines[$z] == substr($target, 3)) {
                        $sizeSideMachines--;
                        $tempSideMachines = array();
                        for ($x = 0; $x < $z; $x++) {
                            array_push($tempSideMachines, $sideMachines[$x]);
                        }
                        for ($x = $z + 1; $x < $sizeSideMachines + 1; $x++) {
                            array_push($tempSideMachines, $sideMachines[$x]);
                        }
                        $sideMachines = $tempSideMachines;
                    }
                }
                for ($z = 0; $z < $sizeSideMachines; $z++) {
                    if ($sideMachines[$z] > substr($target, 3)) {
                        $sideMachines[$z]--;
                    }
                }
                return $sideMachines;
			}
			function deleteFromSideMachines(&$polarisMachines,&$protectoratMachines,$target) {
                $polarisMachines = subDeleteFromSideMachines($polarisMachines,$target);
                $protectoratMachines = subDeleteFromSideMachines($protectoratMachines,$target);
                /*header("Set-Cookie: polaris-machines=".json_encode($polarisMachines), false);
	            header("Set-Cookie: protectorat-machines=".json_encode($protectoratMachines), false);*/
			}
			function plusMinusStrength($dbh,$isMinus) {
				$machines = getMachines(2);
				if (!$isMinus) {
					$machines[substr($_POST["target"], 3)]['strength'] += 1;
				}
				else {
					$machines[substr($_POST["target"], 3)]['strength'] -= 1;
				}
	            $query = "select*from warmachine where id=";
	            $query .= "?";
	            $query .= ";";
	            $stmt = $dbh->prepare($query);
	    		try {$stmt->execute(array($machines[substr($_POST["target"], 3)]['id']));}
				catch(PDOException $e){error($e->getMessage());}
				$row = $stmt->fetch();
	            $strength = explode("-", $row['Прочность']);
	            $levelAfter = 1000;
	            if ($machines[substr($_POST["target"], 3)]['strength'] <= $strength[0] && $machines[substr($_POST["target"], 3)]['strength'] > $strength[1]) {
	                $levelAfter = 0;
	            }
	            else if ($machines[substr($_POST["target"], 3)]['strength'] <= $strength[1] && $machines[substr($_POST["target"], 3)]['strength'] > $strength[2]) {
	                $levelAfter = 1;
	            }
	            else if ($machines[substr($_POST["target"], 3)]['strength'] <= $strength[2] && $machines[substr($_POST["target"], 3)]['strength'] > 0) {
	                $levelAfter = 2;
	            }
	            else if ($machines[substr($_POST["target"], 3)]['strength'] <= 0) {
	                $levelAfter = 3;
	            }
	            if ($levelAfter == 1000 && !$isMinus) {
	                $machines[substr($_POST["target"], 3)]['strength'] -= 1;
	            }
	            $polarisMachines = json_decode($_COOKIE['polaris-machines']);
	            $protectoratMachines = json_decode($_COOKIE['protectorat-machines']);
	            if ($levelAfter == 3 && $isMinus) {
	            	$machines[substr($_POST["target"], 3)]['id'] = 0;
	            	$polarisMachines = json_decode($_COOKIE['polaris-machines']);
	                $protectoratMachines = json_decode($_COOKIE['protectorat-machines']);
                    deleteFromSideMachines($polarisMachines,$protectoratMachines,$_POST["target"]);
                    header("Set-Cookie: polaris-machines=".json_encode($polarisMachines)."; max-age=604800", false);
	            	header("Set-Cookie: protectorat-machines=".json_encode($protectoratMachines)."; max-age=604800", false);
	            }
	            $query .= "select*from warmachine where id=";
	            $speed = explode("-", $row['Скорость']);
	            if (($levelAfter < 3 && $levelAfter >= 0 && !$isMinus) || ($levelAfter != 3 && $isMinus)) {
	                $machines[substr($_POST["target"], 3)]['speed'] = $speed[$levelAfter];
	            }
	            if (createJsonMachines($machines) == '[]') {
	            	header("Set-Cookie: isEmptyMachines=1"."; max-age=604800", false);
	            }
	            header("Set-Cookie: object-machines=".createJsonMachines($machines)."; max-age=604800",false);
	            if ($isMinus) {
	            	if ($machines[substr($_POST["target"], 3)]['strength'] == $strength[1] || $machines[substr($_POST["target"], 3)]['strength'] == $strength[2] || $machines[substr($_POST["target"], 3)]['strength'] == 0) {//||==0
	            		if (mb_strtolower($row['Название'], 'UTF-8') != str_replace("'", "", mb_strtolower($row['Орудия'], 'UTF-8'))) {//МОЖНО БЫЛО ПРОСТО ПО КЛАССУ В БД МОБИЛЬНОЕ ОРУДИЕ
	            			if (rand()%3 == 0) {
		            			echo "yes";
		            		}
	            		}
	            	}
	            }
			}
			function plusMinusAmmunition($dbh,$isMinus) {
				$machines = getMachines(2);
	            if (!$isMinus) {
					$machines[substr($_POST["target"], 3)]['ammunition'] += 1;
				}
				else {
					$machines[substr($_POST["target"], 3)]['ammunition'] -= 1;
				}
				if ($machines[substr($_POST["target"], 3)]['ammunition'] < 0 && $isMinus) {
					$machines[substr($_POST["target"], 3)]['ammunition'] = 0;
				}
	            $query = "select * from warmachine where id=";
	            $query .= "?";
	            $query .= ";";
	           	$stmt = $dbh->prepare($query);
	    		try {$stmt->execute(array($machines[substr($_POST["target"], 3)]['id']));}
				catch(PDOException $e){error($e->getMessage());}
				$row = $stmt->fetch();
	            if ($machines[substr($_POST["target"], 3)]['ammunition'] > $row['Боезапас'] && !$isMinus) {
	            	$machines[substr($_POST["target"], 3)]['ammunition'] -= 1;
	            }
	            header("Set-Cookie: object-machines=".createJsonMachines($machines)."; max-age=604800");
			}
			function minusStrength($dbh, $flag, $targetSide, $target, $damage, &$tempHTML, &$logs, $isAttacker, &$machines, &$polarisMachines, &$protectoratMachines) {
				#$machines = getMachines(2);
				$query = "select*from warmachine where id=";
                $query .= "?";
                $query .= ";";
                $stmt = $dbh->prepare($query);
				try {$stmt->execute(array($machines[substr($target, 3)]['id']));}
    			catch(PDOException $e){error($e->getMessage());}
    			$row = $stmt->fetch();
                $temp = $machines[intval(substr($target, 3))]['strength'];
                $machines[intval(substr($target, 3))]['strength'] -= $damage;
                if ($flag) {
                	$tempHTML .= "<p>Броня пробита</p>";
	                $logs = "<p>Броня пробита</p>";
	                $tempHTML .= "<p>Урон - ";
	                $logs .= "<p>Урон - ";
	                $tempHTML .= $damage;
	                $logs .= $damage;
	                $tempHTML .= "</p>";
	                $logs .= "</p>";
                }
                else {
                	if ($isAttacker) {
                		$tempHTML .= "<p>Урон атакующему - ";
	                	$logs .= "<p>Урон атакующему - ";
	                	$tempHTML .= $damage;
	                	$logs .= $damage;
	                	$tempHTML .= "</p>";
	                	$logs .= "</p>";
                	}
                	else {
                		$tempHTML .= "<p>Урон защищаемуся - ";
	                	$logs .= "<p>Урон защищаемуся - ";
	                	$tempHTML .= $damage;
	                	$logs .= $damage;
	                	$tempHTML .= "</p>";
	                	$logs .= "</p>";
                	}
                }
                $strength = explode("-", $row['Прочность']);
                if ($machines[intval(substr($target, 3))]['strength'] <= $strength[0] && $machines[intval(substr($target, 3))]['strength'] > $strength[1]) {
                    $levelAfter = 0;
                }
                else if ($machines[intval(substr($target, 3))]['strength'] <= $strength[1] && $machines[intval(substr($target, 3))]['strength'] > $strength[2]) {
                    $levelAfter = 1;
                }
                else if ($machines[intval(substr($target, 3))]['strength'] <= $strength[2] && $machines[intval(substr($target, 3))]['strength'] > 0) {
                    $levelAfter = 2;
                }
                else if ($machines[intval(substr($target, 3))]['strength'] <= 0) {
                    $levelAfter = 3;
                }
                $query = "select*from warmachine where id=";
                $query .= "?";
                $query .= ";";
                $stmt = $dbh->prepare($query);
				try {$stmt->execute(array($machines[substr($target, 3)]['id']));}
    			catch(PDOException $e){error($e->getMessage());}
    			$row = $stmt->fetch();
                $speed = explode("-", $row['Скорость']);
                if ($levelAfter != 3) {
                    $machines[substr($target, 3)]['speed'] = $speed[$levelAfter];
                }
                if ($temp <= $strength[0] && $temp > $strength[1]) {
                    $levelBefore = 0;
                }
                else if ($temp <= $strength[1] && $temp > $strength[2]) {
                    $levelBefore = 1;
                }
                else if ($temp <= $strength[2] && $temp > 0) {
                    $levelBefore = 2;
                }
                else if ($temp <= 0) {
                    $levelBefore = 3;
                }
                $numOfDice = $levelAfter - $levelBefore;
                if ($numOfDice > 0) {
                	if (mb_strtolower($row['Название'], 'UTF-8') != str_replace("'", "", mb_strtolower($row['Орудия'], 'UTF-8'))) {
                        $tempHTML .= "
<p>Значения бросков теста на смерть пилота: ";
                        $isPilotKilled = false;
                        for ($i = 0; $i < $numOfDice; $i++) {
                            $val = 1 + rand()%6;
                            if ($val > 4) {
                                $isPilotKilled = true;
                            }
                            $tempHTML .= $val;
                            $tempHTML .= " ";
                        }
                        echo "</p>";
                        if ($isPilotKilled) {
                            $tempHTML .= "<p>Пилот убит</p>";
                            $logs .= "<p>Пилот убит</p>";
                        }
                    }
                }
                if ($machines[substr($target, 3)]['strength'] <= 0) {
                    deleteFromSideMachines($polarisMachines,$protectoratMachines,$targetSide);
                    $machines[substr($target, 3)]['id'] = 0;
                    $tempHTML .= "<p>Машина уничтожена</p>";
                    $logs .= "<p>Машина уничтожена</p>";
                    $size = count(json_decode($_COOKIE["object-machines"], true));
                    $size--;
                    if ($size < 0) {
                        $size = 0;
                    }
                    #header("Set-Cookie: size-object-machines=".$size);
                    if ($size == 0) {
                        header("Set-Cookie: isEmptyMachines=1"."; max-age=604800", false);
                    }
                }
                #header("Set-Cookie: object-machines=".createJsonMachines($machines), false);
			}
			try {
				$host = "localhost";
				$db_name = "oleg_robogear";
				$user = "oleg";
				$pass = "asdf";
				$dsn = "mysql:host=$host;dbname=$db_name";

				$opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);

				$dbh = new PDO($dsn, $user, $pass);

				/*$query = $dbh->query('SELECT * FROM users where login=\'' . $_POST["login"] . '\'');
				$row = $query->fetch();
				if ($row["password"] == md5($_POST["pass"])) {
					$_SESSION["user"] = $_POST["login"];
					echo 'Вы авторизированы, теперь можете добавлять или удалять товары';
				}
				else {
					$_SESSION["user"] = "";
					echo 'Авторизация не прошла';
				}*/
			}
			catch (PDOException $e) {
				die('Подключение не удалось: ' . $e->getMessage());
			}
			if ($_POST["method"] == "testshot:chooseattacker") {
				echo "<!DOCTYPE html>
<html>
	<head>
		<meta charset=\"utf-8\">
		<title>Помощник для бронепехоты</title>
		<link rel=\"stylesheet\" href=\"css/header.css\">
		<link rel=\"stylesheet\" href=\"css/testshot.css\">
	</head>
	<body>
		<header>
			<nav>
				<ul>
					<li><a href=\"/\" id=\"pointed\">Помощник</a></li>
					<li><a href=\"/\">Правила</a></li>
					<li><a href=\"/\">Армлисты</a></li>
					<li><a href=\"/\">FAQ</a></li>
				</ul>
			</nav>
		</header>";
				echo "<div id=\"icon-wrap\"><span>Нажмите чтобы поменять атакующую сторону</span><img id=\"icon\" src=\"\"></div>";
				echo "<div id=\"combat\"><span>Дальний бой</span></div>";
				echo "<table style=\"margin: auto; border-spacing: 100px 0px;\">";
            	echo "<tr>";
            	echo "<td id=\"polaris\">";
            	//printAllInfantry($dbh, false, false);
            	//echo "value:".getInfantry();
            	printAllInfantry($dbh,false,false);
            	//echo "kukukukukukukuku";
            	echo "</td><td id=\"protectorat\">";
            	printAllInfantry($dbh,true,false);
            	echo "</td></tr></table>";
            	#print_r(getMachines(2));
            	if (!intval($_COOKIE['isEmptyMachines'])) {
            		#echo "test";
            		echo "<hr align=\"center\" width=\"400\" size=\"5\" color=\"Black\" />";
	                echo "<table style=\"margin: auto; border-spacing: 100px 0px;\">";
	                echo "<tr>";
	                echo "<td id=\"polaris-machines\">";
	                printObjectsMachines($dbh, getMachines(0), false, 0);
	                echo "</td><td id=\"protectorat-machines\">";
	                printObjectsMachines($dbh, getMachines(1), false, 1);
	                echo "</td></tr></table>";
            	}
            	echo "<hr align=\"center\" width=\"400\" size=\"5\" color=\"Black\" />";
	            printAllBlowUps($dbh);
	            echo "<div id=\"chance\">Вероятность пробития: ?</div>
<div id=\"send\"><button>Отправить</button></div>
<div id=\"menu\"><button>Меню</button></div>
<script src=\"/js/testshot.js\"></script>";
				echo "</body>
</html>";
			}
			else if ($_POST["method"] == "setttacker") {
				header("Set-Cookie: idAttacker=".$_POST["attacker"]."; max-age=604800");
			}
			else if ($_POST["method"] == "machinesammunition") {
				$num = substr($_POST["target"], 3);
				$machines = getMachines(2);
				if ($machines[$num]['ammunition'] > 0) {
	                $machines[$num]['ammunition'] -= 1;
	                header("Set-Cookie: object-machines=".createJsonMachines($machines)."; max-age=604800");
	                echo "ok";
	            }
	            else {
	                echo "error";
	            }
	            echo "//logs//";
	            $query = "select Название from warmachine where id=";
	            $query .= "?";
	            $query .= ";";
	            $stmt = $dbh->prepare($query);
			    try {$stmt->execute(array($machines[$num]['id']));}
    			catch(PDOException $e){error($e->getMessage());}
    			$row = $stmt->fetch();
	            echo $row[0];
			}
			else if ($_POST["method"] == "settarget") {
	        	header("Set-Cookie: idTarget=".$_POST["target"]."; max-age=604800");
	        }
	        else if ($_POST["method"] == "testshot:comparedistance") {
	            if (strpos($_COOKIE['idAttacker'], "inf") !== false) {
	                $query = "select*from infantry where id=";
	                $query .= "?";
	                $query .= ";";
	            }
	            else if (strpos($_COOKIE['idAttacker'], "lrw") !== false) {
	                $query = "select*from longrangeweapon where id=";
	                $query .= "?";
	                $query .= ";";
	            }
	            $stmt = $dbh->prepare($query);
				try {$stmt->execute(array(substr($_COOKIE['idAttacker'], 3)));}
    			catch(PDOException $e){error($e->getMessage());}
    			$row = $stmt->fetch();
                $dice = $row['Дальн'];
                if (strpos($dice, '+') !== false) {
                	list($num, $side, $mode) = mb_split('[Д+]', $dice);
                }
                else {
                	list($num, $side) = mb_split('Д', $dice);
                }
	            srand(time(NULL));
	            echo "
<p>Пройден ли тест на дальность? Значения: ";
	            for ($i = 0; $i < $num; $i++) {
	                echo 1 + rand()%$side + $mode;
	                echo " ";
	            }
	            echo "</p>";
	            if (strpos($_COOKIE['idAttacker'], "lrw") !== false && strpos($_COOKIE['idTarget'], "obj") !== false) {
	                echo "
<br>
<div style=\"text-align: center;\">
<label><input type=\"radio\">Близко</label>
<label><input type=\"radio\">Средне</label>
<label><input type=\"radio\">Далеко</label>
</div>";
	            }
	            echo "
<br>
<div style=\"text-align: center;\">
<button id=\"yes\" style=\"text-align: center;\">Да</button>
<button id=\"no\" style=\"text-align: center;\">Нет</button>
</div>";
	        }
	        else if ($_POST["method"] == "logs:getattackerdefender") {
	            $machines = getMachines(2);
	            if (strpos($_COOKIE['idAttacker'], "inf") !== false) {
	                $query = "select Название, Номер from infantry where id=";
	                $query .= "?";
	                $query .= ";";
	                $stmt = $dbh->prepare($query);
					try {$stmt->execute(array(substr($_COOKIE['idAttacker'], 3)));}
	    			catch(PDOException $e){error($e->getMessage());}
	    			$row = $stmt->fetch();
	            }
	            else if (strpos($_COOKIE['idAttacker'], "lrw") !== false) {
	                $query = "select Название from longrangeweapon where id=";
	                $query .= "?";
	                $query .= ";";
	                $stmt = $dbh->prepare($query);
					try {$stmt->execute(array(substr($_COOKIE['idAttacker'], 3)));}
	    			catch(PDOException $e){error($e->getMessage());}
	    			$row = $stmt->fetch();
	            }
	            else if (strpos($_COOKIE['idAttacker'], "obj") !== false) {
	            	$query = "select Название from warmachine where id=";
	                $query .= "?";
	                $query .= ";";
	                $stmt = $dbh->prepare($query);
					try {$stmt->execute(array($machines[intval(substr($_COOKIE['idAttacker'], 3))]['id']));}
	    			catch(PDOException $e){error($e->getMessage());}
	    			$row = $stmt->fetch();
	            }
	            else if (strpos($_COOKIE['idAttacker'], "blu") !== false) {
	                $query = "select Название from blowup where id=";
	                $query .= "?";
	                $query .= ";";
	                $stmt = $dbh->prepare($query);
					try {$stmt->execute(array(substr($_COOKIE['idAttacker'], 3)));}
	    			catch(PDOException $e){error($e->getMessage());}
	    			$row = $stmt->fetch();
	            }
	            if (strpos($_COOKIE['idAttacker'], "inf") !== false) {
	                $response = "<p>Атакующий боец из отряда:";
	                $response .= $row[0];
	                $response .= ", под номером:";
	                $response .= $row[1];
	            }
	            else if (strpos($_COOKIE['idAttacker'], "lrw") !== false) {
	                $response = "<p>Атакующее орудие:";
	                $response .= $row[0];
	            }
	            else if (strpos($_COOKIE['idAttacker'], "obj") !== false) {
	            	$response = "<p>Атакующая машина:";
	            	$response .= $row[0];
	            }
	            else if (strpos($_COOKIE['idAttacker'], "blu") !== false) {
	                $response = "<p>Размер взрыва:";
	                $response .= $row[0];
	            }

	            $response .= " ";
	            if (strpos($_COOKIE['idTarget'], "inf") !== false) {
	                $query = "select Название, Номер from infantry where id=";
	                $query .= "?";
	                $query .= ";";
	                $stmt = $dbh->prepare($query);
					try {$stmt->execute(array(substr($_COOKIE['idTarget'], 3)));}
	    			catch(PDOException $e){error($e->getMessage());}
	    			$row = $stmt->fetch();
	            }
	            else if (strpos($_COOKIE['idTarget'], "obj") !== false) {
	                $query = "select Название from warmachine where id=";
	                $query .= "?";
	                $query .= ";";
	                $stmt = $dbh->prepare($query);
					try {$stmt->execute(array($machines[intval(substr($_COOKIE['idTarget'], 3))]['id']));}
	    			catch(PDOException $e){error($e->getMessage());}
	    			$row = $stmt->fetch();
	            }
	            if (strpos($_COOKIE['idTarget'], "inf") !== false) {
	                $response .= "Цель боец из отряда:";
	                $response .= $row[0];
	                $response .= ", под номером:";
	                $response .= $row[1];
	            }
	            else if (strpos($_COOKIE['idTarget'], "obj") !== false) {
	                $response .= "Цель машина:";
	                $response .= $row[0];
	            }

	            $response .= "</p>";

	            echo $response;
	        }
	        else if ($_POST["method"] == "testshot:checkkill") {
	            $machines = getMachines(2);
	            if (strpos($_COOKIE['idAttacker'], "inf") !== false) {
	                $query = "select*from infantry where id=";
	                $query .= "?";
	                $query .= ";";
	            }
	            else if (strpos($_COOKIE['idAttacker'], "lrw") !== false) {
	                $query = "select*from longrangeweapon where id=";
	                $query .= "?";
	                $query .= ";";
	            }
	            else if (strpos($_COOKIE['idAttacker'], "blu") !== false) {
	                $query = "select*from blowup where id=";
	                $query .= "?";
	                $query .= ";";
	            }
	            $stmt = $dbh->prepare($query);
				try {$stmt->execute(array(substr($_COOKIE['idAttacker'], 3)));}
    			catch(PDOException $e){error($e->getMessage());}
    			$row = $stmt->fetch();
	            if (strpos($_COOKIE['idAttacker'], "inf") !== false) {
	                $power = $row['Мощн'];
	            }
	            else if (strpos($_COOKIE['idAttacker'], "lrw") !== false) {
	                $power = $row['Мощн'];
	            }
	            else if (strpos($_COOKIE['idAttacker'], "blu") !== false) {
	                $power = $row['Попадание'];
	            }
	            if (strpos($_COOKIE['idTarget'], "inf") !== false) {
	                $query = "select*from infantry where id=";
	                $query .= "?";
	                $query .= ";";
	                $stmt = $dbh->prepare($query);
					try {$stmt->execute(array(substr($_COOKIE['idTarget'], 3)));}
	    			catch(PDOException $e){error($e->getMessage());}
	    			$row = $stmt->fetch();
	                $armor = $row['Бр'];
	            }
	            else if (strpos($_COOKIE['idTarget'], "obj") !== false) {
	                $query = "select*from warmachine where id=";
	                $getid = $machines[intval(substr($_COOKIE['idTarget'], 3))]['id'];
	                $query .= "?";
	                $query .= ";";
	                $stmt = $dbh->prepare($query);
					try {$stmt->execute(array($getid));}
	    			catch(PDOException $e){error($e->getMessage());}
	    			$row = $stmt->fetch();
	                $strength = $row['Прочность'];
	                $armor = substr($strength, 0, strpos($strength, '-'));
	            }
	            if (strpos($_COOKIE['idAttacker'], "lrw") !== false) {
	                $query = "select*from longrangeweapon where id=";
	                $query .= "?";
	                $query .= ";";
	                $stmt = $dbh->prepare($query);
					try {$stmt->execute(array(substr($_COOKIE['idAttacker'], 3)));}
	    			catch(PDOException $e){error($e->getMessage());}
	    			$row = $stmt->fetch();
	                $distArr = explode("-", $row['Урон']);
	                $damage = $distArr[$_POST['indexDistance']];
	            }
	            else if (strpos($_COOKIE['idAttacker'], "blu") !== false) {
	                $query = "select*from blowup where id=";
	                $query .= "?";
	                $query .= ";";
	                $stmt = $dbh->prepare($query);
					try {$stmt->execute(array(substr($_COOKIE['idAttacker'], 3)));}
	    			catch(PDOException $e){error($e->getMessage());}
	    			$row = $stmt->fetch();
	                $damage = $row['Урон'];
	            }
	            if (strpos($_COOKIE['idAttacker'], "inf") !== false) {
	                $damage = 1;
	            }
	            list($num, $side) = mb_split("Д", $power);
	            if (strpos($_COOKIE['idTarget'], "inf") !== false) {
	                echo "
<p>Значения бросков мощности: ";
	                $isKilled = false;
	                for ($i = 0; $i < $num; $i++) {
	                    $val = 1 + rand()%$side;
	                    if ($val > $armor) {
	                        $isKilled = true;
	                    }
	                    echo $val;
	                    echo " ";
	                }
	                echo "</p>";
	                if ($isKilled) {
	                    echo "<p>Боец убит</p>";
	                    $logs = "<p>Боец убит</p>";
	                }
	                else {
	                    echo "<p>Боец остался жив</p>";
	                    $logs = "<p>Боец остался жив</p>";
	                }
	            }
	            else if (strpos($_COOKIE['idTarget'], "obj") !== false) {
	                $tempHTML = "<p>Значения бросков мощности: ";
	                $isKilled = false;
	                for ($i = 0; $i < $num; $i++) {
	                    $val = 1 + rand()%$side;
	                    if ($val > $armor) {
	                        $isKilled = true;
	                    }
	                    $tempHTML .= $val;
	                    $tempHTML .= " ";
	                }
	                $tempHTML .= "</p>";
	                if ($isKilled) {
	                	$polarisMachines = json_decode($_COOKIE['polaris-machines']);
	                	$protectoratMachines = json_decode($_COOKIE['protectorat-machines']);
	                    minusStrength($dbh, true, $_COOKIE['idTarget'], $_COOKIE['idTarget'], $damage, $tempHTML, $logs, NULL, $machines, $polarisMachines, $protectoratMachines);
	                    header("Set-Cookie: polaris-machines=".json_encode($polarisMachines)."; max-age=604800", false);
	            		header("Set-Cookie: protectorat-machines=".json_encode($protectoratMachines)."; max-age=604800", false);
	                    header("Set-Cookie: object-machines=".createJsonMachines($machines)."; max-age=604800", false);
	                    if (createJsonMachines($machines) == '[]') {
			            	header("Set-Cookie: isEmptyMachines=1"."; max-age=604800", false);
			            }
	                    echo $tempHTML;
	                }
	                else {
	                    echo $tempHTML;
	                    echo "<p>Броня не пробита</p>";
	                    $logs .= "<p>Броня не пробита</p>";
	                }
	            }
	            echo "<br>
<div style=\"text-align: center;\"><button id=\"ok\" style=\"text-align: center;\">Ok</button></div>";
	            echo "//logs//";
	            echo $logs;
	        }
	        else if ($_POST["method"] == "setmachines") {
	        	header("Set-Cookie: firstTime=0"."; max-age=604800");
	        	echo "<!DOCTYPE html>
<html>
	<head>
		<meta charset=\"utf-8\">
		<title>Помощник для бронепехоты</title>
		<link rel=\"stylesheet\" href=\"css/header.css\">
	</head>
	<body>
		<header>
			<nav>
				<ul>
					<li><a href=\"/\" id=\"pointed\">Помощник</a></li>
					<li><a href=\"/\">Правила</a></li>
					<li><a href=\"/\">Армлисты</a></li>
					<li><a href=\"/\">FAQ</a></li>
				</ul>
			</nav>
		</header>";
				echo "<p style=\"text-align: center; color: white; font-size: 20;\">Выберите участвующих в сражении юнитов</p>
<div style=\"text-align: center;\">Лимит:<input id=\"limit\"></div>";
				echo "<div id=\"polaris\">";
				printAllInfantry($dbh,false,true);
				printAllWarMachines($dbh, false);
				echo "<p id=\"polaris-money\" style=\"text-align: center; font-size: 20;\">0</p>";
				echo "</div>";
				echo "<hr align=\"center\" width=\"400\" size=\"5\" color=\"Black\" />";
				echo "<div id=\"protectorat\" style=\"text-align: center; font-size: 20;\">";
				printAllInfantry($dbh,true,true);
				printAllWarMachines($dbh, true);
				echo "<p id=\"protectorat-money\">0</p>";
				echo "</div>";
				echo "
<div style=\"text-align: center;\"><button id=\"setmachines\">Ок</button></div>
<script src=\"/js/setmachines.js\"></script>
</body>
</html>";
	        }
	        else if ($_POST["method"] == "fillarraymachines") {
	        	$machines = explode(",", $_POST['machines']);
	            $counter = substr_count($_POST["machines"], ':');
	            $obj = array();
	            $polaris = array();
	            $protectorat = array();
	            $index = 0;
	            for ($i = 0; $i < $counter; $i++) {
	                list($id, $num) = explode(":", $machines[$i]);
	                if (strpos($id, "-") !== false) {
	                    list($id, $side) = explode("-", $id);
	                }
	                $bindex = $index;
	                for ($j = $bindex; $j < $bindex + $num; $j++) {
	                    $array = array();
	                    array_push($array, intval($id));
	                    $query = "select * from warmachine where id=";
	                    $query .= "?";
	                    $query .= ";";
	                    $stmt = $dbh->prepare($query);
						try {$stmt->execute(array($id));}
		    			catch(PDOException $e){error($e->getMessage());}
		    			$row = $stmt->fetch();
	                    array_push($array, intval($row['Боезапас']));
	                    array_push($array, intval(substr($row['Прочность'], 0, strpos($row['Прочность'], '-'))));
	                    array_push($array, intval(substr($row['Скорость'], 0, strpos($row['Скорость'], '-'))));
	                    $key = "obj".$j;
	                    $obj = array_merge($obj, array($key=>$array));
	                    if (!strcmp($row['Сторона'], "Империя Полярис")) {
	                        array_push($polaris, $j);
	                    }
	                    else if (!strcmp($row['Сторона'], "Торговый Протекторат")) {
	                        array_push($protectorat, $j);
	                    }
	                    else if (!strcmp($row['Сторона'], "Нейтральный")) {
	                        if ($side == 0) {
	                            array_push($polaris, $j);
	                        }
	                        else if ($side == 1) {
	                            array_push($protectorat, $j);
	                        }
	                    }
	                    $index++;
	                }
	            }
	            header("Set-Cookie: object-machines=".json_encode($obj)."; max-age=604800", false);
	            if (strcmp(json_encode($obj), "[]")) {
	                header("Set-Cookie: isEmptyMachines=0"."; max-age=604800", false);
	            }
	            header("Set-Cookie: polaris-machines=".json_encode($polaris)."; max-age=604800", false);
	            header("Set-Cookie: protectorat-machines=".json_encode($protectorat)."; max-age=604800", false);
	        }
	        else if ($_POST["method"] == "editvalues") {
	            echo "<!DOCTYPE html>
<html>
	<head>
		<meta charset=\"utf-8\">
		<title>Помощник для бронепехоты</title>
		<link rel=\"stylesheet\" href=\"css/header.css\">
	</head>
	<body>
		<header>
			<nav>
				<ul>
					<li><a href=\"/\" id=\"pointed\">Помощник</a></li>
					<li><a href=\"/\">Правила</a></li>
					<li><a href=\"/\">Армлисты</a></li>
					<li><a href=\"/\">FAQ</a></li>
				</ul>
			</nav>
		</header>";
	            printObjectsMachines($dbh, getMachines(0), true, false);
	            printObjectsMachines($dbh, getMachines(1), true, true);
	            echo "
	<div style=\"text-align: center;\"><button id=\"menu\">Ок</button></div>
	<script src=\"/js/editvalues.js\"></script>
	</body>
	</html>";
	        }
	        else if ($_POST["method"] == "plusstrength") {
	        	plusMinusStrength($dbh,false);
	        }
	        else if ($_POST["method"] == "minusstrength") {
	        	plusMinusStrength($dbh,true);
	        }
	        else if ($_POST["method"] == "plusammunition") {
	        	plusMinusAmmunition($dbh,false);
	        }
	        else if ($_POST["method"] == "minusammunition") {
	        	plusMinusAmmunition($dbh,true);
	        }
	        else if ($_POST["method"] == "showlogs") {
	            echo "<!DOCTYPE html>
<html>
	<head>
		<meta charset=\"utf-8\">
		<title>Помощник для бронепехоты</title>
		<link rel=\"stylesheet\" href=\"css/header.css\">
		<link rel=\"stylesheet\" href=\"css/logs.css\">
	</head>
	<body>
		<header>
			<nav>
				<ul>
					<li><a href=\"/\" id=\"pointed\">Помощник</a></li>
					<li><a href=\"/\">Правила</a></li>
					<li><a href=\"/\">Армлисты</a></li>
					<li><a href=\"/\">FAQ</a></li>
				</ul>
			</nav>
		</header>";
				echo "
	<div id=\"logs\">
	</div>
	<div style=\"text-align: center;\"><button id=\"undo\">Отмена предыдущего действия</button></div>
	<br>
	<div style=\"text-align: center;\"><button id=\"ok\">Ок</button></div>
	<script>
		window.onload = function()
		{
    		document.getElementById('ok').scrollIntoView(true);
		}
	    function getCookie(name) {
	      var value = \"; \" + document.cookie;
	      var parts = value.split(\"; \" + name + \"=\");
	      if (parts.length == 2) return parts.pop().split(\";\").shift();
	    }
	    function xhrSend (s) {
	        var xhr = new XMLHttpRequest();
	        xhr.open('POST', '/', true);
	        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	        xhr.send(s);
	        xhr.onreadystatechange = function() {
	            if (xhr.readyState == XMLHttpRequest.DONE) {
	                document.open();
	                document.write(xhr.responseText);
	                document.close();
	            }
	        }
	    }
	    if (localStorage.getItem(\"logs\")) {
	        document.getElementById(\"logs\").innerHTML = localStorage.getItem(\"logs\");
	    }
	    else {
	        document.getElementById(\"logs\").innerHTML = \"Здесь будет ваша история битвы\";
	    }
	    document.getElementById(\"ok\").addEventListener(\"click\", okButtonListener);
	    document.getElementById(\"undo\").addEventListener(\"click\", restoreCookiesEventListener);
	    function okButtonListener() {
	        xhrSend(\"method=menu\");
	    }
	    function restoreCookiesEventListener() {
	        if (getCookie(\"old-object-machines\") == \"[]\" && getCookie(\"object-machines\") == \"[]\") {
	            if (document.getElementById(\"logs\").lastChild.lastChild.innerHTML != \"Отменено\") {
	                var p = document.createElement(\"p\");
	                p.innerHTML = \"Отменено\";
	                p.style = \"color: red;\";
	                document.getElementById(\"logs\").lastChild.appendChild(p);
	                localStorage.setItem(\"logs\", document.getElementById(\"logs\").innerHTML);
	            }
	            else {
	                alert(\"Уже отменено!\");
	            }
	        }
	        else {
	            if (getCookie(\"old-object-machines\")) {
	            	if (getCookie(\"object-machines\") == \"[]\") {
	            		document.cookie = \"isEmptyMachines = 0\" + \"; max-age=604800\";
	            	}
	                document.cookie = \"object-machines = \" + getCookie(\"old-object-machines\") + \"; max-age=604800\";
	                //document.cookie = \"size-object-machines = \" + getCookie(\"old-size-object-machines\");
	                document.cookie = \"old-object-machines = ; expires = Thu, 01 Jan 1970 00:00:00 GMT\";
	                //document.cookie = \"old-size-object-machines = ; expires = Thu, 01 Jan 1970 00:00:00 GMT\";
	                document.cookie = \"polaris-machines = \" + getCookie(\"old-polaris-machines\") + \"; max-age=604800\";
	                //document.cookie = \"size-polaris-machines = \" + getCookie(\"old-size-polaris-machines\");
	                document.cookie = \"old-polaris-machines = ; expires = Thu, 01 Jan 1970 00:00:00 GMT\";
	                //document.cookie = \"old-size-polaris-machines = ; expires = Thu, 01 Jan 1970 00:00:00 GMT\";
	                document.cookie = \"protectorat-machines = \" + getCookie(\"old-protectorat-machines\") + \"; max-age=604800\";
	                //document.cookie = \"size-protectorat-machines = \" + getCookie(\"old-size-protectorat-machines\");
	                document.cookie = \"old-protectorat-machines = ; expires = Thu, 01 Jan 1970 00:00:00 GMT\";
	                //document.cookie = \"old-size-protectorat-machines = ; expires = Thu, 01 Jan 1970 00:00:00 GMT\";
	                var p = document.createElement(\"p\");
	                p.innerHTML = \"Отменено\";
	                p.style = \"color: red;\";
	                document.getElementById(\"logs\").lastChild.appendChild(p);
	                localStorage.setItem(\"logs\", document.getElementById(\"logs\").innerHTML);
	            }
	            else {
	                alert(\"Уже отменено!\");
	            }
	        }
	    }
	</script>
	</body>
	</html>";
        	}
        	else if ($_POST["method"] == "gateway") {
        		header("Location: /");
        	}
        	else if ($_POST["method"] == "closecombat") {
        		if (strpos($_COOKIE['idAttacker'], "inf") !== false) {
	                $query = "select*from infantry where id=";
	                $query .= "?";
	                $query .= ";";
	                $stmt = $dbh->prepare($query);
					try {$stmt->execute(array(substr($_COOKIE['idAttacker'], 3)));}
	    			catch(PDOException $e){error($e->getMessage());}
	    			$row = $stmt->fetch();
	    			$attack = $row['ББ'];
	            }
	            else if (strpos($_COOKIE['idAttacker'], "obj") !== false) {
	            	$attack = $_POST['closecombat'];
	            	$query = "select*from warmachine where id=";
	                $query .= "?";
	                $query .= ";";
	                $stmt = $dbh->prepare($query);
	                try {$stmt->execute(array(getMachines(2)[intval(substr($_COOKIE['idAttacker'], 3))]['id']));}
	    			catch(PDOException $e){error($e->getMessage());}
	    			$row = $stmt->fetch();
	            	$attackArmor = substr($row['Прочность'], 0, strpos($row['Прочность'], '-'));
	            }
    			if (strpos($_COOKIE['idTarget'], "inf") !== false) {
	                $query = "select*from infantry where id=";
	                $query .= "?";
	                $query .= ";";
	                $stmt = $dbh->prepare($query);
					try {$stmt->execute(array(substr($_COOKIE['idTarget'], 3)));}
	    			catch(PDOException $e){error($e->getMessage());}
	    			$row = $stmt->fetch();
	            }
	            else if (strpos($_COOKIE['idTarget'], "obj") !== false) {
	            	$defence = $_POST['closecombat2'];
	            	$query = "select*from warmachine where id=";
	                $query .= "?";
	                $query .= ";";
	                $stmt = $dbh->prepare($query);
	                try {$stmt->execute(array(getMachines(2)[intval(substr($_COOKIE['idTarget'], 3))]['id']));}
	    			catch(PDOException $e){error($e->getMessage());}
	    			$row = $stmt->fetch();
	            }
    			if (strpos($_COOKIE['idTarget'], "inf") !== false) {
    				$defence = $row['Бр'];
    			}
    			else if (strpos($_COOKIE['idTarget'], "obj") !== false) {
    				$defenceArmor = substr($row['Прочность'], 0, strpos($row['Прочность'], '-'));
    			}
    			$attackDice = rand()%6 + 1;
    			$defenceDice = rand()%6 + 1;
    			if (strpos($_COOKIE['idAttacker'], "inf") !== false && strpos($_COOKIE['idTarget'], "inf") !== false) {
    				$tempHTML = "<p>Сумма ББ и кубика у атакующего: ".$attack."+".$attackDice."</p>";
	    			$attack += $attackDice;
	    			$tempHTML .= "<p>Сумма Бр и кубика у защищаегося: ".$defence."+".$defenceDice."</p>";
	    			$defence += $defenceDice;
	    			if ($attack > $defence) {
	    				$tempHTML .= "<p>Победил атакующий</p>";
	    				$logs = "<p>Победил атакующий</p>";
	    			}
	    			else if ($attack < $defence) {
	    				$tempHTML .= "<p>Победил защищающийся</p>";
	    				$logs = "<p>Победил защищающийся</p>";
	    			}
	    			else {
	    				$tempHTML .= "<p>Ничья</p>";
	    				$logs = "<p>Ничья</p>";
	    			}
    			}
    			else {
    				$tempHTML = "<p>Сумма ББ, брони и кубика у атакующего: ".$attack."+".$attackArmor."+".$attackDice."</p>";
    				$attack += $attackArmor + $attackDice;
    				$tempHTML .= "<p>Сумма ББ, брони и кубика у защищаегося: ".$defence."+".$defenceArmor."+".$defenceDice."</p>";
    				$defence += $defenceArmor + $defenceDice;
    				$machines = getMachines(2);
    				if ($attack > $defence) {
	    				$tempHTML .= "<p>Победил атакующий</p>";
	    				$logs = "<p>Победил атакующий</p>";
	    				$polarisMachines = json_decode($_COOKIE['polaris-machines']);
	                	$protectoratMachines = json_decode($_COOKIE['protectorat-machines']);
	    				minusStrength($dbh, false, $_COOKIE['idAttacker'], $_COOKIE['idAttacker'], ceil(($attack - $defence)/2), $tempHTML, $logs, true, $machines, $polarisMachines, $protectoratMachines);
	    				if (substr($_COOKIE['idTarget'], 3) > substr($_COOKIE['idAttacker'], 3)) {
	    					$target = substr($_COOKIE['idTarget'], 3) - 1;
	    				}
	    				minusStrength($dbh, false, $target, $_COOKIE['idTarget'], $attack - $defence, $tempHTML, $logs, false, $machines, $polarisMachines, $protectoratMachines);
	    				header("Set-Cookie: polaris-machines=".json_encode($polarisMachines)."; max-age=604800", false);
	            		header("Set-Cookie: protectorat-machines=".json_encode($protectoratMachines)."; max-age=604800", false);
	    			}
	    			else if ($attack < $defence) {
	    				$tempHTML .= "<p>Победил защищающийся</p>";
	    				$logs = "<p>Победил защищающийся</p>";
	    				$polarisMachines = json_decode($_COOKIE['polaris-machines']);
	                	$protectoratMachines = json_decode($_COOKIE['protectorat-machines']);
	    				minusStrength($dbh, false, $_COOKIE['idAttacker'], $_COOKIE['idAttacker'], $defence - $attack, $tempHTML, $logs, true, $machines, $polarisMachines, $protectoratMachines);
	    				if (substr($_COOKIE['idTarget'], 3) > substr($_COOKIE['idAttacker'], 3)) {
	    					$target = substr($_COOKIE['idTarget'], 3) - 1;
	    				}
	    				minusStrength($dbh, false, $target, $_COOKIE['idTarget'], ceil(($defence - $attack)/2), $tempHTML, $logs, false, $machines, $polarisMachines, $protectoratMachines);
	    				header("Set-Cookie: polaris-machines=".json_encode($polarisMachines)."; max-age=604800", false);
	    				header("Set-Cookie: protectorat-machines=".json_encode($protectoratMachines)."; max-age=604800", false);
	    			}
	    			else {
	    				$tempHTML .= "<p>Ничья</p>";
	    				$logs = "<p>Ничья</p>";
	    				$polarisMachines = json_decode($_COOKIE['polaris-machines']);
	                	$protectoratMachines = json_decode($_COOKIE['protectorat-machines']);
	    				minusStrength($dbh, false, $_COOKIE['idAttacker'], $_COOKIE['idAttacker'], 0, $tempHTML, $logs, true, $machines, $polarisMachines, $protectoratMachines);
	    				if (substr($_COOKIE['idTarget'], 3) > substr($_COOKIE['idAttacker'], 3)) {
	    					$target = substr($_COOKIE['idTarget'], 3) - 1;
	    				}
	    				minusStrength($dbh, false, $target, $_COOKIE['idTarget'], 0, $tempHTML, $logs, false, $machines, $polarisMachines, $protectoratMachines);
	    				header("Set-Cookie: polaris-machines=".json_encode($polarisMachines)."; max-age=604800", false);
	    				header("Set-Cookie: protectorat-machines=".json_encode($protectoratMachines)."; max-age=604800", false);
	    			}
	    			if (createJsonMachines($machines) == '[]') {
		            	header("Set-Cookie: isEmptyMachines=1"."; max-age=604800", false);
		            }
	    			header("Set-Cookie: object-machines=".createJsonMachines($machines)."; max-age=604800", false);
    			}
    			echo $tempHTML;
    			echo "<br>
<div style=\"text-align: center;\"><button id=\"ok\" style=\"text-align: center;\">Ok</button></div>";
	            echo "//logs//";
	            echo $logs;
        	}
			else {
				if (is_null($_COOKIE["firstTime"])) {
					$firstTime = true;
					//$next = true;
				}
				else if ($_COOKIE["firstTime"] == "0") {
					$firstTime = false;
					//$next = true;
				}
				/*else {
					error("Ошибка куки: неверное значение firstTime");
				}*/
				//НЕ НАДО ЗАБОТИТЬСЯ О ТЕХ КТО ИЗМЕНИЛ ЦЕЛЕНАПРАВЛЕННО КУКИ, САМИ ВИНОВАТЫ
				//А ЕСЛИ ЭТО ПО МОЕЙ ВИНЕ КУКИ ПОПОРТИЛИСЬ ТО НУЖНО ПРОСТО ПИСАТЬ ИСПРАВНЫЙ КОД
				/*else if ($_COOKIE["firstTime"] != "0") {
					$next = false;
					echo "Ошибка: кука firstTime имеет неправильное значение, удалите если вы еще не создавали армии или сделайте ее равной нулю если создавали, чтобы не потерять данные об армиях!";
				}
				if ($next) {

				}*/
				if ($firstTime) {
	                header("Set-Cookie: isEmptyMachines=1"."; max-age=604800");
	            }
	            header("Set-Cookie: isFirstTimeEdit=1"."; max-age=604800");
	            echo "<!DOCTYPE html>
<html>
	<head>
		<meta charset=\"utf-8\">
		<title>Помощник для бронепехоты</title>
		<link rel=\"stylesheet\" href=\"css/header.css\">
	</head>
	<body>
		<header>
			<nav>
				<ul>
					<li><a href=\"/\" id=\"pointed\">Помощник</a></li>
					<li><a href=\"/\">Правила</a></li>
					<li><a href=\"/\">Армлисты</a></li>
					<li><a href=\"/\">FAQ</a></li>
				</ul>
			</nav>
		</header>";
				if (!$firstTime) {
	                echo "<div style=\"text-align: center;\"><button id=\"newgame\">Новая игра</button></div>
	                <br>
	                <div style=\"text-align: center;\"><button id=\"testshot\">Тест на выстрел</button></div>
					<br>";
				}
				if ($firstTime) {
	                echo "
	<div style=\"text-align: center;\"><button id=\"setmachines\">Создать технику</button></div>
	<br>";
				}
				if (!$firstTime && count(json_decode($_COOKIE['object-machines'], true))) {
					echo "
	<div style=\"text-align: center;\"><button id=\"editvalues\">Отредактировать боезапас или прочность</button></div>
	<br>";
				}
			echo "	
	<div style=\"text-align: center;\"><button id=\"logs\">История</button></div>
	<script>
	    function xhrSend (s) {
	        var xhr = new XMLHttpRequest();
	        xhr.open('POST', '/', true);
	        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	        xhr.send(s);
	        xhr.onreadystatechange = function() {
	            if (xhr.readyState == XMLHttpRequest.DONE) {
	                document.open();
	                document.write(xhr.responseText);
	                document.close();
	            }
	        }
	    }
	    function deleteAllCookies() {
		    var cookies = document.cookie.split(\";\");
		    for (var i = 0; i < cookies.length; i++) {
		        var cookie = cookies[i];
		        var eqPos = cookie.indexOf(\"=\");
		        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
		        document.cookie = name + \"=;expires=Thu, 01 Jan 1970 00:00:00 GMT\";
		    }
		}";
		if (!$firstTime) {
			echo "document.getElementById('newgame').addEventListener(\"click\", newgameButtonListener);
	    	document.getElementById('testshot').addEventListener(\"click\", testshotButtonListener);";
		}
	    		if ($firstTime) {
	                echo "
	    document.getElementById('setmachines').addEventListener(\"click\", setmachinesButtonListener);
	    localStorage.removeItem('logs');";
	            }
	            if (!$firstTime && count(json_decode($_COOKIE['object-machines'], true))) {
	            	echo "
	    document.getElementById('editvalues').addEventListener(\"click\", editvaluesButtonListener);";
	            }
	            echo "
	    document.getElementById('logs').addEventListener(\"click\", logsButtonListener);
	    function newgameButtonListener() {
	        var answer = confirm(\"Действительно начать новую игру?\");
	        if (answer) {
	            deleteAllCookies();
	            localStorage.removeItem('logs');
	            window.location.reload(false);
	        }
	    }
	    function testshotButtonListener() {
	        xhrSend(\"method=testshot:chooseattacker\");
	    }
	            ";
	            if ($firstTime) {
	                echo "
	    function setmachinesButtonListener() {
	        xhrSend(\"method=setmachines\");
	    }";
				}
	    echo "
	    function editvaluesButtonListener() {
	        xhrSend(\"method=editvalues\");
	    }
	    function logsButtonListener() {
	        xhrSend(\"method=showlogs\");
	    }
	</script>";
	echo "	</body>
</html>";
				}
			?>