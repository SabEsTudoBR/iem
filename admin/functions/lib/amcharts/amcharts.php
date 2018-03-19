<?php

class AmCharts
{
    public static function InsertPieChart($dataUrl, $title = '')
    {

        $id = mt_rand(100000,999999);

        $dataUrl .= '&' . $id;

        $return = <<<EOF
<script type='text/javascript'>
    AmCharts.makeChart("chartdiv{$id}", {
        "type": "pie",
        "valueField": "value",
        "titleField": "title",
        "labelRadiusField": "labelRadius",
        "labelText": "[[title]]",
        "startDuration": 0,
        "dataProvider": AmCharts.loadJSON('{$dataUrl}'),
        "balloonText": "[[value]] [[title]]",
        "legend": {
            "markerType": "circle",
            "position": "left",
        },
        "titles": [{
            "text": "{$title}",
        }],
        "creditsPosition": "bottom-right",
    });
</script>

<div id="chartdiv{$id}" style="width: 100%; height: 300px;"></div>
EOF;

        return $return;
    }

    public static function InsertColumnChart($dataUrl, $title = '')
    {

        $id = mt_rand(100000,999999);

        $dataUrl .= '&' . $id;

        $return = <<<EOF
<script type='text/javascript'>
    var remoteData = AmCharts.loadJSON("{$dataUrl}");
    var valueFields = remoteData['valueFields'];
    var graphData = [];
    var chartData = [];

    $.each(remoteData['chartData'], function (index, value) {
        chartData.push(value);
    });

    $.each(valueFields, function( index, value ) {
        graphData.push({
            "type": "column",
            "title": value,
            "valueField": value,
            "balloonText": value+":[[value]]",
            "lineAlpha": 0,
            "fillAlphas": 1,
        });
    });

    AmCharts.makeChart("chartdiv{$id}", {
        "categoryField": "category",
        "type": "serial",
        "labelRadiusField": "labelRadius",
        "labelText": "[[title]]",
        "startDuration": 0,
        "dataProvider": chartData,
        "plotAreaBorderColor": "#DADADA",
        "plotAreaBorderAlpha": 1,
        "categoryAxis": {
            "autoGridCount": false,
            "gridCount": chartData.length,
            "gridPosition": "start",
            "gridAlpha": 0.1,
            "axisAlpha": 0,
        },
        "graphs": graphData,
        "valueAxes": [{
            "axisAlpha": 0,
            "gridAlpha": 0.1,
            "position": "top",
        }],
        "legend": {
            "markerType": "square",
            "position": "bottom",
        },
        "titles": [{
            "text": "{$title}",
        }],
    });
</script>

<div id="chartdiv{$id}" style="width: 100%; height: 300px;"></div>
EOF;

        return $return;
    }

}
