<template>
    <div class="container">
        <div class="row" v-if="isOrderDone">
            <h3>Your payment has been accepted. Thanks!</h3>
        </div>
        <div class="row" v-if="noProducts">
            <div class="empty-cart-notice">
                <h3 class="text-info mb-3">Your shopping cart is empty!</h3>
                <router-link :to="{ path: '/products' }" class="btn btn-success">Continue Shopping <i class="fas fa-arrow-circle-right fa-fw mr-2"></i></router-link>
            </div>
        </div>
       <div class="row" v-if="!noProducts">
            <div class="col-md-4 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Your cart</span>
                    <span class="badge badge-secondary badge-pill">{{ cartCount }}</span>
                </h4>
                <ul class="list-group mb-3">
                    <!-- Product -->
                    <li v-for="cartContent in cartContents" v-bind:key="cartContent.id" class="list-group-item d-flex justify-content-between">
                        <img class="img-fluid" style="max-width: 30%;" src="/storage/products/flavour-ananas.png">
                        <div style="max-width: 45%" class="ml-2">
                            <h6 class="my-0">{{ cartContent.name }}</h6>
                            <hr style="margin: 5px 0">
                            <small class="text-muted">{{ cartContent.model.short_description | truncatedText }}</small>
                        </div>
                        <div style="max-width: 25%" class="text-center">
                            <span class="text-muted">{{ cartContent.model.regular_price }} €</span>
                            <span class="badge badge-secondary badge-pill">{{ cartContent.qty }}</span>
                        </div>
                    </li>
                    <!-- Product ends -->

                    <!-- <li class="list-group-item d-flex justify-content-between bg-light">
                        <div class="text-success">
                            <h6 class="my-0">Promo code</h6>
                            <small>EXAMPLECODE</small>
                        </div>
                        <span class="text-success">-$5</span>
                    </li> -->
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Subtotal (EUR)</span>
                        {{ subTotal }}
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Tax - %18 (EUR)</span>
                        {{ tax }}
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (EUR)</span>
                        <strong>{{ total }}</strong>
                    </li>
                </ul>

                <form class="card p-2">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Coupone Code">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary">Redeem</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8 order-md-1">
                <h4 class="mb-3">Billing address</h4>
                <form class="needs-validation vld-parent" @submit.prevent="handleFormSubmission" ref="formContainer" id="payment-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName">First name (*)</label>
                            <input type="text" class="form-control" id="firstName" placeholder="" v-model="buyerDetails.firstName" required>
                            <div class="invalid-feedback">
                                Valid first name is required.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName">Last name (*)</label>
                            <input type="text" class="form-control" id="lastName" placeholder="" v-model="buyerDetails.lastName" required>
                            <div class="invalid-feedback">
                                Valid last name is required.
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email">Email (*)</label>
                            <input type="email" class="form-control" id="email" placeholder="you@example.com" required v-model="buyerDetails.email">
                            <div class="invalid-feedback">
                                Please enter a valid email address for shipping updates.
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone">Telefon (Optional)</label>
                            <input type="text" class="form-control" id="phone" placeholder="+49 111 22 33" v-model="buyerDetails.phone">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address">Address (*)</label>
                        <input type="text" class="form-control" id="address" placeholder="1234 Main St" required v-model="buyerDetails.address1">
                        <div class="invalid-feedback">
                            Please enter your shipping address.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address2">Address 2 (Optional)</label>
                        <input type="text" class="form-control" id="address2" placeholder="Apartment or suite" v-model="buyerDetails.address2">
                    </div>

                    <div class="row">
                        
                        <div class="col-md-6 mb-3">
                            <label for="city">City (*)</label>
                            <select class="custom-select d-block w-100" id="city" name="city" required v-model="buyerDetails.selectedCity">
                                <option value="">Stadt wählen</option>
                                <option v-for="city in cities" :value="city.name" v-bind:key="city.id">{{ city.name }}</option>
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="zip">Zip (*)</label>
                            <input type="text" class="form-control" id="zip" name="zip" placeholder="" v-model="buyerDetails.zipcode" required>
                            <div class="invalid-feedback">
                                Zip code required.
                            </div>
                        </div>

                    </div>
                    <hr class="mb-4">
                    <!-- <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="same-address">
                        <label class="custom-control-label" for="same-address">Shipping address is the same as my billing address</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="save-info">
                        <label class="custom-control-label" for="save-info">Save this information for next time</label>
                    </div>
                    <hr class="mb-4"> -->

                    <h4 class="mb-3">Payment</h4>

                    <div class="d-block my-3">
                        <div class="custom-control custom-radio">
                            <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked>
                            <label class="custom-control-label" for="credit">Credit card</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input id="paypal" name="paymentMethod" type="radio" class="custom-control-input">
                            <label class="custom-control-label" for="paypal">Paypal</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="name-on-card">Name on card (*)</label>
                            <input type="text" class="form-control" id="name-on-card" placeholder="" v-model="buyerDetails.nameOnCard" required>
                            <small class="text-muted">Full name as displayed on card</small>
                            <div class="invalid-feedback">
                                Name on card is required
                            </div>
                        </div>
                    </div>
                    <!-- Stripe -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="card-element">Credit Card Information</label>
                            <div id="card-element">
                                <!-- Elements will create input elements here -->
                            </div>

                            <!-- We'll put the error messages in this element -->
                            <div id="card-errors" role="alert"></div>
                        </div>
                    </div>
                    <!-- Stripe ends here -->

                    <!-- <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="cc-number">Credit card number</label>
                            <input type="text" class="form-control" id="cc-number" placeholder="" required>
                            <div class="invalid-feedback">
                                Credit card number is required
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cc-expiration">Expiration</label>
                            <input type="text" class="form-control" id="cc-expiration" placeholder="MM/YY" required>
                            <div class="invalid-feedback">
                                Expiration date required
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cc-expiration">CVV</label>
                            <input type="text" class="form-control" id="cc-cvv" placeholder="" required>
                            <div class="invalid-feedback">
                                Security code required
                            </div>
                        </div>
                    </div> -->

                    <hr class="mb-4">
                    <button class="btn btn-primary btn-lg btn-block" id="checkout-btn">Continue to checkout</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import StripeErrorCodes from '../localization/StripeErrorCodes';

    // Set your publishable key: remember to change this to your live publishable key in production
    // See your keys here: https://dashboard.stripe.com/account/apikeys
    // Mix variables have been defined in .env file
    let stripe = Stripe(process.env.MIX_STRIPE_KEY);
    let elements = stripe.elements();
    let card = undefined;
    let stripeStyle = {
        base: {
            color: "#32325d",
        },

        invalid: {
            // All of the error styles go inside of here.
        },

    };

    export default {
        data() {
            return {
                fullPage: false,
                cities: [],
                cartContents: [],
                subTotal: 0,
                tax: 0,
                total: 0,
                cartCount: 0,
                cities: [],
                isOrderDone: false,
                buyerDetails: {
                    firstName: '',
                    lastName: '',
                    email: '',
                    phone: '',
                    address1: '',
                    address2: '',
                    selectedCity: '',
                    zipcode: '',
                    nameOnCard: '',
                }
            }
        },
        methods: {
            getCities() {
                axios.get('/api/cities')
                .then((response) => {
                    this.cities = response.data;
                }).catch((error) => {
                    console.log(error);
                });
            },

            getCartContents() {
                axios.get('/check-cart-status')
                .then((response) => {
                    this.cartContents = response.data.cartContent;
                    this.subTotal = response.data.subTotal;
                    this.tax = response.data.tax;
                    this.total = response.data.total;
                    this.cartCount = response.data.cartCount;
                }).catch((error) => {
                    console.log(error);
                });
            },

            listenForErrors() {
                card.addEventListener('change', (event) => {
                    const displayError = document.getElementById('card-errors');
                    if (event.error) {
                        let errorMessage = StripeErrorCodes.online_payment_error_codes[event.error.code];
                        displayError.textContent = errorMessage;
                    } else {
                        displayError.textContent = '';
                    }
                });
            },

            handleFormSubmission() {
                // disable the checkout button
                const checkoutBtn = document.getElementById('checkout-btn');
                const form = document.getElementById('payment-form');

                checkoutBtn.disabled = true;

                let loader = this.$loading.show({
                    // Optional parameters
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });

                let billingDetails = this.buyerDetails;
                
                axios.get('/get-stripe-client-secret', {
                    'customerEmail': billingDetails.email,
                })
                .then((response) => {
                    let clientSecret = response.data.client_secret;
                    stripe.confirmCardPayment(clientSecret, {
                        payment_method: {
                            card: card,
                            billing_details: {
                                name: billingDetails.nameOnCard,
                                email: billingDetails.email,
                                address: {
                                    city: billingDetails.selectedCity,
                                    line1: billingDetails.address1,
                                    // line2: billingDetails.address2,
                                    postal_code: billingDetails.postal_code,
                                    // phone: billingDetails.phone,
                                },
                            },
                        }
                    }).then((result) => {
                        if (result.error) {
                            // Show error to your customer (e.g., insufficient funds)
                            let errorMessage = StripeErrorCodes.online_payment_error_codes[result.error.code];
                            swal.fire(
                                'Error!',
                                errorMessage,
                                'error',
                            );
                        } else {
                            // The payment has been processed!
                            if (result.paymentIntent.status === 'succeeded') {
                                // Empty shopping cart
                                this.emptyCart();

                                // payment done let the customer know it
                                this.isOrderDone = true;

                                swal.fire(
                                    'Success!',
                                    'Your payment has been accepted. Thanks!',
                                    'success',
                                );
                                // There's a risk of the customer closing the window before callback
                                // execution. Set up a webhook or plugin to listen for the
                                // payment_intent.succeeded event that handles any business critical
                                // post-payment actions.
                            }
                            // reset checkout form
                            form.reset();
                            // reset stripe elements
                            card.clear();
                        }
                        loader.hide();
                        checkoutBtn.disabled = false;
                    });
                }).catch((response) => {
                    swal.fire(
                        'Error!',
                        response.data.error_message,
                        'error',
                    );
                    loader.hide();
                    checkoutBtn.disabled = false;
                });
            },

            emptyCart() {
                axios.get('/empty-cart')
                .then(() => {
                    this.cartContents = [];
                    this.subTotal = 0;
                    this.tax = 0;
                    this.total = 0;
                    this.cartCount = 0;
                });
            },
        },

        mounted() {
            this.getCartContents();
            this.getCities();
            // create the card without postal code, mount it on dom element #card-element
            // then attach change event to it.
            window.addEventListener('load', () => {
                if(!this.noProducts) {
                    card = elements.create("card", {
                        style: stripeStyle,
                        hidePostalCode: true,
                    });
                    card.mount('#card-element');
                    this.listenForErrors();
                }
            })
        },

        computed: {
            noProducts: function() {
                return this.cartCount === 0;
            },
        }
    }
</script>
