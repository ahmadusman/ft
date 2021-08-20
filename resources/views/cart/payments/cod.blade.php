@if(!env('HIDE_COD',false))
    <div class="text-center" id="totalSubmitCOD"  style="display: {{ env('DEFAULT_PAYMENT','cod')=="cod"&&!env('HIDE_COD',false)?"block":"none"}};" >
        <button
            v-if="totalPrice"
            type="submit"
            class="btn btn-success mt-4 paymentbutton"
            onclick="this.disabled=true;this.form.submit();"
        >{{ __('Place order') }}</button>
    </div>
@endif
