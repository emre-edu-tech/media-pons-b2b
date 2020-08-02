<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2>Willkommen beim 2 Brothers Tobacco Dealer Management System</h2>
                <div class="card">
                    <div class="card-header">Hallo, <strong>{{ user.name }}</strong></div>

                    <div class="card-body">
                        <p>Sie können Folgendes tun, indem Sie dieses System als System <strong>{{ user.user_role }}</strong> verwenden.</p>
                        <div class="list-group">
                            <a href="/users" v-if="$gate.isAdmin()" class="list-group-item list-group-item-action">Überprüfen registrierte Benutzer</a>
                            <a href="/dealers" v-if="$gate.isAdmin()" class="list-group-item list-group-item-action">Überprüfen Händler Anwendungen</a>
                            <a href="/dealer-chat" class="list-group-item list-group-item-action">{{ $gate.isAdmin() ? 'Chat mit Händlern' : 'Chat mit dem Systemadministrator' }}</a>
                            <a href="/categories" class="list-group-item list-group-item-action">Produkte</a>
                            <a href="/profile" class="list-group-item list-group-item-action">Siehe Profil Informationen</a>
                            <a @click="logout" class="logout-link list-group-item list-group-item-action">Abmelden</a>
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
                user: '',
            }
        },
        methods: {
            logout() {
                // logout functionality is a bit tricky but it works for now
                axios.post('/logout')
                .then(() => {
                    window.location.href = '/';
                });
            },
            getAuthenticatedUser() {
                axios.get('/api/profile')
                .then((response) => {
                    this.user = response.data;
                })
                .catch(() => {
                    swal.fire(
                        'Fehler!',
                        'Fehler beim Abrufen der Benutzerinformationen. Prüfe deine Internetverbindung.',
                        'error',
                    );
                });
            }
        },
        created() {
          this.getAuthenticatedUser();  
        }
    }
</script>

<style scoped>
.logout-link {
    cursor: pointer;
}
</style>