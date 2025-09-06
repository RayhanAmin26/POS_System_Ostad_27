<template>
<div>
<h1 class="text-2xl font-bold mb-4">Categories</h1>
<form @submit.prevent="addCategory">
<input v-model="newCategory" placeholder="New Category" class="border p-1 mr-2">
<button type="submit" class="bg-blue-500 text-white p-1">Add</button>
</form>
<ul class="mt-4">
<li v-for="category in categories" :key="category.id">
{{ category.name }}
<button @click="deleteCategory(category.id)" class="text-red-500 ml-2">Delete</button>
</li>
</ul>
</div>
</template>

<script>
import axios from 'axios';
export default {
data(){ return { categories:[], newCategory:'' }; },
mounted(){ this.fetchCategories(); },
methods:{
fetchCategories(){ axios.get('/api/categories',{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(res=>this.categories=res.data); },
addCategory(){
axios.post('/api/categories',{name:this.newCategory},{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(res=>{this.categories.push(res.data); this.newCategory='';});
},
deleteCategory(id){ axios.delete(`/api/categories/${id}`,{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(()=>this.categories=this.categories.filter(c=>c.id!==id)); }
}
}
</script>
