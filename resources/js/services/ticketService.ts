import api from '@/api/config/axios.js'
import { API_ENDPOINTS } from '@/constants/apiEndpoints.js'
export interface Ticket {
  id?: number
  title: string
  description: string
  status: string
}

export class TicketService {
  async getAllTickets() {
    const response = await api.get(API_ENDPOINTS.TICKET)
    return response.data.data
  }

  async createTicket(ticket: Omit<Ticket, 'id'>) {
    const response = await api.post(API_ENDPOINTS.TICKET, ticket)
    return response.data
  }

  async updateTicket(id: number, ticket: Partial<Ticket>) {
    const response = await api.put(`${API_ENDPOINTS.TICKET}/${id}`, ticket)
    return response.data
  }

  async deleteTicket(id: number) {
    const response = await api.delete(`${API_ENDPOINTS.TICKET}/${id}`)
    return response.data
  }
}

export const ticketService = new TicketService()