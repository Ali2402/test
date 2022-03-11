@extends('layouts.app-auth')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
<link rel="stylesheet" href="/css/vuetify.datatable.css">
@endsection

@section('content')
    <div class="flex flex-col gap-y-5">
        <p class="font-semibold text-xl">Issues</p>
        <div class="bg-white rounded-lg shadow-md h-24">
            <v-data-table :headers="headers" :items="issues" item-key="name" class="shadow text-left" hide-default-footer>
                <template v-slot:item="issues">
                    <tr>
                        <td>@{{ issues.item.date }}</td>
                        <td>@{{ issues.item.title }}</td>
                        <td>@{{ issues.item.content }}</td>
                        <td>
                            <select name="status" :id="'status'+issues.item.id" class="pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg focus:shadow-outline" @change="updateStatus(issues.item.id)">
                                <option value="1" :selected="issues.item.status == 1">Pending</option>
                                <option value="2" :selected="issues.item.status == 2">Proccess</option>
                                <option value="3" :selected="issues.item.status == 3">Fixed</option>
                            </select>
                        </td>
                        {{-- <td v-if="issues.item.status == 1">Pending</td>
                        <td v-if="issues.item.status == 2" class="text-yellow-500">Proccess</td>
                        <td v-if="issues.item.status == 3" class="text-green-500">Fixed</td> --}}
                        <td>
                            <a :href="'/admin/issues/delete/' + issues.item.id" class="no-underline">
                                <svg class="w-6 h-6 stroke-current text-white bg-blue-500 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
@endsection

@section('bottom-app')
    <div class="dialogku flex flex-row items-center justify-center bg-gray-900 bg-opacity-50" id="topup_form">
        <div class="flex w-3/4 h-screen m-auto">
            <div class="m-auto w-full">
                <div class="w-full bg-white py-5 px-10 rounded-lg flex flex-col shadow-lg gap-y-5">
                    <p class="font-bold text-xl">Top Up</p>
                    <form action="/" method="POST" >
                        <div>
                            <p>Amount</p>
                            <input id="amount" type="amount" name="amount" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating @error('amount') border-red-500 @enderror" required autocomplete="amount">
                        </div>
                        <div class="mt-4">
                            <p>Payment Method</p>
                            <div class="flex flex-col mt-1">
                                <label class="text-gray-700">
                                    <input type="radio" value="perfectmoney" name="method" />
                                    <span class="ml-1">Perfect Money</span>
                                </label>
                                <label class="text-gray-700">
                                    <input type="radio" value="coinpayment" name="method" />
                                    <span class="ml-1">Coin Payment (BTC, ETH, USDT)</span>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="px-3 py-2 bg-yellow-500 rounded font-bold mt-3 text-white">Submit</button>
                        <button type="button" class="px-3 py-2 bg-red-700 rounded font-bold mt-3 text-white" onclick="document.getElementById('topup_form').style.display='none';">Close</button>
                    </form>
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
                this.getIssues()
            },
            data: {
                issues: [],
                headers: [
                    { text: 'Date', sortable: true, value: 'date'},
                    { text: 'Title', sortable: true, value: 'title'},
                    { text: 'Desc', sortable: true, value: 'desc'},
                    { text: 'Status', sortable: true, value: 'status'},
                    { text: 'Action', sortable: false}
                ],
                pagination: {
                    itemsPerPage: 10,
                    current: 1,
                    total: 0
                },
            },
            methods:{
                getIssues(){
                    this.startLoading()
                    fetch('/issues/get?page=' + this.pagination.current)
                    .then(res => res.json())
                    .then(data => {
                        this.issues = data.PAYLOAD.data
                        this.pagination.current = data.PAYLOAD.current_page
                        this.pagination.total = data.PAYLOAD.last_page
                        this.endLoading()
                    })
                },
                onPageChange(){
                    this.getIssues()
                },
                startLoading(){
                    document.getElementById('modal_loading').style.display = 'block'
                }, 
                endLoading(){
                    document.getElementById('modal_loading').style.display = 'none'
                },
                updateStatus(id){
                    this.startLoading()
                    fetch('/admin/issues/update', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json', 
                            'Content-Type': 'application/json',
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-Token": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            id: id,
                            status: document.getElementById('status'+id).options[document.getElementById('status'+id).selectedIndex].value
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.endLoading()
                    })
                }
            }
        })
    </script>


@endsection