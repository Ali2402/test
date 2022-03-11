@extends('layouts.app-auth')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/vuetify.datatable.css">
@endsection

@section('content')
{{-- @if(session('msg')) --}}
<div v-if="msg != ''" class="col-span-1 md:col-span-2 relative py-3 pl-4 mb-5 pr-10 leading-normal bg-gray-100 text-gray-700 rounded-lg" role="alert">
    <p>@{{ msg }}</p>
</div>
{{-- @endif --}}
<div>
    <div class="flex flex-row w-full items-center gap-x-3 mb-3">
        <a href="/admin">
            <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-yellow-700 bg-yellow-100 border border-yellow-300 ">
                <div class="text-xs font-normal leading-none max-w-full flex-initial">Admin Dashboard</div>
            </div>
        </a>
        <a href="#" @click="dialog_user = true">
            <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-green-700 bg-green-100 border border-green-300 ">
                <div class="text-xs font-normal leading-none max-w-full flex-initial">Add Users</div>
            </div>
        </a>
    <a href="/admin/invitecode/generate">
        <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-red-700 bg-red-100 border border-red-300 ">
            <div class="text-xs font-normal leading-none max-w-full flex-initial">Generate invitecode</div>
        </div>
    </a>
  
    </div>
    <p class="font-semibold text-xl">Invite Code</p>
    <div class="flex flex-col gap-y-5">
        <div class="bg-white rounded-lg shadow-md">
          <v-data-table :headers="headersInvite" :items="invite" :items-per-page="paginationInvite.itemsPerPage" item-key="name" class="shadow text-left" hide-default-footer>
              <template v-slot:item="invite">
                  <tr>
                    <td>@{{ invite.item.code }}</td>
                    <td>@{{ (invite.item.status == 0) ? 'Invalid' : 'Valid' }}</td>
                    <td>@{{ invite.item.description }}</td>
                    <td>@{{ invite.item.author }}</td>
                    <td>@{{ invite.item.created_at }}</td>
                  </tr>
              </template>
          </v-data-table>
        </div>
        <div v-if="paginationInvite.total != 1" class="flex flex-row justify-center items-center pt-2 pb-10">
            <div class="w-3/4 m-auto">
                <v-pagination class="mb-2" v-model="paginationInvite.current" :length="paginationInvite.total" @input="onPageChangeInvite"></v-pagination>
            </div>
        </div>
    </div>
    <hr class="my-5" style="border-top: 2px solid #bbb; border-radius: 5px">
    <p class="font-semibold text-xl">Users</p>
    <div class="flex flex-col gap-y-5">
        <div class="bg-white rounded-lg shadow-md">
          <v-data-table :headers="headers" :items="users" :items-per-page="pagination.itemsPerPage" item-key="name" class="shadow text-left" hide-default-footer>
              <template v-slot:item="users">
                  <tr>
                    <td>@{{ users.item.username }}</td>
                    <td>@{{ users.item.email }}</td>
                    <td>$ @{{ users.item.balance }}</td>
                    <td>@{{ users.item.reff }}</td>
                    <td>@{{ users.item.domain_limit }}</td>
                    <td>@{{ users.item.created_at }}</td>
                    <td>@{{ (users.item.role == 1 ) ? 'ADMIN' : 'USER ' }}</td>
                    <td>
                        <a href="#" class="no-underline" @click="goToDetailUser(users.item)">
                            <svg class="w-6 h-6 stroke-current text-white bg-blue-500 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </a>
                    </td>
                  </tr>
              </template>
            </v-data-table>
        </div>
        <div v-if="pagination.total != 1" class="flex flex-row justify-center items-center pt-2 pb-10">
            <div class="w-3/4 m-auto">
                <v-pagination class="mb-2" v-model="pagination.current" :length="pagination.total" @input="onPageChange"></v-pagination>
            </div>
        </div>
    </div>
</div>
@endsection

@section('bottom-app')
<div v-if="dialog_user" class="dialogku flex flex-row items-center justify-center bg-gray-900 bg-opacity-50" id="add_user" style="display: block !important">
    <div class="flex w-3/4 h-screen m-auto">
        <div class="m-auto w-full">
            <div class="w-full bg-white py-5 px-10 rounded-lg flex flex-col shadow-lg gap-y-5">
                <p class="font-bold text-xl">User</p>
                <div class="flex flex-col gap-y-3">
                    {{-- @csrf --}}
                    <div>
                        <p>Username</p>
                        <input v-model="username" id="username" type="text" name="username" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating" required autocomplete="username">
                    </div>
                    <div>
                        <p>E-mail Address</p>
                        <input v-model="email" id="email" type="email" name="email" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating" required autocomplete="email">
                    </div>
                    <div>
                        <p>Password</p>
                        <input v-model="password" id="password" type="password" name="password" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating" required autocomplete="password">
                    </div>
                    <div>
                        <p>Balance</p>
                        <input v-model="balance" id="balance" type="number" name="balance" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating" required>
                    </div>
                    <div class="flex flex-row gap-x-2 mt-3 justify-between">
                        <div>
                            <button type="submit" class="px-3 py-2 bg-yellow-500 rounded font-semibold text-white" @click="saveUser">Submit</button>
                            <button type="button" class="px-3 py-2 bg-red-500 rounded font-semibold text-white" @click="closeDialogUser">Close</button>
                        </div>
                        <a v-if="is_detail" :href="'/admin/user/delete/' + id">
                            <button type="button" class="px-3 py-2 border border-red-500 text-red-500 rounded font-semibold text-white">Delete</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            mounted(){
                this.getUsers()
                this.getInvite()
            },
            data: {
                dialog_user: false,
                is_detail: false,
                id: '',
                username: '',
                email: '',
                password: '',
                balance: '',
                msg: '',
                users: [],
                invite: [],
                headers: [
                    { text: 'Username', sortable: true, value: 'username'},
                    { text: 'Email', sortable: true, value: 'email'},
                    { text: 'Balance', sortable: true, value: 'balance'},
                    { text: 'Referral', sortable: true, value: 'referral'},
                    { text: 'Domain limit' , sortable: true, value: 'domain'},
                    { text: 'Created date', sortable: true, value: 'created'},
                    { text: 'Level' , sortable:true, value: 'level'},
                    { text: 'Action' , sortable:false}
                ],
                headersInvite: [
                    { text: 'Code', sortable: true, value: 'code'},
                    { text: 'Status', sortable: true, value: 'status'},
                    { text: 'Description', sortable: true, value: 'description'},
                    { text: 'Author', sortable: true, value: 'author'},
                    { text: 'Created date', sortable: true, value: 'created'},
                    // { text: 'Action' , sortable:false}
                ],
                pagination: {
                    itemsPerPage: 10,
                    current: 1,
                    total: 0
                },
                paginationInvite: {
                    itemsPerPage: 10,
                    current: 1,
                    total: 0
                },
            },
            methods:{
                getUsers(){
                    this.startLoading()
                    fetch('/admin/api/getuser?page=' + this.pagination.current)
                    .then(res => res.json())
                    .then(data => {
                        this.users = data.PAYLOAD.data
                        this.pagination.current = data.PAYLOAD.current_page
                        this.pagination.total = data.PAYLOAD.last_page
                        this.endLoading()
                    })
                },
                getInvite(){
                    this.startLoading()
                    fetch('/admin/api/getinvitecode?page=' + this.paginationInvite.current)
                    .then(res => res.json())
                    .then(data => {
                        this.invite = data.PAYLOAD.data
                        this.paginationInvite.current = data.PAYLOAD.current_page
                        this.paginationInvite.total = data.PAYLOAD.last_page
                        this.endLoading()
                    })
                },
                onPageChange(){
                    this.getUsers()
                },
                onPageChangeInvite(){
                    this.getInvite()
                },
                startLoading(){
                    document.getElementById('modal_loading').style.display = 'block'
                }, 
                endLoading(){
                    document.getElementById('modal_loading').style.display = 'none'
                },
                closeDialogUser(){
                    this.is_detail = false
                    this.id = ''
                    this.username = ''
                    this.email = ''
                    this.password = ''
                    this.balance = ''
                    this.dialog_user = false
                },
                saveUser(){
                    var url = ''
                    this.startLoading()
                    this.dialog_user = false
                    if(this.is_detail) url = '/admin/user/update'
                    else url = '/admin/user/add'
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json', 
                            'Content-Type': 'application/json',
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-Token": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            id: this.id,
                            username: this.username,
                            email: this.email,
                            password: this.password,
                            balance: this.balance
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.endLoading()
                        this.msg = data.PAYLOAD.msg
                        setTimeout(function(){
                            location.reload()
                        },1500)
                    })
                },
                goToDetailUser(user){
                    this.dialog_user = true
                    this.is_detail = true
                    this.id = user.id
                    this.username = user.username 
                    this.email = user.email 
                    this.balance = user.balance
                }
            }
        })
    </script>
@endsection
