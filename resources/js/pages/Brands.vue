<template>
<div>
<h1 class="text-2xl font-bold mb-4">Brands</h1>
<form @submit.prevent="addBrand">
<input v-model="newBrand" placeholder="New Brand" class="border p-1 mr-2">
<button type="submit" class="bg-blue-500 text-white p-1">Add</button>
</form>
<ul class="mt-4">
<li v-for="brand in brands" :key="brand.id">{{ brand.name }} <button @click="deleteBrand(brand.id)" class="text-red-500 ml-2">Delete</button></li>
</ul>
</div>
</template>

<script>
import axios from 'axios';
export default {
data(){ return { brands:[], newBrand:'' }; },
mounted(){ this.fetchBrands(); },
methods:{
fetchBrands(){ axios.get('/api/brands',{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(res=>this.brands=res.data); },
addBrand(){ axios.post('/api/brands',{name:this.newBrand},{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(res=>{this.brands.push(res.data); this.newBrand='';}); },
deleteBrand(id){ axios.delete(`/api/brands/${id}`,{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(()=>this.brands=this.brands.filter(b=>b.id!==id)); }
}
}
</script>
