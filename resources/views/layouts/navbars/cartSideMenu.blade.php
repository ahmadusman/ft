<div id="cartSideNav" class="sidenav-cart sidenav-cart-close">
    <div class="offcanvas-menu-inner">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="minicart-content">
            <div class="minicart-heading">
                <h4>{{ __('Shopping Cart') }}</h4>
            </div>
            <div class="searchable-container">
                <div id="cartList">
                    <div v-for="item in items" class="items col-xs-12 col-sm-12 col-md-12 col-lg-12 clearfix">
                        <div class="info-block block-info clearfix" v-cloak>
                            <div class="square-box pull-left">
                                <img :src="item.attributes.image"  class="productImage" width="100" height="105" alt="">
                            </div>
                            <h6 class="product-item_title">@{{ item.name }}</h6>
                            <p class="product-item_quantity">@{{ item.quantity }} x @{{ item.attributes.friendly_price }}</p>
                            <ul class="pagination">
                                <li class="page-item">
                                    <button v-on:click="decQuantity(item.id)" :value="item.id" class="page-link" tabindex="-1">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </li>
                                <li class="page-item">
                                    <button v-on:click="incQuantity(item.id)" :value="item.id" class="page-link" >
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </li>
                                <li class="page-item">
                                    <button v-on:click="remove(item.id)"  :value="item.id" class="page-link" >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="totalPrices" v-cloak>
                    <div  class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <span v-if="totalPrice==0">{{ __('Cart is empty') }}!</span>
                                    <span v-if="totalPrice"><strong>{{ __('Subtotal') }}:</strong></span>
                                    <span v-if="totalPrice" class="ammount"><strong>@{{ totalPriceFormat }}</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div v-if="totalPrice" v-cloak>
                        <a  href="/cart-checkout" class="btn btn-primary text-white">{{ __('Checkout') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
