<template>
    <div class="manage-table">
        
        <div class="tab announcement-tab">
            <h4> Manage <b>Announcement</b></h4>

            <div class="tab-action">
                <b-button type="button" @click="showAddModal(false)" class="btn btn-add"> <i class="fa-solid fa-plus"></i> Post</b-button>

                <input type="text" v-model="keyword">

                <!-- <select v-model="filterBy">
                    <option value="name">Name</option>
                    <option value="clinicAddress">Address</option>
                    <option value="email">Email</option>
                    <option value="specialization">Specialization</option>
                </select> -->

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
        props:['announcementData'],

        data() {
            return { 
                
                perPage: 5,
                currentPage: 1,
                
                keyword: '',
                filterBy: 'name',

                announcements: this.announcementData == '' ? [] : this.announcementData,
                
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

            addAnnouncement(){
                alert("HAHAHA")
                // const filePhoto = new FormData();
                // filePhoto.append('degree', addForm.degree)
                // filePhoto.append('photo', addForm.photo)
                // filePhoto.append('firstName', addForm.firstName)
                // filePhoto.append('lastName', addForm.lastName)
                // filePhoto.append('clinicAddress', addForm.clinicAddress)
                // filePhoto.append('specialization', addForm.specialization)
                // filePhoto.append('gender', addForm.gender)
                // filePhoto.append('phone', addForm.phone)
                // filePhoto.append('consultFee', addForm.consultFee)
                // filePhoto.append('email', addForm.email)
                // filePhoto.append('about', addForm.about)

                // return axios.post('/doctor/add/', filePhoto)
                // .then( function (response){
                //     return response.data
                // })
                // .catch( function (error){
                //     console.log(error);
                // });
            },

            deleteAnnouncement(id, id_fb, title){
                if( confirm("Do you really want to delete? " + title) ){
                    // let self = this 
                    // axios.post('/doctor/delete/', {
                    //     params:{
                    //         id: id,
                    //         id_fb: id_fb,
                    //     },
                    // })
                    // .then( function (response){
                    //     if(response.data.success){
                    //         self.$parent.successDeleteDoctor( response.data.success, id)
                    //     }
                    // })
                    // .catch( function (error){
                    //     console.log(error);
                    // });
                }

            },

            updateAnnouncement(id, id_fb, name){},
        },

        computed:{
            items(){
                return this.keyword
                    ? this.announcements.filter(
                        item => item[this.filterBy].toLowerCase().includes(this.keyword.toLowerCase()) 
                    )
                    : this.announcements
            },

            rows() {
                return this.items.length
            },
        },

    }
</script>