<?php
include './layouts/connect.php';

if (isset($_POST['action'])) $action = $_POST['action'];
else if (isset($_GET['action'])) $action = $_GET['action']; else $action = '';



$r = array('r'=>200);


if ($action == 'getInteractions'){
	$r['a'] = array();
	$stmt = $db->prepare("SELECT * FROM audios WHERE info IS NOT NULL AND status=2 ORDER BY id DESC");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $q){		
		$a = array("id"=>$q["id"]);
		$duration = 0;
		try{
			$info = str_replace( "}],}",   "}]}", $q["info"]);
	    	$info = str_replace("[],}","[]}", $info);
	    	$info = json_decode($info,true);
	    	foreach ($info['utterances'] as $u) {
	    		$duration = $duration + $u['duration'];
	    	}
    	} catch(Exception $ex){}
    	$a['name'] = $q['name'];
    	$a['duration'] = $duration;
    	$a['pushed_at'] = $q['pushed_at'];
		array_push($r['a'], $a);
	}
	echo json_encode($r);
} 

else 


if ($action == 'getInteraction'){
	$id = $_GET['id'];	
	//j.path data/mono'++'.wav TEMP
	//echo file_get_contents('data/out'.$id.'.json'); die();//mode=2 demo mode

	$stmt = $db->prepare("SELECT * FROM audios WHERE id = :id");
	$query = $stmt->execute(array("id"=>$id));	
	$q = $stmt->fetch();   	

	$info = getInfo($q["info"]);
    $info['path'] = '/choco/audios/'.$q["path"]; //'data/'.$q['path'];    
    $g_asr_utterances = json_decode($q["g_asr"],true);

    //kaz
    #$kaz_info = getInfo($q["info_kaz_choco_forte_v1"]);

    //g_asr
    $idx=0;
    foreach($info["utterances"] as &$u){		    	    	
    	$li = $chunks[$idx]["lang_info"];	    	
    	$u["google_asr"] = $g_asr_utterances[$idx];
    	#$u["info_kaz"] = $kaz_info["utterances"][$idx]["utteranceText"];
    	//if ($li["kaz_n"]>=$li["ru_n"]) $u["alternative"] = $u["info_kaz"]; else $u["alternative"] = $u["google_asr"];
    	$u["alternative"] = $u["google_asr"];
    	$idx = $idx + 1;
    }
    /*
    try{
		$stmt = $db->prepare("SELECT * FROM chunks WHERE audios_id=:audios_id ORDER BY segment");
		$stmt->execute(array("audios_id"=>$id));
		$chunks = $stmt->fetchAll();
		#....
	} catch(Exception $e){}
	*/
    //endOf g_asr
	$info["categories"] = array();
    $audio_categories = json_decode($q["categories"], true);
	$stmt = $db->prepare("SELECT * FROM categories");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach($audio_categories as $cat_id)
		foreach($result as $category){		    
			if ($cat_id == $category["id"]){
				$info["categories"][] = $category["name"];
			}
	}

	echo json_encode($info);
}

else

if ($action == 'sendRecord'){
	if (isset($_FILES['file'])){
		$name = $_FILES['file']['name'];
		$path = uniqid(true).".mp3"; 				
		// /var/www/html/choco/audios  /Applications/XAMPP/htdocs/temp
		if ( move_uploaded_file($_FILES['file']['tmp_name'], '/var/www/html/choco/audios/'.$path) ){
			$stmt = $db->prepare("INSERT INTO audios(type,path,name) VALUES(9,:path,:name)");			
			$query = $stmt->execute(array("path"=>$path, "name"=>$name));					
			echo "Done";
		} else echo "failed";
	}
}


function getInfo($info){
	$info = str_replace( "}],}",   "}]}", $info);
    $info = str_replace("[],}","[]}", $info);
    $info = json_decode($info,true);
    return $info;
}


//curl -X POST --header 'Content-Type: multipart/form-data' --header 'Accept: application/json' --header 'Authorization: <<Removed>>' -F file=@"10.wav"  http://localhost/analytics/func.php?action=sendRecord -k 
?>