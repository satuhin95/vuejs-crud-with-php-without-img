<!DOCTYPE html>
<html>
<head>
	<title>Vue Php CRUD</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="js/axios.min.js"></script>
	<script src="js/vue.js"></script>
	<style type="text/css">
		.contact{
			width: 960px;
			margin: 0 auto;
			margin-top: 20px;
			text-align:center;
		}
		tr,td{
			padding: 5px;
		}
	</style>
</head>
<body>
	<div id="app">
		<div class="contact">
			<div class="content-top">
				<div class="float-left">
				<h2>All Users</h2>
			</div>
			<div class="float-right">
				<button type="button" @click="userModel=true">Add New</button>
			</div>
			</div>

			<div class="alert success" v-if="successMessage">{{successMessage}} <button @click="clearMessage">X</button></div>
			<div class="alert error" v-if="errorMessage">{{errorMessage}}<button @click="clearMessage">X</button></div>
			

			<table width="100%" border="1">
				<tr>
					<td>Id</td>
					<td>Name</td>
					<td>Contact</td>
					<td>Email</td>
					<td>Action</td>
				</tr>

				<tr v-for="user in users">
					<td>{{user.id}}</td>
					<td>{{user.name}}</td>
					<td>{{user.contact}}</td>
					<td>{{user.email}}</td>
					<td>
						<button @click="userUpdateModel=true;setUpdateData(user)">Edit</button>
						<button @click="deleteUser(user.id)">Delete</button>
					</td>
				</tr>
			</table>
		</div>
		<div class="modal" v-if="userModel">
			<div class="modal-content">
				<div class="modal-header row">
					<div class="float-left">
						<h3>Add New User</h3>
					</div>
					<div class="float-right">
						<button type="button" @click="userModel=false">X</button>
					</div>
				</div>
				<hr>
				<div class="modal-body">
					<form>
						<table>
							<tr>
							<th>Name</th>
							<td><input type="text" v-model="form.name"> </td>
						</tr>
						<tr>
							<th>Contact</th>
							<td><input type="text" v-model="form.contact"> </td>
						</tr>
						<tr>
							<th>Email</th>
							<td><input type="text" v-model="form.email"> </td>
						</tr>
						<tr>
							<th></th>
							<td><button type="button" @click="userModel=false;addNewUser()">Save</button> </td>
						</tr>
						</table>
					</form>
				</div>
			</div>
		</div>

		<div class="modal" v-if="userUpdateModel">
			<div class="modal-content">
				<div class="modal-header row">
					<div class="float-left">
						<h3>Update User</h3>
					</div>
					<div class="float-right">
						<button type="button" @click="userUpdateModel=false">X</button>
					</div>
				</div>
				<hr>
				<div class="modal-body">
					<form>
						<table>
							<tr>
							<th>Name</th>
							<td><input type="text" v-model="updateUser.name"> </td>
						</tr>
						<tr>
							<th>Contact</th>
							<td><input type="text" v-model="updateUser.contact"> </td>
						</tr>
						<tr>
							<th>Email</th>
							<td><input type="text" v-model="updateUser.email"> </td>
						</tr>
						<tr>
							<th></th>
							<td><button type="button" @click="userUpdateModel=false;updateUserData()">Update</button> </td>
						</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
		
	</div>

<script>
	var app = new Vue({
		el:"#app",
		data:{
			users:[],
			errorMessage:null,
			successMessage:null,
			form:{
				name:'',
				contact:'',
				email:''
			},
			updateUser:{},
			userModel:false,
			userUpdateModel:false,
		},
		methods:{
			getData:function(){
					axios.get("http://localhost/vue/motaleb/php/api.php?action=read")
					.then(function(response){

							if (!response.data.error) {
								app.users = response.data.users;
							}else{
								app.errorMessage = response.data.message;
							}
							
					});
			},
			addNewUser(){
				let formData = this.toFormData(this.form);
				axios.post("http://localhost/vue/motaleb/php/api.php?action=create",formData)
				.then(function(response){
					if (response.data.error) {
						app.errorMessage = response.data.message;
						}else{
							
							app.form.name =null;
							app.form.contact =null;
							app.form.email =null;
							app.getData(); 
							app.successMessage = response.data.message;
						}
							
				});
			},
			updateUserData(){
				let formData = this.toFormData(this.updateUser);
				axios.post("http://localhost/vue/motaleb/php/api.php?action=update",formData)
				.then(function(response){
					if (response.data.error) {
						app.errorMessage = response.data.message;
						}else{
							
							app.updateUser.name =null;
							app.updateUser.contact =null;
							app.updateUser.email =null;
							app.getData(); 
							app.successMessage = response.data.message;
						}
							
				});
			},
			deleteUser(id){
				let formData = this.toFormData({id:id});
				axios.post("http://localhost/vue/motaleb/php/api.php?action=delete",formData)
				.then(function(response){
					if (response.data.error) {
						app.errorMessage = response.data.message;
						}else{
							
							app.getData(); 
							app.successMessage = response.data.message;
						}
							
				});
			},
			toFormData(obj){
				let data = new FormData();
				for(let key in obj){
					data.append(key,obj[key]);
				}
				return data;
			},
			clearMessage(){
				this.errorMessage = null;
				this.successMessage = null;
			},
			setUpdateData(data){
				this.updateUser = data;
			}
		
		},
		mounted: function(){
			this.getData();
		}
	});
</script>
</body>
</html>