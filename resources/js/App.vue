<script setup>
import { ref } from 'vue'
import axios from 'axios'

const email = ref('')
const password = ref('')
const token = ref(localStorage.getItem('token'))
const projects = ref([])
const newProject = ref('')

const login = async () => {
    try {
        const res = await axios.post('/login', {
            email: email.value,
            password: password.value
        })

        token.value = res.data.data.token
        localStorage.setItem('token', token.value)
        axios.defaults.headers.common['Authorization'] =
            `Bearer ${token.value}`

        loadProjects()
    } catch (err) {
        alert('Login failed')
    }
}

const loadProjects = async () => {
    const res = await axios.get('/projects')
    projects.value = res.data.data
}

const createProject = async () => {
    await axios.post('/projects', {
        name: newProject.value
    })
    newProject.value = ''
    loadProjects()
}
</script>

<template>
<div style="padding:40px">

    <div v-if="!token">
        <h2>Login</h2>
        <input v-model="email" placeholder="Email" />
        <input v-model="password" type="password" placeholder="Password" />
        <button @click="login">Login</button>
    </div>

    <div v-else>
        <h2>Projects</h2>

        <input v-model="newProject" placeholder="New project name" />
        <button @click="createProject">Create</button>

        <ul>
            <li v-for="project in projects" :key="project.id">
                {{ project.name }}
            </li>
        </ul>
    </div>

</div>
</template>