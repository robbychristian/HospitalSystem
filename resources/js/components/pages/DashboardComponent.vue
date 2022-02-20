<template>
    <div class="dashboard-page">
        
        <div class="alert-top">
            <b-alert
                :show="dismissCountDown"
                :variant="alertBackground"
                @dismissed="dismissCountDown=0"
                @dismiss-count-down="countDownChanged"
                fade
                
            >
                {{ error }} 
            </b-alert>
        </div>

        <b-modal 
            ref="add-modal"
            scrollable 
            :header-class="addModal.headerClass"
        >
            <template #modal-header-close>
                <i class="fa-solid fa-xmark"></i>
            </template>
        
            <template #modal-title>
                Add <b>{{addModal.title}}</b>
            </template>

            <div class="form" v-if="addModal.isDoctor">
                <h6>Doctor's Name: </h6>
                <p class="text-danger" v-for="error in addFormError.firstName"> {{error}}</p>
                <p class="text-danger" v-for="error in addFormError.lastName"> {{error}}</p>

                <div class="form-input">
                    <input :class="[{ 'error': addFormError.firstName  }]" type="text" placeholder="First Name" v-model="addForm.firstName">
                    <input :class="[{ 'error': addFormError.lastName  }, 'mt-2']" type="text" placeholder="Last Name" v-model="addForm.lastName">
                </div>
                
                <h6>Clinic's Address: </h6>

                <p class="text-danger" v-for="error in addFormError.clinicAddress"> {{error}}</p>

                <div class="form-input">
                    <input :class="[{ 'error': addFormError.clinicAddress }]" type="text" placeholder="Clinic Address" v-model="addForm.clinicAddress">
                </div>

                <h6>Doctor's Specialization and degree: </h6>
                
                <p class="text-danger" v-for="error in addFormError.specialization"> {{error}}</p>
                <p class="text-danger" v-for="error in addFormError.degree"> {{error}}</p>

                <div class="form-input flex-column">
                    <input :class="[{ 'error': addFormError.specialization}]" type="text" placeholder="Specialization" v-model="addForm.specialization">
                    <input accept=".pdf, .doc, .docx, .pptx"  :class="[{ 'error': addFormError.degree }, 'form-control mt-2']" type="file" ref="degree" @change="addDoctorFile('degree', true)">
                </div>

                <h6> Doctor's Phone and Picture</h6>
                
                <p class="text-danger" v-for="error in addFormError.phone"> {{error}}</p>
                <p class="text-danger" v-for="error in addFormError.photo"> {{error}}</p>

                <div class="form-input flex-column">
                    <input :class="[{ 'error': addFormError.phone }, 'w-100']" type="text" placeholder="Phone number" v-model="addForm.phone">
                    <input accept=".png, .jpeg, .jpg," :class="[{ 'error': addFormError.photo }, 'form-control mt-2']" type="file" ref="picture" @change="addDoctorFile('picture', false)">
                </div>

                <h6> Doctor's Gender and Consult Fee</h6>
                
                <p class="text-danger" v-for="error in addFormError.consultFee"> {{error}}</p>

                <div class="form-input">
                    <div class='d-flex align-items-center'>
                        <input class="'form-check-input' " type="checkbox" id="flexRadioDefault1" value="true" v-model="addForm.gender">
                        <label class="form-check-label mx-3" for="flexRadioDefault1"> Male </label>
                    </div>

                    <input :class="[{ 'error': addFormError.consultFee }, 'mt-2']" type="text" placeholder="Consult Fee" v-model="addForm.consultFee">
                </div>

                <h6> Doctor's Email</h6>

                <p class="text-danger" v-for="error in addFormError.email"> {{error}}</p>
                
                <div class="form-input">
                    <input :class="[{ 'error': addFormError.email }]" type="email" placeholder="Doctor's Email" v-model="addForm.email">
                </div>

                <!-- <h6>Doctor's Password: </h6>

                <p class="text-danger" v-for="error in addFormError.password"> {{error}}</p>
                <p class="text-danger" v-for="error in addFormError.password_confirmation"> {{error}}</p>

                <div class="form-input">
                    <input :class="[{ 'error': addFormError.password != '' }]" type="password" placeholder="Password" v-model="addForm.password">
                    <input :class="[{ 'error': addFormError.password_confirmation != '' }, 'mt-2']" type="password" placeholder="Confirm Password" v-model="addForm.password_confirmation">
                </div> -->
                
                <h6> About</h6>
                <div class="form-input">
                    <textarea v-model="addFormError.about" cols="30" rows="10" class="w-100" style="resize: none;"></textarea>
                </div>
            </div>

            <div class="form" v-else>
                Announcement Form here
            </div>

            <template #modal-footer="ok">
                <b-button v-if="addModal.isDoctor" size="md" variant="outline-success" @click="addDoctor()"> Add Doctor </b-button>

                <b-button v-else size="md" variant="outline-success" @click="addAnnouncement()"> Add Announcement </b-button>
            </template>

        </b-modal>

        <doctor-component ref="doctor-component" v-if="user.isAdmin" :doctor-data="doctorData">
        </doctor-component>

        <!-- <announcement-component ref="announcement-component" :announcement-data="announcementsData">
        </announcement-component> -->

    </div>
</template>

<script>
    export default {
        props:['userData', 'doctors', 'announcements'],

        data() {
            return { 
                user: JSON.parse(this.userData),

                doctorData: JSON.parse(this.doctors),
                announcementsData: JSON.parse(this.announcements),

                alertBackground: '',
                error: '',
                dismissSecs: 5,
                dismissCountDown: 0,

                addModal:{
                    headerClass: '',
                    title: '',
                    isDoctor: null,
                },

                addForm: {
                    firstName: '',
                    lastName: '',
                    clinicAddress: '',
                    specialization: '',
                    phone: '',
                    gender: false,
                    consultFee: '',
                    email: '',
                    degree: '',
                    photo: '',
                    about: '',
                    // password: '',
                    // password_confirmation: '',
                },

                addFormError: {
                    firstName: '',
                    lastName: '',
                    clinicAddress: '',
                    specialization: '',
                    phone: '',
                    consultFee: '',
                    email: '',
                    degree: '',
                    photo: '',
                    // password: '',
                    // password_confirmation: '',
                },
            }
        },

        methods:{
            showAddModal(isDoctor){
                if(isDoctor){
                    this.addModal.headerClass = "bg-dark-blue text-white modal-head"
                    this.addModal.title = "Doctor"
                    this.addModal.isDoctor = isDoctor
                }
                else{
                    this.addModal.headerClass = "bg-warning modal-head"
                    this.addModal.title = "Announcement"
                    this.addModal.isDoctor = isDoctor
                }
                this.$refs['add-modal'].show()
            },

            addDoctor(){

                let self = this
                let response = this.$refs['doctor-component'].addDoctor(this.addForm)

                response.then( function (data){
                    if(data.hasError){
                        self.populateAddError(data)
                    }
                    else if (data.error){
                        self.$refs['add-modal'].hide()
                        self.error = data.error;
                        self.alertBackground = "danger"
                        self.showAlert()
                    }
                    else{
                        console.log( JSON.parse(data.doctor) )
                        self.doctorData.push( JSON.parse(data.doctor) )
                        console.log(self.doctorData)
                        self.$refs['add-modal'].hide()
                        self.error = data.success;
                        self.alertBackground = "success"
                        self.neutralizeAddForm()
                        self.showAlert()
                    }
                })
            },

            successDeleteDoctor(message, id){
                let length = this.doctorData.length

                for(var i = 0; i < length; i++){
                    console.log( this.doctorData[i].id == id )
                    if(this.doctorData[i].id == id){
                        this.doctorData.splice(i, 1);
                        break;
                    }
                }
                
                this.error = message
                this.alertBackground = "success"
                this.showAlert()
                
            },
            
            addDoctorFile(refName, isDegree){
                if(isDegree)
                    this.addForm.degree = this.$refs[refName].files[0]
                else
                    this.addForm.photo = this.$refs[refName].files[0]
            },

            populateAddError(data){
                this.addFormError.firstName = data.firstName 
                this.addFormError.lastName = data.lastName
                this.addFormError.clinicAddress = data.clinicAddress
                this.addFormError.specialization = data.specialization
                this.addFormError.phone = data.phone
                this.addFormError.consultFee = data.consultFee
                this.addFormError.email = data.email
                this.addFormError.degree = data.degree
                this.addFormError.photo = data.photo
                // this.addFormError.password = data.password
                // this.addFormError.password_confirmation = data.password_confirmation
            },

            neutralizeAddForm(){
                this.addForm = {
                    firstName: '',
                    lastName: '',
                    clinicAddress: '',
                    specialization: '',
                    phone: '',
                    gender: false,
                    consultFee: '',
                    email: '',
                    degree: '',
                    photo: '',
                    about: '',
                    // password: '',
                    // password_confirmation: '',
                }

                this.addFormError =  {
                    firstName: '',
                    lastName: '',
                    clinicAddress: '',
                    specialization: '',
                    phone: '',
                    consultFee: '',
                    email: '',
                    degree: '',
                    photo: '',
                    // password: '',
                    // password_confirmation: '',
                }
            },

            addAnnouncement(){
                // let response = this.$refs['doctor-component'].addDoctor(this.addForm)
                this.$refs['announcement-component'].addAnnouncement()
            },

            countDownChanged(dismissCountDown) { this.dismissCountDown = dismissCountDown },
            showAlert() { this.dismissCountDown = this.dismissSecs },
        },

        mouted(){
            console.log(this.announcements)
        },
        
    }
</script>