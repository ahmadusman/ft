@extends('layouts.app', ['title' => __('Restaurant Menu Management')])
@section('admin_title')
    {{__('Menu')}}
@endsection
@section('content')
    @include('items.partials.modals')
    @include('items.partials.header', ['title' => __('Edit Restaurant Menu')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col">
                                        <h3 class="mb-0">{{ __('Restaurant Menu Management') }}</h3>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-icon btn-1 btn-sm btn-primary" type="button" data-toggle="modal" data-target="#modal-items-category" data-toggle="tooltip" data-placement="top" title="Add new category">
                                            <span class="btn-inner--icon"><i class="fa fa-plus"></i></span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-import-items" onClick=(setRestaurantId({{ $restorant_id }}))>{{ __('Import from CSV') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="col-12">
                        @include('partials.flash')
                    </div>
                    <div class="card-body">
                        @foreach ($categories as $category)
                        @if($category->active == 1)
                        <div class="alert alert-default">
                            <div class="row">
                                <div class="col">
                                    <span class="h1 font-weight-bold mb-0 text-white">{{ $category->name }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="row">
                                        <script>
                                            function setSelectedCategoryId(id){
                                                $('#category_id').val(id);
                                            }

                                            function setRestaurantId(id){
                                                $('#res_id').val(id);
                                            }
                                        </script>
                                        <button class="btn btn-icon btn-1 btn-sm btn-primary" type="button" data-toggle="modal" data-target="#modal-new-item" data-toggle="tooltip" data-placement="top" title="{{ __('Add item') }} in {{$category->name}}" onClick=(setSelectedCategoryId({{ $category->id }})) >
                                            <span class="btn-inner--icon"><i class="fa fa-plus"></i></span>
                                        </button>
                                        <form action="{{ route('categories.destroy', $category) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            @if(count($category->items) > 0)
                                                
                                                <button class="btn btn-icon btn-1 btn-sm btn-danger" type="button" onclick="confirm('{{ __("Are you sure you want to delete this category?") }}') ? this.parentElement.submit() : ''" data-toggle="tooltip" data-placement="top" title="{{ __('Delete') }} {{$category->name}}">
                                                    <span class="btn-inner--icon"><i class="fa fa-trash"></i></span>
                                                </button>
                                            @endif

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($category->active == 1)
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="row row-grid">
                                    @foreach ( $category->items as $item)
                                        <div class="col-lg-3">
                                            <a href="{{ route('items.edit', $item) }}">
                                                <div class="card">
                                                    <img class="card-img-top" src="{{ $item->logom }}" alt="...">
                                                    <div class="card-body">
                                                        <h3 class="card-title text-primary text-uppercase">{{ $item->name }}</h3>
                                                        <p class="card-text description mt-3">{{ $item->description }}</p>
                                                        
                                                        <span class="badge badge-primary badge-pill">@money($item->price, env('CASHIER_CURRENCY','usd'),true)</span>
                                                        
                                                        <p class="mt-3 mb-0 text-sm">
                                                            @if($item->available == 1)
                                                            <span class="text-success mr-2">{{ __("AVAILABLE") }}</span>
                                                            @else
                                                            <span class="text-danger mr-2">{{ __("UNAVAILABLE") }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <br/>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
