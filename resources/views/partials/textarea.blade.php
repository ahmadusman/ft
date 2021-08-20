<div class="form-group{{ $errors->has($id) ? ' has-danger' : '' }}">
    <label class="form-control-label" for="{{ $id }}">{{ __($name) }}</label>
    <textarea  name="{{ $id }}" id="{{ $id }}"  rows="4" cols="50">{{ old($id, isset($value)?$value:'') }}</textarea>
    @if ($errors->has($id))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($id) }}</strong>
        </span>
    @endif
</div>
