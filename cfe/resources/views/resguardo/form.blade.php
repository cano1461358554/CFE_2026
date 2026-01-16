<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="fecha_resguardo" class="form-label">{{ __('Fecha Resguardo') }}</label>
            <input type="text" name="fecha_resguardo" class="form-control @error('fecha_resguardo') is-invalid @enderror" value="{{ old('fecha_resguardo', $resguardo?->fecha_resguardo) }}" id="fecha_resguardo" placeholder="Fecha Resguardo">
            {!! $errors->first('fecha_resguardo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">{{ __('Estado') }}</label>
            <input type="text" name="estado" class="form-control @error('estado') is-invalid @enderror" value="{{ old('estado', $resguardo?->estado) }}" id="estado" placeholder="Estado">
            {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>