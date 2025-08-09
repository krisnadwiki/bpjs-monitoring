<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = include 'config.php';
include 'bpjs_helper.php';

$param = isset($_REQUEST['param']) ? $_REQUEST['param'] : isset($_REQUEST['param']);

#endpoint untuk vclaim
#$endpoint = "Peserta/nokartu/{$noka}/tglSEP/{$tanggal}";
$tanggal = date('Y-m-d');

if ($param == 'nik') {
    $noka = isset($_REQUEST['noka']) ? $_REQUEST['noka'] : '3507240510770001';    
    $endpoint = "Peserta/nik/$noka/tglSEP/$tanggal";
    $response = bpjsRequest($endpoint, $config);
    echo $response;

} else if ($param == 'rujukan') {
    $norujukan = isset($_REQUEST['norujukan']) ? $_REQUEST['norujukan'] : '1317U0200825P000116';
    $endpoint = "Rujukan/RS/$norujukan";    
    $response = bpjsRequest($endpoint, $config);
    echo $response;

}
else if ($param == 'noka') {
    $noka = isset($_REQUEST['noka']) ? $_REQUEST['noka'] : '3507240510770001';    
    $endpoint = "Peserta/noka/$noka/tglSEP/$tanggal"; 
    $response = bpjsRequest($endpoint, $config);
    echo $response;

} 
else {

#post Data untuk antrol/HFIS
    $postData = '{
   "kodebooking": "2211210013",
   "jenispasien": "JKN",
   "nomorkartu": "0002073663292",
   "nik": "1118010107560020",
   "nohp": "085635228888",
   "kodepoli": "INT",
   "namapoli": "DALAM",
   "pasienbaru": 0,
   "norm": "123345",
   "tanggalperiksa": "2025-08-09",
   "kodedokter": 16308,
   "namadokter": "Dr Rahadian",
   "jampraktek": "09:00-13:00",
   "jeniskunjungan": 1,
   "nomorreferensi": "132418010922P000020",
   "nomorantrean": "A-12",
   "angkaantrean": 12,
   "estimasidilayani": 1615869169000,
   "sisakuotajkn": 5,
   "kuotajkn": 30,
   "sisakuotanonjkn": 5,
   "kuotanonjkn": 30,
   "keterangan": "Peserta harap 30 menit lebih awal guna pencatatan administrasi."
}';
    $response1 = bpjsRequest('antrean/add', $config, 'POST', $postData, 'antrol');
    echo $response1;
}