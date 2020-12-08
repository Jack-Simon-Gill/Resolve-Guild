<?php
	require('db-connect.php');
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }
				// Credentials from Battle.net
                $clientid = "";
                $secret = "";
				
				// Create Token request
                $url = "https://eu.battle.net/oauth/token?grant_type=client_credentials&client_id=$clientid&client_secret=$secret";
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
                curl_setopt($curl, CURLOPT_ENCODING, "gzip,deflate");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                $output = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);
                $result = json_decode($output, true);
                if(isset($result['access_token'])) {
                    $token = $result['access_token'];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $tokenurl = 'https://eu.api.blizzard.com/data/wow/guild/draenor/resolve/roster?namespace=profile-eu&locale=en_GB&access_token=' . $token;
                    curl_setopt($ch, CURLOPT_URL, $tokenurl);
                    $result = curl_exec($ch);
                    curl_close($ch);
                    $obj = json_decode($result);
                    $members = array();
                    foreach ($obj->members as $member) {
                        if ($member->rank <= 6) {
                            $rank = $member->rank;
                            $name = $member->character->name;
                            $spec = $member->character->spec->name;
                            $classID = $member->character->playable_class->id;
                            
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $nameLookup = strtolower($name);
                            $nameLookup = curl_escape($ch, $nameLookup);
                            $profileurl = 'https://eu.api.blizzard.com/profile/wow/character/draenor/'.$nameLookup.'?namespace=profile-eu&access_token=' . $token;
                            echo $profileurl.'<br>';
                            curl_setopt($ch, CURLOPT_URL, $profileurl);
                            $profiles = curl_exec($ch);
                            curl_close($ch);
                            $profile = json_decode($profiles);
                            $class = $profile->character_class->name->en_GB;
                            $spec = $profile->active_spec->name->en_GB;
                            $specurl = $profile->active_spec->key->href.'&access_token=' . $token;
                            $mediaUrl = $profile->media->href.'&access_token=' . $token;
                            $gender = strtolower($profile->gender->type);
                            $averageItemlvl = $profile->average_item_level.','.$profile->equipped_item_level;
                            curl_close($ch);
                            echo $class.'<br>';
                            echo $spec.'<br>';
                            
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_URL, $specurl);
                            $specs = curl_exec($ch);
                            curl_close($ch);
                            $specDetails = json_decode($specs);
                            $role = $specDetails->role->name->en_GB;
                            $description = $specDetails->gender_description->$gender->en_GB;
                            
                            curl_close($ch);
                            echo $role.'<br>';
                            
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_URL, $mediaUrl);
                            $medias = curl_exec($ch);
                            curl_close($ch);
                            $media = json_decode($medias);
                            $thumbnailUrl = $media->bust_url;
                            $mainUrl = $media->render_url;
                            
                            curl_close($ch);
                            echo $thumbnailUrl.'<br>';

							// Download Media Images
                            $dest = '/home/jack/domains/resolve-guild.com/public_html/wp-content/themes/resolve/roster/insets/'.$name.'.jpg';
                            $dest_main = '/home/jack/domains/resolve-guild.com/public_html/wp-content/themes/resolve/roster/insets/'.$name.'_main.jpg';
                            if (file_exists('/home/jack/domains/resolve-guild.com/public_html/wp-content/themes/resolve/roster/insets/'.$name.'.jpg')) {
                                unlink($dest);
                                copy($thumbnailUrl, $dest);
                                copy($mainUrl, $dest_main);
                                echo 'Existing image found - updating with latest<br>';
                            }
                            else {
                                copy($thumbnailUrl, $dest);
                                copy($mainUrl, $dest_main);
                            }
                            $description = mysqli_real_escape_string($conn, $description);
                            $name = mysqli_real_escape_string($conn, $name);
                            $role = mysqli_real_escape_string($conn, $role);
                            $spec = mysqli_real_escape_string($conn, $spec);
                            $class = strtolower($class);
                            $class = str_replace(' ', '', $class);
                            
                            $sql = "INSERT INTO wpwh_roster (rank, name, spec, description, classID, class, role, thumbnail)
                                                        VALUES ('$rank','$name','$spec','$description', '$classID', '$class','$role','$thumbnail')
                                                        ON DUPLICATE KEY UPDATE
                                                        rank = '$rank', name = '$name', spec = '$spec', description = '$description', classID = '$classID', class = '$class', role = '$role', thumbnail = '$thumbnail'";
                            if (mysqli_query($conn, $sql)) {
                                echo "Member updated - <strong>".$name."</strong><br>";
                            } else {
                                echo "Error: " . $sql . "<br>" . mysqli_error($conn) . "<br><br>";
                            }
                            
                            $sql = "SELECT ID FROM wpwh_roster WHERE name = '$name'";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                   $armouryID = $row["ID"];
                                }
                            } else {
                                echo "0 results";
                            }
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $characterurl = 'https://eu.api.blizzard.com/profile/wow/character/draenor/'.$nameLookup.'/equipment?namespace=profile-eu&access_token='.$token;
                            curl_setopt($ch, CURLOPT_URL, $characterurl);
                            
                            echo $characterurl .' url <br>';
                            $characters = curl_exec($ch);
                            curl_close($ch);
                            $character = json_decode($characters);
                            
                            $headID = $character->equipped_items[0]->item->id;
                            $headname = $character->equipped_items[0]->name->en_GB;
                            $headilvl = $character->equipped_items[0]->level->value;
                            $headicon = get_item_icon($character, $token,0);
                            $headAzerites = $character->equipped_items[0]->azerite_details->selected_powers;
                            $headAzerite = array();
                            foreach ($headAzerites as $azerites){
                                if ($azerites->id != 0){
                                    $headAzerite[] = $azerites->id;
                                }
                            }
                            $headAzerite = implode(':', $headAzerite);
                            $head = $headID.','.$headicon.','.$headname.','.$headilvl;
                            
                            $neckID = $character->equipped_items[1]->item->id;
                            $neckicon = get_item_icon($character, $token,1);
                            $neckname = $character->equipped_items[1]->name->en_GB;
                            $neckilvl = $character->equipped_items[1]->level->value;
                            $neck = $neckID.','.$neckicon.','.$neckname.','.$neckilvl;
                            
                            $shoulderID = $character->equipped_items[2]->item->id;
                            $shouldericon = get_item_icon($character, $token,2);
                            $shouldername = $character->equipped_items[2]->name->en_GB;
                            $shoulderilvl = $character->equipped_items[2]->level->value;
                            $shoulderAzerites = $character->equipped_items[2]->azerite_details->selected_powers;
                            $shoulderAzerite = array();
                            foreach ($shoulderAzerites as $azerites){
                                if ($azerites->id != 0){
                                    $shoulderAzerite[] = $azerites->id;
                                }
                            }
                            $shoulderAzerite = implode(':', $shoulderAzerite);
                            $shoulder = $shoulderID.','.$shouldericon.','.$shouldername.','.$shoulderilvl;
                            
                            $backID = $character->equipped_items[14]->item->id;
                            $backicon = get_item_icon($character, $token,14);
                            $backname = $character->equipped_items[14]->name->en_GB;
                            $backilvl = $character->equipped_items[14]->level->value;
                            $back = $backID.','.$backicon.','.$backname.','.$backilvl;
                            
                            $chestID = $character->equipped_items[4]->item->id;
                            $chesticon = get_item_icon($character, $token,4);
                            $chestname = $character->equipped_items[4]->name->en_GB;
                            $chestilvl = $character->equipped_items[4]->level->value;
                            $chestAzerites = $character->equipped_items[4]->azerite_details->selected_powers;
                            $chestAzerite = array();
                            foreach ($chestAzerites as $azerites){
                                if ($azerites->id != 0){
                                    $chestAzerite[] = $azerites->id;
                                }
                            }
                            $chestAzerite = implode(':', $chestAzerite);
                            $chest = $chestID.','.$chesticon.','.$chestname.','.$chestilvl;
                            
                            $tabardID = $character->equipped_items[17]->item->id;
                            $tabardicon = get_item_icon($character, $token,17);
                            $tabardname = $character->equipped_items[17]->name->en_GB;
                            $tabardilvl = $character->equipped_items[17]->level->value;
                            $tabard = $tabardID.','.$tabardicon.','.$tabardname.','.$tabardilvl;
                            
                            $wristID = $character->equipped_items[8]->item->id;
                            $wristicon = get_item_icon($character, $token,8);
                            $wristname = $character->equipped_items[8]->name->en_GB;
                            $wristilvl = $character->equipped_items[8]->level->value;
                            $wrist = $wristID.','.$wristicon.','.$wristname.','.$wristilvl;
                            
                            $handsID = $character->equipped_items[9]->item->id;
                            $handsicon = get_item_icon($character, $token,9);
                            $handsname = $character->equipped_items[9]->name->en_GB;
                            $handsilvl = $character->equipped_items[9]->level->value;
                            $hands = $handsID.','.$handsicon.','.$handsname.','.$handsilvl;
                            
                            $waistID = $character->equipped_items[5]->item->id;
                            $waisticon = get_item_icon($character, $token,5);
                            $waistname = $character->equipped_items[5]->name->en_GB;
                            $waistilvl = $character->equipped_items[5]->level->value;
                            $waist = $waistID.','.$waisticon.','.$waistname.','.$waistilvl;
                            
                            $legsID = $character->equipped_items[6]->item->id;
                            $legsicon = get_item_icon($character, $token,6);
                            $legsname = $character->equipped_items[6]->name->en_GB;
                            $legsilvl = $character->equipped_items[6]->level->value;
                            $legs = $legsID.','.$legsicon.','.$legsname.','.$legsilvl;
                            
                            $feetID = $character->equipped_items[7]->item->id;
                            $feeticon = get_item_icon($character, $token,7);
                            $feetname = $character->equipped_items[7]->name->en_GB;
                            $feetilvl = $character->equipped_items[7]->level->value;
                            $feet = $feetID.','.$feeticon.','.$feetname.','.$feetilvl;
                            
                            $finger1ID = $character->equipped_items[10]->item->id;
                            $finger1icon = get_item_icon($character, $token,10);
                            $finger1name = $character->equipped_items[10]->name->en_GB;
                            $finger1ilvl = $character->equipped_items[10]->level->value;
                            $finger1 = $finger1ID.','.$finger1icon.','.$finger1name.','.$finger1ilvl;
                            
                            $finger2ID = $character->equipped_items[11]->item->id;
                            $finger2icon = get_item_icon($character, $token,11);
                            $finger2name = $character->equipped_items[11]->name->en_GB;
                            $finger2ilvl = $character->equipped_items[11]->level->value;
                            $finger2 = $finger2ID.','.$finger2icon.','.$finger2name.','.$finger2ilvl;
                            
                            $trinket1ID = $character->equipped_items[12]->item->id;
                            $trinket1icon = get_item_icon($character, $token,12);
                            $trinket1name = $character->equipped_items[12]->name->en_GB;
                            $trinket1ilvl = $character->equipped_items[12]->level->value;
                            $trinket1 = $trinket1ID.','.$trinket1icon.','.$trinket1name.','.$trinket1ilvl;
                            
                            $trinket2ID = $character->equipped_items[13]->item->id;
                            $trinket2icon = get_item_icon($character, $token,13);
                            $trinket2name = $character->equipped_items[13]->name->en_GB;
                            $trinket2ilvl = $character->equipped_items[13]->level->value;
                            $trinket2 = $trinket2ID.','.$trinket2icon.','.$trinket2name.','.$trinket2ilvl;
                            
                            $mainHandID = $character->equipped_items[15]->item->id;
                            $mainHandicon = get_item_icon($character, $token,15);
                            $mainHandname = $character->equipped_items[15]->name->en_GB;
                            $mainHandilvl = $character->equipped_items[15]->level->value;
                            $mainHand = $mainHandID.','.$mainHandicon.','.$mainHandname.','.$mainHandilvl;
                            
                            $offHandID = $character->equipped_items[16]->item->id;
                            $offHandicon = get_item_icon($character, $token,16);
                            $offHandname = $character->equipped_items[16]->name->en_GB;
                            $offHandilvl = $character->equipped_items[16]->level->value;
                            $offHand = $offHandID.','.$offHandicon.','.$offHandname.','.$offHandilvl;
                            
                            if (empty($offHandID)) {
                                $offHand = 0;
                            }      
                            
                            if (empty($tabardID)) {
                                $tabard = 0;
                            }
                            
                            $head = mysqli_real_escape_string($conn, $head);
                            $neck = mysqli_real_escape_string($conn, $neck);
                            $shoulder = mysqli_real_escape_string($conn, $shoulder);
                            $back = mysqli_real_escape_string($conn, $back);
                            $chest = mysqli_real_escape_string($conn, $chest);
                            $tabard = mysqli_real_escape_string($conn, $tabard);
                            $wrist = mysqli_real_escape_string($conn, $wrist);
                            $hands = mysqli_real_escape_string($conn, $hands);
                            $waist = mysqli_real_escape_string($conn, $waist);
                            $legs = mysqli_real_escape_string($conn, $legs);
                            $feet = mysqli_real_escape_string($conn, $feet);
                            $finger1 = mysqli_real_escape_string($conn, $finger1);
                            $finger2 = mysqli_real_escape_string($conn, $finger2);
                            $trinket1 = mysqli_real_escape_string($conn, $trinket1);
                            $trinket2 = mysqli_real_escape_string($conn, $trinket2);
                            $mainHand = mysqli_real_escape_string($conn, $mainHand);
                            $offHand = mysqli_real_escape_string($conn, $offHand);
                            
                            $sql2 = "INSERT INTO wpwh_armoury (ID,averageItemLevel,head,headAzerite,neck,shoulder,shoulderAzerite,back,chest,chestAzerite,tabard,wrist,hands,waist,legs,feet,finger1,finger2,trinket1,trinket2,mainHand,offHand) VALUES ('$armouryID', '$averageItemlvl', '$head','$headAzerite','$neck','$shoulder','$shoulderAzerite','$back','$chest','$chestAzerite','$tabard','$wrist','$hands','$waist','$legs','$feet','$finger1','$finger2','$trinket1','$trinket2','$mainHand','$offHand')
                            ON DUPLICATE KEY UPDATE
                            averageItemLevel = '$averageItemlvl', head = '$head', headAzerite = '$headAzerite', neck = '$neck', shoulder = '$shoulder', shoulderAzerite = '$shoulderAzerite', back = '$back', chest = '$chest', chestAzerite = '$chestAzerite', tabard = '$tabard', wrist = '$wrist', hands = '$hands', waist = '$waist', legs = '$legs', feet = '$feet', finger1 = '$finger1', finger2 = '$finger2', trinket1 = '$trinket1', trinket2 = '$trinket2', mainHand = '$mainHand', offHand = '$offHand'";
    
                                if (mysqli_query($conn, $sql2)) {
                                } else {
                                    echo 'Error: ' . $sql2 . '<br>' . mysqli_error($conn) . '<br>';
                                }
                                
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $name = strtolower($name);
                            $mythics = 'https://eu.api.blizzard.com/profile/wow/character/draenor/'.$name.'/mythic-keystone-profile?namespace=profile-eu&locale=en_GB&access_token=' . $token;;
                            curl_setopt($ch, CURLOPT_URL, $mythics);
                            $charmythics = curl_exec($ch);
                            curl_close($ch);
                            $charmythic = json_decode($charmythics);  
                            $dungs = $charmythic->current_period->best_runs;
                            foreach ($dungs as $dung){
                                echo $dung->keystone_level.' '.$dung->dungeon->name.' ';
                            }
                                
                                echo '<br><hr>';
                        }
                    }
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $wowtoken = 'https://eu.api.blizzard.com/data/wow/token/index?namespace=dynamic-eu&locale=en_gb&access_token=' . $token;
                curl_setopt($ch, CURLOPT_URL, $wowtoken);
                $tokenprices = curl_exec($ch);
                $data = json_decode($tokenprices);
                $price = $data->price;
                curl_close($ch);
                echo $price;
                $price = ($price / 10000);
                $currentTime = date('l jS F Y - G:i');
                $date = date('Y-m-d');
                $sql = "INSERT INTO wpwh_token (price,date,date_e) VALUES ('$price', '$currentTime','$date')";
                if (mysqli_query($conn, $sql)) {
                    echo "<br><strong>Token price updated</strong>";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn) . "<br><br>";
                }
                mysqli_close($conn);
                
                            function get_item_icon($character, $token, $position){
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $iconUrl = $character->equipped_items[$position]->media->key->href.'&access_token=' . $token;
                                curl_setopt($ch, CURLOPT_URL, $iconUrl);
                                $icons = curl_exec($ch);
                                $data = json_decode($icons);
                                $iconValue = $data->assets[0]->value;
                                curl_close($ch);
                                return $iconValue;
                            }