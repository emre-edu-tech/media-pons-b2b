export default class Gate {
    constructor(user) {
        this.user = user;
    }

    isAdmin() {
        return this.user.user_role === 'admin';
    }

    isUser() {
        return this.user.user_role === 'user';
    }
}