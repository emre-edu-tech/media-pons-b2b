<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Handler Application Form</div>

                    <div class="card-body">
                        <form>

                            <div class="form-group row">
                                <label for="company_name" class="col-md-4 col-form-label">Şirket Adı</label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="company_name" 
                                    v-model="form.company_name" :class="{'is-invalid': form.errors.has('company_name')}">
                                    <has-error :form="form" field="company_name"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label">Adınız Soyadınız</label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="name" 
                                    v-model="form.name" :class="{'is-invalid': form.errors.has('name')}">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label">E-Posta Adresi</label>

                                <div class="col-md-8">
                                    <input type="email" v-model="form.email" class="form-control" 
                                    id="email" :class="{'is-invalid': form.errors.has('email')}">
                                    <has-error :form="form" field="email"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label">Telefon</label>

                                <div class="col-md-8">
                                    <input type="tel" v-model="form.phone" class="form-control" 
                                    id="phone" :class="{'is-invalid': form.errors.has('phone')}">
                                    <has-error :form="form" field="phone"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="business_registration_form" class="col-md-4 col-form-label">Gewerbe Anmeldung (Kopie)</label>
                                <div class="col-md-8">
                                    <input type="file" @change="updateRegForm" id="business_registration_form" name="business_registration_form" class="form-input" :class="{'is-invalid': form.errors.has('business_registration_form')}">
                                    <has-error :form="form" field="business_registration_form"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="id_card" class="col-md-4 col-form-label">Ausweiss (Kopie)</label>
                                <div class="col-sm-8">
                                    <input type="file" @change="updateIdCard" id="id_card" name="id_card" class="form-input"
                                    :class="{'is-invalid': form.errors.has('id_card')}">
                                    <has-error :form="form" field="id_card"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tax_number" class="col-md-4 col-form-label">Umsatzsteuer ID</label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="tax_number" 
                                    v-model="form.tax_number" :class="{'is-invalid': form.errors.has('tax_number')}">
                                    <has-error :form="form" field="tax_number"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label">Açıklama</label>
                                <div class="col-md-8">
                                    <textarea rows="10" class="form-control" id="description" name="description" v-model="form.description"
                                    :class="{'is-invalid': form.errors.has('description')}"></textarea>
                                    <has-error :form="form" field="description"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="accept-info" class="col-md-8 offset-md-4">
                                    <input type="checkbox" v-model="accepted" :checked="accepted" @change="checkAcceptance" name="accept-info" id="accept-info"> Gönderdiğim bilgilerimin doğru olduğunu kabul ediyorum. Bilgilerimin yanlışlığından doğacak aksaklıklardan sorumlu olduğumu taahhüt ediyorum.
                                </label>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" id="send-info" @click.prevent="registerInfo" class="btn btn-success btn-block">Bilgileri Yükle</button>
                                </div>
                            </div>
                        </form>
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
                handler: '',
                accepted: false,
                form: new Form({
                    company_name: '',
                    name: '',
                    email: '',
                    phone: '',
                    business_registration_form: '',
                    id_card: '',
                    tax_number: '',
                    description: '',
                })
            }
        },
        methods: {
            checkAcceptance(event) {
                if(!this.accepted) {
                    $('#send-info').prop('disabled', true);
                } else {
                    $('#send-info').prop('disabled', false);
                }
            },
            updateRegForm(event) {
                // Here event parameter will contain the file that we want to upload
                let file = event.target.files[0];
                let reader = new FileReader();
                if(file.size < (2 * 1024 * 1024)) {
                    // reader.onloadend = (file) => {
                    //     this.form.business_registration_form = reader.result;
                    // }
                    // reader.readAsDataURL(file);
                    this.form.business_registration_form = file;
                } else {
                    swal.fire(
                        'Hata!',
                        'Dosya boyutu en fazla 2MB olabilir',
                        'error',
                    );
                }
            },

            updateIdCard(event) {
                // Here event parameter will contain the file that we want to upload
                let file = event.target.files[0];
                let reader = new FileReader();
                
                if(file.size < (2 * 1024 * 1024)) {
                // reader.onloadend = (file) => {
                //     this.form.id_card = reader.result;
                // }
                // reader.readAsDataURL(file);
                this.form.id_card = file;
                } else {
                    swal.fire(
                        'Hata!',
                        'Dosya boyutu en fazla 2MB olabilir',
                        'error',
                    );
                }

            },

            registerInfo() {
                this.$Progress.start();
                this.form.submit('post', '/apply', {
                    transformRequest: [function (data, headers) {
                        return objectToFormData(data)
                    }],
                })
                .then(() => {
                    swal.fire(
                        'Başarılı!',
                        'Bilgileriniz gönderildi',
                        'success',
                    );
                    this.$Progress.finish();
                    // clear the errors
                    this.form.clear();
                    // reset the modal form
                    this.form.reset();
                })
                .catch(() => {
                    swal.fire(
                        'Hata!',
                        'Bilgileriniz gönderilirken hata oluştu. Tekrar deneyin.',
                        'error',
                    );
                    this.$Progress.fail();
                });
            }
        },
        created() {
            // Process things that should be done on dom is created
        }
    }
</script>
