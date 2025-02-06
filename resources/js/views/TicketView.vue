<template>
    <div class="container mx-auto p-4 bg-white min-h-screen">
  
      <div class="card shadow-lg rounded-lg">
        <!-- Header Section -->
        <div class="p-4 flex justify-between items-center border-b">
          <span class="text-2xl font-bold text-gray-700">Tickets Management</span>
          <Button :label="authToken ? 'Authorized' : 'Authorize'" @click="authDialog = true" class="p-button-warning"
            :disabled="!!authToken" />
        </div>
  
        <!-- Search and Add Section -->
        <div class="p-4 flex flex-col space-y-4">
          <template v-if="!loading">
            <div class="flex justify-between items-center gap-4">
              <div class="flex-1">
                <span class="p-input-icon-left w-full">
                  <i class="pi pi-search" />
                  <InputText v-model="searchQuery" placeholder="Search tickets..."
                    class="w-full p-2 pl-8 border rounded" />
                </span>
              </div>
              <Button label="Add Ticket" icon="pi pi-plus" @click="openNew" :disabled="!authToken"
                class="p-button-success custom-button whitespace-nowrap" />
            </div>
  
            <!-- Data Table -->
            <DataTable :value="filteredTickets" :paginator="true" :rows="10" :sortField="sortField" :sortOrder="sortOrder"
              @sort="onSort" tableStyle="min-width: 50rem">
              <Column field="title" header="Title" sortable class="font-medium"></Column>
              <Column field="description" header="Description"></Column>
              <Column field="status" header="Status" sortable>
                <template #body="{ data }">
                  <Tag :value="data.status" :severity="getStatusSeverity(data.status)" />
                </template>
              </Column>
              <Column header="Actions" headerClass="text-center" bodyClass="text-center">
                <template #body="slotProps">
                  <span @click="editTicket(slotProps.data)" class="mr-3 cursor-pointer">
                    <FontAwesomeIcon :icon="faPencil" />
                  </span>
                  <span @click="confirmDeleteTicket(slotProps.data)" class="cursor-pointer">
                    <FontAwesomeIcon :icon="faTrash" />
                  </span>
                </template>
              </Column>
              <template #empty>
                <div class="flex flex-col items-center justify-center py-12 px-4">
                  <div class="w-16 h-16 mb-4">
                    <svg viewBox="0 0 24 24" fill="none" class="w-full h-full text-gray-300" stroke="currentColor"
                      stroke-width="2">
                      <path
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </div>
                  <template v-if="!authToken">
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Authentication Required</h3>
                    <p class="text-sm text-gray-500 text-center max-w-sm">
                      Please authorize to view tickets.
                    </p>
                  </template>
                  <template v-else>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No Tickets Found</h3>
                    <p class="text-sm text-gray-500 text-center max-w-sm">
                      Start by creating your first ticket using the "Add Ticket" button above.
                    </p>
                  </template>
                </div>
              </template>
            </DataTable>
          </template>
          <template v-else>
            <!-- Skeleton Loading -->
            <div class="flex justify-between items-center gap-4">
              <div class="flex-1 h-10 bg-gray-100 rounded-lg animate-pulse"></div>
              <div class="h-10 w-24 bg-gray-100 rounded-lg animate-pulse"></div>
            </div>
  
            <!-- Table Skeleton -->
            <div class="space-y-4">
              <div v-for="i in 5" :key="i" class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-4 h-4 bg-gray-100 rounded-lg animate-pulse"></div>
                <div class="col-span-5 h-4 bg-gray-100 rounded-lg animate-pulse"></div>
                <div class="col-span-2 h-4 bg-gray-100 rounded-lg animate-pulse"></div>
                <div class="col-span-1 h-4 bg-gray-100 rounded-lg animate-pulse"></div>
              </div>
  
              <!-- Pagination Skeleton -->
              <div class="grid justify-items-center mt-4">
                <div class="h-8 w-48 bg-gray-100 rounded-lg animate-pulse mt-4 mb-4"></div>
              </div>
            </div>
          </template>
        </div>
      </div>
  
      <!-- Add/Edit Dialog  -->
      <Dialog v-model:visible="ticketDialog" :style="{ width: '450px' }"
        :header="isEditMode ? 'Edit Ticket' : 'Create Ticket'" :modal="true" class="p-fluid rounded-lg"
        @hide="hideDialog" position="center" :draggable="false">
  
        <div class="field mt-4">
          <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
          <InputText id="title" v-model.trim="currentTicket.title" required autofocus class="w-full p-2 border rounded"
            :class="{ 'border-red-500': submitted && !currentTicket.title }" />
          <small v-if="submitted && !currentTicket.title" class="text-red-500">Title is required.</small>
          <small v-if="validationErrors.title" class="text-red-500">{{ validationErrors.title[0] }}</small>
        </div>
  
        <div class="field mt-4">
          <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
          <Textarea id="description" v-model="currentTicket.description" required rows="3"
            class="w-full p-2 border rounded" />
          <small v-if="submitted && !currentTicket.description" class="text-red-500">Description is required.</small>
          <small v-if="validationErrors.description" class="text-red-500">{{ validationErrors.description[0] }}</small>
        </div>
  
        <div class="field mt-4">
          <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
          <Dropdown id="status" v-model="currentTicket.status" :options="statuses" placeholder="Select a Status"
            class="w-full" />
          <small v-if="submitted && !currentTicket.status" class="text-red-500">Status is required.</small>
        </div>
  
        <template #footer>
          <Button label="Cancel" :disabled="submitted" @click="hideDialog"
            class="p-button-text text-gray-600 hover:bg-gray-100" />
          <Button :label="isEditMode ? 'Update' : 'Create'" @click="saveTicket" :disabled="submitted"
            class="custom-button" />
        </template>
      </Dialog>
  
      <Dialog v-model:visible="authDialog" :style="{ width: '350px' }" header="Authorization" :modal="true"
        @hide="hideDialog" position="center" :draggable="false">
        <div class="field mt-4">
          <label for="username" class="block text-gray-700 font-medium mb-2">Name</label>
          <InputText id="username" v-model.trim="username" required="true" class="w-full p-2 border rounded" />
          <small class="text-red-500 text-sm" v-if="submitted && !username">Name is required.</small>
          <small v-if="validationErrors.error || Object.keys(validationErrors).length > 0" class="text-red-500">
            {{ validationErrors.error || validationErrors }}
          </small>        
        </div>
        <template #footer>
          <Button label="Cancel" :disabled="submitted" @click="authDialog = false"
            class="p-button-text text-gray-600 hover:bg-gray-100" />
          <Button label="Authorize" @click="login" :disabled="submitted" class="p-button-success" />
        </template>
      </Dialog>
  
      <!-- Delete Confirmation Dialog  -->
      <Dialog v-model:visible="deleteTicketDialog" :style="{ width: '450px' }" header="Confirm Deletion" :modal="true" position="center" :draggable="false">
        <div class="confirmation-content flex items-center">
          <i class="pi pi-exclamation-triangle mr-3 text-yellow-500" style="font-size: 2rem" />
          <span v-if="ticket" class="text-gray-700">Are you sure you want to delete <b>{{ currentTicket.title
              }}</b>?</span>
        </div>
        <template #footer>
          <Button label="No" :disabled="submitted" @click="deleteTicketDialog = false"
            class="p-button-text text-gray-600 hover:bg-gray-100" />
          <Button label="Yes" @click="deleteTicket" :disabled="submitted"
            class="bg-red-500 hover:bg-red-600 border-red-500" />
        </template>
      </Dialog>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, onMounted, computed, watchEffect } from 'vue'
  import DataTable from 'primevue/datatable'
  import Column from 'primevue/column'
  import Button from 'primevue/button'
  import Dialog from 'primevue/dialog'
  import InputText from 'primevue/inputtext'
  import Textarea from 'primevue/textarea'
  import Dropdown from 'primevue/dropdown'
  import Tag from 'primevue/tag'
  import api, { setAuthToken } from '@/api/config/axios';
  import { API_ENDPOINTS } from '@/constants/apiEndpoints'
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
  import { faPencil, faTrash } from '@fortawesome/free-solid-svg-icons'
  import type { DataTableSortEvent } from 'primevue'
  import { toast } from 'vue3-toastify'
  import 'vue3-toastify/dist/index.css'
  
  import { useTicketStore } from '@/stores/ticketStore'
  import { storeToRefs } from 'pinia'
  import { AxiosError } from 'axios'
  
  const ticketStore = useTicketStore()
  const { tickets, loading, statuses, currentTicket, authToken } = storeToRefs(ticketStore)
  
  interface Ticket {
    id?: number;
    title: string;
    description: string;
    status: string;
  }
  
  const ticketDialog = ref(false);
  const deleteTicketDialog = ref(false);
  const ticket = ref<Partial<Ticket>>({});
  const submitted = ref(false);
  const searchQuery = ref('');
  const sortField = ref('');
  const sortOrder = ref(0);
  const authDialog = ref(false)
  const username = ref('')
  const validationErrors = ref<{ title?: string[]; description?: string[]; error?: string[];name?: string[] }>({});
  const isEditMode = ref(false);
  
  onMounted(() => {
    loading.value = true;
    if (authToken.value) {
      api.defaults.headers.common['Authorization'] = `Bearer ${authToken.value}`
    }
    loadTickets()
  })
  
  watchEffect(() => {
    if (currentTicket.value.title && currentTicket.value.description && currentTicket.value.status || username.value) {
      console.log('All ticket fields are filled');
      submitted.value = false;
    }
  });
  
  const login = async () => {
    submitted.value = true;
    if (username.value) {
      try {
        const response = await api.post(API_ENDPOINTS.GENERATE_TOKEN, {
          name: username.value
        })
  
        setAuthToken(response.data.token);
        authToken.value = response.data.token;
        authDialog.value = false;
  
        toast.success('Authentication successful.')
        loadTickets()
      } catch (error) {
        setAuthToken(null);
        authToken.value = '';
        if (error instanceof AxiosError && error.response) {
          validationErrors.value =  error.response?.data?.errors?.name[0] || error.response?.data?.error || "An unknown error occurred";
          console.log("error in component", error.response.data.errors.name);
        } else {
          toast.error((error as Error).message || "An unexpected error occurred.");
        }
      } finally {
        submitted.value = false
      }
    }
  }
  const loadTickets = async () => {
    try {
      const response = await api.get(API_ENDPOINTS.TICKET);
      tickets.value = response.data.data;
    } catch (error) {
      console.error('Error loading tickets:', error);
    } finally {
      loading.value = false;
    }
  };
  
  const filteredTickets = computed(() => {
    return tickets?.value.filter(ticket =>
      ticket.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      ticket.description.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      ticket.status.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  });
  
  const onSort = (event: DataTableSortEvent) => {
    sortField.value = event.sortField as string;
    sortOrder.value = event.sortOrder as number;
  };
  
  
  
  const openNew = () => {
    isEditMode.value = false;
    ticketStore.setCurrentTicket({ status: 'Open' })
    submitted.value = false
    ticketDialog.value = true
  }
  
  const hideDialog = () => {
    ticketDialog.value = false
    submitted.value = false
    ticketStore.setCurrentTicket(null)
    validationErrors.value = {};
  }
  
  const saveTicket = async () => {
    submitted.value = true
    if (currentTicket.value?.title && currentTicket.value?.description && currentTicket.value?.status) {
      try {
        await ticketStore.saveTicket(currentTicket.value)
        toast.success(`Ticket ${currentTicket.value.id ? 'Updated' : 'Created'} successfully.`)
        ticketDialog.value = false
      } catch (error: unknown) {
        if (error instanceof AxiosError && error.response) {
          validationErrors.value = error.response.data.errors;
          console.log("error in component", error.response.data.errors);
        } else {
          toast.error(validationErrors.value || "An unexpected error occurred.");
        }
      } finally {
        submitted.value = false
      }
    }
  }
  const editTicket = (editTicket: Ticket) => {
    isEditMode.value = true;
    currentTicket.value = { ...editTicket };
    validationErrors.value = {};
    ticketDialog.value = true;
  };
  
  const confirmDeleteTicket = (deleteTicket: Ticket) => {
    currentTicket.value = deleteTicket;
    deleteTicketDialog.value = true;
  };
  
  const deleteTicket = async () => {
    console.log("DELETEUP", currentTicket.value?.id)
    submitted.value = true
    if (currentTicket.value?.id) {
      try {
        console.log("DELETE")
        await ticketStore.deleteTicket(currentTicket.value.id)
        toast.success('Ticket Deleted successfully.')
        deleteTicketDialog.value = false
      } catch (error) {
        console.log("error", error)
        toast.error((error as Error).message)
      } finally {
        submitted.value = false
      }
    }
  }
  
  const getStatusSeverity = (status: string) => {
    switch (status) {
      case 'Open':
        return 'success';
      case 'In Progress':
        return 'warning';
      case 'Closed':
        return 'danger';
      default:
        return 'info';
    }
  };
  </script>
  
  <style>
  .custom-button {
    background-color: rgb(100 188 130) !important;
    border-color: rgb(100 188 130) !important;
  }
  
  .custom-button:hover {
    background-color: rgb(80 168 110) !important;
    border-color: rgb(80 168 110) !important;
  }
  
  .p-dialog .p-dialog-header {
    border-bottom: 1px solid #e5e7eb;
  }
  
  .p-dialog .p-dialog-footer {
    border-top: 1px solid #e5e7eb;
    padding: 1rem;
  }
  </style>