<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-md-4 offset-md-1 col-sm-12 offset-sm-0">

            <h1 class="text-center">Weather Forecast</h1>

            <div id="weather-forecast">
                <h2 class="city" id="city"></h2>
                <div class="weather" id="weather">
                    <div class="group secondary">
                        <h3 id="dt"></h3>
                        <h3 id="description"></h3>
                    </div>
                    <div class="group secondary">
                        <h3 id="wind"></h3>
                        <br />
                        <h3 id="humidity"></h3>
                    </div>
                    <div class="temperature" id="temperature">
                        <h1 class="temp" id="temp">
                            <i id="condition"></i>
                            <span id="num"></span>
                            &deg;C
                        </h1>
                    </div>
                    <div class="forecast" id="forecast"></div>
                </div>
            </div>

        </div>
        <?php if (User::g('user_id')){ ?>
        <div class="col-md-6 offset-md-1 col-sm-12 offset-sm-0">

            <h1 class="text-center">Sensor Data</h1>

            <canvas id="sensorDataChart"></canvas>

        </div>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"
        integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>

<?php

if (User::g('user_id')){
    $unique_sensors = [];

    for ($i = 0; $i < count($sensor_data); $i++) {
        if (array_key_exists($sensor_data[$i]["node_id"], $unique_sensors)) {
            if (!in_array($sensor_data[$i]["type"], $unique_sensors[$sensor_data[$i]["node_id"]])) {
                $unique_sensors[$sensor_data[$i]["node_id"]][] = $sensor_data[$i]["type"];
            }
        } else {
            $unique_sensors[$sensor_data[$i]["node_id"]] = [$sensor_data[$i]["type"]];
        }
    }

    $unique_sensor_count = 0;

    foreach ($unique_sensors as $sensor) {
        $unique_sensor_count += count($sensor);
    }

    $date = new DateTime('now');
    $date->modify('-2 hours')->modify('-10 minutes');
    $timestamps = [];

    for ($i = 0; $i < 13; $i++) {
        $date->modify('+10 minute');
        $timestamps[] = clone $date;
    }

    $js_timestamps = "[";
    foreach ($timestamps as $timestamp) {
        $js_timestamps .= "'" . $timestamp->format("H:i") . "',";
    }
    $js_timestamps = substr($js_timestamps, 0, -1) . "]";

    $date_parse = function ($value) {
        return date_parse($value);
    };

    function inTimeBracket($timestamp_array, $index, $minute_index)
    {
        $in_bracket = false;
        if (floor($timestamp_array[$index]->format("i") / 6) == $minute_index) {
            $in_bracket = true;
        }
        return $in_bracket;
    }
}


?>

<script type="text/javascript">

    <?php if (User::g('user_id')){ ?>

    const chartConfig = {
        type: 'line',
        data: {
            labels: <?= $js_timestamps ?>,
            datasets: [
                <?php
                $units = [];
                for ($i = 0; $i < count($sensor_data); $i++) {
                $unit = $sensor_data[$i]["unit"];
                if ($unit !== "") {
                    $label = "[" . $sensor_data[$i]["node_id"] . "] " . $sensor_data[$i]["type"] . " (" . $unit . ")";
                } else {
                    $label = "[" . $sensor_data[$i]["node_id"] . "] " . $sensor_data[$i]["type"];
                }
                if (!in_array($unit, $units)) {
                    $units[] = $unit;
                }
                ?>
                {
                    label: "<?= $label ?>",
                    <?php
                    $data_string = 'data: [';

                    for ($j = 0; $j < 13; $j++) {
                        $added = false;
                        foreach ($sensor_data as $data) {
                            if ($timestamps[$j]->format("H") == $data["hour"] && inTimeBracket($timestamps, $j, $data["minute_window_id"]) && $data["type"] == $sensor_data[$i]["type"] && $data["node_id"] == $sensor_data[$i]["node_id"]) {
                                $data_string .= $data['value'] . ",";
                                $added = true;
                            }
                        }
                        if (!$added) {
                            $data_string .= ",";
                        }
                    }

                    $data_string = rtrim($data_string, ", ") . "], fill: false,";

                    echo $data_string;

                    if ($i == $unique_sensor_count - 1) {
                        echo "}";
                        break;
                    }
                    ?>
                },
                <?php
                } ?>
            ],
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'click a sensor to hide its data'
            },
            hover: {
                mode: 'point'
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                    },
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                        labelString: 'Value'
                    },
                }]
            }
        }
    };

    $.each(chartConfig.data.datasets, function (i, dataset) {
        const color = hexToRgb(intToRGB(hashCode(dataset.label)));
        dataset.borderColor = 'rgba(' + color.r + ',' + color.g + ',' + color.b + ', 0.4)';
        dataset.backgroundColor = 'rgba(' + color.r + ',' + color.g + ',' + color.b + ', 0.5)';
        dataset.pointBorderColor = 'rgba(' + color.r + ',' + color.g + ',' + color.b + ', 0.7)';
        dataset.pointBackgroundColor = 'rgba(' + color.r + ',' + color.g + ',' + color.b + ', 0.5)';
        dataset.pointBorderWidth = 1;
    });

    function hashCode(str) { // java String#hashCode
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        return hash;
    }

    function intToRGB(i) {
        const c = (i & 0x00FFFFFF)
            .toString(16)
            .toUpperCase();

        return "00000".substring(0, 6 - c.length) + c;
    }

    function hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }
    <?php } ?>

    // https://codepen.io/socrates/pen/waYPXx

    const weatherUrl = "https://api.weather.com/v2/turbo/vt1dailyForecast?apiKey=d522aa97197fd864d36b418f39ebb323&format=json&geocode=52.00%2C4.38&language=en-US&units=m";

    function getLatestWeatherData() {
        $.getJSON(weatherUrl, function (data) {
            console.log(data);
            console.log(data['vt1dailyForecast']);
        });
    }

    function titleCase(str) {
        return str.split(' ').map(function (word) {
            return word[0].toUpperCase() + word.substring(1);
        }).join(' ');
    }

    function fullDay(str) {
        switch (str) {
            case 'Tue':
                return 'Tuesday';
            case 'Wed':
                return 'Wednesday';
            case 'Thu':
                return 'Thursday';
            case 'Sat':
                return 'Saturday';
            default:
                return str + 'day';
        }
    }

    const $city = $('#city'),
        $weather = $('.weather'),
        $group = $('.group'),
        $dt = $group.find('#dt'),
        $description = $group.find('#description'),
        $wind = $group.find('#wind'),
        $humidity = $group.find('#humidity'),
        $temperature = $weather.find('#temperature'),
        $temp = $temperature.find('#temp'),
        $icon = $temp.find('#condition'),
        $tempNumber = $temp.find('#num'),
        $celsius = $temp.find('#celsius'),
        $forecast = $weather.find('#forecast');

    function getWeather(input) {

        const appid = '58b6f7c78582bffab3936dac99c31b25';
        const requestWeather = $.ajax({
            dataType: 'json',
            url: '//api.openweathermap.org/data/2.5/weather',
            data: {
                q: input,
                units: 'imperial',
                appid: appid
            }
        });
        const requestForecast = $.ajax({
            dataType: 'json',
            url: '//api.openweathermap.org/data/2.5/forecast/daily',
            data: {
                q: input,
                units: 'imperial',
                cnt: '6',
                appid: appid
            }
        });

        $icon.removeClass();

        requestWeather.done(function (data) {

            const weather = document.getElementById('weather');
            if (data.cod === '404') {
                $city.html('city not found');
                setBackground('color404');
                weather.style.display = 'none';
            } else weather.style.display = '';

            const dt = new Date(data.dt * 1000).toString().split(' ');

            const title = data.sys.country ? data.name + ', ' + data.sys.country : data.name;

            $city.html(title);
            $tempNumber.html(Math.round((data.main.temp - 32) * (5 / 9)));
            $description.html(titleCase(data.weather[0].description));
            $wind.html('Wind: ' + Math.round(data.wind.speed * 160.934) / 100 + ' km/h');
            $humidity.html('Humidity ' + data.main.humidity + '%');
            $dt.html(fullDay(dt[0]) + ' ' + dt[4].substring(0, 5));

            function setBackground(background) {
                $('#weather-forecast').removeClass().addClass(background);
            }

            if (data.main.temp >= 80) setBackground('hot', 'button-hot');
            else if (data.main.temp >= 70) setBackground('warm', 'button-warm');
            else if (data.main.temp >= 60) setBackground('cool', 'button-cool');
            else setBackground('cold', 'button-cold');

            switch (data.weather[0].icon) {
                case '01d':
                    $icon.addClass('wi wi-day-sunny');
                    break;
                case '02d':
                    $icon.addClass('wi wi-day-sunny-overcast');
                    break;
                case '01n':
                    $icon.addClass('wi wi-night-clear');
                    break;
                case '02n':
                    $icon.addClass('wi wi-night-partly-cloudy');
                    break;
            }

            switch (data.weather[0].icon.substr(0, 2)) {
                case '03':
                    $icon.addClass('wi wi-cloud');
                    break;
                case '04':
                    $icon.addClass('wi wi-cloudy');
                    break;
                case '09':
                    $icon.addClass('wi wi-showers');
                    break;
                case '10':
                    $icon.addClass('wi wi-rain');
                    break;
                case '11':
                    $icon.addClass('wi wi-thunderstorm');
                    break;
                case '13':
                    $icon.addClass('wi wi-snow');
                    break;
                case '50':
                    $icon.addClass('wi wi-fog');
                    break;
            }
        });

        requestForecast.done(function (data) {

            let forecast = [];
            for (let i = 1; i < data.list.length; i++) {
                forecast.push({
                    date: new Date(data.list[i].dt * 1000).toString().split(' ')[0],
                    celsius: {
                        high: Math.round((data.list[i].temp.max - 32) * (5 / 9)),
                        low: Math.round((data.list[i].temp.min - 32) * (5 / 9))
                    }
                });
            }

            let arr = [];
            for (let i = 0; i < forecast.length; i++) {
                arr[i] = ("<div class='block'><h3 class='secondary'>" + forecast[i].date + "</h3><h2 class='high'>" + forecast[i].celsius.high + "</h2><h4 class='secondary'>" + forecast[i].celsius.low + "</h4></div>");
            }
            $forecast.html(arr.join(''));

        });
    }


    window.onload = function () {
        <?php if (User::g('user_id')){ ?>
            const ctx = document.getElementById("sensorDataChart").getContext("2d");
            window.myLine = new Chart(ctx, chartConfig);
        <?php } ?>
        getWeather("Delft, The Netherlands");
    };
</script>
