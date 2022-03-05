<?php 
require_once('database/dbConnect.php'); 
//出力するcsvファイル情報を定義
$fileName = 'people_' . date('YmdHis') . '.csv';
$header = ['#', '姓', 'ミドル', '名', 'メールアドレス'];
$records = $db->query('SELECT * FROM people'); 
$createCsvFile = fopen('php://output', 'w'); //ファイルopen
mb_convert_variables('SJIS-win', 'UTF-8', $header); //文字化け対策
fputcsv($createCsvFile, $header); //ヘッダーwrite

foreach($records as $record){ //レコード数分回す
  $csv = [$record['id'], $record['lastName'], $record['middleName'], $record['firstName'], $record['email']];
  mb_convert_variables('SJIS-win', 'UTF-8', $csv);
  fputcsv($createCsvFile, $csv);
}
fclose($createCsvFile); //ファイルclose
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename={$fileName}"); //名前を付けて保存
header('Content-Transfer-Encoding: binary');
$db = null;
exit;
?>