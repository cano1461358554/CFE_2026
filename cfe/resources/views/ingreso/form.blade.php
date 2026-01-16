<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="cantidad_ingresada" class="form-label">{{ __('Cantidad Ingresada') }}</label>
            <input type="text" name="cantidad_ingresada" class="form-control @error('cantidad_ingresada') is-invalid @enderror" value="{{ old('cantidad_ingresada', $ingreso?->cantidad_ingresada) }}" id="cantidad_ingresada" placeholder="Cantidad Ingresada">
            {!! $errors->first('cantidad_ingresada', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha" class="form-label">{{ __('Fecha') }}</label>
            <input type="text" name="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ old('fecha', $ingreso?->fecha) }}" id="fecha" placeholder="Fecha">
            {!! $errors->first('fecha', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>