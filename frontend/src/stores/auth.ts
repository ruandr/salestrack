import { defineStore } from 'pinia'
import api from '@/utils/axios'
import { useToast } from 'vue-toastification'
import { useRouter } from 'vue-router'

export const useAuthStore = defineStore('auth', () => {
  const toast = useToast()
  const router = useRouter()

  const login = async (email: string, password: string) => {
    try {
      const response = await api.post('/auth/login', {
        email,
        password,
      })

      localStorage.setItem('token', response.data.token)
      localStorage.setItem('user', JSON.stringify(response.data.user))

      router.push({ name: 'sales' })
    } catch (err) {
      toast.error('Credenciais inválidas, tente novamente.')
      throw err
    }
  }

  const logout = () => {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    router.push({ name: 'login' })
  }

  return { login, logout }
})
