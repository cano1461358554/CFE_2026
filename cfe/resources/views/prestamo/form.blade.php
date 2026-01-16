<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="fecha_prestamo" class="form-label">{{ __('Fecha Prestamo') }}</label>
            <input type="text" name="fecha_prestamo" class="form-control @error('fecha_prestamo') is-invalid @enderror" value="{{ old('fecha_prestamo', $prestamo?->fecha_prestamo) }}" id="fecha_prestamo" placeholder="Fecha Prestamo">
            {!! $errors->first('fecha_prestamo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="desc_uso" class="form-label">{{ __('Desc Uso') }}</label>
            <input type="text" name="desc_uso" class="form-control @error('desc_uso') is-invalid @enderror" value="{{ old('desc_uso', $prestamo?->desc_uso) }}" id="desc_uso" placeholder="Desc Uso">
            {!! $errors->first('desc_uso', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>