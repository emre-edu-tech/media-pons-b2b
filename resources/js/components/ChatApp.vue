<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">Media Pons Chat</div>
                <div class="card-body chat-card-body">
                    <div class="chat-app">
                        <Conversation :contact="selectedContact" :messages="messages" @newMessage="saveNewMessage" />
                        <ContactsList :contacts="contacts" @selected="startConversationWith" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import Conversation from './Conversation';
    import ContactsList from './ContactsList';

    export default {
        props: {
            user: {
                type: Object,
                required: true,
            }
        },
        data() {
            return {
                selectedContact: null,
                messages: [],
                contacts: [],
            }
        },
        methods: {
            getListOfContacts() {
                axios.get('/api/users')
                .then((response) => {
                    this.contacts = response.data;
                });
            },

            startConversationWith(contact) {
                this.updateUnreadCount(contact, true);
                axios.get(`/api/conversation/${contact.id}`)
                .then((response) => {
                    this.messages = response.data;
                    this.selectedContact = contact;
                });
            },
            saveNewMessage(message) {
                this.messages.push(message);
            },
            handleIncomingMessage(message) {
                if (this.selectedContact && (message.from_user == this.selectedContact.id)) {
                    this.saveNewMessage(message);
                    return;
                }

                // send a message unless if this was not the contact we are talking
                // alert(message.message);
                this.updateUnreadCount(message.from_contact, false);
            },
            updateUnreadCount(contact, reset) {
                this.contacts = this.contacts.map((singleContact) => {
                    if (singleContact.id != contact.id) {
                        // this is not the contact we wanted to talk we do not need any changes
                        return singleContact;
                    }

                    if (reset) {
                        singleContact.unread = 0;
                    } else {
                        singleContact.unread += 1;
                    }

                    return singleContact;
                });
            }
        },
        mounted() {
            Echo.private(`messages.${this.user.id}`)
                .listen('NewMessage', (event) => {
                    console.log(event);
                    this.handleIncomingMessage(event.message);
                });
            this.getListOfContacts();
        },
        components: {
            Conversation,
            ContactsList,
        }
    }
</script>
