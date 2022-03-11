@extends('layouts.app-auth')

@section('css')
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/vuetify.datatable.css">
@endsection

@section('content')

<div class="col-span-1 md:col-span-2 relative py-3 pl-4 pr-10 leading-normal bg-blue-100 text-blue-700  rounded-lg" role="alert">
  <p>
    <b>Important information</b>
    <li><i>Your website statistics and settings will be set here.</i></li>
    <li><i>Every product is only allowed to register 3 domains.</i> </li></p>
    
</div>
<br>
<div class="flex flex-row w-full items-center gap-x-3 mb-3">

  
    <a href="#" onclick="document.getElementById('domain_register').style.display='block'">
        <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-green-700 bg-green-100 border border-green-300 ">
            <div class="text-xs font-normal leading-none max-w-full flex-initial">Register domain</div>
        </div>
    </a>


 
      <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-blue-700 bg-blue-100 border border-blue-300 ">
          <div class="text-xs font-normal leading-none max-w-full flex-initial">Domain quota : {{ $limit_domain }} / Product</div>
      </div>

      @if (session()->get('msg'))
          
      <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-yellow-700 bg-yellow-100 border border-yellow-300 ">
        <div class="text-xs font-normal leading-none max-w-full flex-initial">{{ session()->get('msg') }}</div>
    </div>
      @endif

      @if (session()->get('response'))
          
      <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-{{session()->get('response')['color']}}-700 bg-{{session()->get('response')['color']}}-100 border border-{{session()->get('response')['color']}}-300 ">
        <div class="text-xs font-normal leading-none max-w-full flex-initial">{{ session()->get('response')['text'] }}</div>
    </div>
      @endif

</div>

<div class="flex flex-col gap-y-5">
    <p class="font-semibold text-xl">Manage</p>
    <div class="bg-white rounded-lg shadow-md">
      <v-data-table :headers="headers" :items="purchases" item-key="name" class="shadow text-left" hide-default-footer>
          <template v-slot:item="purchases">
              <tr>
                <td>@{{ purchases.item.name }}</td>
                <th class="flex flex-row items-center gap-1 cursor-pointer">
                  <p :id="'ryu'+purchases.item.id" class="overflow-ellipsis overflow-hidden">@{{ purchases.item.signature }}</p>
                  <svg @click="copyText(purchases.item.signature)" class="w-6 h-6 cursor-pointer text-yellow-500 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z"></path><path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z"></path></svg>
                </th>
                <td>@{{ purchases.item.domain }}</td>
                <td>@{{ purchases.item.result_email }}</td>
                <td v-if="purchases.item.config != '{}'"><span class="text-green-500">YES</span> - Everything save</td>
                <td v-if="purchases.item.config == '{}'"><span class="text-red-500">NO</span> <a :href="'/manage/domain/config?d=' + purchases.item.id_domain">needed to configure</a></td>
                <td>@{{ purchases.item.date }}</td>
                <td class="flex flex-row gap-x-1 items-center">

                  <a :href="'/manage/domain/download?sig=' + purchases.item.signature" class="no-underline">
                    <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                  </a>

                    <a :href="'/manage/domain/config?d=' + purchases.item.id_domain" class="no-underline">
                      <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
                    </a>

                    <a :href="'/manage/domain/stats?d='+purchases.item.id_domain">
                      <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
                    </a>

                    <a :href="'/manage/domain/delete?d='+purchases.item.id_domain" title="Delete domain">
                      <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
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
@endsection

@section('bottom-app')
    <div class="dialogku flex flex-row items-center justify-center bg-gray-900 bg-opacity-50" id="domain_register">
        <div class="flex w-3/4 h-screen m-auto">
            <div class="m-auto w-full">
                <div class="w-full bg-white py-5 px-10 rounded-lg flex flex-col shadow-lg gap-y-5">
                    <p class="font-bold text-xl">Domain Register</p>
                    <form action="/manage/domain/register" method="POST" >
                        @csrf
                        <div>
                            <p>Domain</p>
                            <input id="domain" type="text" name="domain" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating @error('domain') border-red-500 @enderror" required placeholder="domain without http or https">
                        </div>
                        <div>
                          <p>Email result</p>
                          <input id="result_email" type="email" name="result_email" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating @error('result_email') border-red-500 @enderror" required placeholder="your@email.com">
                      </div>
                      
                        <div>
                          <p>Product</p>
                          <select name="purchase_id" class="select2" style="width: 100%">
                            @foreach ($products as $product)
                                <option value="{{ $product->id_purchase }}" > {{ $product->product_name }} - ( {{ $product->version }} )
                                </option>
                            @endforeach
                          </select>
                      </div>
                        <button type="submit" class="px-3 py-2 bg-yellow-500 rounded font-bold mt-3 text-white">Submit</button>
                        <button type="button" class="px-3 py-2 bg-red-700 rounded font-bold mt-3 text-white" onclick="document.getElementById('domain_register').style.display='none';">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  $(document).ready(function() {
    $('.select2').select2(
      {
        theme:'classic',
        width:'resolve'
      }
    );
});
</script>

  <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
  <script>
    new Vue({
      el: '#app',
      vuetify: new Vuetify(),
      mounted(){
        this.getAllPurchases()
      },
      data: {
        purchases: [],
        headers: [
            { text: 'Name', sortable: true, value: 'name'},
            { text: 'Signature Key' , sortable:false},
            { text: 'Domain', sortable: true, value: 'domain'},
            { text: 'Email result' ,sortable: false , value: 'result_email'},
            { text: 'Configured' , sortable:false},
            { text: 'Date', sortable: true, value: 'date'},
            { text: 'Action', sortable: false}
        ],
        pagination: {
            itemsPerPage: 10,
            current: 1,
            total: 0
        },
      },
      methods: {
        getAllPurchases(){
          this.startLoading()
          fetch('/manage/get?page=' + this.pagination.current).then(res => res.json()).then(data => {
            this.purchases = data.PAYLOAD.data
            this.pagination.current = data.PAYLOAD.current_page
            this.pagination.total = data.PAYLOAD.last_page
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
        },
        copyText(value){
          // var ryu = document.getElementById('ryu' + id)
          var ryu = document.createElement('textarea')
          ryu.value = value
          document.body.appendChild(ryu)
          ryu.select();
          document.execCommand('copy')
          document.body.removeChild(ryu)
        }
      }
    })
  </script>
@endsection