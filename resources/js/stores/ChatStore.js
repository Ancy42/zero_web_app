import axios from "axios";
import { defineStore } from "pinia";

export const useChat = defineStore("chatStore", {
    state: () => ({
        chats: [],
        activeShop: null,
        unreadMessages: 0,
    }),

    actions: {
        setChats(chats) {
            this.chats = chats;
        },
    },

    persist: true,
});
