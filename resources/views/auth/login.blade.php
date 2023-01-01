@extends('layouts.auth')

@section('titulo')
    Ingresar
@endsection

@section('menu_select')
    {{ $select = 'login' }}
@endsection

@section('contenido')
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="{{ asset('admin/img/login.jpeg') }}" class="img-fluid" alt="login">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                           <h1 class="lead fw-normal">CONTROL ASISTENCIAS</h1>
                        </div>

                        <div class="divider d-flex align-items-center my-4">
                            <p class="text-center fw-bold mx-3 mb-0"></p>
                        </div>

                        <!-- Email input -->
                        <div class="form-outline mb-4">
                            <input type="email" name="email" id="correo" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                placeholder="Introduzca un correo electrónico" />
                            <label class="form-label" for="correo">Correo Electronico</label>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-3">
                            <input type="password" name="password" id="password" class="form-control form-control-lg  @error('password') is-invalid @enderror"
                                placeholder="Introduzca la contraseña" />
                            <label class="form-label" for="password">Contraseña</label>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Checkbox -->
                            <div class="form-check mb-0">
                                <input class="form-check-input me-2" type="checkbox" name="remember" />
                                <label class="form-check-label" for="form2Example3">
                                    Recuérdame
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-body">¿Ha olvidado su contraseña?</a>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" class="btn btn-primary btn-lg"
                              style="padding-left: 2.5rem; padding-right: 2.5rem;">Ingresar</button>
                          </div>

                    </form>
                </div>
            </div>
        </div>
        <div
            class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-primary">
            <!-- Copyright -->
            <div class="text-white mb-3 mb-md-0">
                Copyright © 2022. All rights reserved.
            </div>
            <!-- Copyright -->

            <!-- Right -->
            <div>
                
            </div>
            <!-- Right -->
        </div>
    </section>
@endsection
