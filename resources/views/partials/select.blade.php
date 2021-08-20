<div class="form-group{{ $errors->has($id) ? ' has-danger' : '' }}">
    <br />
    <label class="form-control-label">{{ __($name) }}</label>

    <select class="form-control col-sm"  name="{{ $id }}" id="{{  $id }}">
        <option disabled selected value> {{ __('Select')." ".__($name)}} </option>
        @foreach ($data as $key => $item)
            @if (isset($value)&&$key==$value)
                <option value="{{ $key }}" selected>{{$item }}</option>
            @else
                <option value="{{ $key }}">{{$item }}</option>
            @endif
        @endforeach
    </select>


    @isset($additionalInfo)
        <small class="text-muted"><strong>{{ __($additionalInfo) }}</strong></small>
    @endisset
    @if ($errors->has($id))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($id) }}</strong>
        </span>
    @endif
</div>
