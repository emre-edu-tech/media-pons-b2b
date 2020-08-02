<template>
    
    <div class="container">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="category.html">Category</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sub-category</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-3 mb-3">
                <div class="card bg-light">
                    <div class="card-header bg-primary text-white text-uppercase"><i class="fa fa-list"></i> Categories</div>
                    <ul class="list-group category_block">
                        <li class="list-group-item">
                            <router-link :to="{ path: '/categories'}">All Categories</router-link>
                        </li>
                        <li v-for="category in categories" v-bind:key="category.id" class="list-group-item">
                            <router-link :to="{ path: `/categories?category=${category.slug}` }">
                                {{ category.name }}
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="col-sm-12 col-md-9">
                <div class="products-header d-flex justify-content-between">
                    <h3>{{ categoryName }}</h3>
                    <div class="sorting">
                        <strong>Price: </strong>
                        <router-link :to="{ path: `${currentUrl}${selectedCategory ? `?category=${selectedCategory}&` : '?'}price=low_high` }" class="btn btn-link">low to high</router-link>
                        <router-link :to="{ path: `${currentUrl}${selectedCategory ? `?category=${selectedCategory}&` : '?'}price=high_low` }" class="btn btn-link">high to low</router-link>
                    </div>
                </div>
                <hr>
                <div class="row productListing">
                    <div v-for="product in products.data" v-bind:key="product.id" class="col-sm-12 col-md-6 col-xl-4 mb-3">
                        <div class="card p-4">
                            <router-link :to="{ path: `/products/${product.slug}` }">
                                <img class="card-img-top" src="/storage/products/flavour-ananas.png" :alt="product.name">
                            </router-link>
                            <div class="card-body d-flex flex-column align-items-center">
                                <router-link :to="{ path: `/products/${product.slug}` }">
                                    <h5 class="card-title">{{ product.name }}</h5>
                                </router-link>
                                <p class="card-text product-price">€ {{ product.regular_price }}</p>
                                <p class="card-text text-left">{{ product.short_description | truncatedText }}</p>
                                <router-link :to="{ path: `/products/${product.slug}` }" class="btn btn-primary">Jetzt einkaufen</router-link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <!-- pagination -->
                        <pagination :data="products" @pagination-change-page="getProductsByCategory">
                            <span slot="prev-nav">&lt; Önceki</span>
                            <span slot="next-nav">Sonraki &gt;</span>
                        </pagination>
                    </div>
                </div>
                <div class="row" v-if="products.length <= 0">
                    <div class="col-sm-12 mt-3 font-weight-bold">No products found</div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    export default {
        data() {
            return {
                products:  {},
                categories: [],
                categoryName: '',
                currentUrl: '',
                selectedCategory: '',
                selectedPriceSort: '',
            }
        },
        methods: {
            getCategories() {
                axios.get('/api/get-categories')
                .then((response) => {
                    this.categories = response.data;
                }).catch((error) => {
                    console.log(error);
                });
            },

            getProductsByCategory(page = 1) {
                let url = '';
                if(this.selectedCategory != '') {
                    if(this.selectedPriceSort != '') {
                        url = `/api/get-products-by-category?category=${this.selectedCategory}&price=${this.selectedPriceSort}&page=${page}`;
                    } else {
                        url = `/api/get-products-by-category?category=${this.selectedCategory}&page=${page}`;
                    }
                } else if(this.selectedPriceSort != ''){
                    url = `/api/get-products-by-category?price=${this.selectedPriceSort}&page=${page}`;
                } else {
                    url = `/api/get-products-by-category?page=${page}`;
                }

                axios.get(url)
                .then((response) => {
                    console.log(response);
                    this.products = response.data;
                }).catch((error) => {
                    console.log(error);
                });
            }
        },
        created() {
            this.currentUrl = this.$route.fullPath;
            this.getCategories();
            // in case of refresh save the current page
            let category = this.$route.query.category;
            let priceSort = this.$route.query.price;
            if(category) {
                if (priceSort) {
                    this.selectedCategory = category;
                    this.selectedPriceSort = priceSort;
                    this.getProductsByCategory();
                } else {
                    this.selectedCategory = category;
                    this.getProductsByCategory();
                }
            } else if (priceSort) {
                this.selectedPriceSort = priceSort;
                this.getProductsByCategory();
            } else {
                // probably the first load
                this.getProductsByCategory();
            }
        },

        // watch url changes on this route and view
        watch: {
            '$route'(to, from) {
                this.currentUrl = to.path;
                if(to.query.category) {
                    if(to.query.price) {
                        this.selectedCategory = to.query.category;
                        this.selectedPriceSort = to.query.price;
                        this.getProductsByCategory();
                    } else {
                        this.selectedCategory = to.query.category;
                        this.selectedPriceSort = '';
                        this.getProductsByCategory();
                    }
                } else if(to.query.price) {
                    this.selectedPriceSort = to.query.price;
                    this.getProductsByCategory();
                } else {
                    this.selectedCategory ='';
                    this.selectedPriceSort = '';
                    this.getProductsByCategory();
                }
            }
        },
    }
</script>

<style scoped>
.card-title {
    font-size: 1rem;
}
</style>