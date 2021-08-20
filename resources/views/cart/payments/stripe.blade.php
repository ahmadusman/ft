<!-- STRIPE -->
@if (env('STRIPE_KEY',false)&&env('ENABLE_STRIPE',false))
<form action="/charge" method="post" id="stripe-payment-form" style="display: {{ env('DEFAULT_PAYMENT','cod')=="stripe"?"block":"none"}};"   >

    <div style="width: 100%;" class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
        <input name="name" id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __( 'Name on card' ) }}" value="{{auth()->user()->name}}" required>
        @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>

    <div class="form">
        <div style="width: 100%;" #stripecardelement  id="card-element" class="form-control">

        <!-- A Stripe Element will be inserted here. -->
      </div>

      <!-- Used to display form errors. -->
      <br />
      <div class="" id="card-errors" role="alert">

      </div>
  </div>
  <div class="text-center" id="totalSubmitStripe">
    <button
        v-if="totalPrice"
        type="submit"
        class="btn btn-success mt-4 paymentbutton"
        >{{ __('Place stripe order') }}</button>
  </div>

  </form>
@endif
