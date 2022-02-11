<template>
    <div class="patient-page">

        <div class="patient-content text-dark-green">
            <div class="search-holder">

                <label>Search: </label>
                <input type="text" name="" id="">

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
    export default {
        props:[],

        data() {
            return { 
                items: [
                    {
                        icon: '',
                        name: "John Andrew F. Dacumos",
                        birtday: "June 30, 2022",
                        age: "22",
                    },
                    
                    {
                        name: "Robby Christian De Leon",
                        birtday: "June 02, 2022",
                        age: "22",
                    },
                ],

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
                        key: 'birtday', 
                        label: 'Birthdate', 
                        sortable: true,
                    },

                ],
            }
        },

        methods: {
        },

    }
</script>