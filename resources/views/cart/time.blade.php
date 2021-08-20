<div class="card card-profile shadow">
    <div class="px-4">
      <div class="mt-5">
        <h3><span class="delTime">{{ __('Delivery time') }}</span><span class="picTime">{{ __('Pickup time') }}</span><span class="font-weight-light"></span></h3>
      </div>
      <div class="card-content border-top">
        <br />
        <select name="timeslot" id="timeslot" class="form-control{{ $errors->has('timeslot') ? ' is-invalid' : '' }}" required>
          @foreach ($timeSlots as $value => $text)
              <option value={{ $value }}>{{$text}}</option>
          @endforeach
      </select>
      </div>
      <br />
      <br />
    </div>
  </div>
  <br />