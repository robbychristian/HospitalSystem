<template>
    <div class="profile-page">

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
            ref="pass-modal"
            id="pass-modal" title="Change Password" 
            header-bg-variant="danger" header-text-variant="light" footer-bg-variant="danger" 
            hide-header-close ok-variant="warning" ok-title="Update" 
            @ok="updatePassword()"
        >
            
            <p class="text-danger" v-for="(error, ind) in errors.password" :key="'PASS'+ind">{{error}}</p>
            <div>
                <label for="password" class="form-label">Password</label>
                <input type="password" :class="[{error: errors.password }, 'form-control']" id="password" placeholder="password" v-model="password">
            </div>

            <div class="mt-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" v-model="confirmPassword">    
            </div>
        </b-modal>

        <div class="profile-content">
            
            <div :class="[{ 'on-update': personalForm.active }, 'personal-info']">
                <b-button @click="editPersonal()" variant="transparent" size="sm" class="settings"> <h6 class="m-0"> <i v-if="personalForm.active" class="fa-solid text-danger fa-xmark"></i> <i v-else class="fa-solid fa-pen-to-square"></i> </h6></b-button>
                <div 
                    @click="triggerFile()"
                    :class="[{'image-edit': personalForm.active}, 'image']"
                    :style="{
                        backgroundImage: 'url(' + user.photoUrl + ')',
                    }"
                > <h6>Choose Image</h6></div>

                <input accept=".png, .jpeg, .jpg," ref="fileImage" type="file" class="d-none" @change="changeImage()">

                <div class="mt-3" v-if="personalForm.active">

                    <p class="text-danger" v-for="(error, ind) in errors.fname" :key="'FN'+ind">{{error}}</p>
                    <p class="text-danger" v-for="(error, ind) in errors.lname" :key="'LN'+ind">{{error}}</p>
                    <input :class="{error: errors.fname}" type="text" placeholder="First Name" v-model="personalForm.fname">
                    <input :class="{error: errors.lname}" type="text" placeholder="Last Name" v-model="personalForm.lname">

                    <div class="container mt-3">
                        <b-button variant="light" @click="toggleGender()">  
                            <i v-if="personalForm.gender == 'Male'" class="fa-solid fa-mars text-primary"></i> 
                            <i v-else class="fa-solid fa-venus text-pink"></i>
                            &nbsp; {{personalForm.gender}}
                        </b-button>
                    </div>

                    <p class="text-danger" v-for="(error, ind) in errors.degree" :key="'D'+ind">{{error}}</p>
                    <div class="form-box">
                        <i class="fa-solid fa-graduation-cap"></i>: &nbsp;
                        <input :class="{error: errors.degree}" type="text" placeholder="Degree" v-model="personalForm.degree">
                    </div>

                    <p class="text-danger" v-for="(error, ind) in errors.specialization" :key="'SE'+ind">{{error}}</p>
                    <div class="form-box">
                        <i class="fa-solid fa-certificate text-dark-blue"></i> : &nbsp;
                        <select v-model="personalForm.specialization">
                            <option v-for="(option, ind) in options" :key="'S'+ind" >{{option}}</option>
                        </select>
                    </div>
                    
                    <p class="text-danger" v-for="(error, ind) in errors.email" :key="'E'+ind">{{error}}</p>
                    <div class="form-box">
                        <i class="fa-solid fa-at"></i> : &nbsp;
                        <input type="text" :class="{error: errors.email}" placeholder="Email" v-model="personalForm.email">
                    </div>

                    <p class="text-danger" v-for="(error, ind) in errors.phone" :key="'P'+ind">{{error}}</p>
                    <div class="form-box">
                        <i class="fa-solid fa-mobile"></i> : &nbsp;
                        <input type="text" :class="{error: errors.phone}" placeholder="Phone" v-model="personalForm.phone">
                    </div>

                    <div class="other-box">
                        <h6>Other Specializaiton</h6>
                        <p v-for="(specialization, ind) in personalForm.otherSpecialization" :key="'OSS'+ind"> 
                            {{specialization}}                         
                            <b-button @click="deleteSpecialization(ind)" variant="outline-danger" size="sm"><i class="fa-solid fa-xmark"></i></b-button>
                        </p>

                        <div class="input-box">
                            <select v-model="specialization">
                                <option v-for="(option, ind) in options" :key="'OS'+ind" > {{option}}</option>
                            </select>
                            <b-button @click="addSpecialization()" variant="success" size="sm"><i class="fa-solid fa-plus"></i></b-button>
                        </div>
                    </div>

                    <div class="form-box flex-column">
                        <h6>About: </h6>
                        <textarea cols="30" rows="10" placeholder="About" v-model="personalForm.about"></textarea>
                    </div>

                </div>

                <div v-else>
                    <div class="details">
                        <h4 class="mt-3"> {{user.fname}} {{user.lname}} <i v-if="user.gender == 'Male'" class="fa-solid fa-mars text-primary"></i> <i v-else class="fa-solid fa-venus text-pink"></i></h4>
                        <p> <i class="fa-solid fa-graduation-cap"></i> : &nbsp; <span> {{user.degree == '' || user.degree == null ? 'Not stated' : user.degree}} </span></p>
                        <p> <i class="fa-solid fa-certificate text-dark-blue"></i> : &nbsp; <span> {{user.specialization == '' || user.specialization == null ? 'Not stated' : user.specialization}} </span></p>
                        <p> <i class="fa-solid fa-at"></i> : &nbsp; <span> {{user.email}} </span></p>
                        <p> <i class="fa-solid fa-mobile"></i>: &nbsp; <span> {{user.phone}} </span></p>
                    </div>

                    <h6 class="mt-3"> 
                        <i class="fa-solid fa-file-medical"></i> {{user.totalPrescribe == '' || user.totalPrescribe == null ? '0' : user.totalPrescribe }}
                    </h6>
                    <b-dropdown variant="light" text="Other Specialization">
                        <b-dropdown-item v-for="(item, ind) in user.otherSpecialization" :key="'OS'+ind"> {{item}}</b-dropdown-item>
                    </b-dropdown>
                    <p class="about"> {{user.about == '' || user.about == null ? 'No Bio' : user.about}}</p>
                    
                </div>

        
                <b-button @click="update('personal', 'personalForm')" class="mt-3" v-if="personalForm.active" variant="warning" :disabled="this.personalBtn"> Update </b-button>
                <b-button v-else v-b-modal.pass-modal variant="link" class="text-danger">Change Password</b-button>
            </div>

            <div :class="[{ 'on-update': clinicForm.active }, 'clinic-info']">
                <b-button @click="editClinic()" variant="transparent" size="sm" class="settings"> <h6 class="m-0 p-0"> <i v-if="clinicForm.active" class="fa-solid text-danger fa-xmark"></i> <i v-else class="fa-solid fa-pen-to-square"></i> </h6></b-button>
                <h4> Hospital Information </h4>
                
                <div class="mt-3" v-if="clinicForm.active">
                    
                    <p class="text-danger" v-for="(error, ind) in errors.clinicAddress" :key="'CA'+ind">{{error}}</p>
                    <div class="form-box">
                        <i class="fa-solid fa-hospital text-dark-green"></i>: &nbsp;
                        <input  :class="{error: errors.clinicAddress}" type="text" placeholder="Clinic Address" v-model="clinicForm.clinicAddress">
                    </div>

                    <p class="text-danger" v-for="(error, ind) in errors.consultFee" :key="'CF'+ind">{{error}}</p>
                    <div class="form-box">
                        <i class="fa-solid fa-money-bill text-dark-green"></i>: &nbsp;
                        <input  :class="{error: errors.consultFee}" type="text" placeholder="Consult Fee" v-model="clinicForm.consultFee">
                    </div>

                    <div class="on-update availability">
                        <h5> Edit Availability</h5>

                        <div v-for="(item, ind) in teleDays" :key="'TD'+ind">

                            <p class="text-danger mb-0" v-for="(error, ind) in errors[item.field]" :key="'CF'+ind">{{error}}</p>
                            <h6> 
                                <small class="text-dark-blue"> {{item.day}} </small> : &nbsp;
                                <input :class="{error: errors[item.field]}" type="time" v-model="clinicForm[item.field][0]">
                                &nbsp; to &nbsp;
                                <input :class="{error: errors[item.field]}" type="time" v-model="clinicForm[item.field][1]">
                            </h6>
                        </div>
                    </div>

                </div>

                <div v-else>
                    <h5>  <i class="fa-solid fa-hospital text-dark-green"></i> : &nbsp; {{user.clinicAddress == '' || user.clinicAddress == null ? 'Not stated' : user.clinicAddress}} </h5>
                    <h5>  <i class="fa-solid fa-money-bill text-dark-green"></i> : &nbsp; {{user.consultFee == '' || user.consultFee == null ? 'Not stated' : user.consultFee}} <small class="text-dark-blue"> per Appointment</small></h5>
                    <h6 class="mt-2 text-dark-green"> Provide TeleService: <span> {{ user.provideTeleService ? 'Nope' : 'Yes'}} </span></h6>
                    <h6 class=" text-dark-green"> Tele consult Fee: <span> {{ user.teleconsultFee == '' || user.teleconsultFee == null ? 'NaN' : user.teleconsultFee}} </span></h6>
                    <div :class="[{'on-update': clinicForm.active}, 'availability']">
                        <h5> Availability each day <i class="fa-solid fa-sun text-orange"></i></h5>

                        <h6 v-for="(item, ind) in teleDays" :key="'TD'+ind"> 
                            <small class="text-dark-blue"> {{item.day}} </small> : &nbsp;
                            {{ user[item.field][0] == '' || user[item.field] == null ? 'Not Available' :  timeBestFormat(user[item.field][0]) + ' - ' + timeBestFormat(user[item.field][1]) }}
                        </h6>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <b-button  @click="update('clinic', 'clinicForm')" class="mt-3 mb-3" v-if="clinicForm.active" variant="warning" :disabled="this.clinicBtn"> Update </b-button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import moment from "moment";
import Swal from "sweetalert";
export default {
    props: ["profileData", "csrf"],

    data() {
        return {
            user: JSON.parse(this.profileData)[0],

            initialImage: '',
            specialization: '',

            password: '',
            confirmPassword: '',
            showPass: 'password',
            showCPass: 'password',


            personalForm: {
                image: null,
                fname: '',
                lname: '',
                specialization: '',
                otherSpecialization:  [],
                degree: '',
                email:  '',
                about:  '',
                phone:  '',
                gender: '',
                active: false,
            },

            clinicForm: {
                clinicAddress: '',
                consultFee: '',
                teleSun: ['', ''],
                teleMon: ['', ''],
                teleTue: ['', ''],
                teleWed: ['', ''],
                teleThurs: ['', ''],
                teleFri: ['', ''],
                teleSat: ['', ''],
                active: true,
            },

            errors: {
                photoUrl: '',
                degree: '',
                fname: '',
                lname: '',
                phone: '',
                consultFee: '',
                email: '',
                password: '',
                clinicAddress: '',
                teleSun: '',
                teleMon: '',
                teleTue: '',
                teleWed: '',
                teleThurs: '',
                teleFri: '',
                teleSat: '',
                password: '',
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

            teleDays:[
                { day: 'Sunday', field: 'teleSun' },
                { day: 'Monday', field: 'teleMon' },
                { day: 'Tuesday', field: 'teleTue' },
                { day: 'Wednesday', field: 'teleWed' },
                { day: 'Thursday', field: 'teleThurs' },
                { day: 'Friday', field: 'teleFri' },
                { day: 'Saturday', field: 'teleSat'},
            ],
        };
    },

    methods:{

        update(route, object){
                       
            this.openLoading()
            let self = this
            
            let formData = JSON.stringify(this.$data[object])

            const form = new FormData();
            form.append('data', formData)
            if(route == 'personal') form.append('image', this.personalForm.image)
            
            let go = true
            if(route == 'clinic') {
                go = this.validateTeledays(go)
                form.append('go', go)
            }

            axios.post('/profile/'+ route +'/', form)
            .then( function (response){
                let data = response.data
                
                if(data.hasError || !go){
                    
                    if(!data.hasError){
                        self.errors['consultFee'] = ''
                        self.errors['clinicAddress'] = ''
                    }

                    self.populateError(route, data)
                    self.closeLoading()
                }
                else{
                    
                    self.closeLoading()

                    Swal({
                        title: 'Success!',
                        text: data.text,
                        icon: 'success'
                    })

                    self.neutralize(route)

                }
            })
            .catch( function (error){
                console.log(error);
            });

            this.closeLoading()
        },

        validateTeledays(go){
            
            for(let i = 0; i < 7; i++){
                let field = this.teleDays[i].field
                
                if(this.clinicForm[field][0] != ''){
                    
                    if( this.clinicForm[field][0] != '' && this.clinicForm[field][1] != ''){
                        
                        let check = moment('01/01/2022 ' + this.clinicForm[field][0]).isBefore('01/01/2022 ' + this.clinicForm[field][1] )
                        
                        if( !check ){
                            
                            go = false
                            this.errors[field] = [
                                'Invalid Scheduled time'
                            ]
                        }

                        else this.errors[field] = ''

                    }
                    else if( this.clinicForm[field][0] == '' && this.clinicForm[field][1] != '' || this.clinicForm[field][0] != '' && this.clinicForm[field][1] == ''){
                        this.errors[field] = [
                            'Invalid Scheduled time',
                        ]
                        go = false
                    }
                    else this.errors[field] = ''
                }
            }

            return go
        },

        neutralize(route){
            if(route == 'personal'){

                this.user.otherSpecialization = []
                let length = this.personalForm.otherSpecialization.length
                for(let i = 0; i < length; i++)
                    this.user.otherSpecialization.push(this.personalForm.otherSpecialization[i])

                this.user.fname = this.personalForm.fname
                this.user.lname = this.personalForm.lname
                this.user.specialization = this.personalForm.specialization
                this.user.degree = this.personalForm.degree
                this.user.email = this.personalForm.email
                this.user.about = this.personalForm.about
                this.user.phone = this.personalForm.phone
                this.user.gender = this.personalForm.gender
                this.initialImage = this.user.photoUrl
                
                this.personalForm.image = null
                this.personalForm.active = false

                this.specialization = ''

                this.errors.photoUrl = ''
                this.errors.degree = ''
                this.errors.fname = ''
                this.errors.lname = ''
                this.errors.phone = ''
                this.errors.email = ''


            }
            else{
                
                for(let i = 0; i < 7; i++){
                    let field = this.teleDays[i].field
                    this.user[field][0] = this.clinicForm[field][0]
                    this.user[field][1] = this.clinicForm[field][1]
                    this.errors[field] = ''
                }
                
                this.user.consultFee =  this.clinicForm.consultFee
                this.user.clinicAddress =  this.clinicForm.clinicAddress
                this.clinicForm.active = false
                this.errors.consultFee = ''
                this.errors.clinicAddress = ''
            }
        },
        populateError(route, data){
            if(route == 'personal'){
                this.errors.photoUrl = data.photo
                this.errors.degree = data.degree
                this.errors.fname = data.firstName
                this.errors.lname = data.lastName
                this.errors.phone = data.phone
                this.errors.email = data.email
            }
            else{
                this.errors.consultFee = data.consultFee
                this.errors.clinicAddress = data.clinicAddress
            }
        },

        editClinic(){ this.clinicForm.active = !this.clinicForm.active },

        addSpecialization(){ 
            if(this.specialization == ''){
                Swal({
                    title: 'Oops!',
                    text: 'Please pick a specialization',
                    icon: 'error'
                })
            }
            else{
                this.personalForm.otherSpecialization.push(this.specialization)
                this.specialization = ''
            }
        },
        deleteSpecialization(ind){ this.personalForm.otherSpecialization.splice(ind, 1) },
        toggleGender(){ this.personalForm.gender = this.personalForm.gender == 'Male' ? 'Female' : 'Male' },
        triggerFile(){ if(this.personalForm.active) this.$refs['fileImage'].click() },
        changeImage(){
            this.personalForm.image = this.$refs['fileImage'].files[0]

            if(this.personalForm.image) this.user.photoUrl = URL.createObjectURL(this.personalForm.image)
            else { 
                this.user.photoUrl = this.initialImage
                this.personalForm.image = null
            }
        },

        editPersonal(){ 
            if(this.personalForm.active) {
                this.user.photoUrl = this.initialImage
                this.personalForm.image = null
            }
            this.personalForm.active = ! this.personalForm.active 
        },

        updatePassword(){ 
            
            this.openLoading()
            let self = this
            
            const form = new FormData();
            form.append('password', this.password)
            form.append('confirmPassword', this.confirmPassword)
            
            axios.post('/profile/password', form)
            .then( function (response){
                let data = response.data
                console.log(data)
                
                if(data.hasError){
                    self.errors.password = data.password
                    self.$refs['pass-modal'].show()
                    self.closeLoading()
                }
                else{
                    
                    self.closeLoading()

                    Swal({
                        title: 'Success!',
                        text: data.text,
                        icon: 'success'
                    })

                    self.password = ''
                    self.confirmPassword = ''
                    self.errors.password = ''

                }
            })
            .catch( function (error){
                console.log(error);
            });

            this.closeLoading()
        },

        arrayEquals(a, b) {
            return  Array.isArray(a) &&
                    Array.isArray(b) &&
                    a.length === b.length &&
                    a.every((val, index) => val === b[index]);
        },
        // Loading Modal Related
            openLoading(){ this.$refs['loading-modal'].show() },
            closeLoading(){ this.$refs['loading-modal'].hide() },
            timeBestFormat(time){ return moment( '01/01/2022 '+ time).format('h:mm a') },
        // END

    },

    computed: {

        personalBtn(){
            if(
                this.personalForm.image == null && this.personalForm.fname == this.user.fname &&
                this.personalForm.lname == this.user.lname && this.personalForm.specialization == this.user.specialization &&
                this.arrayEquals(this.personalForm.otherSpecialization, this.user.otherSpecialization) &&
                this.personalForm.degree == this.user.degree && this.personalForm.email == this.user.email && 
                this.personalForm.about == this.user.about && this.personalForm.gender == this.user.gender && 
                this.personalForm.phone == this.user.phone
            ){
                return true
            }
            return false
        },

        clinicBtn(){
            if(
                this.clinicForm.clinicAddress == this.user.clinicAddress && this.clinicForm.consultFee == this.user.consultFee &&
                this.arrayEquals(this.clinicForm.teleMon, this.user.teleMon) &&  this.arrayEquals(this.clinicForm.teleSun, this.user.teleSun) &&
                this.arrayEquals(this.clinicForm.teleTue, this.user.teleTue) &&  this.arrayEquals(this.clinicForm.teleWed, this.user.teleWed) &&
                this.arrayEquals(this.clinicForm.teleThurs, this.user.teleThurs) &&  this.arrayEquals(this.clinicForm.teleFri, this.user.teleFri) &&
                this.arrayEquals(this.clinicForm.teleSat, this.user.teleSat)      
            ){
                return true
            }
            return false
        },
    },

    
    mounted() {
        
        this.initialImage = this.user.photoUrl == '' || this.user.photoUrl == null ? '/img/avatar.png' : this.user.photoUrl
        
        this.personalForm = {
            image: null,
            fname: this.user.fname,
            lname: this.user.lname,
            specialization:  this.user.specialization,
            otherSpecialization:  [],
            degree:  this.user.degree,
            email:  this.user.email,
            about:  this.user.about,
            phone:  this.user.phone,
            gender:  this.user.gender,
            active: false,
        }

        let length = this.user.otherSpecialization.length
        for(let i = 0; i < length; i++)
            this.personalForm.otherSpecialization.push(this.user.otherSpecialization[i])
        
        this.clinicForm = {
            clinicAddress: this.user.clinicAddress,
            consultFee: this.user.consultFee,
            teleSun: ['', ''],
            teleMon: ['', ''],
            teleTue: ['', ''],
            teleWed: ['', ''],
            teleThurs: ['', ''],
            teleFri: ['', ''],
            teleSat: ['', ''],
            active: false,
        }

        for(let i = 0; i < 7; i++){
            let field = this.teleDays[i].field
            
            if(this.user[field] != null){
                if(this.user[field].length){
                    this.clinicForm[field][0] = this.user[field][0]
                    this.clinicForm[field][1] = this.user[field][1]
                }
            }
            else{
                this.user[field] = ['', '']
            }
        }
        
    },
};
</script>
