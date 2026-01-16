@extends('layouts.app')

@section('template_title')
    {{ $ingreso->name ?? __('Show') . " " . __('Ingreso') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Ingreso</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('ingresos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cantidad Ingresada:</strong>
                                    {{ $ingreso->cantidad_ingresada }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha:</strong>
                                    {{ $ingreso->fecha }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
