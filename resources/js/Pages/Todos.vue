<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const todos = ref([])
const loading = ref(false)
const form = ref({ title: '', notes: '' })
const editing = ref(null)
const editForm = ref({ title: '', notes: '' })

async function fetchTodos() {
  loading.value = true
  const { data } = await axios.get('/api/todos')
  todos.value = data.data
  loading.value = false
}

async function addTodo() {
  if (!form.value.title.trim()) return
  const { data } = await axios.post('/api/todos', form.value)
  todos.value.unshift(data.data)
  form.value = { title: '', notes: '' }
}

async function toggleDone(todo) {
  const { data } = await axios.patch(`/api/todos/${todo.id}`, { is_done: !todo.is_done })
  const i = todos.value.findIndex(t => t.id === todo.id)
  if (i > -1) todos.value[i] = data.data
}

function startEdit(todo) {
  editing.value = todo.id
  editForm.value = { title: todo.title, notes: todo.notes ?? '' }
}

async function saveEdit(todo) {
  const { data } = await axios.patch(`/api/todos/${todo.id}`, editForm.value)
  const i = todos.value.findIndex(t => t.id === todo.id)
  if (i > -1) todos.value[i] = data.data
  editing.value = null
}

async function removeTodo(todo) {
  await axios.delete(`/api/todos/${todo.id}`)
  todos.value = todos.value.filter(t => t.id !== todo.id)
}

onMounted(fetchTodos)
</script>

<template>
  <div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white shadow rounded-xl p-5">
      <h1 class="text-2xl font-semibold mb-4">To-Do List</h1>

      <div class="flex gap-2 mb-3">
        <input v-model="form.title" class="flex-1 border rounded px-3 py-2"
               placeholder="New task title…" @keyup.enter="addTodo" />
        <button @click="addTodo" class="bg-blue-600 text-white px-4 py-2 rounded">
          Add
        </button>
      </div>

      <textarea v-model="form.notes" rows="2" class="w-full border rounded px-3 py-2 mb-6"
                placeholder="Notes (optional)" />

      <div v-if="loading">Loading…</div>
      <ul v-else class="space-y-3">
        <li v-for="todo in todos" :key="todo.id"
            class="flex items-start justify-between gap-3 border rounded-lg p-3">
          <div class="flex items-start gap-3">
            <input type="checkbox" :checked="todo.is_done" @change="toggleDone(todo)" class="mt-1" />
            <div>
              <div :class="['text-lg', todo.is_done ? 'line-through text-gray-500' : 'text-gray-900']">
                {{ todo.title }}
              </div>
              <div v-if="todo.notes" class="text-sm text-gray-600" :class="todo.is_done ? 'line-through' : ''">
                {{ todo.notes }}
              </div>
              <div class="text-xs text-gray-400 mt-1">
                {{ new Date(todo.created_at).toLocaleString() }}
              </div>
            </div>
          </div>

          <div class="flex gap-2">
            <button v-if="editing !== todo.id" @click="startEdit(todo)" class="px-3 py-1 border rounded">Edit</button>
            <button @click="removeTodo(todo)" class="px-3 py-1 border rounded text-red-600">Delete</button>
          </div>

          <div v-if="editing === todo.id" class="w-full col-span-2 mt-3">
            <input v-model="editForm.title" class="w-full border rounded px-3 py-2 mb-2" />
            <textarea v-model="editForm.notes" rows="2" class="w-full border rounded px-3 py-2 mb-2" />
            <div class="flex gap-2">
              <button @click="saveEdit(todo)" class="bg-green-600 text-white px-3 py-1 rounded">Save</button>
              <button @click="editing = null" class="px-3 py-1 border rounded">Cancel</button>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
