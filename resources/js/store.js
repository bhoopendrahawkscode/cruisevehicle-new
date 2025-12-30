import { defineStore } from 'pinia'
export const useAppStore = defineStore('appStore', {
  state: () => ({
    token: "",
    locale: 'en',
 }),
  actions: {
    setToken(value) {
      this.token = value
    },
    setLocale(value) {
        this.locale = value
    },
  },
  persist:true
})

