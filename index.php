<?php

$api = "http://i2singenierie.com:8084/mapfish/print/apps.json";
$postURL = "http://i2singenierie.com:8084/mapfish/print/datasource_dynamic_tables/report.pdf";
$downloadReportURL = "http://i2singenierie.com:8084/mapfish/print/report/::ref::";

// prepare report
if(isset($_GET['report'])){
    $curl = curl_init();

    $data = file_get_contents('http://i2singenierie.com:8084/mapfish/print-apps/datasource_dynamic_tables/requestData-landscape.json'); // get data from json file
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // set headers
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // set the data fields
    curl_setopt($curl, CURLOPT_URL, $postURL); // Set the url
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Disable the output on the browser
    $result = curl_exec($curl); // execute

    $output = json_decode($result); // parse json result
    $downloadURL = str_replace('::ref::', $output->ref, $downloadReportURL);

    // Download the pdf file
    $report = file_get_contents($downloadURL);
    header('Content-Type: application/pdf');
    header("Content-Disposition:attachment;filename=\"report.pdf\"");
    header("Content-Length: " . filesize($report));

    echo $report;
}else{
    // Load apps list
    echo '<pre>';
    print_r(json_decode(file_get_contents($api)));
    echo '</pre>';
}