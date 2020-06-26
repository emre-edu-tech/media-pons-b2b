<template>
    <div class="contacts-list">
        <ul>
            <li v-for="(contact) in sortedContacts" :key="contact.id" @click="selectContact(contact)" :class="{ 'selected': contact == selected }">
                <div class="avatar">
                    <img :src="'/img/' + contact.photo" :alt="contact.name">
                </div>
                <div class="contact-info">
                    <p class="name">{{ contact.name }}</p>
                    <p class="email">{{ contact.email }}</p>
                </div>
                <span class="unread" v-if="contact.unread">{{ contact.unread }}</span>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    props: {
        contacts: {
            type: Array,
            default: [],
        }
    },

    data() {
        return {
            selected: this.contacts.length ? this.contacts[0] : null,
        }
    },

    methods: {
        selectContact(contact) {
            this.selected = contact,
            this.$emit('selected', contact);
        }
    },

    computed: {
        sortedContacts() {
            // use lodash
            return _.sortBy(this.contacts, [(contact) => {
                if(contact == this.selected) {
                    return Infinity;
                }
                return contact.unread;
            }]).reverse();
        }
    }
}
</script>