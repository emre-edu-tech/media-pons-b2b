<template>
    <div class="container-fluid">
        <div class="row justify-content-center" v-if="$gate.isAdmin()">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Händleranwendungen</h3>

                        <div class="card-tools">
                            <span class="mr-2"><strong>Nur Administratoren können bearbeiten und löschen.</strong></span>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Firmenname</th>
                                    <th>Name</th>
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
                                <tr v-for="dealer in dealers.data" v-bind:key="dealer.id">
                                    <td>{{ dealer.company_name | titleCase }}</td>
                                    <td>{{ dealer.name | titleCase }}</td>
                                    <td>{{ dealer.email }}</td>
                                    <td>{{ dealer.phone }}</td>
                                    <td>{{ dealer.description }}</td>
                                    <td><a target="_blank" :href="`/storage/documents/${dealer.business_registration_form}`">anzeigen</a></td>
                                    <td><a target="_blank" :href="`/storage/documents/${dealer.id_card}`">anzeigen</a></td>
                                    <td>{{ dealer.tax_number }}</td>
                                    <td>{{ dealer.created_at | customDate }}</td>
                                    <td>
                                        <div>
                                            <a href="#" @click="acceptDealer(dealer)" title="Başvuruyu kabul et"><i class="fas fa-check text-green"></i></a> /
                                            <a href="#" @click="rejectDealer(dealer.id)" title="Başvuruyu sil"><i class="fa fa-trash text-red"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer" v-if="dealers.last_page > 1">
                        <pagination :data="dealers" @pagination-change-page="getAllDealers">
                            <span slot="prev-nav">&lt; Vorherige</span>
                            <span slot="next-nav">Nächste &gt;</span>
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
                dealers: {},
                form: new Form(),
            }
        },
        methods: {
            acceptDealer(dealer) {
                this.$Progress.start();
                axios.post('/api/accept-dealer', {
                    id: dealer.id,
                    name: dealer.name,
                    email: dealer.email,
                    phone_number: dealer.phone,
                    company_name: dealer.company_name,
                    business_registration_form: dealer.business_registration_form,
                    id_card: dealer.id_card,
                    tax_number: dealer.tax_number,
                    description: dealer.description,
                }).then((response) => {
                    if(response.data.status == 'success') {
                        FireEvent.$emit('AfterDealerAccepted'),
                        swal.fire(
                            'Erfolgreich!',
                            'Der Antrag wurde angenommen. Benutzer wurde dem System hinzugefügt. Benutzerinformationen an den Benutzer gesendet.',
                            'success',
                        );
                        this.$Progress.finish();
                    } else if(response.data.status == 'mailerror') {
                        swal.fire(
                            'Der Benutzer ist bereits registriert!',
                            `${response.data.message}`,
                            'error',
                        );
                        this.$Progress.finish();
                    }
                }).catch(() => {
                    swal.fire(
                        'Fehler!',
                        response.data.message,
                        'error',
                    );
                });
            },
            rejectDealer(id) {
                this.$Progress.start();
                swal.fire({
                    title: 'Bist du sicher?',
                    text: 'Löschen kann nicht rückgängig gemacht werden!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ja löschen',
                    cancelButtonText: 'Abbrechen',
                }).then((result) => {
                    if(result.value) {
                        // Send ajax request to delete it
                        this.form.delete(`/api/dealers/${id}`)
                        .then((response) => {
                            // Broadcast the event     
                            FireEvent.$emit('AfterDealerDeleted');
                            swal.fire(
                                'Gelöscht!',
                                'Antrag abgelehnt. Die Informationen wurden an den Benutzer gesendet.',
                                'success',
                            );
                        this.$Progress.finish();
                        }).catch(() => {
                            swal.fire(
                                'Fehler!',
                                'Serverfehler!',
                                'error',
                            );
                            this.$Progress.fail();
                        });
                    } else if (result.dismiss === swal.DismissReason.cancel) {
                        swal.fire(
                            'Der Benutzer konnte nicht gelöscht werden!',
                            'Das Löschen des Benutzers wurde abgebrochen.',
                            'warning',
                        );
                        this.$Progress.fail();
                    }
                })
            },
            getAllDealers(page = 1) {
                if(this.$gate.isAdmin()){
                    axios.get(`/api/dealers?page=${page}`)
                    .then((response) => {
                        this.dealers = response.data;
                    }).catch((response) => {
                        swal.fire(
                            'Fehler!',
                            'Serverfehler oder Sie sind nicht berechtigt, diesen Vorgang auszuführen.',
                            'error',
                        );
                    });
                }
            },
        },
        created() {
            // let this work only if the authenticated user is admin
            this.getAllDealers();
            FireEvent.$on('AfterDealerDeleted', this.getAllDealers);
            FireEvent.$on('AfterDealerAccepted', this.getAllDealers);
        }
    }
</script>
