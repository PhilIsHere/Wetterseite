<?php
header("Access-Control-Allow-Origin: *");
?>

<html>
<style>
    .flex-box {
        display: flex;
        flex-wrap: wrap;
        flex-direction:row;
    }
    .flex-items{
        display: inherit;
        flex-wrap: wrap;
        justify-content: space-around;
        flex-direction: column;
</style>
<head>
<title>Wetter</title>
    <body>
        <form id="standortForm" method="GET">
            <div>
                <label>Standort: </label>
                <input type="text" id="standort" placeholder="Gib den Standort ein" data-min-char="3" data-route="/autocomplete.php" />
            </div>
            <button id="buttonOrt" type="submit">Wetterdaten abfragen</button>
        </form>
        <div id="wetteraktuell">

        </div>
    </body>
</head>
<script>
    try {
        //Schritt 1 Wetter für eine Feste Station abfragen
        //Schritt 2 Darstellung der Response
        //Schritt 3 PHP Script um Anhand eines Ortsnamens die Id abzufragen.
        //Schritt 4 das Skript per Javacsript ansprechen

        //document.getElementById("standort").value
        function nameToLatLon(location) {
            const BASE_URL_STANDORT = "https://nominatim.openstreetmap.org/search.php?"
            const QUERY_STANDORT = "q="+location+"&limit=1&format=jsonv2";
            console.log("LANLOT FUNC: "+BASE_URL_STANDORT+QUERY_STANDORT)
            return fetch(BASE_URL_STANDORT + QUERY_STANDORT)
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    return {
                        'lat': data[0].lat,
                        'lon': data[0].lon
                    }
                });
        }
        const BASE_URL_WETTER = "https://api.brightsky.dev/";
        let suche = document.querySelector('#standortForm');
        let httpRequest = new XMLHttpRequest();
        suche.addEventListener('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();
            console.log("in der function");
            nameToLatLon(document.getElementById("standort").value)
                .then(result => {
                    let standortURL =BASE_URL_WETTER+'' +`current_weather?lat=${result.lat}&lon=${result.lon}`;
                    console.log(standortURL);
                    fetch(standortURL)
                        .then(response => {
                            return response.json();
                        })
                        .then(function (data) {
                                console.log(data);
                                let wetterContainer = document.getElementById('wetteraktuell');
                                let divElement = document.createElement('div');
                                divElement.innerHTML = "Die aktuelle Temperatur beträgt : " + data.weather.temperature;
                                wetterContainer.appendChild(divElement);

                            }
                        )
                        .catch(error => console.error(error));
                })
        });
        //function autocompleteStationen(){
        //}

    }catch (e) {
        console.error(e);
    }
</script>
</html>
