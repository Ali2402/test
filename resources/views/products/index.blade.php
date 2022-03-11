@extends('layouts.app-auth')

@section('css')
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/vuetify.datatable.css">
@endsection

@section('content')
@if(session('msg'))
    <div class="col-span-1 md:col-span-2 relative py-3 pl-4 pr-10 leading-normal @if(preg_match('/Oops/', session('msg'))) bg-red-100 text-red-700 @else bg-green-100 text-green-700 @endif rounded-lg" role="alert">
        <p>{{ session()->get('msg') }}</p>
    </div>
@endif
<div class="col-span-1 md:col-span-2 relative py-3 pl-4 pr-10 leading-normal bg-blue-100 text-blue-700  rounded-lg" role="alert">
  <p>
    <b>Important information</b>
    <li><i>Products that you have purchased cannot be refunded.</i></li>
    <li><i>register your domain in the manage menu after that the script will be automatically downloaded</i> </li></p>
    <li><i>How to use? after download the script, upload it to cpanel or other hosting and just access it.</i></li>
</div>
<br><br>

<div class="flex flex-col gap-y-5 mt-3">
    <p class="font-semibold text-xl">Products</p>
    <div class="bg-white rounded-lg shadow-md">
      <v-data-table :headers="headers" :items="products" item-key="name" class="shadow text-left" hide-default-footer>
          <template v-slot:item="products">
              <tr>
                <td>@{{ products.item.date }}</td>
                <td>@{{ products.item.name }}</td>
                <td>@{{ products.item.version }}</td>
                <td>@{{ products.item.price }}</td>
                <td class="flex flex-row items-center gap-x-1">
                    <a :href="'/products/detail/' + products.item.id" class="no-underline">
                        <svg class="w-6 h-6 stroke-current text-white bg-blue-500 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </a>
                    <a :href="'/products/buy/' + products.item.id" class="no-underline">
                        <svg class="w-6 h-6 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>
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

@section('js')
  <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
  <script>
    new Vue({
      el: '#app',
      vuetify: new Vuetify(),
      mounted(){
        this.getAllProducts()
      },
      data: {
        products: [],
        headers: [
            { text: 'Date', sortable: true, value: 'date'},
            { text: 'Name', sortable: true, value: 'name'},
            { text: 'Version', sortable: true, value: 'version'},
            { text: 'Price', sortable: true, value: 'price'},
            { text: 'Action', sortable: false}
        ],
        pagination: {
            itemsPerPage: 10,
            current: 1,
            total: 0
        },
      },
      methods: {
        getAllProducts(){
          this.startLoading()
          fetch('/products/get?page=' + this.pagination.current).then(res => res.json()).then(data => {
            this.products = data.PAYLOAD.data
            this.pagination.current = data.PAYLOAD.current_page
            this.pagination.total = data.PAYLOAD.last_page
            this.endLoading()
          })
        },
        onPageChange(){
          this.getAllProducts()
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