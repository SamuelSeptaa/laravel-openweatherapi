@extends('index')
@section('content')
    <div class="container-fluid">
        <div class="row mt-3" id="home">
            <div class="col-12">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div id="alert-message-error" style="display: none;" class="alert alert-danger alert-dismissible">
                            <div class="alert-body">
                            </div>
                        </div>
                        <div id="alert-message-success" style="display: none;"
                            class="alert alert-success alert-dismissible">
                            <div class="alert-body">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <blockquote class="blockquote text-center">
                    <p class="mb-0">Klik Pada Map Untuk melihat data cuaca</p>
                    </footer>
                </blockquote>
                <div id="map" class="map">

                </div>
            </div>
            <div class="col-6">
                <blockquote class="blockquote text-center">
                    <p class="mb-0">Data lokasi dan cuaca</p>
                    </footer>
                </blockquote>
                <form method="POST" action="#" id="form-create-weather-data" v-on:submit.prevent="submitCreate">
                    <div class="form-group">
                        <label for="name">Location Name</label>
                        <input readonly type="text" class="form-control" name="location_name" id="location_name"
                            v-model="location_name" v-on:change="remove_invalid_error">
                        <div class="invalid-feedback" for="location_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input readonly type="text" class="form-control" name="latitude" id="latitude"
                            v-model="latitude" v-on:change="remove_invalid_error">
                        <div class="invalid-feedback" for="latitude">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input readonly type="text" class="form-control" name="longitude" id="longitude"
                            v-model="longitude" v-on:change="remove_invalid_error">
                        <div class="invalid-feedback" for="longitude">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="timezone">Timezone</label>
                        <input readonly type="text" class="form-control" name="timezone" id="timezone"
                            v-model="timezone" v-on:change="remove_invalid_error">
                        <div class="invalid-feedback" for="timezone">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pressure">Pressure</label>
                        <input readonly type="text" class="form-control" name="pressure" id="pressure"
                            v-model="pressure" v-on:change="remove_invalid_error">
                        <div class="invalid-feedback" for="pressure">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="humidity">Humidity</label>
                        <input readonly type="text" class="form-control" name="humidity" id="humidity"
                            v-model="humidity" v-on:change="remove_invalid_error">
                        <div class="invalid-feedback" for="humidity">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="wind_speed">Wind Speed</label>
                        <input readonly type="text" class="form-control" name="wind_speed" id="wind_speed"
                            v-model="wind_speed" v-on:change="remove_invalid_error">
                        <div class="invalid-feedback" for="wind_speed">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Save Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
