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
                            <input class="'form-check-input' " type="radio" id="flexRadioDefault1" value="true" v-model="doctorForm.gender">
                            <label class="form-check-label mx-3" for="flexRadioDefault1"> Male </label>
                        </div>
                        <div class='d-flex align-items-center mt-3 mt-lg-0'>
                            <input class="'form-check-input' " type="radio" id="flexRadioDefault2" value="false" v-model="doctorForm.gender">
                            <label class="form-check-label mx-3" for="flexRadioDefault2"> Female </label>
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

                <div class="form-box">
                    <h6>Prescription Info: </h6>

                    <p class="text-danger" v-for="(error, ind) in doctorFormError.lic" :key="ind+'lic'"> {{error}}</p>
                    <p class="text-danger" v-for="(error, ind) in doctorFormError.ptr" :key="ind+'ptr'"> {{error}}</p>

                    <div class="form-input flex-wrap">
                        <input :class="[{ 'error': doctorFormError.lic}]" type="text" placeholder="Lic No." v-model="doctorForm.lic">
                        <input :class="[{ 'error': doctorFormError.ptr },]" type="text" placeholder="PTR No." v-model="doctorForm.ptr">
                        <input type="text" placeholder="S2 No. (Optional)" v-model="doctorForm.s2">
                    </div>
                </div>
                
                <div class="form-box">
                    <h6> About</h6>
                    <div class="form-input">
                        <textarea v-model="doctorFormError.about" cols="30" rows="10" class="w-100" style="resize: none;"></textarea>
                    </div>
                </div>
            </div>

            <template #modal-footer> <b-button size="md" variant="success" @click="addDoctor()"> {{modal.operation}} Doctor </b-button> </template>

        </b-modal>

        <!-- Loading Modal -->
            <b-modal 
                ref="loading-modal"
                scrollable 
                no-close-on-esc
                no-close-on-backdrop
                hide-header-close
                header-class="border-0 text-dark-green"
                hide-footer 
                centered 
            >
                <template #modal-title> 
                    <div>
                        <h4> <span  style="margin: 0px 1rem 0px 0px;"> Loading please wait </span> <i class="fa-solid fa-spinner fa-spin"></i> </h4>
                    </div>
                </template>

                <div class="d-flex justify-content-center">
                    <img src="/img/Loading.gif" alt="loading">
                </div>

                <template #modal-footer> 
                    <div></div>
                </template>

            </b-modal>
        <!-- END -->
        
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
                            <input class="'form-check-input' " type="radio" id="flexRadioDefault1" value="true" v-model="doctorForm.gender">
                            <label class="form-check-label mx-3" for="flexRadioDefault1"> Male </label>
                        </div>
                        <div class='d-flex align-items-center mt-3 mt-lg-0'>
                            <input class="'form-check-input' " type="radio" id="flexRadioDefault2" value="false" v-model="doctorForm.gender">
                            <label class="form-check-label mx-3" for="flexRadioDefault2"> Female </label>
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

                <div class="form-box">
                    <h6>Prescription Info: </h6>

                    <p class="text-danger" v-for="(error, ind) in doctorFormError.lic" :key="ind+'lic'"> {{error}}</p>
                    <p class="text-danger" v-for="(error, ind) in doctorFormError.ptr" :key="ind+'ptr'"> {{error}}</p>

                    <div class="form-input flex-wrap">
                        <input :class="[{ 'error': doctorFormError.lic}]" type="text" placeholder="Lic No." v-model="doctorForm.lic">
                        <input :class="[{ 'error': doctorFormError.ptr },]" type="text" placeholder="PTR No." v-model="doctorForm.ptr">
                        <input type="text" placeholder="S2 No. (Optional)" v-model="doctorForm.s2">
                    </div>
                </div>
                
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

        <div class="reviews" v-if="!user.isAdmin">
             <h5 class="mt-3 text-dark-green"> Dr. {{user.name}} Review</h5>
             
             <div class="review-graph" >
                <apexchart v-if="hasReviews" type="donut" width="400" height="400" :options="chartOptions" :series="series"></apexchart>

                <h4 v-else>  You haven't recieve a review yet</h4>
             </div>
        </div>

        <div class="feedbacks mb-3" v-if="!user.isAdmin">

            <div v-if="feedbacks.length">
                <h5 class="mt-3 mb-0"> Today's Feedbacks</h5>
                <div class="feedbacks-holder">

                    <div v-for="(feedback, index) in feedbacks" class="feedbacks-box" :key="index">
                        <div class="head">
                            <img :src="feedback.img">
                            <div>
                                <h6> {{feedback.name}} </h6>
                                <div class="review-details">
                                    <h5> {{feedback.star}}: <i class="fa-solid fa-star"></i></h5>
                                </div>
                            </div>
                        </div>

                        <div class="body">
                            <p> {{feedback.comment}}</p>
                        </div>
                    </div>
                </div>  
            </div>

            <h5 class="mb-0"> You have no feedbacks for today</h5>
        </div>

        <doctor-component ref="doctor-component" v-if="user.isAdmin" :doctor-data="doctorData">
        </doctor-component>

    </div>
</template>

<script>
    import moment from "moment";

    export default {
        props:['userData', 'doctors', 'reviewData'],

        data() {
            return { 

                reviews: JSON.parse(this.reviewData),
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

                feedbacks:[ ],

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
                    lic: '',
                    ptr: '',
                    s2: '',
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
                    lic: '',
                    ptr: '',
                },

                chartOptions: {
                    chart: {
                        type: 'donut',
                    },

                    labels: ["1 Star", "2 Star", "3 Star", "4 Star", "5 Star"],
                    
                    plotOptions: {
                        pie: {
                            donut: {
                                labels: {
                                    show: true,
                                    total: {
                                        showAlways: true,
                                        show: true
                                    }
                                }
                            }
                        }
                    },

                },
                hasReviews: false,
                series: [],
            }
        },

        methods:{
            
            // Loading Modal Related
                openLoading(modal){
                    this.$refs[modal].hide()
                    this.$refs['loading-modal'].show()
                },

                closeLoading(modal, hasError){
                    this.$refs['loading-modal'].hide()
                    if(hasError)
                        this.$refs[modal].show()
                },

            // END

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
                this.doctorFormError.lic = data.lic
                this.doctorFormError.ptr = data.ptr
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
                    lic: '',
                    ptr: '',
                    s2: '',
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
                    lic: '',
                    ptr: '',
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
                this.doctorForm.gender = data.gender == 0 ? false : true 
                this.doctorForm.lastName = data.lname
                this.doctorForm.clinicAddress = data.clinicAddress
                this.doctorForm.specialization = data.specialization
                this.doctorForm.phone = data.phone
                this.doctorForm.consultFee = data.consultFee
                this.doctorForm.email = data.email
                this.doctorForm.id = id
                this.doctorForm.id_fb = data.id_fb
                this.doctorForm.degree = data.degree
                this.doctorForm.lic = data.lic
                this.doctorForm.ptr = data.ptr
                this.doctorForm.s2 = data.s2
                
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
                this.$refs['loading-modal'].show()
                setTimeout(
                    this.$refs['doctor-component'].deleteDoctor(this.doctorForm.id, this.doctorForm.id_fb, this.doctorForm.firstName + " " + this.doctorForm.lastName),
                    3000
                )
            },
            addDoctor(){ 
                this.openLoading('modal-add')
                setTimeout(
                    this.$refs['doctor-component'].addDoctor(this.doctorForm),
                    3000
                )
            },
            updateDoctor(){ 
                this.openLoading('doctor-show')
                setTimeout(
                    this.$refs['doctor-component'].updateDoctor(this.doctorForm),
                    3000
                )
            },
        },

        mounted(){
            this.hasReviews = this.reviews.length

            if(this.hasReviews){
                let series = [0, 0, 0, 0, 0]
                let length = this.hasReviews

                for(let i = 0; i < length; i++){

                    let feedback = {
                        comment: this.reviews[i].comment,
                        img: this.reviews[i].img == null || this.reviews[i].img == '' ? '/img/avatar.png' : this.reviews[i].img,
                        name: this.reviews[i].name,
                        star: parseInt(this.reviews[i].star),
                        date: this.reviews[i].date,
                    }

                    let date = moment(feedback.date)
                    
                    series[feedback.star-1] += 1
                    if( date.isSame( moment(), 'date' ) ) this.feedbacks.push(feedback)
                }

                this.series = series
            }
        },
        
    }
</script>