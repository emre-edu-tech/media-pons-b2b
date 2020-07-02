<template>
    <div class="container-fluid">
        <div class="row justify-content-center" v-if="$gate.isAdmin()">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">Benutzerlist</h3>

                        <div class="card-tools">
                            <span class="mr-2"><strong>Nur Administratoren können bearbeiten und löschen.</strong></span>
                            <button type="button" class="btn btn-success" @click="newUserModal">
                              Neue Benutzer <i class="fas fa-plus fa-fw"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Firmenname</th>
                                    <th>E-mail</th>
                                    <th>Telefon</th>
                                    <th>Beschreibung</th>
                                    <th>Gewerbe Anmeldung</th>
                                    <th>Ausweis</th>
                                    <th>Umsatzsteuer ID</th>
                                    <th>Registrierungsdatum</th>
                                    <th>Bearbeiten</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users.data" v-bind:key="user.id">
                                    <td>{{ user.name | titleCase }}</td>
                                    <td>{{ user.company_name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.phone_number }}</td>
                                    <td>{{ user.bio }}</td>
                                    <td><a target="_blank" :href="`/storage/documents/${user.business_registration_form}`">anzeigen</a></td>
                                    <td><a target="_blank" :href="`/storage/documents/${user.id_card}`">anzeigen</a></td>
                                    <td>{{ user.tax_number }}</td>
                                    <td>{{ user.created_at | customDate }}</td>
                                    <td>
                                        <div>
                                            <a href="#" @click="deleteUser(user.id)" title="Benutzer löschen"><i class="fa fa-trash text-red"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer" v-if="users.last_page > 1">
                        <pagination :data="users" @pagination-change-page="getAllUsers">
                            <span slot="prev-nav">&lt; Önceki</span>
                            <span slot="next-nav">Sonraki &gt;</span>
                        </pagination>
                    </div><!-- /.card-footer -->
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userModalLabel">Neuen Benutzer hinzufügen</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form @submit.prevent="createUser()">
                            <div class="modal-body">
                                <div class="form-group row">
                                    <label for="company_name" class="col-md-4 col-form-label">Şirket Adı</label>

                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="company_name" v-model="form.company_name" :class="{'is-invalid': form.errors.has('company_name')}">
                                        <has-error :form="form" field="company_name"></has-error>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label">Adınız Soyadınız</label>

                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="name" v-model="form.name" :class="{'is-invalid': form.errors.has('name')}">
                                        <has-error :form="form" field="name"></has-error>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label">E-Posta Adresi</label>

                                    <div class="col-md-8">
                                        <input type="email" v-model="form.email" class="form-control" id="email" :class="{'is-invalid': form.errors.has('email')}">
                                        <has-error :form="form" field="email"></has-error>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="phone" class="col-md-4 col-form-label">Telefon</label>

                                    <div class="col-md-8">
                                        <input type="tel" v-model="form.phone" class="form-control" id="phone" :class="{'is-invalid': form.errors.has('phone')}">
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
                                        <input type="file" @change="updateIdCard" id="id_card" name="id_card" class="form-input" :class="{'is-invalid': form.errors.has('id_card')}">
                                        <has-error :form="form" field="id_card"></has-error>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="tax_number" class="col-md-4 col-form-label">Umsatzsteuer ID</label>

                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="tax_number" v-model="form.tax_number" :class="{'is-invalid': form.errors.has('tax_number')}">
                                        <has-error :form="form" field="tax_number"></has-error>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="description" class="col-md-4 col-form-label">Açıklama</label>
                                    <div class="col-md-8">
                                        <textarea rows="10" class="form-control" id="description" name="description" v-model="form.description" :class="{'is-invalid': form.errors.has('description')}"></textarea>
                                        <has-error :form="form" field="description"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Scließen</button>
                                <button type="submit" class="btn btn-success">Speichern</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- endModal -->            
        </div>

    <not-found v-if="!$gate.isAdmin()"></not-found>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: {},
                form: new Form({
                    company_name: '',
                    name: '',
                    email: '',
                    phone: '',
                    business_registration_form: '',
                    id_card: '',
                    tax_number: '',
                    description: '',
                }),
            }
        },

        methods: {
            newUserModal() {
                // clear the errors
                this.form.clear();
                // reset the modal form
                this.form.reset();
                // show modal for creating category
                $('#userModal').modal('show');
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

            deleteUser(id) {
                swal.fire({
                    title: 'Emin misiniz?',
                    text: 'Silme işlemi geri alınamaz',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil',
                    cancelButtonText: 'İptal',
                }).then((result) => {
                    if(result.value) {
                        // Send ajax request to delete it
                        this.form.delete(`/api/users/${id}`)
                        .then(() => {
                            // Broadcast the event     
                            FireEvent.$emit('AfterUserDeleted');
                            swal.fire(
                                'Silindi!',
                                'Sistem kullanıcısı başarıyla silindi',
                                'success',
                            );
                            
                        }).catch(() => {
                            swal.fire(
                                'Silme başarısız!',
                                'Sunucuda hata oluştu',
                                'error',
                            );
                        });
                    } else if (result.dismiss === swal.DismissReason.cancel) {
                        swal.fire(
                            'Başvuru silinemedi!',
                            'Başvuru silme işlemi kullanıcı tarafından iptal edildi',
                            'warning',
                        );
                    }
                })
            },
            getAllUsers(page = 1) {
                if(this.$gate.isAdmin()){
                    axios.get(`/api/get-users?page=${page}`)
                    .then((response) => {
                        this.users = response.data;
                    }).catch((response) => {
                        swal.fire(
                            'İşlem başarısız!',
                            'Sunucu hatası ya da bu işlemi yapmanız için yetkili değilsiniz',
                            'error',
                        );
                    });
                }
            },
        },
        created() {
            this.getAllUsers();
            FireEvent.$on('AfterUserDeleted', this.getAllUsers);
        }
    }
</script>
