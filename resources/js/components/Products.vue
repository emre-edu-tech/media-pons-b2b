<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">

                    <div v-for="product in products" v-bind:key="product.id" class="col-md-4 mt-3">
                        <div class="card p-4">
                            <router-link :to="{ path: `/products/${product.id}` }">
                                <img class="card-img-top" src="/storage/products/flavour-ananas.png" alt="Card image cap">
                            </router-link>
                            <div class="card-body d-flex flex-column align-items-center">
                                <router-link :to="{ path: `/products/${product.id}` }">
                                    <h5 class="card-title">{{ product.name }}</h5>
                                </router-link>
                                <p class="card-text product-price">€ {{ product.regular_price }}</p>
                                <p class="card-text text-left">{{ product.short_description }}</p>
                                <router-link :to="{ path: `/products/${product.id}` }" class="btn btn-primary">Jetzt einkaufen</router-link>
                            </div>
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
                products: [],
            }
        },
        methods: {
            getProducts() {
                axios.get('/api/products')
                .then((response) => {
                    this.products = response.data;
                }).catch((error) => {
                    console.log(error);
                });
            }
        },
        created() {
            this.getProducts();
        }
    }
</script>

<style scoped>
.card-title {
    font-size: 1rem;
}
</style>