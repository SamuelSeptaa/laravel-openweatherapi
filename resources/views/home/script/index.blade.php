<script>
    let app = new Vue({
        el: "#home",
        data: {
            location_name: "",
            latitude: "",
            longitude: "",
            timezone: "",
            pressure: "",
            humidity: "",
            wind_speed: "",
        },
        methods: {
            submitCreate: function() {
                let form_data = new FormData();
                let self = this;
                form_data.append("location_name", this.location_name);
                form_data.append("latitude", this.latitude);
                form_data.append("longitude", this.longitude);
                form_data.append("timezone", this.timezone);
                form_data.append("pressure", this.pressure);
                form_data.append("humidity", this.humidity);
                form_data.append("wind_speed", this.wind_speed);
                $.ajax({
                    url: "{{ route('store-weather') }}",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"),
                    },
                    type: "POST",
                    data: form_data,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        showLoading();
                    },
                    success: function(response) {
                        $("#alert-message-success").find(".alert-body").html(response
                            .message);
                        $("#alert-message-success").fadeIn(200);
                        self.location_name = "";
                        self.latitude = "";
                        self.longitude = "";
                        self.timezone = "";
                        self.pressure = "";
                        self.humidity = "";
                        self.wind_speed = "";
                    },
                    error: function(xhr, status, error) {
                        const responseJson = xhr.responseJSON;
                        $("#alert-message-error").find(".alert-body")
                            .html(
                                responseJson
                                .message);
                        $("#alert-message-error").fadeIn(200);
                        switch (xhr.status) {
                            case 422:
                                const errors = Object.entries(
                                    responseJson
                                    .errors);
                                errors.forEach(([field, message]) => {
                                    $(`div.invalid-feedback[for="${field}"]`)
                                        .html(
                                            message);
                                    $(`#${field}`).addClass(
                                        'is-invalid');
                                });

                                break;
                        }
                    },
                    complete: function() {
                        hideLoading();
                        setTimeout(
                            function() {
                                $("#alert-message-error")
                                    .fadeOut(
                                        500);
                                $("#alert-message-success")
                                    .fadeOut(
                                        500);
                            }, 5000);
                    }
                });

            },
            remove_invalid_error: function(target) {
                $(target).removeClass('is-invalid');
            },
        },
    })
    mapboxgl.accessToken = mapboxKey;
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v12', // style URL
        center: [106.816666, -6.200000], // starting position [lng, lat]
        zoom: 12 // starting zoom
    });
    let geolocateControl = new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true
        },
        trackUserLocation: false,
        showUserHeading: true
    });

    let marker;
    map.on('load', () => {
        map.resize();
        map.addControl(geolocateControl);
    });

    map.on('click', (e) => {
        if (marker)
            marker.remove();
        marker = new mapboxgl.Marker()
            .setLngLat([e.lngLat.lng, e.lngLat.lat])
            .addTo(map);

        generateWeatherData(e.lngLat.lng, e.lngLat.lat);
    });

    function generateWeatherData(longitude, latitude) {
        $.ajax({
            url: "{{ route('get-weather-data') }}",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"),
            },
            type: "POST",
            data: {
                longitude: longitude,
                latitude: latitude
            },
            dataType: "json",
            // processData: false,
            // contentType: false,
            beforeSend: function() {
                showLoading();
                app.$data.location_name = "";
                app.$data.latitude = "";
                app.$data.longitude = "";
                app.$data.timezone = "";
                app.$data.pressure = "";
                app.$data.humidity = "";
                app.$data.wind_speed = "";
            },
            success: function(response) {
                const weather_data = response.data.weather_data;
                app.$data.location_name = weather_data.location_name;
                app.$data.latitude = weather_data.coord.lat;
                app.$data.longitude = weather_data.coord.lon;
                app.$data.timezone = weather_data.timezone;
                app.$data.pressure = weather_data.pressure;
                app.$data.humidity = weather_data.humidity;
                app.$data.wind_speed = weather_data.wind_speed;

                $("#form-create-weather-data input").removeClass('is-invalid');
            },
            error: function(xhr, status, error) {
                const responseJson = xhr.responseJSON;
                $("#alert-message-error").find(".alert-body")
                    .html(
                        responseJson
                        .message);
                $("#alert-message-error").fadeIn(200);
                switch (xhr.status) {
                    case 422:
                        const errors = Object.entries(
                            responseJson
                            .errors);
                        errors.forEach(([field, message]) => {
                            $(`div.invalid-feedback[for="${field}"]`)
                                .html(
                                    message);
                            $(`#${field}`).addClass(
                                'is-invalid');
                        });

                        break;
                }
            },
            complete: function() {
                hideLoading();
                setTimeout(
                    function() {
                        $("#alert-message-error")
                            .fadeOut(
                                500);
                        $("#alert-message-success")
                            .fadeOut(
                                500);
                    }, 5000);
            }
        });
    }
</script>
