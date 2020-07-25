<template>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <router-link :to="{ path: '/products' }" class="btn btn-success"><i class="fas fa-arrow-circle-left fa-fw mr-2"></i> Zurück</router-link>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <img class="img-fluid" src="/storage/products/flavour-ananas.png" :alt="product.name">
                <h3 class="title">{{product.name}}</h3>
                <h4 class="text-red">€ {{ product.regular_price }}</h4>
                <p class="text-muted">{{product.short_description}}</p>
                <input type="number" name="quantity" id="quantity" min="100" step="50" value="100">
                <button class="btn btn-primary" @click="addtoCart(product)">Add to cart</button>
                <hr>
                <p>{{ product.long_description }}</p>
                <hr>
                <div class="cart-info" v-if="!noProduct">
                    <p><strong>{{ cartCount }}</strong> product(s) found in your cart.</p>
                    <router-link :to="{ path: '/cart' }" class="btn btn-success">Go to Cart <i class="fas fa-arrow-circle-right fa-fw mr-2"></i></router-link>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                product: [],
                cartCount: 0,
            }
        },
        methods: {
            addtoCart(product) {
                let quantity = parseInt($('#quantity').val());
                if(!quantity) {
                    swal.fire(
                        'Fehler!',
                        'Quantity must be selected',
                        'error',
                    );
                    return;       
                }
                axios.post('/add-product', {
                    id: product.id,
                    name: product.name,
                    quantity: quantity,
                    price: product.regular_price,
                }).then((response)=>{
                    this.cartCount = response.data.cartCount;
                    swal.fire(
                        'Success',
                        response.data.success_message,
                        'success',
                    );
                }).catch((error) => {
                    console.log(error);
                });
            },

            getProduct() {
                let url = `/api/products/${this.$route.params.id}`;
                axios.get(url)
                .then((response) => {
                    this.product = response.data;
                }).catch((error) => {
                    console.log(error);
                });
            },

            getCartCount() {
                axios.get(`/get-cart-count`)
                .then((response) => {
                    this.cartCount = response.data.cartCount;
                }).catch((error) => {
                    console.log(error);
                });
            }
        },

        created() {
            this.getProduct();
            this.getCartCount();
        },

        computed: {
            noProduct: function() {
                return this.cartCount <= 0;
            }
        }
    }
</script>

<style scoped>
.text-red {
    color: red;
}

.title {
    font-size: 36px;
}
</style>