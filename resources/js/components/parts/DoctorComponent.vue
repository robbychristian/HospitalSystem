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
                <b-button type="button" @click="showModal('Add', 0)" class="btn btn-add"> <i class="fa-solid fa-plus"></i> Doctor</b-button>

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
            stacked="sm"
        >
        
        <template #cell(action)="data">
            <button class="btn bg-primary btn-sm text-white" @click="showModal('Show', data.item.id)"><i class="fa-solid fa-eye"></i></button>
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
                        // tdClass: 'text-hidden-ellipsis',
                    },

                    {
                        key: 'specialization', 
                        label: 'Specialization', 
                        sortable: true,
                        // tdClass: 'text-hidden-ellipsis',
                    },

                    {
                        key: 'clinicAddress', 
                        label: 'Address', 
                        sortable: true,
                        // tdClass: 'text-hidden-ellipsis',
                    },

                    {
                        key: 'email', 
                        label: 'Email', 
                        sortable: true,
                        // tdClass: 'text-hidden-ellipsis',
                    },
                    
                    {
                        key: 'action', 
                        label: 'Action', 
                    },
                ],
            }
        },

        methods:{
            showModal(isDoctor, operation, id){
                this.$parent.showModal(isDoctor, operation, id);
            },
            
            addDoctor(doctorForm){
                const filePhoto = new FormData();
                filePhoto.append('degree', doctorForm.degree)
                filePhoto.append('photo', doctorForm.photo)
                filePhoto.append('firstName', doctorForm.firstName)
                filePhoto.append('lastName', doctorForm.lastName)
                filePhoto.append('clinicAddress', doctorForm.clinicAddress)
                filePhoto.append('specialization', doctorForm.specialization)
                filePhoto.append('gender', doctorForm.gender)
                filePhoto.append('phone', doctorForm.phone)
                filePhoto.append('consultFee', doctorForm.consultFee)
                filePhoto.append('email', doctorForm.email)
                filePhoto.append('about', doctorForm.about)
                
                let self = this
                
                axios.post('/doctor/add/', filePhoto)
                .then( function (response){
                    let data = response.data
                    console.log(data)
                    
                    if(data.hasError){
                        self.$parent.populateFormError(data)
                        self.$parent.closeLoading('modal-add', true)
                    }
                    else if (data.error){
                        self.$parent.error = data.error;
                        self.$parent.alertBackground = "danger"
                        self.$parent.closeLoading('modal-add', false)
                        self.$parent.showAlert()
                    }
                    else{
                        self.$parent.doctorData.push( JSON.parse(data.doctor) )
                        self.$parent.error = data.success;
                        self.$parent.alertBackground = "success"
                        self.$parent.neutralizeDoctorForm()
                        self.$parent.closeLoading('modal-add', false)
                        self.$parent.showAlert()
                    }
                    // return response.data
                })
                .catch( function (error){
                    console.log(error);
                })
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
                            self.$parent.$refs['loading-modal'].hide()
                            self.$parent.successDeleteDoctor( response.data.success, id)
                        }
                    })
                    .catch( function (error){
                        console.log(error);
                    })
                }

            },

            updateDoctor(doctorForm){
                let withEmail = 0;
                let length = this.doctors.length
                for(var i = 0; i < length; i++){
                    if(this.doctors[i].id == doctorForm.id){
                        if(this.doctors[i].email != doctorForm.email) withEmail = 1
                        break;
                    }
                }
                const form = new FormData();
                form.append('degree', doctorForm.degree)
                form.append('photo', doctorForm.photo)
                form.append('firstName', doctorForm.firstName)
                form.append('lastName', doctorForm.lastName)
                form.append('clinicAddress', doctorForm.clinicAddress)
                form.append('specialization', doctorForm.specialization)
                form.append('gender', doctorForm.gender)
                form.append('phone', doctorForm.phone)
                form.append('consultFee', doctorForm.consultFee)
                form.append('email', doctorForm.email)
                form.append('about', doctorForm.about)
                form.append('id', doctorForm.id)
                form.append('withEmail', withEmail)

                let self = this

                axios.post('/doctor/update/', form)
                .then( function (response){
                    let data = response.data
                    
                    if(data.hasError){
                        self.populateAddError(data)
                        self.$parent.closeLoading('doctor-show', true)
                    }
                    else if (data.error){
                        self.$parent.error = data.error;
                        self.$parent.alertBackground = "danger"
                        self.$parent.closeLoading('doctor-show', false)
                        self.$parent.showAlert()
                    }
                    else{

                        let length = self.$parent.doctorData.length

                        for(var i = 0; i < length; i++){
                            if(self.$parent.doctorData[i].id == doctorForm.id){
                                self.$parent.doctorData.splice(i, 1, JSON.parse(data.doctor));
                                break;
                            }
                        }
                        
                        self.$parent.error = data.success;
                        self.$parent.alertBackground = "success"
                        self.$parent.neutralizeDoctorForm()
                        self.$parent.closeLoading('doctor-show', false)
                        self.$parent.showAlert()
                    }
                })
                .catch( function (error){
                    console.log(error);
                })
            },
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