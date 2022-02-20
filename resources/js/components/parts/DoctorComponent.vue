<template>
    <div class="manage-table">
        <b-pagination
            v-model="currentPage"
            :total-rows="rows"
            :per-page="perPage"
            aria-controls="doctor-table"
            class="mb-0 mt-3"
        ></b-pagination>

        <div class="tab doctor-tab">
            <h4> Manage <b>Doctors</b></h4>

            <div class="tab-action">
                <b-button type="button" @click="showAddModal(true)" class="btn btn-add"> <i class="fa-solid fa-plus"></i> Doctor</b-button>

                <input type="text" v-model="keyword">
                <select v-model="filterBy">
                    <option value="name">Name</option>
                    <option value="clinicAddress">Address</option>
                    <option value="email">Email</option>
                    <option value="specialization">Specialization</option>
                </select>
            </div>
        </div>

        <b-table
            id="doctor-table"
            outlined
            :fields="fields" 
            :items="items" 
            :per-page="perPage"
            :current-page="currentPage"
        >
        
        <template #cell(action)="data">
            <button class="btn bg-warning" @click="updateDoctor(data.item.id, data.item.id_fb, data.item.name)"><i class="fa-solid fa-pencil"></i></button>
            <button class="btn bg-danger" @click="deleteDoctor(data.item.id, data.item.id_fb, data.item.name)"><i class="fa-solid fa-trash"></i></button>
        </template>


        </b-table>
    </div>
</template>

<script>
    export default {
        props:['doctorData'],

        data() {
            return { 
                perPage: 5,
                currentPage: 1,

                doctors: this.doctorData,
                keyword: '',
                filterBy: 'name',
                
                fields: [
                    {
                        key: 'name', 
                        label: 'Name', 
                        sortable: true,
                    },

                    {
                        key: 'specialization', 
                        label: 'Specialization', 
                        sortable: true,
                    },

                    {
                        key: 'clinicAddress', 
                        label: 'Address', 
                        sortable: true,
                    },

                    {
                        key: 'email', 
                        label: 'Email', 
                        sortable: true,
                    },
                    
                    {
                        key: 'action', 
                        label: 'Action', 
                    },
                ],
            }
        },

        methods:{
            showAddModal(isDoctor){
                this.$parent.showAddModal(isDoctor);
            },

            showUpdateModal(isDoctor){
                this.$parent.showUpdateModal(isDoctor);
            },

            addDoctor(addForm){

                const filePhoto = new FormData();
                filePhoto.append('degree', addForm.degree)
                filePhoto.append('photo', addForm.photo)
                filePhoto.append('firstName', addForm.firstName)
                filePhoto.append('lastName', addForm.lastName)
                filePhoto.append('clinicAddress', addForm.clinicAddress)
                filePhoto.append('specialization', addForm.specialization)
                filePhoto.append('gender', addForm.gender)
                filePhoto.append('phone', addForm.phone)
                filePhoto.append('consultFee', addForm.consultFee)
                filePhoto.append('email', addForm.email)
                filePhoto.append('about', addForm.about)

                return axios.post('/doctor/add/', filePhoto)
                .then( function (response){
                    return response.data
                })
                .catch( function (error){
                    console.log(error);
                });
            },

            deleteDoctor(id, id_fb, name){
                if( confirm("Do you really want to delete? " + name) ){
                    let self = this 
                    axios.post('/doctor/delete/', {
                        params:{
                            id: id,
                            id_fb: id_fb,
                        },
                    })
                    .then( function (response){
                        if(response.data.success){
                            self.$parent.successDeleteDoctor( response.data.success, id)
                        }
                    })
                    .catch( function (error){
                        console.log(error);
                    });
                }

            },

            updateDoctor(id, id_fb, name){},
        },

        computed:{
            items () {
                return this.keyword
                    ? this.doctors.filter(
                        item => item[this.filterBy].toLowerCase().includes(this.keyword.toLowerCase()) 
                    )
                    : this.doctors
            },

            rows() {
                return this.items.length
            },
        },

    }
</script>