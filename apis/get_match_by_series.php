<?php   
include('config.php');

$series_id = $_GET['series_id'];

$sql = "SELECT 
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
WHERE ser.seriesId = " . $series_id . " AND mar.active = 1 AND mar.Name='Match Odds'";

$sql = mysqli_query($conn, $sql);

$rows = array();
$count = @mysqli_num_rows($sql);
$i = 0;
if ($count > 0) {
  while ($row = mysqli_fetch_assoc($sql)) {
    $rows[$i] = $row;
    $rows[$i]['srno'] = $i;
    $runner = $row['market_runner_json'];
    $items = json_decode($runner, true);
    $rows[$i]['selectionId1'] = $items[0]['selectionId'];
    $rows[$i]['runnerName1'] = $items[0]['name'];
    $rows[$i]['selectionId2'] = $items[1]['selectionId'];
    $rows[$i]['runnerName2'] = $items[1]['name'];
    $rows[$i]['selectionId3'] = isset($items[2]) ? $items[2]['selectionId'] : "";
    $rows[$i]['runnerName3'] = isset($items[2]) ? $items[2]['name'] : "";
    $i++;
  }
}

echo (json_encode($rows));
