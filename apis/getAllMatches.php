<?php   
include('config.php'); 
$tennis=mysqli_query($conn, "SELECT 
  mat.MstCode AS eventId,
  mat.matchName AS eventName,
  mat.MstDate AS eventDate,
  spo.id AS EventTypeId,
  spo.name AS EventTypeName,
  ser.seriesId AS SeriesId,
  ser.Name AS SeriesName,
  mar.Id AS marketId,
  mar.Name AS marketName,
  'MATCH_ODDS' AS marketType,
  mar.market_runner_json,
  mat.MstDate AS startDate,
  mat.scoreboard_id
FROM market mar
INNER JOIN seriesmst ser ON ser.seriesId = mar.seriesId
INNER JOIN matchmst mat ON mat.Mstcode = mar.matchId
INNER JOIN sportmst spo ON spo.id = mar.sportsId
WHERE  mar.active = 1 AND mar.Name='Match Odds' and ser.SportID='2'");

$sql=mysqli_query($conn, "SELECT 
  mat.MstCode AS eventId,
  mat.matchName AS eventName,
  mat.MstDate AS eventDate,
  spo.id AS EventTypeId,
  spo.name AS EventTypeName,
  ser.seriesId AS SeriesId,
  ser.Name AS SeriesName,
  mar.Id AS marketId,
  mar.Name AS marketName,
  'MATCH_ODDS' AS marketType,
  mar.market_runner_json,
  mat.MstDate AS startDate,
  mat.scoreboard_id
FROM market mar
INNER JOIN seriesmst ser ON ser.seriesId = mar.seriesId
INNER JOIN matchmst mat ON mat.Mstcode = mar.matchId
INNER JOIN sportmst spo ON spo.id = mar.sportsId
WHERE  mar.active = 1 AND mar.Name='Match Odds' and ser.SportID='4'");


$soccer=mysqli_query($conn, "SELECT 
  mat.MstCode AS eventId,
  mat.matchName AS eventName,
  mat.MstDate AS eventDate,
  spo.id AS EventTypeId,
  spo.name AS EventTypeName,
  ser.seriesId AS SeriesId,
  ser.Name AS SeriesName,
  mar.Id AS marketId,
  mar.Name AS marketName,
  'MATCH_ODDS' AS marketType,
  mar.market_runner_json,
  mat.MstDate AS startDate,
  mat.scoreboard_id
FROM market mar
INNER JOIN seriesmst ser ON ser.seriesId = mar.seriesId
INNER JOIN matchmst mat ON mat.Mstcode = mar.matchId
INNER JOIN sportmst spo ON spo.id = mar.sportsId
WHERE  mar.active = 1 AND mar.Name='Match Odds' and ser.SportID'1'");

$empty =array();

$count = mysqli_num_rows($tennis); 
 	    if($count < 1){		
		$rows['tennis']=$empty;		
		}


$scount = mysqli_num_rows($soccer); 
 	    if($scount < 1){		
		$rows['soccer']=$empty;		
		}

$crcount = mysqli_num_rows($sql); 
 	    if($crcount < 1){		
		$rows['cricket']=$empty;		
		}



 
  $i=0;
		while($row = mysqli_fetch_assoc($sql)){ 
	  $rows['cricket'][$i] = $row;
    $rows['cricket'][$i]['srno'] = $i;
    $runner = $row['market_runner_json'];
    $items = json_decode($runner, true);
    $rows['cricket'][$i]['selectionId1'] = $items[0]['selectionId'];
    $rows['cricket'][$i]['runnerName1'] = $items[0]['name'];
    $rows['cricket'][$i]['selectionId2'] = $items[1]['selectionId'];
    $rows['cricket'][$i]['runnerName2'] = $items[1]['name'];
    $rows['cricket'][$i]['selectionId3'] = isset($items[2]) ? $items[2]['selectionId'] : "";
    $rows['cricket'][$i]['runnerName3'] = isset($items[2]) ? $items[2]['name'] : "";
    $i++;
		 			
				 			
		}	


$j=0;
while($rowsoccer = mysqli_fetch_assoc($soccer)){ 
		 
		 
		 
		 $rows['soccer'][$j] = $rowsoccer;
    $rows['soccer'][$j]['srno'] = $j;
    $runner = $row['market_runner_json'];
    $tems = json_decode($runner, true);
    $rows['soccer'][$j]['selectionId1'] = $tems[0]['selectionId'];
    $rows['soccer'][$j]['runnerName1'] = $tems[0]['name'];
    $rows['soccer'][$j]['selectionId2'] = $tems[1]['selectionId'];
    $rows['soccer'][$j]['runnerName2'] = $tems[1]['name'];
    $rows['soccer'][$j]['selectionId3'] = isset($tems[2]) ? $tems[2]['selectionId'] : "";
    $rows['soccer'][$j]['runnerName3'] = isset($tems[2]) ? $tems[2]['name'] : "";
		 
	 	
				 
		 $j++;				
				 			
		}
		
		
		
		$k=0;
while($rowtennis = mysqli_fetch_assoc($tennis)){ 
		 
		 
		  $rows['tennis'][$k] = $rowtennis;
    $rows['tennis'][$k]['srno'] = $j;
    $runner = $row['market_runner_json'];
    $jtems = json_decode($runner, true);
    $rows['tennis'][$k]['selectionId1'] = $jtems[0]['selectionId'];
    $rows['tennis'][$k]['runnerName1'] = $jtems[0]['name'];
    $rows['tennis'][$k]['selectionId2'] = $jtems[1]['selectionId'];
    $rows['tennis'][$k]['runnerName2'] = $jtems[1]['name'];
    $rows['tennis'][$k]['selectionId3'] = isset($jtems[2]) ? $jtems[2]['selectionId'] : "";
    $rows['tennis'][$k]['runnerName3'] = isset($jtems[2]) ? $jtems[2]['name'] : "";
		 			
					
				 
		 $k++;				
				 			
		}

		
		
		 
			
 echo (json_encode($rows));
?>