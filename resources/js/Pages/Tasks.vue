<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />

    <div class="bg-slate-50">

        <div v-if="canLogin" class="sm:right-0 p-6 text-end">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                Dashboard
            </Link>

            <template v-else>
                <Link :href="route('login')" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    Log in
                </Link>

                <Link v-if="canRegister" :href="route('register')" class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    Register
                </Link>
            </template>
        </div>

        <div class="p-8">

            <div>
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First name</label>
                        <input v-model="task.name" type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Name" required>
                    </div>
                    <div>
                        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input  v-model="task.email" type="text" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Email" required>
                    </div>
                </div>

                <div class="mb-6">    
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your message</label>
                    <textarea v-model="task.text" id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your thoughts here..."></textarea>
                </div>

                <button type="button" v-on:click="createTask" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Alternative</button>

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
                                <span v-if="task.confirmed">Выполнено</span>
                                <span v-else>В процессе</span>
                                
                            </td>
                            <td class="px-6 py-4">
                                <span v-if="task.created_at !== task.updated_at">отредактировано администратором</span>
                                <span v-else>{{ task.confirmed }}</span>
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

</template>

<script>

    import axios from 'axios'

    export default {

        data(){

            return{
                tasks: {},
                task: {
                    name: '',
                    email: '',
                    text: '',
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

            createTask(){
           
                axios.post('/api/task', this.task)

                .then(response => {
                
                    console.log(response)
                    this.getTasks(1) 

                    this.task.name = ''
                    this.task.email = ''
                    this.task.text = ''
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

        },

    }

</script>