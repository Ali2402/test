@extends('layouts.app-auth')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
<link rel="stylesheet" href="/css/vuetify.datatable.css">
@endsection

@section('content')
    <div class="flex flex-col gap-y-5 md:gap-y-10">
        <div class="col-span-1 md:col-span-2 relative py-3 pl-4 pr-10 leading-normal bg-blue-100 text-blue-700  rounded-lg" role="alert">
            <p>
              <b>Important information</b>
              <li><i>top up with crypto payment methods takes quite a long time, wait a few hours until balance added.</i></li>
              <li><i>automatic payments are managed by third parties, payments outside the website are not our responsibility</i> </li></p>
              
          </div>
        <div class="flex flex-row justify-between items-center bg-white rounded-lg shadow-md">
            <div class="flex flex-row p-10 gap-x-5 items-center">
                <svg class="w-10 h-10 p-2 bg-gray-200 stroke-current text-purple-500 rounded-full" fill="none" stroke="currentColor" view-box="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                <p class="font-semibold text-xl">$ @php echo DB::table('users')->where('id',session()->get('user_id'))->first()->balance; @endphp</p>
                <p class="text-xs text-gray-600">Total Balance</p>
                </div>
            </div>   
            <div class="flex flex-row gap-x-3 pr-10">
                <a href="#" onclick="document.getElementById('topup_form').style.display='block';">
                    <div class="flex flex-col gap-2 items-center">
                        <svg class="w-10 h-10 p-2 bg-gray-200 stroke-current text-purple-500 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <p class="font-semibold">TopUp</p>
                    </div>   
                </a>
                <a href="#" onclick="document.getElementById('redeem').style.display='block';">
                    <div class="flex flex-col gap-2 items-center">
                        <svg class="w-10 h-10 p-2 bg-gray-200 stroke-current text-red-500 rounded-full" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <p class="font-semibold">Redeem</p>
                    </div>   
                </a>
            </div>
        </div>
        @if(session('msg'))
            <div class="col-span-1 md:col-span-2 relative py-3 pl-4 pr-10 leading-normal @if(preg_match('/Oops/', session('msg'))) bg-red-100 text-red-700 @else bg-green-100 text-green-700 @endif rounded-lg" role="alert">
                <p>{{ session()->get('msg') }}</p>
            </div>
        @endif
        <div class="bg-white rounded-lg shadow-md">
            <p class="m-5 font-semibold text-xl">Transaction History</p>
            <v-data-table :headers="headers" :items="transactions" item-key="name" class="shadow text-left" hide-default-footer>
                <template v-slot:item="transactions">
                    <tr>
                        <td>@{{ transactions.item.date }}</td>
                        <td>@{{ transactions.item.amount }}</td>
                        <td>@{{ transactions.item.desc }}</td>
                        <td>@{{ transactions.item.ip_address }}</td>
                    </tr>
                </template>
            </v-data-table>
        </div>
        <div v-if="pagination.total != 1" class="flex flex-row justify-center items-center pt-2">
            <div class=" w-3/4 m-auto">
                <v-pagination class="mb-2" v-model="pagination.current" :length="pagination.total" @input="onPageChange"></v-pagination>
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
                    <form action="/payment/make" method="POST" >
                        @csrf
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
                                    <input type="radio" value="coinpayments" name="method" />
                                    <span class="ml-1">CoinPayments (BTC, ETH, USDT)</span>
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

    <div class="dialogku flex flex-row items-center justify-center bg-gray-900 bg-opacity-50" id="redeem">
        <div class="flex w-3/4 h-screen m-auto">
            <div class="m-auto w-full">
                <div class="w-full bg-white py-5 px-10 rounded-lg flex flex-col shadow-lg gap-y-5">
                    <p class="font-bold text-xl">Redeem code</p>
                    <form action="/balance/redeem" method="POST" >
                        @csrf
                        <div>
                            <p>Voucher code</p>
                            <input id="voucher" type="text" name="code" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating @error('voucher') border-red-500 @enderror" required autocomplete="voucher">
                        </div>
                        <button type="submit" class="px-3 py-2 bg-yellow-500 rounded font-bold mt-3 text-white">Redeem</button>
                        <button type="button" class="px-3 py-2 bg-red-700 rounded font-bold mt-3 text-white" onclick="document.getElementById('redeem').style.display='none';">Close</button>
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
                this.getTransactionHistory()
            },
            data: {
                transactions: [],
                headers: [
                    { text: 'Date', sortable: true, value: 'date'},
                    { text: 'Amount', sortable: true, value: 'amount'},
                    { text: 'Desc', sortable: true, value: 'desc'},
                    { text: 'IP Address', sortable: true, value: 'ip'},
                ],
                pagination: {
                    itemsPerPage: 10,
                    current: 1,
                    total: 0
                },
            },
            methods:{
                getTransactionHistory(){
                    this.startLoading()
                    fetch('/balance/transaction/history?page=' + this.pagination.current)
                    .then(res => res.json())
                    .then(data => {
                        this.transactions = data.PAYLOAD.data
                        this.pagination.current = data.PAYLOAD.current_page
                        this.pagination.total = data.PAYLOAD.last_page
                        this.endLoading()
                    })
                },
                onPageChange(){
                    this.getTransactionHistory()
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