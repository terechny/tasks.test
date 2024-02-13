<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="mt-8">
            <div class=" sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <div>

                            <div class="p-2">

                            <div>

                                <div class="mb-6">    
                                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your message</label>
                                    <textarea v-model="task.text" id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your thoughts here..."></textarea>

                                    <div class="mt-4 mb-4">
                                    <input v-model="task.confirmed" id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Default checkbox</label>
                                    </div>

                                </div>

                                <button type="button" v-on:click="updateTask" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                    Update
                                </button>

                            </div>

                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Name
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Text
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Email
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Status
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Redacted
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Redact
                                            </th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(task, index) in tasks.data" :key="index" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ task.name }}
                                            </th>
                                            <td class="px-6 py-4">
                                                {{ task.text }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ task.email }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ task.confirmed }}
                                            </td>
                                            <td class="px-6 py-4">
                                                Redacted
                                            </td>
                                            <td class="px-6 py-4">                                                                             
                                                <button v-on:click="getTask(task.id)" type="button" class="px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                    Redact
                                                </button>
                                            </td>                                            
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="p-4">

                                    <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4" aria-label="Table navigation">
                                        <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
                                            <li v-for="(link, index) in tasks.links" :key="index">
                                                <a  v-on:click="paginate(link.url)" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" :class="{ 'bg-blue-50': link.active }">
                                                    {{ link.label }}
                                                </a>
                                            </li>                            
                                        </ul>
                                        <span>
                                            <label for="small" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Direction</label>
                                            <select @change="getTasks" v-model="selectedDirection" id="small" class="block w-40 p-2 mb-6 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option v-for="(direction, index) in sortDirection" :key="index" v-bind:value="direction.value">{{direction.label}}</option>
                                            </select>  
                                        </span>                      
                                        <span>       
                                            <label for="small" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Column</label>
                                            <select @change="getTasks" v-on:select="getTasks" v-model="selectedType" id="small" class="block w-40 p-2 mb-6 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option v-for="(type, index) in sortTypes" :key="index" v-bind:value="type.value">{{type.label}}</option>
                                            </select> 
                                        </span>                           
                                    </nav> 

                                </div>
                            </div>

                            </div>

                            <button v-on:click="getTasks">start</button>

                        </div>    

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
     
</template>


<script>

    import axios from 'axios'

    export default {

        data(){

            return{
                tasks: {},
                task: {
                    id:0,
                    name: '',
                    email: '',
                    text: '',
                    confirmed: false,
                },
                sortDirection:[
                    { label:'ASC',value:'asc'},
                    { label:'DESC', value:'desc'},
                ],
                sortTypes:[
                    { label:'Name',value:'name'},
                    { label:'Email', value:'email'},
                    { label:'Status', value:'confirmed'},
                ],                
                selectedDirection:'asc',
                selectedType:'name'
            }
        },
        mounted(){

            this.getTasks(1)    
        },
        methods:{

            getTasks(p){

                let param = {   
                   page:p,
                   direction: this.selectedDirection,
                   column: this.selectedType
                }

                axios.get('/api/task', {params: param }).then(res=>{ 

                    this.tasks = res.data.data

                }).catch(err => {

                    console.log(err);                           
                }) 

               console.log('get tasks')
            },

            updateTask(){
           
                console.log(this.task.confirmed)

                const formData = {
                    text: this.task.text,
                    confirmed: this.task.confirmed,
                };

                axios.put('/api/task/' + this.task.id, formData)
                .then(response => {
                
                    this.getTasks(1)
                })
                .catch(error => {
                
                    console.error(error);
                });

            },

            paginate(url){

                if(url){
                    
                    let page = url.split('=')[1]
                    this.getTasks(page)
                }
            },

            getTask(id){

                axios.get('/api/task/' + id,).then(res=>{ 

                    this.task = res.data.data

                    console.log(res.data.data)
                }).catch(err => {

                    console.log(err);                           
                }) 

                console.log('get task')                
            }
        },
    }

</script>
