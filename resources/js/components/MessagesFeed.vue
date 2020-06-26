<template>
    <div class="messages-feed" ref="feed">
        <ul v-if="contact">
            <li v-for="message in messages" :key="message.id" :class="`message${message.to_user == contact.id ? ' sent' : ' received'}`">
                <div class="text">
                    {{ message.message }}
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    props: {
        contact: {
            type: Object,
        },
        
        messages: {
            type: Array,
            required: true,
        }
    },

    methods: {
        scrollToBottom() {
            setTimeout(() => {
                this.$refs.feed.scrollTop = this.$refs.feed.scrollHeight - this.$refs.feed.clientHeight;
            }, 50);
        }
    },

    watch: {
        contact(contact) {
            this.scrollToBottom();
        },

        messages(messages) {
            this.scrollToBottom();
        }
    }
}
</script>