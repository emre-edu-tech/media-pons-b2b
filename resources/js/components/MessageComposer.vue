<template>
    <div class="message-composer">
        <textarea v-model="message" @keydown.enter="send" placeholder="Enter your message..."></textarea>
    </div>
</template>
<script>
export default {
    data() {
        return {
            message: '',
        }
    },

    methods: {
        send(event) {
            event.preventDefault();
            if(this.message == '') {
                return;
            }

            this.$emit('send', this.message);
            this.message = '';
        }
    }
}
</script>