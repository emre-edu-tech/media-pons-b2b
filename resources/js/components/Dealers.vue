<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">Händleranwendungen</h3>

                        <div class="card-tools">
                            <span class="mr-2"><strong>Düzenleme ve silme işlemini ancak admin kullanıcısı yapabilir</strong></span>
                            <button type="button" class="btn btn-success">
                              Neue Händler <i class="fas fa-plus fa-fw"></i>
                            </button>
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
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="dealer in dealers.data" v-bind:key="dealer.id">
                                    <td>{{ dealer.company_name }}</td>
                                    <td>{{ dealer.name }}</td>
                                    <td>{{ dealer.email }}</td>
                                    <td>{{ dealer.phone }}</td>
                                    <td>{{ dealer.description }}</td>
                                    <td><a target="_blank" :href="`/storage/documents/${dealer.business_registration_form}`">anzeigen</a></td>
                                    <td><a target="_blank" :href="`/storage/documents/${dealer.id_card}`">anzeigen</a></td>
                                    <td>{{ dealer.tax_number }}</td>
                                    <td>{{ dealer.created_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer" v-if="dealers.last_page > 1">
                        <pagination :data="dealers" @pagination-change-page="getAllDealers">
                            <span slot="prev-nav">&lt; Önceki</span>
                            <span slot="next-nav">Sonraki &gt;</span>
                        </pagination>
                    </div><!-- /.card-footer -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                dealers: {}
            }
        },
        methods: {
            getAllDealers(page = 1) {
                axios.get(`/api/dealers?page=${page}`)
                .then((response) => {
                    this.dealers = response.data;
                });
            },
        },
        created() {
            this.getAllDealers();
        }
    }
</script>
