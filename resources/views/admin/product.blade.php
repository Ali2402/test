@extends('layouts.app-auth')

@section('css')
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/vuetify.datatable.css">
@endsection

@section('content')
<div v-if="msg != ''" class="col-span-1 md:col-span-2 relative py-3 pl-4 mb-5 pr-10 leading-normal bg-gray-100 text-gray-700 rounded-lg" role="alert">
  <p>@{{ msg }}</p>
</div>
<div class="flex flex-row w-full items-center gap-x-3 mb-3">
    <a href="/admin">
        <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-green-700 bg-green-100 border border-green-300 ">
            <div class="text-xs font-normal leading-none max-w-full flex-initial">Admin Dashboard</div>
        </div>
    </a>
    <a href="#" @click="dialog_product = true">
    <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-yellow-700 bg-yellow-100 border border-yellow-300 ">
        <div class="text-xs font-normal leading-none max-w-full flex-initial">Add product</div>
    </div>
</a>



</div>
<div class="flex flex-col gap-y-5">
    <p class="font-semibold text-xl">Products</p>
    <div class="bg-white rounded-lg shadow-md h-24">
      <v-data-table :headers="headers" :items="products" item-key="name" class="shadow text-left" hide-default-footer>
          <template v-slot:item="products">
              <tr>
                <td>@{{ products.item.date }}</td>
                <td>@{{ products.item.name }}</td>
                <td>@{{ products.item.version }}</td>
                <td>@{{ products.item.desc }}</td>
                <td>@{{ products.item.price }}</td>
                <td>
                    <a href="#" class="no-underline" @click="goToDetailProduct(products.item)">
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
@endsection

@section('bottom-app')
<div v-if="dialog_product" class="dialogku flex flex-row items-center justify-center bg-gray-900 bg-opacity-50" style="display: block !important">
  <div class="flex w-3/4 h-screen m-auto">
      <div class="m-auto w-full">
          <div class="w-full bg-white py-5 px-10 rounded-lg flex flex-col shadow-lg gap-y-5">
              <p class="font-bold text-xl">@{{ is_detail ? 'Info Product' : 'Add Product' }}</p>
              <div class="flex flex-col gap-y-3">
                  <div>
                      <p>Name</p>
                      <input v-model="name" id="name" type="text" name="name" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating" required autocomplete="name">
                  </div>
                  <div>
                      <p>Version</p>
                      <input v-model="version" id="version" type="text" name="version" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating" required autocomplete="version">
                  </div>
                  <div>
                      <p>Description</p>
                      <textarea v-model="desc" id="desc" type="desc" name="desc" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating" required autocomplete="desc"></textarea>
                  </div>
                  <div>
                      <p>Price</p>
                      <input v-model="price" id="price" type="number" name="price" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating" autocomplete="price">
                  </div>
                  <div>
                      <p>Filename</p>
                      <input v-model="filename" id="filename" type="text" name="filename" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating" autocomplete="filename">
                  </div>
                  <div class="flex flex-row gap-x-2 mt-3 justify-between">
                    <div>
                      <button type="submit" class="px-3 py-2 bg-yellow-500 rounded font-semibold text-white" @click="saveProduct">Submit</button>
                      <button type="button" class="px-3 py-2 bg-red-500 rounded font-semibold text-white" @click="closeDialogProduct">Close</button>
                    </div>
                    <a v-if="is_detail" :href="'/admin/product/delete/' + id">
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
        this.getAllProducts()
      },
      data: {
        dialog_product: false,
        is_detail: false,
        msg: '',
        id: '',
        name: '',
        version: '',
        desc: '',
        price: '',
        filename: '',
        products: [],
        headers: [
            { text: 'Date', sortable: true},
            { text: 'Name', sortable: true},
            { text: 'Version', sortable: true},
            { text: 'Desc', sortable: true},
            { text: 'Price', sortable: false},
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
          fetch('/admin/product/get?page=' + this.pagination.current).then(res => res.json()).then(data => {
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
        },
        saveProduct(){
          var url = ''
          this.startLoading()
          this.dialog_product = false
          if(this.is_detail) url = '/admin/product/update'
          else url = '/admin/product/add'
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
                  name: this.name,
                  version: this.version,
                  desc: this.desc,
                  price: this.price,
                  filename: this.filename
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
        goToDetailProduct(product){
          this.dialog_product = true
          this.is_detail = true
          this.id = product.id
          this.name = product.name
          this.version = product.version
          this.desc = product.desc
          this.price = product.price
          this.filename = product.filename
        },
        closeDialogProduct(){
          this.dialog_product = false
          this.is_detail = false
          this.id = ''
          this.name = ''
          this.version = ''
          this.desc = ''
          this.price = ''
          this.filename = ''
        },
      }
    })
  </script>
@endsection