<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="descripcion_unidad" class="form-label">{{ __('Descripcion Unidad') }}</label>
            <input type="text" name="descripcion_unidad" class="form-control @error('descripcion_unidad') is-invalid @enderror" value="{{ old('descripcion_unidad', $unidadMedida?->descripcion_unidad) }}" id="descripcion_unidad" placeholder="Descripcion Unidad">
            {!! $errors->first('descripcion_unidad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>