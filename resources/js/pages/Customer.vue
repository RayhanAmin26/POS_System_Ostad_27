<template>
<div>
<h1 class="text-2xl font-bold mb-4">Customers</h1>

<form @submit.prevent="addCustomer" class="mb-4">
<input v-model="newCustomer.name" placeholder="Name" class="border p-1 mr-2">
<input v-model="newCustomer.email" placeholder="Email" class="border p-1 mr-2">
<input v-model="newCustomer.phone" placeholder="Phone" class="border p-1 mr-2">
<input v-model="newCustomer.address" placeholder="Address" class="border p-1 mr-2">
<button type="submit" class="bg-blue-500 text-white p-1">Add</button>
</form>

<ul>
<li v-for="customer in customers" :key="customer.id">
{{ customer.name }} | {{ customer.email }} | {{ customer.phone }} | {{ customer.address }}
<button @click="deleteCustomer(customer.id)" class="text-red-500 ml-2">Delete</button>
</li>
</ul>
</div>
</template>

<script>
import axios from 'axios';
export default {
data(){ return { customers:[], newCustomer:{ name:'', email:'', phone:'', address:'' } }; },
mounted(){ this.fetchCustomers(); },
methods:{
fetchCustomers(){ axios.get('/api/customers',{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(res=>this.customers=res.data); },
addCustomer(){ axios.post('/api/customers',this.newCustomer,{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(res=>{ this.customers.push(res.data); this.newCustomer={ name:'', email:'', phone:'', address:'' }; }); },
deleteCustomer(id){ axios.delete(`/api/customers/${id}`,{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(()=>this.customers=this.customers.filter(c=>c.id!==id)); }
}
}
</script>
