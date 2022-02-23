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
                <p class="text-danger" v-for="error in doctorFormError.firstName"> {{error}}</p>
                <p class="text-danger" v-for="error in doctorFormError.lastName"> {{error}}</p>

                <div class="form-input">
                    <input :class="[{ 'error': doctorFormError.firstName  }]" type="text" placeholder="First Name" v-model="doctorForm.firstName">
                    <input :class="[{ 'error': doctorFormError.lastName  }, 'mt-2']" type="text" placeholder="Last Name" v-model="doctorForm.lastName">
                </div>
                
                <h6>Clinic's Address: </h6>

                <p class="text-danger" v-for="error in doctorFormError.clinicAddress"> {{error}}</p>

                <div class="form-input">
                    <input :class="[{ 'error': doctorFormError.clinicAddress }]" type="text" placeholder="Clinic Address" v-model="doctorForm.clinicAddress">
                </div>

                <h6>Doctor's Specialization and degree: </h6>
                
                <p class="text-danger" v-for="error in doctorFormError.specialization"> {{error}}</p>
                <p class="text-danger" v-for="error in doctorFormError.degree"> {{error}}</p>

                <div class="form-input flex-column">
                    <input :class="[{ 'error': doctorFormError.specialization}]" type="text" placeholder="Specialization" v-model="doctorForm.specialization">
                    <input accept=".pdf, .doc, .docx, .pptx"  :class="[{ 'error': doctorFormError.degree }, 'form-control mt-2']" type="file" ref="degree" @change="addDoctorFile('degree', true)">
                </div>

                <h6> Doctor's Phone and Picture</h6>
                
                <p class="text-danger" v-for="error in doctorFormError.phone"> {{error}}</p>
                <p class="text-danger" v-for="error in doctorFormError.photo"> {{error}}</p>

                <div class="form-input flex-column">
                    <input :class="[{ 'error': doctorFormError.phone }, 'w-100']" type="text" placeholder="Phone number" v-model="doctorForm.phone">
                    <input accept=".png, .jpeg, .jpg," :class="[{ 'error': doctorFormError.photo }, 'form-control mt-2']" type="file" ref="picture" @change="addDoctorFile('picture', false)">
                </div>

                <h6> Doctor's Gender and Consult Fee</h6>
                
                <p class="text-danger" v-for="error in doctorFormError.consultFee"> {{error}}</p>

                <div class="form-input">
                    <div class='d-flex align-items-center'>
                        <input class="'form-check-input' " type="checkbox" id="flexRadioDefault1" value="true" v-model="doctorForm.gender">
                        <label class="form-check-label mx-3" for="flexRadioDefault1"> Male </label>
                    </div>

                    <input :class="[{ 'error': doctorFormError.consultFee }, 'mt-2']" type="text" placeholder="Consult Fee" v-model="doctorForm.consultFee">
                </div>

                <h6> Doctor's Email</h6>

                <p class="text-danger" v-for="error in doctorFormError.email"> {{error}}</p>
                
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
                Announcement Form here
            </div>

            <template #modal-footer="ok">
                <b-button size="md" variant="outline-success" @click="addOrUpdate()"> {{modal.operation}} Doctor </b-button>
            </template>

        </b-modal>

        <doctor-component ref="doctor-component" v-if="user.isAdmin" :doctor-data="doctorData">
        </doctor-component>

        <announcement-component ref="announcement-component" :announcement-data="announcementsData">
        </announcement-component>

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
                updateAnnouncement(){ alert("update Announcement")},
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
                    // let response = this.$refs['announcement-component'].addAnnouncement(this.announcementForm)
                    // this.$refs['announcement-component'].addAnnouncement()
                    alert("ADD announcement")
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
                            console.log( JSON.parse(data.doctor) )
                            self.doctorData.push( JSON.parse(data.doctor) )
                            console.log(self.doctorData)
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
 
            showModal(isDoctor, operation, id){
                if( operation == "Update"){
                    if (isDoctor) this.populateDoctorForm(id)
                    // else this.populateAnnouncementForm(id)
                }
                else if(operation != this.modal.operation){
                    if (isDoctor) this.neutralizeDoctorForm()
                    // else this.neutralizeAnouncementForm()
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

        mouted(){
            console.log(this.announcements)
        },
        
    }
</script>