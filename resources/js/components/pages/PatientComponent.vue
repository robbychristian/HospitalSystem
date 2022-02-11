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
            </b-table>
        </div>
        

    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        
        props:['patient'],

        mounted(){

            let data = JSON.parse(this.patient)
            
            data.forEach(this.toItems)

        },

        data() {
            return { 
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

                // {
                //     name: "Robby Christian De Leon",
                //     birtdate: "June 02, 2022",
                //     age: "22",
                // },

                let data = {
                    name: item.fname + " " + item.lname,
                    birtdate: item.birtdate,
                    age: moment().diff(item.birtdate, 'years'),
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