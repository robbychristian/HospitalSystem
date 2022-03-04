<template>
    <div class="inquiry-page">

        <div class="alert-top">
            <b-alert
                :show="dismissCountDown"
                :variant="variant"
                @dismissed="dismissCountDown=0"
                @dismiss-count-down="countDownChanged"
                fade
                
            >
                {{ message }} 
            </b-alert>
        </div>

        <div class="inquiry-content">

            <div class="patient-box">

                <div class="patient-pick">
                    <h5> Patient: </h5>

                    <div class="patient-dropdown">

                        <div class="patient-button" @click="toggleDropdown()">
                            <h5>{{name}}</h5>
                            <i class="fa-solid fa-arrow-down"></i>
                        </div>

                        <div class="patient-select" v-show="showBox">
                            <div class="search-bar">
                                <input type="text" v-model="keyword">
                                <button><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>

                            <button v-for="(patient, index) in items" class="box-info border-1" :key="index" @click="pickPatient(index)">
                                <h6>{{patient.name}}</h6>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="patient-info" v-if="patient_id != -1">
                    <img :src="patient.imageUrl" alt="Image does not exist" >

                    <div class="patient-details">
                        <h6>Name: <span class="text-dark-green">{{patient.name}}</span></h6>
                        <h6>Blood Type: <span class="text-dark-green">{{patient.bloodType}}</span></h6>
                        <h6>Email: <span class="text-dark-green">{{patient.email}}</span></h6>
                    </div>

                </div>


            </div>

            <div class="divider"></div>

            <div class="form">
                
                <div class="form-box">
                    <h6> Subject (optional): </h6>
                    <div class="form-input ">
                        <input type="text">
                    </div>
                </div>
                
                <div class="form-box">
                    <div class="form-input flex-column align-items-start">
                        <h6> Body (optional): </h6>
                        <textarea cols="30" rows="10" class="w-100" style="resize: none;"></textarea>
                    </div>
                </div>

                <div class="form-box">
                    <div class="form-input flex-column align-items-start">
                        <h6> Prescription / Lab Request File: </h6>
                        <input  :class="[ 'form-control']" type="file" ref="picture" @change="triggerFile()">
                    </div>
                </div>
                <!-- :class="[{ 'error': doctorFormError.photo }, 'form-control']"  -->
            </div>

            <button class="btn bg-dark-green text-white mt-3"> Send to the Patient </button>
        </div>
    </div>
</template>

<script>
    export default {
        props:['patientData'],

        data() {
            return { 
                file: null,
                dismissSecs: 5,
                dismissCountDown: 0,
                message: '',
                variant: '',
                patients: JSON.parse(this.patientData),


                name: '<Select a Patient>',
                patient: '',
                patient_id: -1,
                showBox: false,
                keyword: '',
            }
        },

        methods: {
            pickPatient(ind){
                
                this.patient = this.items[ind]

                let sub = this.patient.imageUrl.substring(0, 5).toLowerCase()

                if(sub != 'https'){
                    this.toLinkImage(ind)
                }

                this.name = this.items[ind].name
                this.patient_id = this.items[ind].id

                this.toggleDropdown()
            },

            toLinkImage(ind){
                let self = this
                axios.post('/inquiry/image/', {
                    image: this.items[ind].imageUrl,
                })
                .then( function (response){
                    let data = response.data
                    console.log(data)
                    if(data.hasError){
                        self.variant = 'danger'
                        self.message = 'Image does not exist in the database'
                        self.showAlert()
                    }
                    else{
                        self.patient.imageUrl = data.image
                    }
                })
                .catch( function (error){
                    console.log(error);
                });
            },

            toggleDropdown(){ this.showBox = !this.showBox },
            
            countDownChanged(dismissCountDown) { this.dismissCountDown = dismissCountDown },
            showAlert() { this.dismissCountDown = this.dismissSecs },
        },

        computed:{
            items(){
                return this.keyword
                    ? this.patients.filter(
                        item => item.name.toLowerCase().includes(this.keyword.toLowerCase()) 
                    )
                    : this.patients
            },
        },

        // mounted(){
        //     console.log(JSON.parse(this.patientData))
        // }

    }
</script>