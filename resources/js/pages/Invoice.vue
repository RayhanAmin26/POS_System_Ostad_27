<template>
<div>
<h1 class="text-2xl font-bold mb-4">Invoices</h1>

<!-- Create Invoice -->
<form @submit.prevent="addInvoice" class="mb-4">
<select v-model="newInvoice.customer_id" class="border p-1 mr-2">
<option disabled value="">Select Customer</option>
<option v-for="c in customers" :key="c.id" :value="c.id">{{ c.name }}</option>
</select>

<div v-for="(p,index) in newInvoice.products" :key="index" class="mb-2">
<select v-model="p.id" class="border p-1 mr-2">
<option disabled value="">Select Product</option>
<option v-for="prod in products" :key="prod.id" :value="prod.id">{{ prod.name }}</option>
</select>
<input type="number" v-model="p.quantity" placeholder="Qty" class="border p-1 mr-2">
<button @click.prevent="removeProduct(index)" class="text-red-500">Remove</button>
</div>

<button @click.prevent="addProductField" class="bg-gray-300 p-1 mb-2">Add Product</button>
<button type="submit" class="bg-blue-500 text-white p-1">Create Invoice</button>
</form>

<!-- Invoice List -->
<ul>
<li v-for="invoice in invoices" :key="invoice.id">
Invoice #{{ invoice.id }} | Customer: {{ invoice.customer.name }} | Total: ${{ invoice.total }}
<button @click="deleteInvoice(invoice.id)" class="text-red-500 ml-2">Delete</button>
<button @click="downloadPDF(invoice.id)" class="text-green-500 ml-2">PDF</button>
</li>
</ul>
</div>
</template>

<script>
import axios from 'axios';
export default {
data(){ return {
invoices:[], customers:[], products:[],
newInvoice:{ customer_id:'', products:[{id:'', quantity:1}] }
}; },
mounted(){ this.fetchInvoices(); this.fetchCustomers(); this.fetchProducts(); },
methods:{
fetchInvoices(){ axios.get('/api/invoices',{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(res=>this.invoices=res.data); },
fetchCustomers(){ axios.get('/api/customers',{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(res=>this.customers=res.data); },
fetchProducts(){ axios.get('/api/products',{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(res=>this.products=res.data); },
addProductField(){ this.newInvoice.products.push({id:'', quantity:1}); },
removeProduct(i){ this.newInvoice.products.splice(i,1); },
addInvoice(){
axios.post('/api/invoices',this.newInvoice,{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(res=>{
this.invoices.push(res.data);
this.newInvoice={customer_id:'', products:[{id:'', quantity:1}]};
});
},
deleteInvoice(id){ axios.delete(`/api/invoices/${id}`,{headers:{Authorization:`Bearer ${localStorage.getItem('token')}`}}).then(()=>this.invoices=this.invoices.filter(i=>i.id!==id)); },
downloadPDF(id){ window.open(`/api/invoices/${id}/pdf?token=${localStorage.getItem('token')}`,'_blank'); }
}
}
</script>
