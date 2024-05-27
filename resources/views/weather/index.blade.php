@extends('index')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-center mt-3">
            <div class="col-8">
                <blockquote class="blockquote text-center">
                    <p class="mb-0">Data Cuaca Tersimpan</p>
                    </footer>
                </blockquote>
                <div class="card">
                    <div class="card-body">
                        <div id="alert-message-error" style="display: none;" class="alert alert-danger alert-dismissible">
                            <div class="alert-body">
                            </div>
                        </div>
                        <div id="alert-message-success" style="display: none;"
                            class="alert alert-success alert-dismissible">
                            <div class="alert-body">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="the-data-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Location Name</th>
                                        <th>Lat, Long</th>
                                        <th>Timezone</th>
                                        <th>Pressure</th>
                                        <th>Humidity</th>
                                        <th>Wind Speed (m/s)</th>
                                        <th>Last Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
