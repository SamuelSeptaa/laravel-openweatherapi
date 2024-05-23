@extends('index')
@section('content')
    <div id="login">
        <div class="d-flex justify-content-center mt-5 p-5">
            <div class="col-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Login</h4>
                    </div>
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
                        <form method="POST" action="#" id="form-login" v-on:submit.prevent="submitLogin">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" v-model="email"
                                    v-on:change="remove_invalid_error">
                                <div class="invalid-feedback" for="email">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="password" class="d-block">Password</label>
                                <input id="password" type="password" class="form-control" name="password"
                                    v-model="password" v-on:change="remove_invalid_error">
                                <div id="pwindicator" class="pwindicator">
                                    <div class="bar"></div>
                                    <div class="label"></div>
                                </div>
                                <div class="invalid-feedback" for="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    Login
                                </button>
                            </div>
                            <div class="mt-2 text-muted text-center">
                                Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
