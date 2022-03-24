<template>
    <div class="appointment-page">

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

        <div class="search-bar">
            <input type="text" v-model="keyword">

            <select v-model="filterBy">
                <option v-for="(option, ind) in options" v-if="option.show" :value="option.value" :key="'O'+ind"> {{option.text}}</option>
            </select>
        </div>

        <div class="appointment-content">
            <h1 v-if="appointments.length == 0">
                There's no ongoing approved appointment right now
            </h1>

            <div v-else class="appointment-box mx-auto" v-for="(data, ind) in items" :key="'A'+ind">
                <div :class="[ data.appointStatus , 'status']"> {{data.appointStatus}} </div>

                <div class="patient-info">
                    <h6> Patient:</h6>
                    <h6> {{data.pfName}} {{data.plName}} </h6>
                    <p> {{data.pGender == null ? "Not stated" : data.pGender}} </p>
                </div>

                <div class="doctor-info">
                    <h6> Dr. {{data.drfName}} {{data.drlName}}</h6>
                    <p> {{data.drDegree}}</p>
                </div>
                
                <div class="appointment-info">
                    <p> Schedule: {{data.bookingDate}} {{data.bookingSchedule}}</p>
                    <p> Payment: {{data.proofOfPay ? "Online Payment" : "Cash on Hand"}}</p>
                    <p class="text-danger"> Reason: {{data.pProblem}}</p>
                    <button v-if="data.appointStatus == 'Pending' && data.proofOfPay" @click="showPayment(data.pName, data.proofOfPay)" class="btn btn-sm p-1 btn-info w-100 text-center text-white"> Show Payment</button>



                    <div v-if="data.appointStatus == 'Pending'" class="d-flex px-3 py-1 mt-3 justify-content-between" style="background-color: #ececec;">
                        <button @click="updateAppointment(data.id, 'Rejected', ind)" class="btn btn-sm p-1 btn-danger flex-grow-1"> Reject</button>
                        <button @click="updateAppointment(data.id, 'Approved', ind)" class="btn btn-sm p-1 btn-success flex-grow-1"> Accept</button>
                    </div>

                    <div v-else-if="data.appointStatus == 'Pending payment'"  class="d-flex px-3 py-1 mt-3 justify-content-between" style="background-color: #ececec;">
                        <button @click="updateAppointment(data.id, 'Rejected', ind)" class="btn btn-sm p-1 btn-danger  flex-grow-1"> Reject</button>
                    </div>

                    <div v-else-if="data.appointStatus == 'Approved'"  class="d-flex px-3 py-1 mt-3 justify-content-between" style="background-color: #ececec;">
                        <button @click="removeLab(data.id, ind, data.labRequest == '1' ? '0' : '1')" :class="[ data.labRequest == '1' ? 'bg-primary' : 'bg-secondary', 'btn btn-sm p-1  flex-grow-1 text-white btn-lab']" :disabled="data.labRequest == '2'"> Lab request {{ data.labRequest == 1 ? 'unessential' : 'essential'}}</button>
                        <button @click="updateAppointment(data.id, 'Cancelled', ind)" class="btn btn-sm p-1 btn-danger  flex-grow-1"> Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Swal from "sweetalert";
export default {
    props: ["appointmentData", "isAdmin"],


    data() {
        return {
            keyword: '',
            filterBy: 'pName',
            options:[
                {text: "Patient Name", value: 'pName', show: true },
                {text: "Doctor Name", value: 'dName', show: this.isAdmin == 0 ? false : true },
                {text: "Doctor Degree", value: 'drDegree', show: this.isAdmin == 0 ? false : true},
            ],
            appointments: JSON.parse(this.appointmentData),
        };
    },

    methods: {

        removeLab(id, ind, category){
            this.openLoading()
            let self = this
            
            axios.post('/appointment/lab', {
                id: id,
                data: category,
            })
            .then( function (response){
                let data = response.data
                
                if(data.hasError){
                    Swal({
                        title: 'Error!',
                        text: 'Please refresh the page',
                        icon: 'error'
                    })
                    self.closeLoading()
                }
                else{
                    Swal({
                        title: 'Success!',
                        icon: 'success'
                    })
                    
                    self.items[ind].labRequest = category
                    

                    self.closeLoading()
                }
            })
            .catch( function (error){
                console.log(error);
            });

        },
        
        updateAppointment(id, status, ind){
                    
            this.openLoading()
            let self = this
            
            axios.post('/appointment/status', {
                id: id,
                status: status,
            })
            .then( function (response){
                let data = response.data
                
                if(data.hasError){
                    Swal({
                        title: 'Error!',
                        text: 'Please refresh the page',
                        icon: 'error'
                    })
                    self.closeLoading()
                }
                else{
                    Swal({
                        title: 'Success!',
                        text: 'Appointment is ' + status,
                        icon: 'success'
                    })

                    let appointment = self.items.splice(ind, 1)
                    appointment[0].appointStatus = status
                    self.items.push(appointment[0])

                    self.closeLoading()
                }
            })
            .catch( function (error){
                console.log(error);
            });

            this.closeLoading()
        },

        showPayment(name, image){

            Swal({
                title: 'Proof of Payment!',
                text: name + ' Has already paid!',
                icon: image,
                imageWidth: 600,
                imageHeight: 600,
                imageAlt: 'Image does not exist!',
            })
        },

        // Loading Modal Related
            openLoading(){
                this.$refs['loading-modal'].show()
            },

            closeLoading(){
                this.$refs['loading-modal'].hide()
            },
        // END
    },

    computed:{
        items(){
            return this.keyword
                ? this.appointments.filter(
                    item => item[this.filterBy].toLowerCase().includes(this.keyword.toLowerCase()) 
                )
                : this.appointments
        },
    },
};
</script>
