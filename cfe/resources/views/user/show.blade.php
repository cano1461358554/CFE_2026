@extends('layouts.app')

@section('template_title')
    {{ $user->name ?? __('Show') . " " . __('User') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} User</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('users.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $user->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Apellido:</strong>
                                    {{ $user->apellido }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Rp:</strong>
                                    {{ $user->RP }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo Usuario:</strong>
                                    {{ $user->tipo_usuario }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
