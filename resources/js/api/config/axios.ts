import axios from 'axios'

const baseURL = import.meta.env.VITE_API_BASE_URL as string

const api = axios.create({
  baseURL: baseURL,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  withCredentials: false,
})

// Request interceptor for dynamic token handling
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('authToken')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => Promise.reject(error.response.data.message),
)

// Response interceptor for error handling
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      setAuthToken(null)
    }
    return Promise.reject(error)
  },
)

export const setAuthToken = (token: string | null) => {
  if (token) {
    localStorage.setItem('authToken', token)
  } else {
    localStorage.removeItem('authToken')
  }
  // Updating axios instance header immediately after setting
  api.defaults.headers.common['Authorization'] = token ? `Bearer ${token}` : ''
}
const initialToken = localStorage.getItem('authToken')
if (initialToken) {
  setAuthToken(initialToken)
}

export default api