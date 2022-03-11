@extends('layouts.app-auth')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/vuetify.datatable.css">
@endsection

@section('content')
<div>
    <div class="flex flex-row w-full items-center gap-x-3 mb-3">
        <a href="/admin/user">
        <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-green-700 bg-green-100 border border-green-300 ">
            <div class="text-xs font-normal leading-none max-w-full flex-initial">Users</div>
        </div>
    </a>
    <a href="/admin/voucher">
        <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-red-700 bg-red-100 border border-red-300 ">
            <div class="text-xs font-normal leading-none max-w-full flex-initial">Vouchers</div>
        </div>
    </a>
    <a href="/admin/product">
        <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-indigo-700 bg-indigo-100 border border-indigo-300 ">
            <div class="text-xs font-normal leading-none max-w-full flex-initial">Products</div>
        </div>
    </a>
    <a href="/admin/issues">
        <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-yellow-700 bg-yellow-100 border border-yellow-300 ">
            <div class="text-xs font-normal leading-none max-w-full flex-initial">Issues</div>
        </div>
    </a>

    <a href="/admin/invitecode/generate">
        <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-pink-700 bg-pink-100 border border-pink-300 ">
            <div class="text-xs font-normal leading-none max-w-full flex-initial">Get Invitecode</div>
        </div>
    </a>
    </div>
    <div class="flex flex-col gap-y-5">
        <div class="bg-white rounded-lg shadow-md h-24">
          <v-data-table :headers="headers" :items="logs" :items-per-page="pagination.itemsPerPage" item-key="name" class="shadow text-left" hide-default-footer>
              <template v-slot:item="logs">
                  <tr>
                    <td>@{{ logs.item.username }}</td>
                    <td>@{{ (logs.item.type == 1) ? 'Auth' : 'Transaction' }}</td>
                    <td>@{{ logs.item.amount }}</td>
                    <td>@{{ logs.item.ip_address }}</td>
                    <td>@{{ logs.item.date }}</td>
                    <td>
                        <a :href="'/logs/detail/' + logs.item.id" class="no-underline">
                            <svg class="w-6 h-6 stroke-current text-white bg-blue-500 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </a>
                    </td>
                  </tr>
              </template>
          </v-data-table>
          <div class="flex flex-row justify-center items-center pt-2 pb-10">
              <div class=" w-3/4 m-auto">
                  <v-pagination v-if="pagination.total != 1" class="mb-2" v-model="pagination.current" :length="pagination.total" @input="onPageChange"></v-pagination>
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
                this.getLogs()
            },
            data: {
                logs: [],
                headers: [
                    { text: 'Username', sortable: true},
                    { text: 'Type', sortable: true},
                    { text: 'Amount', sortable: true},
                    { text: 'IP Address', sortable: true},
                    { text: 'Date' , sortable: true},
                    { text: 'Action', sortable: false}
                ],
                pagination: {
                    itemsPerPage: 25,
                    current: 1,
                    total: 0
                },
            },
            methods:{
                getLogs(){
                    this.startLoading()
                    fetch('/admin/api/logs?page=' + this.pagination.current)
                    .then(res => res.json())
                    .then(data => {
                        this.logs = data.PAYLOAD.data
                        this.pagination.current = data.PAYLOAD.current_page
                        this.pagination.total = data.PAYLOAD.last_page
                        this.endLoading()
                    })
                },
                onPageChange(){
                    this.getLogs()
                },
                startLoading(){
                    document.getElementById('modal_loading').style.display = 'block'
                }, 
                endLoading(){
                    document.getElementById('modal_loading').style.display = 'none'
                }
            }
        })
    </script>
@endsection
