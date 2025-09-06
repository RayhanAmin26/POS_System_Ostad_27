<template>
<div>
<h1 class="text-2xl font-bold mb-4">Products</h1>

<!-- Add Product Form -->
<form @submit.prevent="addProduct" class="mb-4">
<input v-model="newProduct.name" placeholder="Name" class="border p-1 mr-2">
<select v-model="newProduct.category_id" class="border p-1 mr-2">
<option disabled value="">Select Category</option>
<option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
</select>
<select v-model="newProduct.brand_id" class="border p-1 mr-2">
<option disabled value="">Select Brand</option>
<option v-for="b in brands" :key="b.id" :value="b.id">{{ b.name }}</option>
</select>
<input v-model="newProduct.price" type="number" placeholder="Price" class="border p-1 mr-2">
<input v-model="newProduct.stock" type="number" placeholder="Stock" class="border p-1 mr-2">
<input type="file" @change="onFileChange">
<button type="submit" class="bg-blue-500 text-white p-1">Add</button>
</form>

<!-- Product List -->
<ul>
<li v-for="product in products" :key="product.id">
<img :src="`/images/products/${product.image}`" alt="" width="50">
{{ product.name }} | {{ product.category.name }} | {{ product.brand.name }} | ${{ product.price }} | Stock: {{ product.stock }}
<button @click="deleteProduct(product.id)" class="text-red-500 ml-2">Delete</button>
</li>
</ul>
</div>
</template>

<script>
import axios from 'axios';
export default {
data(){
return {
products:[], categories:[], brands:[],
newProduct:{ name:'', category_id:'', brand_id:'', price:'', stock:'', image:null }
};
},
mounted(){
this.fetchProducts();
this.fetchCategories();
this.fetchBrands();
},
methods:{
fetchProducts(){ axios.get('/api/products',{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(res=>this.products=res.data); },
fetchCategories(){ axios.get('/api/categories',{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(res=>this.categories=res.data); },
fetchBrands(){ axios.get('/api/brands',{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(res=>this.brands=res.data); },
onFileChange(e){ this.newProduct.image=e.target.files[0]; },
addProduct(){
let formData=new FormData();
for(let key in this.newProduct) formData.append(key,this.newProduct[key]);
axios.post('/api/products',formData,{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`, 'Content-Type':'multipart/form-data'}})
.then(res=>{ this.products.push(res.data); this.newProduct={ name:'', category_id:'', brand_id:'', price:'', stock:'', image:null }; });
},
deleteProduct(id){ axios.delete(`/api/products/${id}`,{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(()=>this.products=this.products.filter(p=>p.id!==id)); }
}
}
</script>
