<template>
    <div class="patient-page">
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
        <div class="patient-content text-dark-green">
            
            
        <b-modal 
            ref="modal"
            scrollable 
            :header-class="modal.headerClass"
        >
        
            <div class="form">
                <h6>Patients's Name: </h6>
                <p class="text-danger" v-for="(error, index) in patientFormError.firstName" :key="'d0'+index"> {{error}}</p>
                <p class="text-danger" v-for="(error, index) in patientFormError.lastName" :key="'d1'+index"> {{error}}</p>

                <div class="form-input">
                    <input :class="[{ 'error': patientFormError.firstName  }]" type="text" placeholder="First Name" v-model="patientForm.firstName">
                    <input :class="[{ 'error': patientFormError.lastName  }, 'mt-2']" type="text" placeholder="Last Name" v-model="patientForm.lastName">
                </div>
                
                <h6>Patients's Address and Birthdate: </h6>

                <p class="text-danger" v-for="(error, index) in patientFormError.address" :key="'d2'+index"> {{error}}</p>
                <p class="text-danger" v-for="(error, index) in patientFormError.birthdate" :key="'d3'+index"> {{error}}</p>

                <div class="form-input">
                    <input :class="[{ 'error': patientFormError.address }]" type="text" placeholder="Patient's Address" v-model="patientForm.address">
                    <input :class="[{ 'error': patientFormError.birthdate }, 'mt-2']" type="date"  v-model="patientForm.birthdate">
                </div>

                <h6> Patient's Phone and Picture</h6>
                
                <p class="text-danger" v-for="(error, index) in patientFormError.phone" :key="'d5'+index"> {{error}}</p>
                <p class="text-danger" v-for="(error, index) in patientFormError.photo" :key="'d6'+index"> {{error}}</p>

                <div class="form-input flex-column">
                    <input :class="[{ 'error': patientFormError.phone }, 'w-100']" type="text" placeholder="Phone number" v-model="patientForm.phone">
                    <input accept=".png, .jpeg, .jpg," :class="[{ 'error': patientFormError.photo }, 'form-control mt-2']" type="file" ref="picture" @change="addFile('picture', false)">
                </div>

                <h6> Patient's Gender and Nationality</h6>
                
                <p class="text-danger" v-for="(error, index) in patientFormError.nationality" :key="'d7'+index"> {{error}}</p>

                <div class="form-input">
                    <div class='d-flex align-items-center'>
                        <input class="'form-check-input' " type="checkbox" id="flexRadioDefault1" value="true" v-model="patientForm.gender">
                        <label class="form-check-label mx-3" for="flexRadioDefault1"> Male </label>
                    </div>

                    <input :class="[{ 'error': patientFormError.nationality }, 'mt-2']" type="text" placeholder="Nationality" v-model="patientForm.nationality">
                </div>

                <h6> Patient's Email</h6>

                <p class="text-danger" v-for="(error, index) in patientFormError.email" :key="'d8'+index"> {{error}}</p>
                <p class="text-danger" v-for="(error, index) in patientFormError.bloodType" :key="'d9'+index"> {{error}}</p>
                
                <div class="form-input">
                    <input :class="[{ 'error': patientFormError.email }]" type="email" placeholder="Patient's Email" v-model="patientForm.email">
                    <select v-model="patientForm.bloodType">
                        <option value="A+">A+</option>
                        <option value="B+">B+</option>
                        <option value="O">O</option>
                        <option value="AB">AB</option>
                    </select>
                </div>
            </div>

            <template #modal-header-close>
                <i class="fa-solid fa-xmark"></i>
            </template>
        
            <template #modal-title>
                {{modal.operation}} <b> Patient</b>
            </template>

            <template #modal-footer>
                <b-button size="md" variant="outline-success" @click="addOrUpdate()"> {{modal.operation}} Patient </b-button>
            </template>

        </b-modal>

            <div class="search-holder">
                <b-button v-if="user.isDoctor" type="button" @click="showModal('Add', 0)" class="btn btn-add"> <i class="fa-solid fa-plus"></i> Patient</b-button>
                <label class="d-none d-sm-block">Search: </label>
                <input
                    class="col-12 col-sm-3"
                    type="text"
                    v-model="keyword"
                />
                
                <select v-model="filterBy">
                    <option value="name">Name</option>
                    <option value="age">Age</option>
                    <option value="birthdate">Birthdate</option>
                </select>
            </div>

            <b-table class="text-dark-green" :fields="fields" :items="items">
                <template #cell(icon)>
                    <i class="fa-solid fa-face-grin-beam"></i>
                </template>

                <template #cell(action)="data">
                    <button
                        class="btn bg-dark-green text-white"
                        @click="viewPatient(data.item.id)"
                    >
                        <i class="fa-solid fa-hospital-user"></i>
                    </button>
                    
                    <button class="btn bg-danger" @click="deletePatient(data.item.id, data.item.name)"><i class="fa-solid fa-trash"></i></button>
                </template>
            </b-table>
        </div>
    </div>
</template>

<script>
export default {
    props: ["patient", "userData"],

    mounted() {
        let userData = JSON.parse(this.userData);
        this.user = {
            id: userData.id_fb,
            email: userData.email,
            isDoctor: !userData.isAdmin,
        };

        if (this.user.isDoctor) {
            this.fields.push({ key: "action", label: "Action" });
        }
    },

    data() {
        return {
            user: "",

            keyword: "",
            filterBy: 'name',

            patients: JSON.parse(this.patient),

            patientForm: {
                firstName: '',
                lastName: '',
                address: '',
                nationality: '',
                birthdate: '',
                phone: '',
                gender: false,
                email: '',
                photo: '',
                bloodType: 'A+',
                id: '',
            },

            patientFormError: {
                bloodType: '',
                firstName: '',
                lastName: '',
                address: '',
                nationality: '',
                birthdate: '',
                phone: '',
                gender: false,
                email: '',
                photo: '',
            },

            modal:{
                headerClass: '',
                operation: '',
            },

            alertBackground: '',
            error: '',
            dismissSecs: 5,
            dismissCountDown: 0,

            fields: [
                { key: "icon", label: " " },
                {
                    key: "name",
                    label: "Name",
                    sortable: true,
                },

                {
                    key: "age",
                    label: "Age",
                    sortable: true,
                },

                {
                    key: "birthdate",
                    label: "Birthdate",
                    sortable: true,
                },
            ],
        };
    },

    methods: {

        viewPatient(id) {
            alert("view: " + id);
        },

        addPatient(){
        let self = this
        const filePhoto = new FormData();
        filePhoto.append('birthdate', this.patientForm.birthdate)
        filePhoto.append('photo', this.patientForm.photo)
        filePhoto.append('firstName', this.patientForm.firstName)
        filePhoto.append('lastName', this.patientForm.lastName)
        filePhoto.append('address', this.patientForm.address)
        filePhoto.append('nationality', this.patientForm.nationality)
        filePhoto.append('gender', this.patientForm.gender)
        filePhoto.append('phone', this.patientForm.phone)
        filePhoto.append('email', this.patientForm.email)
        filePhoto.append('bloodType', this.patientForm.bloodType)

        return axios.post('/patient/add/', filePhoto)
        .then( function (response){
            let data  = response.data

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

                self.patients.push( JSON.parse(data.patient))
                self.$refs['modal'].hide()
                self.error = data.success;
                self.alertBackground = "success"
                self.neutralizeForm()
                self.showAlert()
            }
        })
        .catch( function (error){
            console.log(error);
        });
        },


        updatePatient(){
            alert("UPDATE ME");
        },

        deletePatient(id){
            alert("delete: " + id);
        },

        addOrUpdate(){
            if(this.modal.operation == 'Add'){
                this.addPatient();
            }
            else{
                this.updatePatient();
            }
        },

        countDownChanged(dismissCountDown) { this.dismissCountDown = dismissCountDown },
        showAlert() { this.dismissCountDown = this.dismissSecs },

        showModal(operation, id){

            if( operation == "Update"){
                // this.populateForm(id)
                this.modal.headerClass = "bg-warning modal-head"
            }
            else if(operation != this.modal.operation){
                // this.neutralizeForm()
                this.modal.headerClass = "bg-dark-blue text-white modal-head"
            }
            else{
                this.modal.headerClass = "bg-dark-blue text-white modal-head"
            }

            this.modal.operation = operation
            this.$refs['modal'].show()
        },

        populateError(data){
            this.patientFormError.firstName = data.firstName 
            this.patientFormError.lastName = data.lastName
            this.patientFormError.photo = data.photo
            this.patientFormError.phone = data.phone
            this.patientFormError.email = data.email
            this.patientFormError.nationality = data.nationality
            this.patientFormError.birthdate = data.birthdate
            this.patientFormError.address = data.address
        },
        
        addFile(){
            this.patientForm.photo = this.$refs['picture'].files[0]
        },

        neutralizeForm(){
            this.patientForm = {
                firstName: '',
                lastName: '',
                address: '',
                nationality: '',
                birthdate: '',
                phone: '',
                gender: false,
                email: '',
                photo: '',
                id: '',
            }

            this.patientFormError = {
                firstName: '',
                lastName: '',
                address: '',
                nationality: '',
                birthdate: '',
                phone: '',
                gender: false,
                email: '',
                photo: '',
            }

        },
    },

    computed: {
        
        items () {
            return this.keyword
                ? this.patients.filter(
                    item => item[this.filterBy].toLowerCase().includes(this.keyword.toLowerCase()) 
                )
                : this.patients
        },

        rows() {
            return this.items.length
        },
    },
};
</script>
