@extends('layouts.app-auth')

@section('css')
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/vuetify.datatable.css">
@endsection

@section('content')
@if(session('msg'))
    <div class="col-span-1 md:col-span-2 relative py-3 pl-4 my-5 pr-10 leading-normal @if(preg_match('/Oops/', session('msg'))) bg-red-100 text-red-700 @else bg-green-100 text-green-700 @endif rounded-lg" role="alert">
        <p>{{ session()->get('msg') }}</p>
    </div>
@endif
<div class="flex flex-row w-full items-center gap-x-3 mb-3">
    <a href="/admin">
        <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-green-700 bg-green-100 border border-green-300 ">
            <div class="text-xs font-normal leading-none max-w-full flex-initial">Admin Dashboard</div>
        </div>
    </a>
    <a href="#" onclick="document.getElementById('voucher_add').style.display='block'">
    <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-yellow-700 bg-yellow-100 border border-yellow-300 ">
        <div class="text-xs font-normal leading-none max-w-full flex-initial">Add voucher</div>
    </div>
</a>



</div>
<div class="flex flex-col gap-y-5">
    <p class="font-semibold text-xl">Vouchers</p>
    <div class="bg-white rounded-lg shadow-md h-24">
      <v-data-table :headers="headers" :items="vouchers" item-key="name" class="shadow text-left" hide-default-footer>
          <template v-slot:item="vouchers">
              <tr>
                <td>@{{ vouchers.item.created_at }}</td>
                <td>@{{ vouchers.item.author }}</td>
                <td>@{{ vouchers.item.code }}</td>
                <td>@{{ vouchers.item.amount }}</td>
                <td>@{{ vouchers.item.status }}</td>
                <td>
                    <a :href="'/admin/voucher/delete/' + vouchers.item.id" class="no-underline">
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
    <div class="dialogku flex flex-row items-center justify-center bg-gray-900 bg-opacity-50" id="voucher_add">
        <div class="flex w-3/4 h-screen m-auto">
            <div class="m-auto w-full">
                <div class="w-full bg-white py-5 px-10 rounded-lg flex flex-col shadow-lg gap-y-5">
                    <p class="font-bold text-xl">Add voucher</p>
                    <form action="/admin/voucher/add" method="POST" >
                      @csrf
                        <div>
                            <p>User ID</p>
                            <select name="user" class="select2" style="width: 100%">
                              @foreach ($users as $user)
                                  <option value="{{ $user->id }}" > {{ $user->username }} - {{ $user->email }}
                                  </option>
                              @endforeach
                            </select>
                        </div>
                        <div class="mt-4">
                            <p>Amount</p>
                            <input name="amount" type="number" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating">
                        </div>
                        <button type="submit" class="px-3 py-2 bg-yellow-500 rounded font-bold mt-3 text-white">Submit</button>
                        <button type="button" class="px-3 py-2 bg-red-700 rounded font-bold mt-3 text-white" onclick="document.getElementById('voucher_add').style.display='none';">Close</button>
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
        this.getAllVouchers()
      },
      data: {
        vouchers: [],
        headers: [
            { text: 'Date', sortable: true},
            { text: 'Author', sortable: true},
            { text: 'Code', sortable: true},
            { text: 'Amount', sortable: true},
            { text: 'Status', sortable: false},
            { text: 'Action', sortable: false}
        ],
        pagination: {
            itemsPerPage: 10,
            current: 1,
            total: 0
        },
      },
      methods: {
        getAllVouchers(){
          this.startLoading()
          fetch('/admin/api/getvoucher?page=' + this.pagination.current).then(res => res.json()).then(data => {
            this.vouchers = data.PAYLOAD.data
            this.pagination.current = data.PAYLOAD.current_page
            this.pagination.total = data.PAYLOAD.last_page
            this.endLoading()
          })
        },
        onPageChange(){
          this.getAllVouchers()
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