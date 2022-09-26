<?php

header('Content-Type: application/json');

$mete_data = ["date" => 0];

if (file_exists('meteovarela.json')) {
    $mete_data = json_decode(file_get_contents('meteovarela.json'), true);
}

if ($mete_data['date'] + 60 * 10 < time()) {
    $mete_data = getParsedData();
    $mete_data['next_take_after'] = $mete_data['date'] + 60 * 10;

    file_put_contents('meteovarela.json', json_encode($mete_data));

    echo json_encode($mete_data);
} else {
    echo json_encode($mete_data);
}

/**
 * Gets data from meteovarela 
 * @return array
 */

function getParsedData()
{
    $unparsed_html = file_get_contents('http://www.varela.gob.ar/servmetfv/default.aspx');

    $fecha_hora_s = "/<tr>\s*<td width=\"50%\" align=\"center\"><font face=\"Arial\" size=\"3\"><b>FECHA:\s*(.*?)<\/b><\/font><\/td>\s*<td width=\"50%\" align=\"center\"><font face=\"Arial\" size=\"3\"><b>HORA:\s*(.*?)<\/b><\/font><\/td>\s*<\/tr>/i";
    preg_match($fecha_hora_s, $unparsed_html, $fecha_hora_matches);
    $fecha = $fecha_hora_matches[1];
    $hora = $fecha_hora_matches[2];

    $temp = "/Temperatura actual\&nbsp;\s*<\/font><\/b><\/td>\s*<center>\s*<td width=\"57%\"  colspan=\"2\" align=\"center\">\s*<p align=\"center\"><font color=\"#000\">(.*?)\s*°C&nbsp; <\/font><\/td>\s*<\/tr>/i";
    $temp = preg_match($temp, $unparsed_html, $temp_matches);
    $temperatura = $temp_matches[1];

    $s_termica = "/<td width=\"100%\" colspan=\"2\" align=\"center\"><b><font color=\"#000\">Sensacion Termica<\/font><\/b><\/td>\s*<td width=\"28%\"  align=\"center\"><font color=\"#000\">(.*?)\s*°C&nbsp; <\/font><\/td>/i";
    preg_match($s_termica, $unparsed_html, $s_termica_matches);
    $sensacion_termica = $s_termica_matches[1];

    $records_dia_s = "/<tr>\s*<td width=\"43%\" align=\"center\" ><font color=\"#000\">&nbsp;<\/font><\/td>\s*<td width=\"29%\"  align=\"center\"><font color=\"#000\">(.*?)\s*°C&nbsp; <\/font><\/td>\s*<td width=\"28%\"  align=\"center\"><font color=\"#000\">(.*?)\s*°C&nbsp; <\/font><\/td>\s*<\/tr>/i";
    preg_match($records_dia_s, $unparsed_html, $records_dia_s_matches);
    $record_dia = [
        "minima" => $records_dia_s_matches[1],
        "maxima" => $records_dia_s_matches[2],
    ];

    $velocidad_viento_s = "/<tr>\s*<td width=\"43%\"  align=\"center\"><b><font color=\"#000\">V<\/font><font color=\"#000\">elocidad&nbsp;\s*<\/font><\/b><\/td>\s*<td width=\"57%\"  align=\"center\">\s*<p align=\"center\"><font color=\"#000\">(.*?)\s*km\/h&nbsp;<\/font><\/td>\s*<\/tr>/i";
    preg_match($velocidad_viento_s, $unparsed_html, $velocidad_viento_s_matches);
    $velocidad_viento = $velocidad_viento_s_matches[1];


    $direccion_viento_s = "/<tr>\s*<td width=\"43%\"  align=\"center\"><font color=\"#000\"><b>Dirección&nbsp; <\/b><\/font><\/td>\s*<td width=\"57%\"  align=\"center\">\s*<p align=\"center\"><font color=\"#000\">(.*?)\s*&nbsp;<\/font><\/td>\s*<\/tr>/i";
    preg_match($direccion_viento_s, $unparsed_html, $direccion_viento_s_matches);
    $direccion_viento = $direccion_viento_s_matches[1];

    $lluvia_diaria_s = "/<td width=\"43%\"  >\s*<p align=\"center\"><b><font color=\"#000\">Lluvia diaria&nbsp;&nbsp;<\/font><\/b><\/td>\s*<center>\s*<td width=\"57%\"  align=\"center\"><font color=\"#000\">\s*<p align=\"center\"><font color=\"#000\">(.*?)\s*&nbsp<\/font><\/td>\s*<\/tr>/";

    preg_match($lluvia_diaria_s, $unparsed_html, $lluvia_diaria_s_matches);
    $lluvia_diaria = $lluvia_diaria_s_matches[1];

    $presion_atm_s = "/<tr>\s*<td width=\"43%\" align=\"center\" >\s*<p align=\"center\"><font color=\"#000\"><b>Presion&nbsp;&nbsp;<\/b><\/font><\/td>\s*<center>\s*<td width=\"57%\"  align=\"center\">\s*<p align=\"center\"><font color=\"#000\">(.*?)\s*&nbsp<\/font><\/td>\s*<\/tr>/i";
    preg_match($presion_atm_s, $unparsed_html, $presion_atm_s_matches);
    $presion_atm = $presion_atm_s_matches[1];

    $humedad_relativa_s = "/<td width=\"43%\" >\s*<p align=\"center\"><b><font color=\"#000\">Humedad&nbsp; <\/font><\/b><\/td>\s*<center><center>\s*<td width=\"57%\"  colspan=\"2\" align=\"center\">\s*<p align=\"center\"><font color=\"#000\">(.*?)\s*%\s*&nbsp; <\/font><\/td>\s*<\/tr>/i";
    preg_match($humedad_relativa_s, $unparsed_html, $humedad_relativa_s_matches);

    $humedad_relativa = $humedad_relativa_s_matches[1];

    $punto_rocio_s = "/<td width=\"43%\" align=\"center\" >\s*<p align=\"center\"><font color=\"#000\"><b>Punto de rocio&nbsp;&nbsp;<\/b><\/font><\/td>\s*<center>\s*<td width=\"57%\"  align=\"center\">\s*<p align=\"center\"><font color=\"#000\">(.*?)\s*°C\s*&nbsp<\/font><\/td>/i";

    preg_match($punto_rocio_s, $unparsed_html, $punto_rocio_matches);
    $punto_rocio = $punto_rocio_matches[1];

    return [
        "date" => time(),
        "fecha" => $fecha,
        "hora" => $hora,
        "temperatura" => $temperatura,
        "sensacion_termica" => $sensacion_termica,
        "record_dia" => $record_dia,
        "velocidad_viento" => $velocidad_viento,
        "direccion_viento" => $direccion_viento,
        "lluvia_diaria" => $lluvia_diaria,
        "presion_atm" => $presion_atm,
        "humedad_relativa" => $humedad_relativa,
        "punto_rocio" => $punto_rocio,
    ];
}
