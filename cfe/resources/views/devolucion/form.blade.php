<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="fecha_devolucion" class="form-label">{{ __('Fecha Devolucion') }}</label>
            <input type="text" name="fecha_devolucion" class="form-control @error('fecha_devolucion') is-invalid @enderror" value="{{ old('fecha_devolucion', $devolucion?->fecha_devolucion) }}" id="fecha_devolucion" placeholder="Fecha Devolucion">
            {!! $errors->first('fecha_devolucion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cantidad_devuelta" class="form-label">{{ __('Cantidad Devuelta') }}</label>
            <input type="text" name="cantidad_devuelta" class="form-control @error('cantidad_devuelta') is-invalid @enderror" value="{{ old('cantidad_devuelta', $devolucion?->cantidad_devuelta) }}" id="cantidad_devuelta" placeholder="Cantidad Devuelta">
            {!! $errors->first('cantidad_devuelta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="descripcion_estado" class="form-label">{{ __('Descripcion Estado') }}</label>
            <input type="text" name="descripcion_estado" class="form-control @error('descripcion_estado') is-invalid @enderror" value="{{ old('descripcion_estado', $devolucion?->descripcion_estado) }}" id="descripcion_estado" placeholder="Descripcion Estado">
            {!! $errors->first('descripcion_estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>