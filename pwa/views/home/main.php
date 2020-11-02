<style>
    .weather-row {
        width: 100%;
        text-align: center;
    }

    .weather-tile {
        display: inline-block;
        width: 40%;
        text-align: center;
    }

    .weather-icon {
        height: 100%;
        /* width: 4em; */
    }

    .stripe-vert {
        display: inline-block;
        height: 6em;
        width: 8%;
    }

    .strip-horz {
        height: 30px;
        width: 6em;
        padding: 0 5px;
    }

    .weather-info {
        text-align: center;
    }

    .weather-container {
        height: 280px;
        width: 220px;
        display: inline-block;
        border: 3px solid #212529;
        border-radius: 10px;
        margin: 0 50px;
        padding: 20px 10px;
    }
</style>


<div class="container-fluid pt-3">
    <div class="weather-container">
        <!-- <div class="d-flex flex-row justify-content-center"> -->
        <div class="weather-row">
            <div class="weather-tile">
                <svg class="weather-icon" viewBox="0 0 16 16" class="bi bi-brightness-high" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"></path>
                </svg>
                <div class="weather-info">Sunny</div>
            </div>
            <svg class="stripe-vert">
                <line x1="50%" y1="5%" x2="50%" y2="95%" style="stroke-width:5" stroke="#212529" stroke-linecap="round" />
            </svg>
            <div class="weather-tile">
                <svg class="weather-icon" viewBox="0 0 16 16" class="bi bi-cloud" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                </svg>
                <div class="weather-info">Cloudy</div>
            </div>
        </div>
        <!-- </div> -->

        <!-- <div class="d-flex flex-row justify-content-center"> -->
        <div class="d-flex flex-row justify-content-center">

            <svg class="strip-horz">
                <line x1="10%" y1="50%" x2="90%" y2="50%" style="stroke-width:5" stroke="#212529" stroke-linecap="round" />
            </svg>

            <svg class="strip-horz">
                <line x1="10%" y1="50%" x2="90%" y2="50%" style="stroke-width:5" stroke="#212529" stroke-linecap="round" />
            </svg>

        </div>
        <!-- </div> -->

        <!-- <div class="d-flex flex-row justify-content-center"> -->
        <div class="weather-row">
            <div class="weather-tile">
                <svg class="weather-icon" viewBox="0 0 16 16" class="bi bi-brightness-high" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M7.21.8C7.69.295 8 0 8 0c.109.363.234.708.371 1.038.812 1.946 2.073 3.35 3.197 4.6C12.878 7.096 14 8.345 14 10a6 6 0 0 1-12 0C2 6.668 5.58 2.517 7.21.8zm.413 1.021A31.25 31.25 0 0 0 5.794 3.99c-.726.95-1.436 2.008-1.96 3.07C3.304 8.133 3 9.138 3 10a5 5 0 0 0 10 0c0-1.201-.796-2.157-2.181-3.7l-.03-.032C9.75 5.11 8.5 3.72 7.623 1.82z" />
                    <path fill-rule="evenodd" d="M4.553 7.776c.82-1.641 1.717-2.753 2.093-3.13l.708.708c-.29.29-1.128 1.311-1.907 2.87l-.894-.448z" />
                </svg>
                <div class="weather-info">No rain</div>
            </div>
            <svg class="stripe-vert">
                <line x1="50%" y1="5%" x2="50%" y2="95%" style="stroke-width:5" stroke="#212529" stroke-linecap="round" />
            </svg>
            <div class="weather-tile">
                <svg class="weather-icon" viewBox="0 0 16 16" class="bi bi-cloud" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M6 2a2 2 0 1 1 4 0v7.627a3.5 3.5 0 1 1-4 0V2zm2-1a1 1 0 0 0-1 1v7.901a.5.5 0 0 1-.25.433A2.499 2.499 0 0 0 8 15a2.5 2.5 0 0 0 1.25-4.666.5.5 0 0 1-.25-.433V2a1 1 0 0 0-1-1z" />
                    <path d="M8.25 2a.25.25 0 0 0-.5 0v9.02a1.514 1.514 0 0 1 .5 0V2z" />
                    <path d="M9.5 12.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                </svg>
                <div class="weather-info">24 &deg;C</div>
            </div>
        </div>
        <!-- </div> -->
    </div>

    <canvas id="sensorDataChart"></canvas>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>

<script>

    







    const chartConfig = {
        type: 'line',
        data: {
            labels: ["test", "da", "df", "sa"],
            datasets: [{
                data: [30, 20, 30, 40]
            }],
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

    window.onload = function() {
        const ctx = document.getElementById("sensorDataChart").getContext("2d");
        window.myLine = new Chart(ctx, chartConfig);
    };
</script>