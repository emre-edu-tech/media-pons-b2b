<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <button class="btn btn-danger" @click="emptyCart">Empty Cart</button>
                <div class="empty-cart-notice" v-if="noProducts">
                    <h3 class="text-info">Your shopping cart is empty!</h3>
                    <router-link :to="{ path: '/products' }" class="btn btn-success">Continue Shopping <i class="fas fa-arrow-circle-right fa-fw mr-2"></i></router-link>
                </div>
                <div class="card shopping-cart" v-else>
                    <div class="card-header bg-dark text-light">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        Shopping cart
                        <router-link :to="{ path: '/products' }" class="btn btn-outline-info btn-sm float-right">Continue Shopping <i class="fas fa-arrow-circle-right fa-fw mr-2"></i></router-link>
                        <div class="clearfix"></div>
                    </div>
                    <div class="card-body">

                        <!-- PRODUCT -->
                        <div class="row align-items-center cart-product" v-for="cartContent in cartContents" v-bind:key="cartContent.id">
                            <div class="col-12 col-sm-12 col-md-2 text-center">
                                <router-link :to="{ path: `/products/${cartContent.model.id}` }">
                                    <img class="img-fluid" src="/storage/products/flavour-ananas.png" alt="prewiew">
                                </router-link>
                            </div>
                            <div class="col-12 text-sm-center col-sm-12 text-md-left col-md-6">
                                <router-link :to="{ path: `/products/${cartContent.model.id}` }">
                                    <h4 class="product-name"><strong>{{ cartContent.name }}</strong></h4>
                                </router-link>
                                <h4>
                                    <small>{{ cartContent.model.short_description }}</small>
                                </h4>
                            </div>
                            <div class="col-12 col-sm-12 text-sm-center col-md-4 text-md-right row align-items-center">
                                <div class="col-3 col-sm-3 col-md-6 text-md-right">
                                    <h6><strong>{{ cartContent.model.regular_price }} <span class="text-muted">x</span></strong></h6>
                                </div>
                                <div class="col-4 col-sm-4 col-md-4">
                                    <div class="quantity">
                                        <input type="button" value="+" class="plus">
                                        <input type="number" step="1" min="50" :value="cartContent.qty" title="Qty" class="qty" size="4">
                                        <input type="button" value="-" class="minus">
                                    </div>
                                </div>
                                <div class="col-2 col-sm-2 col-md-2 text-right">
                                    <button type="button" @click="removeItem(cartContent.rowId)" class="btn btn-outline-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- END PRODUCT -->
                        
                        <div class="float-right">
                            <a href="" class="btn btn-outline-secondary float-right">
                                Update shopping cart
                            </a>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="coupon col-md-5 col-sm-5 float-left">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <input type="text" class="form-control" placeholder="cupone code">
                                </div>
                                <div class="col-6">
                                    <input type="submit" class="btn btn-outline-secondary" value="Use cupone">
                                </div>
                            </div>
                        </div>
                        <div class="float-right" style="margin: 10px">
                            <div class="subTotal">
                                Subtotal: {{ subTotal }} €
                            </div>
                            <div class="tax">
                                Tax (% 18): {{ tax }} €
                            </div>
                            <hr>
                            <div class="total">
                               <span class="font-weight-bold">Total: {{ total }} €</span>
                            </div>
                            <a href="/checkout" class="btn btn-success mt-3">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                cartContents: [],
                subTotal: 0,
                tax: 0,
                total: 0,
            }
        },
        methods: {
            removeItem(cartItemId) {
                axios.post('/remove-cart-item', {
                    cartItemId: cartItemId,
                }).then((response) => {
                    this.getCartContents();
                    toast.fire({
                        icon: 'success',
                        title: response.data.success_message,
                    });
                }).catch((error) => {
                    console.log(error); 
                });
            },

            emptyCart() {
                axios.get('/empty-cart')
                .then(() => {
                    this.cartContents = [];
                    swal.fire(
                        'Success!',
                        'Cart is empty now',
                        'success',
                    );
                });
            },

            getCartContents() {
                axios.get('/check-cart-status')
                .then((response) => {
                    this.cartContents = response.data.cartContent;
                    this.subTotal = response.data.subTotal;
                    this.tax = response.data.tax;
                    this.total = response.data.total;
                }).catch((error) => {
                    console.log(error);
                });
            },
        },

        created() {
            this.getCartContents();
        },

        computed: {
            noProducts: function() {
                return this.cartContents.length === 0;
            },
        }
    }
</script>

<style scoped>
.cart-product {
    margin-bottom: 30px;
}
</style>