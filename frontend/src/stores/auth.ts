import { defineStore } from 'pinia'
import api from '@/utils/axios'
import { useToast } from 'vue-toastification'
import { useRouter } from 'vue-router'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {
  const toast = useToast()
  const router = useRouter()

  const token = ref<string | null>(localStorage.getItem('token'))
  const user = ref<any>(localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')!) : null)

  // Se existir token no carregamento, define no axios
  if (token.value) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }

  const login = async (email: string, password: string) => {
    try {
      const response = await api.post('/auth/login', { email, password })

      token.value = response.data.token
      user.value = response.data.user

      localStorage.setItem('token', token.value)
      localStorage.setItem('user', JSON.stringify(user.value))

      api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`

      await router.push({ name: 'sales' }) 
    } catch (err) {
      toast.error('Credenciais inválidas, tente novamente.')
      throw err
    }
  }

  const logout = async () => {
    token.value = null
    user.value = null

    localStorage.removeItem('token')
    localStorage.removeItem('user')

    delete api.defaults.headers.common['Authorization']

    await router.push({ name: 'login' })
  }

  return { token, user, login, logout }
})
