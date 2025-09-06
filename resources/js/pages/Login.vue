<template>
<div class="container mt-5">
  <h2>Login</h2>
  <form @submit.prevent="login" class="w-50">
    <input v-model="form.email" class="form-control mb-2" placeholder="Email">
    <input v-model="form.password" type="password" class="form-control mb-2" placeholder="Password">
    <button class="btn btn-primary">Login</button>
  </form>
</div>
</template>

<script>
import axios from 'axios'
export default {
  data(){ return { form:{email:'',password:''} } },
  methods:{
    login(){
      axios.post('/login', this.form).then(res=>{
        localStorage.setItem('token', res.data.token);
        alert('Login successful');
        window.location.href = '/';
      }).catch(e=> alert('Invalid credentials'));
    }
  }
}
</script>
