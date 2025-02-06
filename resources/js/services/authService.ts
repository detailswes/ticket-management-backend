import api, { setAuthToken } from '@/api/config/axios.js'
import { API_ENDPOINTS } from '@/constants/apiEndpoints.js'

export class AuthService {
  async login(username: string) {
    const response = await api.post(API_ENDPOINTS.GENERATE_TOKEN, { name: username })
    const token = response.data.token
    this.setToken(token)
    return token
  }

  setToken(token: string | null) {
    if (token) {
      localStorage.setItem('authToken', token)
      setAuthToken(token)
    } else {
      localStorage.removeItem('authToken')
      setAuthToken(null)
    }
  }

  getToken() {
    return localStorage.getItem('authToken')
  }

  logout() {
    this.setToken(null)
  }
}

export const authService = new AuthService()