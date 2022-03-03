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
            ref="modal"
            scrollable 
            :header-class="modal.headerClass"
        >
            <template #modal-header-close>
                <i class="fa-solid fa-xmark"></i>
            </template>
        
            <template #modal-title>
                {{modal.operation}} <b>{{modal.title}}</b>
            </template>

            <div class="form" v-if="modal.isDoctor">
                <h6>Doctor's Name: </h6>
                <p class="text-danger" v-for="(error, index) in doctorFormError.firstName" :key="'d0'+index"> {{error}}</p>
                <p class="text-danger" v-for="(error, index) in doctorFormError.lastName" :key="'d1'+index"> {{error}}</p>

                <div class="form-input">
                    <input :class="[{ 'error': doctorFormError.firstName  }]" type="text" placeholder="First Name" v-model="doctorForm.firstName">
                    <input :class="[{ 'error': doctorFormError.lastName  }, 'mt-2']" type="text" placeholder="Last Name" v-model="doctorForm.lastName">
                </div>
                
                <h6>Clinic's Address: </h6>

                <p class="text-danger" v-for="(error, index) in doctorFormError.clinicAddress" :key="'d2'+index"> {{error}}</p>

                <div class="form-input">
                    <input :class="[{ 'error': doctorFormError.clinicAddress }]" type="text" placeholder="Clinic Address" v-model="doctorForm.clinicAddress">
                </div>

                <h6>Doctor's Specialization and degree: </h6>
                
                <p class="text-danger" v-for="(error, index) in doctorFormError.specialization" :key="'d3'+index"> {{error}}</p>
                <p class="text-danger" v-for="(error, index) in doctorFormError.degree" :key="'d4'+index"> {{error}}</p>

                <div class="form-input flex-column">
                    <input :class="[{ 'error': doctorFormError.specialization}]" type="text" placeholder="Specialization" v-model="doctorForm.specialization">
                    <input :class="[{ 'error': doctorFormError.degree }, 'form-control mt-2']" type="text" placeholder="Degree" v-model="doctorForm.degree">
                </div>

                <h6> Doctor's Phone and Picture</h6>
                
                <p class="text-danger" v-for="(error, index) in doctorFormError.phone" :key="'d5'+index"> {{error}}</p>
                <p class="text-danger" v-for="(error, index) in doctorFormError.photo" :key="'d6'+index"> {{error}}</p>

                <div class="form-input flex-column">
                    <input :class="[{ 'error': doctorFormError.phone }, 'w-100']" type="text" placeholder="Phone number" v-model="doctorForm.phone">
                    <input accept=".png, .jpeg, .jpg," :class="[{ 'error': doctorFormError.photo }, 'form-control mt-2']" type="file" ref="picture" @change="addDoctorFile('picture', false)">
                </div>

                <h6> Doctor's Gender and Consult Fee</h6>
                
                <p class="text-danger" v-for="(error, index) in doctorFormError.consultFee" :key="'d7'+index"> {{error}}</p>

                <div class="form-input">
                    <div class='d-flex align-items-center'>
                        <input class="'form-check-input' " type="checkbox" id="flexRadioDefault1" value="true" v-model="doctorForm.gender">
                        <label class="form-check-label mx-3" for="flexRadioDefault1"> Male </label>
                    </div>

                    <input :class="[{ 'error': doctorFormError.consultFee }, 'mt-2']" type="text" placeholder="Consult Fee" v-model="doctorForm.consultFee">
                </div>

                <h6> Doctor's Email</h6>

                <p class="text-danger" v-for="(error, index) in doctorFormError.email" :key="'d8'+index"> {{error}}</p>
                
                <div class="form-input">
                    <input :class="[{ 'error': doctorFormError.email }]" type="email" placeholder="Doctor's Email" v-model="doctorForm.email">
                </div>

                <!-- <h6>Doctor's Password: </h6>

                <p class="text-danger" v-for="error in doctorFormError.password"> {{error}}</p>
                <p class="text-danger" v-for="error in doctorFormError.password_confirmation"> {{error}}</p>

                <div class="form-input">
                    <input :class="[{ 'error': doctorFormError.password != '' }]" type="password" placeholder="Password" v-model="doctorForm.password">
                    <input :class="[{ 'error': doctorFormError.password_confirmation != '' }, 'mt-2']" type="password" placeholder="Confirm Password" v-model="doctorForm.password_confirmation">
                </div> -->
                
                <h6> About</h6>
                <div class="form-input">
                    <textarea v-model="doctorFormError.about" cols="30" rows="10" class="w-100" style="resize: none;"></textarea>
                </div>
            </div>

            <div class="form" v-else>

                <h6>Title: </h6>
                <p class="text-danger" v-for="(error, index) in announceFormError.title" :key="'a0'+index"> {{error}}</p>
                <div class="form-input">
                    <input :class="[{ 'error': announceFormError.title  }]" type="text" placeholder="Title" v-model="announceForm.title">
                </div>
                
                <h6>Category: </h6>
                <p class="text-danger" v-for="(error, index) in announceFormError.category" :key="'a1'+index"> {{error}}</p>
                <div class="form-input">
                    <input :class="[{ 'error': announceFormError.category  }]" type="text" placeholder="Category" v-model="announceForm.category">
                </div>

                <h6>Picture (optional): </h6>

                <p class="text-danger" v-for="(error, index) in announceFormError.photoUrl" :key="'a2'+index"> {{error}}</p>
                <div class="form-input">
                    <input accept=".png, .jpeg, .jpg,"  :class="[{ 'error': announceFormError.photoUrl }, 'form-control']" type="file" ref="photoUrl" @change="announcePhoto()">
                </div>


                <h6>Body: </h6>
                <p class="text-danger" v-for="(error, index) in announceFormError.body" :key="'a3'+index"> {{error}}</p>
                <div class="form-input">
                    <textarea :class="[{ 'error': announceFormError.body  }]" v-model="announceForm.body" cols="30" rows="10" class="w-100" style="resize: none;"></textarea>
                </div>


            </div>

            <template #modal-footer>
                <b-button size="md" variant="outline-success" @click="addOrUpdate()"> {{modal.operation}} {{ modal.isDoctor ? 'Doctor' : 'Announcement' }} </b-button>
            </template>

        </b-modal>

        <doctor-component ref="doctor-component" v-if="user.isAdmin" :doctor-data="doctorData">
        </doctor-component>
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

                modal:{
                    headerClass: '',
                    title: '',
                    isDoctor: null,
                    operation: '',
                },

                announceForm: {
                    title: '',
                    category: '',
                    photoUrl: '',
                    body: '',
                    id: '',
                    oldPhoto: '',
                },

                announceFormError: {
                    title: '',
                    category: '',
                    photoUrl: '',
                    body: '',
                },
                
                doctorForm: {
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
                    id: '',
                    // password: '',
                    // password_confirmation: '',
                },

                doctorFormError: {
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

            // Update Announcement Methods
                updateAnnouncement(){ 
                    let self = this
                    let response = this.$refs['announcement-component'].updateAnnouncement(this.announceForm)
                    
                    response.then( function (data){
                        if(data.hasError){
                            self.populateError(data)
                        }
                        else if (data.error){
                            self.$refs['modal'].hide()
                            self.error = data.error;
                            self.alertBackground = "danger"
                            self.showAlert()
                        }
                        else{

                            let length = self.announcementsData.length

                            for(var i = 0; i < length; i++){
                                if(self.announcementsData[i].id == self.announceForm.id){
                                    self.announcementsData.splice(i, 1, JSON.parse(data.announce));
                                    break;
                                }
                            }
                            
                            self.$refs['modal'].hide()
                            self.error = data.success;
                            self.alertBackground = "success"
                            self.neutralizeAnnounceForm()
                            self.showAlert()
                        }
                    })
                },
            // End

            // Update Announcement Methods
                
                updateDoctor(){
                    
                    let self = this
                    let response = this.$refs['doctor-component'].updateDoctor(this.doctorForm)

                    response.then( function (data){
                        if(data.hasError){
                            self.populateAddError(data)
                        }
                        else if (data.error){
                            self.$refs['modal'].hide()
                            self.error = data.error;
                            self.alertBackground = "danger"
                            self.showAlert()
                        }
                        else{

                            let length = self.doctorData.length

                            for(var i = 0; i < length; i++){
                                if(self.doctorData[i].id == self.doctorForm.id){
                                    self.doctorData.splice(i, 1, JSON.parse(data.doctor));
                                    break;
                                }
                            }
                            
                            self.$refs['modal'].hide()
                            self.error = data.success;
                            self.alertBackground = "success"
                            self.neutralizeDoctorForm()
                            self.showAlert()
                        }
                    })
                },
            // End

            // Add Announcement Methods
                addAnnouncement(){
                    let self = this
                    let response = this.$refs['announcement-component'].addAnnouncement(this.announceForm)

                    response.then( function (data){
                        if(data.hasError){
                            self.populateError(data)
                        }
                        else if (data.error){
                            self.$refs['modal'].hide()
                            self.error = data.error;
                            self.alertBackground = "danger"
                            self.showAlert()
                        }
                        else{
                            self.announcementsData.push( JSON.parse(data.announce) )
                            self.$refs['modal'].hide()
                            self.error = data.success;
                            self.alertBackground = "success"
                            self.neutralizeAnnounceForm()
                            self.showAlert()
                        }
                    })
                },

                announcePhoto(){
                    this.announceForm.photoUrl = this.$refs['photoUrl'].files[0]
                },

                neutralizeAnnounceForm(){
                    this.announceForm = {
                        title: '',
                        category: '',
                        photoUrl: '',
                        body: '',
                        id: '',
                    }

                    this.announceFormError = {
                        title: '',
                        category: '',
                        photoUrl: '',
                        body: '',
                    }
                },

                populateError(data){
                    this.announceFormError.title = data.title 
                    this.announceFormError.category = data.category
                    this.announceFormError.photoUrl = data.photo
                    this.announceFormError.body = data.body
                },
            // End

            // Add Doctor Methods
                addDoctor(){
                    let self = this
                    let response = this.$refs['doctor-component'].addDoctor(this.doctorForm)

                    response.then( function (data){
                        if(data.hasError){
                            self.populateAddError(data)
                        }
                        else if (data.error){
                            self.$refs['modal'].hide()
                            self.error = data.error;
                            self.alertBackground = "danger"
                            self.showAlert()
                        }
                        else{
                            
                            self.doctorData.push( JSON.parse(data.doctor) )
                            self.$refs['modal'].hide()
                            self.error = data.success;
                            self.alertBackground = "success"
                            self.neutralizeDoctorForm()
                            self.showAlert()
                        }
                    })
                },

                addDoctorFile(refName, isDegree){
                    if(isDegree)
                        this.doctorForm.degree = this.$refs[refName].files[0]
                    else
                        this.doctorForm.photo = this.$refs[refName].files[0]
                },

                populateAddError(data){
                    this.doctorFormError.firstName = data.firstName 
                    this.doctorFormError.lastName = data.lastName
                    this.doctorFormError.clinicAddress = data.clinicAddress
                    this.doctorFormError.specialization = data.specialization
                    this.doctorFormError.phone = data.phone
                    this.doctorFormError.consultFee = data.consultFee
                    this.doctorFormError.email = data.email
                    this.doctorFormError.degree = data.degree
                    this.doctorFormError.photo = data.photo
                    // this.doctorFormError.password = data.password
                    // this.doctorFormError.password_confirmation = data.password_confirmation
                },

                neutralizeDoctorForm(){
                    this.doctorForm = {
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

                    this.doctorFormError =  {
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
            // End

            successDeleteDoctor(message, id){
                let length = this.doctorData.length

                for(var i = 0; i < length; i++){
                    if(this.doctorData[i].id == id){
                        this.doctorData.splice(i, 1);
                        break;
                    }
                }
                
                this.error = message
                this.alertBackground = "success"
                this.showAlert()
                
            },

            successDeleteAnnounce(message, id){
                let length = this.announcementsData.length

                for(var i = 0; i < length; i++){
                    if(this.announcementsData[i].id == id){
                        this.announcementsData.splice(i, 1);
                        break;
                    }
                }
                
                this.error = message
                this.alertBackground = "success"
                this.showAlert()
                
            },
 
            showModal(isDoctor, operation, id){
                if( operation == "Update"){
                    if (isDoctor) this.populateDoctorForm(id)
                    else this.populateAnnouncementForm(id)
                }
                else if(operation != this.modal.operation){
                    if (isDoctor) this.neutralizeDoctorForm()
                    else this.neutralizeAnnounceForm()
                }

                if(isDoctor){
                    this.modal.headerClass = "bg-dark-blue text-white modal-head"
                    this.modal.title = "Doctor"
                }
                else{
                    this.modal.headerClass = "bg-warning modal-head"
                    this.modal.title = "Announcement"
                }
                this.modal.isDoctor = isDoctor
                this.modal.operation = operation
                this.$refs['modal'].show()
            },

            populateDoctorForm(id){
                let length = this.doctorData.length
                let data = ''

                for(var i = 0; i < length; i++){
                    if(this.doctorData[i].id == id){
                        data = this.doctorData[i]
                        break;
                    }
                }   

                this.doctorForm.firstName = data.fname 
                this.doctorForm.lastName = data.lname
                this.doctorForm.clinicAddress = data.clinicAddress
                this.doctorForm.specialization = data.specialization
                this.doctorForm.phone = data.phone
                this.doctorForm.consultFee = data.consultFee
                this.doctorForm.email = data.email
                this.doctorForm.id = id
                this.doctorForm.degree = data.degree
                
            },

            populateAnnouncementForm(id){
                let length = this.announcementsData.length
                let data = ''

                for(var i = 0; i < length; i++){
                    if(this.announcementsData[i].id == id){
                        data = this.announcementsData[i]
                        break;
                    }
                }  

                this.announceForm.title = data.title
                this.announceForm.category = data.category
                this.announceForm.body = data.body
                this.announceForm.id = id     
                this.announceForm.oldPhoto = data.photoUrl
            },

            addOrUpdate(){
                if( this.modal.operation == "Add" && this.modal.isDoctor ){ this.addDoctor() }
                else if( this.modal.operation == "Update" && this.modal.isDoctor ){ this.updateDoctor() }
                else if( this.modal.operation == "Add") { this.addAnnouncement() }
                else { this.updateAnnouncement() }
            },
            countDownChanged(dismissCountDown) { this.dismissCountDown = dismissCountDown },
            showAlert() { this.dismissCountDown = this.dismissSecs },
        },

        // mouted(){
        //     console.log(this.announcements)
        // },
        
    }
</script>