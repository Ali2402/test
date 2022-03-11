@extends('layouts.app-auth')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
<link rel="stylesheet" href="/css/vuetify.datatable.css">
@endsection

@section('content')
    <div>
        <div class="col-span-1 md:col-span-2 relative py-3 pl-4 pr-10 leading-normal bg-blue-100 text-blue-700  rounded-lg" role="alert">
            <p>
              <b>Important information</b>
              <li><i>report if there is a problem with your product, we will immediately make updates.</i></li>
              <li><i>no spam! if it happens then we will block your account</i> </li></p>
              
          </div><br><br>
        <div class="flex flex-row w-full items-center gap-x-3 mb-3">
            <a href="#" onclick="document.getElementById('add_issues').style.display='block'">
                <div class="flex justify-center items-center pt-3 pb-2 px-5 bg-white rounded-full text-yellow-700 bg-yellow-100 border border-yellow-300 ">
                    <div class="text-xs font-normal leading-none max-w-full flex-initial">Add issues</div>
                </div>
            </a>
        </div>
    </div>
    <div class="flex flex-col gap-y-5">
        <p class="font-semibold text-xl">Issues</p>
        <div class="bg-white rounded-lg shadow-md">
            <v-data-table :headers="headers" :items="issues" item-key="name" class="shadow text-left" hide-default-footer>
                <template v-slot:item="issues">
                    <tr>
                        <td>@{{ issues.item.date }}</td>
                        <td>@{{ issues.item.title }}</td>
                        <td>@{{ issues.item.content }}</td>
                        <td v-if="issues.item.status == 1">Pending</td>
                        <td v-if="issues.item.status == 2" class="text-yellow-500">Proccess</td>
                        <td v-if="issues.item.status == 3" class="text-green-500">Fixed</td>
                        <td>
                            <a :href="'/issues/detail/' + issues.item.id" class="no-underline">
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
            <div class=" w-3/4 m-auto">
                <v-pagination class="mb-2" v-model="pagination.current" :length="pagination.total" @input="onPageChange"></v-pagination>
            </div>
        </div>
    </div>
@endsection

@section('bottom-app')
    <div class="dialogku flex flex-row items-center justify-center bg-gray-900 bg-opacity-50" id="add_issues">
        <div class="flex w-3/4 h-screen m-auto">
            <div class="m-auto w-full">
                <div class="w-full bg-white py-5 px-10 rounded-lg flex flex-col shadow-lg gap-y-5">
                    <p class="font-bold text-xl">Issues / Bug report</p>
                    <form action="/issues/add" method="POST" >

                        @csrf
                        <div>
                            <p>Title</p>
                            <input id="title" type="text" name="title" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating @error('title') border-red-500 @enderror" required autocomplete="title" placeholder="Bug on product ....">
                        </div>
                        <div class="mt-4">
                            <p>Description</p>
                            <textarea id="description" type="text" name="description" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating @error('description') border-red-500 @enderror" required autocomplete="title" ></textarea>
                        </div>
                        <input type="hidden" name="author" value="@php echo DB::table('users')->where('id',session()->get('user_id'))->first()->username @endphp" >

                        <button type="submit" class="px-3 py-2 bg-yellow-500 rounded font-bold mt-3 text-white">Submit</button>
                        <button type="button" class="px-3 py-2 bg-red-700 rounded font-bold mt-3 text-white" onclick="document.getElementById('add_issues').style.display='none';">Close</button>
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
                }
            }
        })
    </script>


@endsection