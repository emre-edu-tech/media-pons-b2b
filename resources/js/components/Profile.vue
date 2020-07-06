<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="card-header text-center">
                        <h3 class="widget-user-username">{{ user.name }}</h3>
                        <h5 class="widget-user-desc">2 Brothers Tobacco</h5>
                    </div>
                    <div class="widget-user-image d-flex justify-content-center py-4">
                        <img class="img-fluid" width="150px" height="auto" :src="getProfilePhoto()" alt="Benutzer Foto">
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <strong>{{ user.bio }}</strong>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>

                <div class="card">
                    <div class="card-header p-2">
                        Benutzer Informationen
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" 
                                    v-model="form.name" :class="{'is-invalid': form.errors.has('name')}">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="phone_number" class="col-sm-2 col-form-label">Telefon</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="phone_number" 
                                    v-model="form.phone_number" :class="{'is-invalid': form.errors.has('phone_number')}">
                                    <has-error :form="form" field="phone_number"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">E-mail</label>
                                <div class="col-sm-10">
                                    <input type="email" v-model="form.email" class="form-control" 
                                    id="email" :class="{'is-invalid': form.errors.has('email')}">
                                    <has-error :form="form" field="email"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bio" class="col-sm-2 col-form-label">Benutzer Info</label>
                                <div class="col-sm-10">
                                    <textarea rows="5" class="form-control" id="bio" placeholder="Informationen über sich" name="bio" v-model="form.bio"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-sm-2 col-form-label">Unternehmen Info</label>
                                <div class="col-sm-10">
                                    <textarea rows="5" class="form-control" id="description" placeholder="Informationen über Unternehmen" name="description" v-model="form.description"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="photo" class="col-sm-2 col-form-label">Profil Foto</label>
                                <div class="col-sm-10">
                                    <input type="file" @change="updatePhoto" id="photo" name="photo" class="form-input">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-sm-2 col-form-label">Passwort | Leer lassen, wenn Sie nicht ändern</label>
                                <div class="col-sm-10">
                                    <input type="password" v-model="form.password" 
                                    name="password" class="form-control" id="password" 
                                    :class="{'is-invalid': form.errors.has('password')}">
                                    <has-error :form="form" field="password"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" @click.prevent="updateInfo" class="btn btn-success">Aktualisieren</button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: '',
                form: new Form({
                    name: '',
                    phone_number: '',
                    email: '',
                    bio: '',
                    photo: '',
                    password: '',
                    description: '',
                }),
            }
        },
        methods: {
            getProfilePhoto() {
                if(this.user.photo == 'profile.png') {
                    return '/img/profile.png';
                } else {
                    return `/img/profile/${this.user.photo}`;
                }
            },
            updatePhoto(event) {
                // Here event parameter will contain the file that we want to upload
                let file = event.target.files[0];
                let reader = new FileReader();
                if(file.size < (2 * 1024 * 1024)) {
                    reader.onloadend = (file) => {
                        this.form.photo = reader.result;
                    }
                    reader.readAsDataURL(file);
                } else {
                    swal.fire(
                        'Hata!',
                        'Dosya boyutu en fazla 2MB olabilir',
                        'error',
                    );
                }
            },
            updateInfo() {
                this.$Progress.start();
                this.form.put(`/api/profile`)
                .then(() => {
                    // Update successful
                    // Fire a custom event after user updated
                    FireEvent.$emit('AfterUpdated');
                    // empty form photo data to prevent update photo on every update post
                    toast.fire({
                        icon: 'success',
                        title: 'Kullanıcı bilgileri başarıyla güncellendi!',
                    });
                    this.form.photo = '';
                    // Update successful
                    this.$Progress.finish();
                    // used for 
                    setInterval(() => {
                        this.$router.go(0);
                    }, 2000);
                })
                .catch(() => {
                    toast.fire({
                        icon: 'error',
                        title: 'Kullanıcı bilgileri güncellenirken hata oluştu!',
                    });
                    this.$Progress.fail();
                });
            },
            getUser() {
                axios.get('/api/profile')
                .then((response) => {
                    this.user = response.data;
                    this.form.name = this.user.name;
                    this.form.phone_number = this.user.phone_number;
                    this.form.email = this.user.email;
                    this.form.bio = this.user.bio;
                    this.form.description = this.user.company_description;
                })
                .catch(() => {
                    // error when getting user
                });
            }
        },
        created () {
            this.getUser();
            FireEvent.$on('AfterUpdated', this.getUser);
        }
    }
</script>
