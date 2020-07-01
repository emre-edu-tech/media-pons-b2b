<template>
    <div class="container-fluid">
        <div class="row justify-content-center" v-if="$gate.isAdmin()">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">Benutzerlist</h3>

                        <div class="card-tools">
                            <span class="mr-2"><strong>Düzenleme ve silme işlemini ancak admin kullanıcısı yapabilir</strong></span>
                            <button type="button" class="btn btn-success">
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
        </div>

    <not-found v-if="!$gate.isAdmin()"></not-found>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: {},
                form: new Form(),
            }
        },

        methods: {
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
