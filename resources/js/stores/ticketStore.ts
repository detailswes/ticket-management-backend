import { authService } from '@/services/authService.js'
import { ticketService, type Ticket } from '@/services/ticketService.js'
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useTicketStore = defineStore('tickets', () => {
  // State
  const tickets = ref<Ticket[]>([])
  const currentTicket = ref<Ticket>({
    id: undefined,
    title: '',
    description: '',
    status: 'Open',
  })
  const loading = ref(false)
  const error = ref<string | null>(null)
  const authToken = ref(authService.getToken() || '')
  const statuses = ref(['Open', 'In Progress', 'Closed'])

  // Getters
  const filteredTickets = computed(() => {
    return (searchQuery: string) =>
      tickets.value.filter(
        (ticket) =>
          ticket.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
          ticket.description.toLowerCase().includes(searchQuery.toLowerCase()) ||
          ticket.status.toLowerCase().includes(searchQuery.toLowerCase()),
      )
  })

  // Actions
  async function login(username: string) {
    loading.value = true
    try {
      const token = await authService.login(username)
      authToken.value = token
      return true
    } catch (err) {
      error.value = 'Authentication failed'
      authService.setToken(null)
      authToken.value = ''
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchTickets() {
    loading.value = true
    try {
      tickets.value = await ticketService.getAllTickets()
    } catch (err) {
      error.value = 'Error loading tickets'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function saveTicket(ticket: Ticket) {
    loading.value = true
    try {
      if (ticket.id) {
        await ticketService.updateTicket(ticket.id, ticket)
      } else {
        await ticketService.createTicket(ticket)
      }
      await fetchTickets()
      return true
    } catch (err) {
      error.value = 'Error saving ticket'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function deleteTicket(id: number) {
    loading.value = true
    try {
      await ticketService.deleteTicket(id)
      await fetchTickets()
      return true
    } catch (err) {
      error.value = 'Error deleting ticket'
      throw err
    } finally {
      loading.value = false
    }
  }

  function setCurrentTicket(ticket: Partial<Ticket> | null) {
    if (ticket === null) {
      resetCurrentTicket()
    } else {
      currentTicket.value = {
        title: ticket.title || '',
        description: ticket.description || '',
        status: ticket.status || 'Open',
        ...(ticket.id ? { id: ticket.id } : {}),
      }
    }
  }

  function resetCurrentTicket() {
    currentTicket.value = {
      title: '',
      description: '',
      status: 'Open',
    }
  }

  return {
    // State
    tickets,
    currentTicket,
    loading,
    error,
    authToken,
    statuses,
    // Getters
    filteredTickets,
    // Actions
    login,
    fetchTickets,
    saveTicket,
    deleteTicket,
    setCurrentTicket,
    resetCurrentTicket,
  }
})