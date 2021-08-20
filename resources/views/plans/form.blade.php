@include('partials.input',['name'=>'Name','id'=>"name",'placeholder'=>"Plan name",'required'=>true,'value'=>(isset($plan)?$plan->name:null)])
<div class="row">
    <div class="col-md-12">
        @include('partials.input',['name'=>'Plan description','id'=>"description",'placeholder'=>"Plan description...",'required'=>false,'value'=>(isset($plan)?$plan->description:null)])
    </div>
    <div class="col-md-12">
        @include('partials.input',['name'=>'Features list (separate features with comma)','id'=>"features",'placeholder'=>"Plan Features comma separated...",'required'=>false,'value'=>(isset($plan)?$plan->features:null)])
    </div>
</div>

@include('partials.input',['type'=>'number','name'=>'Price','id'=>"price",'placeholder'=>"Plan prce",'required'=>true,'value'=>(isset($plan)?$plan->price:null)])

<div class="row">
    <div class="col-md-6">
        @include('partials.input',['type'=>"number", 'name'=>'Items limit','id'=>"limit_items",'placeholder'=>"Number of items",'required'=>false,'additionalInfo'=>"0 is unlimited numbers of items",'value'=>(isset($plan)?$plan->limit_items:null)])
    </div>
    <div class="col-md-6">
        @include('partials.input',['name'=>'Paddle ID','id'=>"paddle_id",'placeholder'=>"Paddle ID here...",'required'=>false,'value'=>(isset($plan)?$plan->paddle_id:null)])
    </div>
    
</div>

<br/>
<!-- THIS IS SPECIAL -->
<div class="">
    <label class="form-control-label">{{ __("Plan period") }}</label>
    <div class="custom-control custom-radio mb-3">
        <input name="period" class="custom-control-input" id="monthly"  @if (isset($plan))  @if ($plan->period == 1) checked @endif @else checked @endif  value="monthly" type="radio">
        <label class="custom-control-label" for="monthly">{{ __('Monthly') }}</label>
    </div>
    <div class="custom-control custom-radio mb-3">
        <input name="period" class="custom-control-input" id="anually" value="anually" @if (isset($plan) && $plan->period == 2) checked @endif type="radio">
        <label class="custom-control-label" for="anually">{{ __('Anually') }}</label>
    </div>
</div>
<br/>



<div class="text-center">
    <button type="submit" class="btn btn-success mt-4">{{ isset($plan)?__('Update plan'):__('SAVE') }}</button>
</div>