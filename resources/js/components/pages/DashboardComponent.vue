<template>
    <div class="dashboard-page">

        <b-modal 
            ref="modal-add"
            scrollable 
            header-class="bg-dark-blue text-white modal-head"
            size="lg"
        >
            <template #modal-header-close>
                <i class="fa-solid fa-xmark"></i>
            </template>
        
            <template #modal-title> Add <b>Doctor</b> </template>

            <div class="form">

                <div class="form-box mt-1">
                    <h6>Doctor's Name: </h6>
                    <p class="text-danger" v-for="(error, index) in doctorFormError.firstName" :key="'d0'+index"> {{error}}</p>
                    <p class="text-danger" v-for="(error, index) in doctorFormError.lastName" :key="'d1'+index"> {{error}}</p>

                    <div class="form-input">
                        <input :class="[{ 'error': doctorFormError.firstName  }, 'mr-15px']" type="text" placeholder="First Name" v-model="doctorForm.firstName">
                        <input  :class="[{ 'error': doctorFormError.lastName  }]" type="text" placeholder="Last Name" v-model="doctorForm.lastName">
                    </div>
                </div>
                
                <div class="form-box">
                    
                    <h6>Clinic's Address: </h6>

                    <p class="text-danger" v-for="(error, index) in doctorFormError.clinicAddress" :key="'d2'+index"> {{error}}</p>

                    <div class="form-input">
                        <input :class="[{ 'error': doctorFormError.clinicAddress }]" type="text" placeholder="Clinic Address" v-model="doctorForm.clinicAddress">
                    </div>
                </div>

                <div class="form-box">

                    <p class="text-danger" v-for="(error, index) in doctorFormError.specialization" :key="'d3'+index"> {{error}}</p>

                    <div class="form-input">
                        <h6 class="mt-1 mr-15px">Specialization: </h6> 
                        <select :class="[{ 'error': doctorFormError.specialization}]" v-model="doctorForm.specialization">

                            <option v-for="(option, index) in options" v-bind:value="option" :key="'opt'+index">
                                {{ option }}
                            </option>
                        </select>
                    </div>
                </div>
                
                <div class="form-box">
                    <p class="text-danger" v-for="(error, index) in doctorFormError.degree" :key="'d4'+index"> {{error}}</p>
                    <div class="form-input">
                        <h6 class="mr-15px"> Degree: </h6>
                        <input :class="[{ 'error': doctorFormError.degree }]" type="text" placeholder="Degree" v-model="doctorForm.degree">
                    </div>
                </div>

                <div class="form-box">
                    <p class="text-danger" v-for="(error, index) in doctorFormError.phone" :key="'d5'+index"> {{error}}</p>

                    <div class="form-input mt-3 mt-lg-0">
                        <h6 class="mr-15px"> Phone: </h6>
                        <input :class="[{ 'error': doctorFormError.phone }]" type="text" placeholder="Phone number" v-model="doctorForm.phone">
                    </div>
                </div>
                
                <div class="form-box">
                    
                    <h6>Picture (optional)</h6>
                    
                    <p class="text-danger" v-for="(error, index) in doctorFormError.photo" :key="'d6'+index"> {{error}}</p>

                    <div class="form-input">
                        <input accept=".png, .jpeg, .jpg," :class="[{ 'error': doctorFormError.photo }, 'form-control']" type="file" ref="picture" @change="addPhotoFile()">
                    </div>

                </div>

                <div class="form-box">
                    
                    <p class="text-danger" v-for="(error, index) in doctorFormError.consultFee" :key="'d7'+index"> {{error}}</p>

                    <div class="form-input">
                        <h6 class="mb-0 mr-15px"> Gender: </h6>
                        <div class='d-flex align-items-center mt-3 mt-lg-0'>
                            <input class="'form-check-input' " type="checkbox" id="flexRadioDefault1" value="true" v-model="doctorForm.gender">
                            <label class="form-check-label mx-3" for="flexRadioDefault1"> Male </label>
                        </div>
                    </div>

                </div>

                <div class="form-box">
                    <h6> Doctor's Consult Fee</h6>
                    
                    <div class="form-input">
                        <input :class="[{ 'error': doctorFormError.consultFee }]" type="text" placeholder="Consult Fee" v-model="doctorForm.consultFee">
                    </div>
                </div>

                <div class="form-box">
                    <h6> Doctor's Email</h6>

                    <p class="text-danger" v-for="(error, index) in doctorFormError.email" :key="'d8'+index"> {{error}}</p>
                    
                    <div class="form-input">
                        <input :class="[{ 'error': doctorFormError.email }]" type="email" placeholder="Doctor's Email" v-model="doctorForm.email">
                    </div>
                </div>

                <!-- <h6>Doctor's Password: </h6>

                <p class="text-danger" v-for="error in doctorFormError.password"> {{error}}</p>
                <p class="text-danger" v-for="error in doctorFormError.password_confirmation"> {{error}}</p>

                <div class="form-input">
                    <input :class="[{ 'error': doctorFormError.password != '' }]" type="password" placeholder="Password" v-model="doctorForm.password">
                    <input :class="[{ 'error': doctorFormError.password_confirmation != '' }, 'mt-2']" type="password" placeholder="Confirm Password" v-model="doctorForm.password_confirmation">
                </div> -->
                
                <div class="form-box">
                    <h6> About</h6>
                    <div class="form-input">
                        <textarea v-model="doctorFormError.about" cols="30" rows="10" class="w-100" style="resize: none;"></textarea>
                    </div>
                </div>
            </div>

            <template #modal-footer> <b-button size="md" variant="outline-success" @click="addDoctor()"> {{modal.operation}} Doctor </b-button> </template>

        </b-modal>

        
        <b-modal 
            ref="doctor-show"
            scrollable 
            header-class="modal-head"
        >
            <template #modal-header-close>
                <i class="fa-solid fa-xmark text-dark"></i>
            </template>
        
            <template #modal-title> Dr <b  style="text-transform:uppercase"> {{doctorForm.firstName}} {{ doctorForm.lastName}}</b> </template>

            <div class="form">
                <div class="form-box mt-1">
                    <h6>Doctor's Name: </h6>
                    <p class="text-danger" v-for="(error, index) in doctorFormError.firstName" :key="'d0'+index"> {{error}}</p>
                    <p class="text-danger" v-for="(error, index) in doctorFormError.lastName" :key="'d1'+index"> {{error}}</p>

                    <div class="form-input">
                        <input :class="[{ 'error': doctorFormError.firstName  }, 'mr-15px']" type="text" placeholder="First Name" v-model="doctorForm.firstName">
                        <input  :class="[{ 'error': doctorFormError.lastName  }]" type="text" placeholder="Last Name" v-model="doctorForm.lastName">
                    </div>
                </div>
                <div class="form-box">
                    
                    <h6>Clinic's Address: </h6>

                    <p class="text-danger" v-for="(error, index) in doctorFormError.clinicAddress" :key="'d2'+index"> {{error}}</p>

                    <div class="form-input">
                        <input :class="[{ 'error': doctorFormError.clinicAddress }]" type="text" placeholder="Clinic Address" v-model="doctorForm.clinicAddress">
                    </div>
                </div>

                <div class="form-box">

                    <p class="text-danger" v-for="(error, index) in doctorFormError.specialization" :key="'d3'+index"> {{error}}</p>

                    <div class="form-input">
                        <h6 class="mt-1 mr-15px">Specialization: </h6> 
                        <select :class="[{ 'error': doctorFormError.specialization}]" v-model="doctorForm.specialization">

                            <option v-for="(option, index) in options" v-bind:value="option" :key="'opt'+index">
                                {{ option }}
                            </option>
                        </select>
                    </div>
                </div>
                
                <div class="form-box">
                    <p class="text-danger" v-for="(error, index) in doctorFormError.degree" :key="'d4'+index"> {{error}}</p>
                    <div class="form-input">
                        <h6 class="mr-15px"> Degree: </h6>
                        <input :class="[{ 'error': doctorFormError.degree }]" type="text" placeholder="Degree" v-model="doctorForm.degree">
                    </div>
                </div>

                <div class="form-box">
                    <p class="text-danger" v-for="(error, index) in doctorFormError.phone" :key="'d5'+index"> {{error}}</p>

                    <div class="form-input mt-3 mt-lg-0">
                        <h6 class="mr-15px"> Phone: </h6>
                        <input :class="[{ 'error': doctorFormError.phone }]" type="text" placeholder="Phone number" v-model="doctorForm.phone">
                    </div>
                </div>
                
                <div class="form-box">
                    
                    <p class="text-danger" v-for="(error, index) in doctorFormError.consultFee" :key="'d7'+index"> {{error}}</p>

                    <div class="form-input">
                        <h6 class="mb-0 mr-15px"> Gender: </h6>
                        <div class='d-flex align-items-center mt-3 mt-lg-0'>
                            <input class="'form-check-input' " type="checkbox" id="flexRadioDefault1" value="true" v-model="doctorForm.gender">
                            <label class="form-check-label mx-3" for="flexRadioDefault1"> Male </label>
                        </div>
                    </div>

                </div>

                <div class="form-box">
                    <h6> Doctor's Consult Fee</h6>
                    
                    <div class="form-input">
                        <input :class="[{ 'error': doctorFormError.consultFee }]" type="text" placeholder="Consult Fee" v-model="doctorForm.consultFee">
                    </div>
                </div>

                <div class="form-box">
                    <h6> Doctor's Email</h6>

                    <p class="text-danger" v-for="(error, index) in doctorFormError.email" :key="'d8'+index"> {{error}}</p>
                    
                    <div class="form-input">
                        <input :class="[{ 'error': doctorFormError.email }]" type="email" placeholder="Doctor's Email" v-model="doctorForm.email">
                    </div>
                </div>

                <!-- <h6>Doctor's Password: </h6>

                <p class="text-danger" v-for="error in doctorFormError.password"> {{error}}</p>
                <p class="text-danger" v-for="error in doctorFormError.password_confirmation"> {{error}}</p>

                <div class="form-input">
                    <input :class="[{ 'error': doctorFormError.password != '' }]" type="password" placeholder="Password" v-model="doctorForm.password">
                    <input :class="[{ 'error': doctorFormError.password_confirmation != '' }, 'mt-2']" type="password" placeholder="Confirm Password" v-model="doctorForm.password_confirmation">
                </div> -->
                
                <div class="form-box">
                    <h6> About</h6>
                    <div class="form-input">
                        <textarea v-model="doctorFormError.about" cols="30" rows="10" class="w-100" style="resize: none;"></textarea>
                    </div>
                </div>
            </div>

            <template #modal-footer> 
                <b-button size="md" variant="danger" @click="deleteDoctor()"> <i class="fa-solid fa-trash"></i>  Delete </b-button> 
                <b-button size="md" variant="warning" @click="updateDoctor()"> <i class="fa-solid fa-pen-to-square"></i> Update </b-button> 
            </template>

        </b-modal>

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

        <doctor-component ref="doctor-component" v-if="user.isAdmin" :doctor-data="doctorData">
        </doctor-component>

    </div>
</template>

<script>
    export default {
        props:['userData', 'doctors'],

        data() {
            return { 
                user: JSON.parse(this.userData),

                doctorData: JSON.parse(this.doctors),

                alertBackground: '',
                error: '',
                dismissSecs: 5,
                dismissCountDown: 0,

                modal:{
                    title: '',
                    operation: '',
                },

                options:[
                    'Pediatricians' ,
                    'Cardiologist',
                    'Lung specialist (Pulmonologists)',
                    'Cancer Specialist (Oncologist)',
                    'Endocrinologist',
                    'Dentistry (Dentist)',
                    'Dietitian & Nutrition Specialist',
                    'Eye Specialist (Ophthalmologists)',
                    'Ears, Nose, Throat (ENT) Specialist',
                    'Gastroenterologist',
                    'Gynecologists & Obstetricians',
                    'Hematologist',
                    'Homeopathic', //
                    'Medicine',
                    'Neuromedicine',
                    'Neuro-Surgery',
                    'Oncology (Cancer)',
                    'Orthopedic Surgeons',
                    'Physical Medicine',
                    'Pain Medicine',
                    'Plastic Surgery',
                    'Physiotherapists',
                    'Psychiatrist',
                    'Sex & Skin VD (Dermatology)',
                    'Thyroid & Hormone',
                    'Urology Specialist',
                    'Urologists (Nephrology)',
                    'Unani & Ayurveda',
                    'Vascular Surgery',
                ],

                doctorForm: {
                    firstName: '',
                    lastName: '',
                    clinicAddress: '',
                    specialization: 'Pediatricians',
                    phone: '',
                    gender: false,
                    consultFee: '',
                    email: '',
                    degree: '',
                    photo: '',
                    about: '',
                    id: '',
                    id_fb: '',
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

            populateFormError(data){
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
                    specialization: 'Pediatricians',
                    phone: '',
                    gender: false,
                    consultFee: '',
                    email: '',
                    degree: '',
                    photo: '',
                    about: '',
                    id: '',
                    id_fb: '',
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
                this.doctorForm.id_fb = data.id_fb
                this.doctorForm.degree = data.degree
                
            },


            successDeleteDoctor(message, id){
                let length = this.doctorData.length

                for(var i = 0; i < length; i++){
                    if(this.doctorData[i].id == id){
                        this.doctorData.splice(i, 1);
                        break;
                    }
                }
                
                this.$refs['doctor-show'].hide()
                this.error = message
                this.alertBackground = "success"
                this.showAlert()
                
                
            },

            showModal(operation, id){
                if( operation == "Show"){
                    this.populateDoctorForm(id)
                }
                else if(operation != this.modal.operation){
                    this.neutralizeDoctorForm()
                }

                this.modal.operation = operation

                if( operation == "Show"){
                    this.$refs['doctor-show'].show()
                }
                else{
                    this.$refs['modal-add'].show()
                }
            },

            
            countDownChanged(dismissCountDown) { this.dismissCountDown = dismissCountDown },
            showAlert() { this.dismissCountDown = this.dismissSecs },
            addPhotoFile(){ this.doctorForm.photo = this.$refs['picture'].files[0] },

            deleteDoctor(){ 
                this.$refs['doctor-component'].deleteDoctor(this.doctorForm.id, this.doctorForm.id_fb, this.doctorForm.firstName + " " + this.doctorForm.lastName) 
            },
            addDoctor(){ this.$refs['doctor-component'].addDoctor(this.doctorForm) },
            updateDoctor(){ this.$refs['doctor-component'].updateDoctor(this.doctorForm) },
        },

        // mouted(){
        //     console.log(this.announcements)
        // },
        
    }
</script>