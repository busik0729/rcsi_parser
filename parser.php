<?php

$search = "педагог";
$dateFrom = "2023-01-01T00:00:01Z";
$dateTo = "2023-12-31T23:59:00Z";

function main($search, $dateFrom, $dateTo) {
    $offset = 1;
    $limit = 100;
    $wh = true;

    $hFile = fopen("res.json", "a+");
    fwrite($hFile, '{"result": [');

    do {
        var_dump($offset);
        $query = [
            'text' => $search,
            'offset' => $offset,
            'limit' => $limit,
            'modifiedFrom' => $dateFrom,
            'modifiedTo' => $dateTo
        ];
        $result = file_get_contents("http://opendata.trudvsem.ru/api/v1/vacancies?" . http_build_query($query));
        $jsonRes = json_decode($result, true);

        if (isset($jsonRes['results']) && isset($jsonRes['results']['vacancies'])) {
            foreach($jsonRes['results']['vacancies'] as $key => $item) {
                fwrite($hFile, json_encode($item, JSON_UNESCAPED_UNICODE) . " ,");
            }

            $offset++;
        } else {
            $wh = false;
        }
        var_dump($wh);
    } while($wh);

    fwrite($hFile, ']}');
    fclose($hFile);
}

main($search, $dateFrom, $dateTo);