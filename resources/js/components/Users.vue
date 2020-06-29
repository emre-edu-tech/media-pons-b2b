<template>
    <div class="container-fluid">
        <div class="row justify-content-center" v-if="$gate.isAdmin()">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Benutzer List</div>

                    <div class="card-body">
                        Benutzer wurde gelistet hier.
                    </div>
                </div>
            </div>
        </div>

    <not-found v-if="!$gate.isAdmin()"></not-found>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
