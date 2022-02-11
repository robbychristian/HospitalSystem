<template>
    <div class="patient-page">

        <div class="patient-content text-dark-green">
            <div class="search-holder">

                <label>Search: </label>
                <input type="text" v-model="keyword" placeholder="Search by name">

            </div>
            
            <b-table
                class="text-dark-green"
                :fields="fields" 
                :items="items"
            >
            
                <template  #cell(icon)>
                    <i class="fa-solid fa-face-grin-beam"></i>
                </template>

                <template  #cell(action)>
                    <button class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                    <button class="btn btn-secondary"><i class="fa-solid fa-pen"></i></button>
                </template>

            </b-table>
        </div>
        

    </div>
</template>

<script>
    export default {
        
        props:['patient'],

        mounted(){

            console.log(this.user.isAdmin)

            if(this.user.isAdmin){
                console.log(this.fields)
                this.fields.push(
                    {key: 'action', label: 'Action'}
                )
            }

            let data = JSON.parse(this.patient)
            
            data.forEach(this.toItems)

        },

        data() {
            return { 
                user: {
                    isAdmin: false,
                },

                keyword: '',

                static: [],

                fields: [
                    {key: 'icon', label: ' '},
                    {
                        key: 'name', 
                        label: 'Name', 
                        sortable: true,
                    },

                    {
                        key: 'age', 
                        label: 'Age', 
                        sortable: true,
                    },

                    {
                        key: 'birtdate', 
                        label: 'Birthdate', 
                        sortable: true,
                    },

                ],
            }
        },

        methods: {

            toItems(item, index){


                let data = {
                    name: item.fname + " " + item.lname,
                    birtdate: item.birtdate,
                    age: item.age,
                }

                this.static.push(data)
            },

        },

        computed: {
            items () {
                return this.keyword
                    ? this.static.filter(
                        item => item.name.toLowerCase().includes(this.keyword.toLowerCase()) 
                    )
                    : this.static
            }
        }

    }
</script>