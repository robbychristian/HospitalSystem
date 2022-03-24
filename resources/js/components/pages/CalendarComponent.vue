<template>
    <div class="calendar-page" style="margin-top: 1rem;">
        <div class="row">
            <div
                class="col d-flex mx-3 justify-content-start"
                v-if="this.isAdmin == 1"
            >
                <b-dropdown
                    split
                    split-variant="outline-primary"
                    variant="primary"
                    :text="doctorName"
                    class="m-2"
                >
                    <b-dropdown-item-button v-for="(doctor, ind) in doctors" @click="chooseDoctor(ind)" :key="'D'+ind">
                        {{doctor.fname}} {{doctor.lname}}
                    </b-dropdown-item-button>
                </b-dropdown>
            </div>

            <div class="col d-flex mx-3 justify-content-end">
                <b-button
                    @click="addCalendar"
                    class="btn"
                    style="background-color: #48898c"
                    ><i class="fa-solid fa-plus"></i> Add Appointment</b-button
                >
            </div>
        </div>
        <FullCalendar :options="calendarOptions" class="py-3 px-5" />

        <b-modal
            id="addCalendar"
            centered
            title="Schedule an Appointment"
            @ok="submitForm"
            ref="addCalendar"
        >
            <form
                action="calendar/addappointment"
                method="post"
                ref="form"
                v-bind:value="csrf"
            >
                <input type="hidden" name="_token" v-bind:value="csrf" />

                <b-row>
                    <b-col sm="2"><label>Date: </label></b-col>
                    <b-col sm="10">
                        <b-form-datepicker
                            id="example-datepicker"
                            v-model="date"
                            name="date"
                        ></b-form-datepicker>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col sm="2"><label>Start: </label></b-col>
                    <b-col sm="10">
                        <b-form-timepicker
                            v-model="startTime"
                            name="startTime"
                            locale="en"
                        ></b-form-timepicker>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col sm="2"><label>End: </label></b-col>
                    <b-col sm="10">
                        <b-form-timepicker
                            v-model="endTime"
                            name="endTime"
                            locale="en"
                        ></b-form-timepicker>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col sm="2"><label>Problem: </label></b-col>
                    <b-col sm="10">
                        <b-form-textarea
                            id="textarea"
                            style="overflow-y: hidden"
                            v-model="description"
                            rows="3"
                            name="problem"
                            max-rows="5"
                        ></b-form-textarea>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col sm="2"><label>Patient: </label></b-col>
                    <b-col sm="10">
                        <b-form-select
                            v-model="selectedPatient"
                            :options="options"
                            name="patient"
                        ></b-form-select>
                    </b-col>
                </b-row>
                
                <b-row>
                    <b-col sm="3"><label>Appointment State: </label></b-col>
                    <b-col sm="9">

                        <input type="radio" id="one" value="Teleconsultation" :disabled="user.provideTeleService == 0 ? true : false" v-model="appointState" >
                        <label for="one">Teleconsultation</label>

                        <br>

                        <input type="radio" id="two" value="Hospital" :disabled="hospital == 0 ? true : false" v-model="appointState">
                        <label for="two">Hospital</label>

                        <input type="hidden" name="appointState" :value="appointState">

                    </b-col>
                </b-row>
            </form>
        </b-modal>
    </div>
</template>
<script>
import { Calendar } from "@fullcalendar/core";
import moment from "moment";
import timeGridPlugin from "@fullcalendar/timegrid";
import "@fullcalendar/core/vdom"; // solves problem with Vite
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import { v4 as uuidv4 } from "uuid";
import swal from "sweetalert";
export default {

    props: ["csrf", "patients", "appointments", "isAdmin", 'doctorsData', 'userData', 'hospital'],

    mounted() {
        let data = JSON.parse(this.patients);

        data.forEach(this.toItems);
        

        let allAppointments = this.appointmentss;

        allAppointments.forEach(this.toEvents);

        console.log(this.user.provideTeleService == 0 ? false : true)

    },

    components: {
        FullCalendar, // make the <FullCalendar> tag available
    },
    data() {
        return {
            //form

            user: JSON.parse(this.userData),

            doctors: JSON.parse(this.doctorsData),
            appointmentss: JSON.parse(this.appointments),

            date: moment(new Date()).format("YYYY-MM-DD"),
            startTime: moment(new Date()).format("HH:mm:ss"),
            endTime: moment(this.startTime)
                .add(30, "minutes")
                .format("HH:mm:ss"),
            description: "",
            selectedPatient: "",
            options: [],
            modalShow: "addCalendar",
            appointState: '',

            //filter
            doctorName: "Choose a Doctor",
            doctor: '',

            //choose doctor schedule
            keyword: "",

            //Calendar
            calendarOptions: {
                plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin],
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek",
                },
                initialView: "timeGridWeek",
                aspectRatio: 2.3,
                expandRows: true,
                selectable: true,
                editable: true,
                events: [],
                eventClick: this.handleEventClick,
            },
        };
    },
    methods: {
        chooseDoctor(ind){
            this.doctor = this.doctors[ind]
            this.doctorName = this.doctor.fname + ' ' + this.doctor.lname
                    
            this.calendarOptions.events = []

            let allAppointments = this.appointmentss;

            allAppointments.forEach(this.toEvents);
        },
        addCalendar() {
            this.$refs["addCalendar"].show();
            console.log(this.startTime);
        },
        submitForm() {
            if (
                this.date == "" ||
                this.description == "" ||
                this.selectedPatient == "" ||
                this.appointState == ''
            ) {
                swal({
                    title: "Error",
                    text: "Some input fields are empty!",
                    icon: "error",
                });
            } else if (moment(this.startTime).isBefore(this.endTime)) {
                swal({
                    title: "Invalid Time",
                    text: "Time set is invalid!",
                    icon: "error",
                });
            } else {
                swal({
                    title: "Appointment Added",
                    text: "Your new appointment for teleconsultation has been added!",
                    icon: "success",
                    buttons: false,
                });
                this.$refs["form"].submit();
            }
        },
        showAlert() {
            alert("test");
        },
        handleEventClick(arg) {
            let self = this
            swal({
                title: "Delete Appointment?",
                text: "Are you sure you want to remove the announcement?",
                icon: "warning",
                buttons: {
                    cancel: true,
                    OK: "Delete",
                },
            }).then((value) => {
                if (value == "OK") {
                    axios
                        .post("/api/calendar/delete", {
                            id: arg.event.id,
                        })
                        .then((response) => {
                            swal({
                                title: "Deleted!",
                                text: "The appointment has been deleted!",
                                icon: "success",
                                buttons: false,
                            });
                            arg.event.remove();

                            let length = self.appointmentss.length

                            for(let i =0; i  < length ; i++){
                                if(self.appointmentss[i].id == arg.event.id){
                                    self.appointmentss.splice(i, 1)
                                }
                            }
                        });
                }
            });
        },
        handleEvents(events) {
            this.calendarOptions.events = events;
        },
        toItems(item) {
            let data = {
                value: item.id,
                text: item.fname + " " + item.lname,
            };

            this.options.push(data);
        },

        toEvents(item) {
            
            let date = item.appointDate
            let schedule = item.bookingSchedule
            let start = schedule.substring( schedule.indexOf(' ') + 1 , schedule.lastIndexOf('-') -1 );
            let end = schedule.substring( schedule.lastIndexOf(' ')+1);
            let name = item.pfName + ' ' + item.plName

            let data = {
                id: item.id,
                start: moment(date + ' ' + start).format("YYYY-MM-DD HH:mm"),
                end: moment(date + ' ' + end).format("YYYY-MM-DD HH:mm"),
                title: name,
            };
            
            if( this.isAdmin == 0)
                this.calendarOptions.events.push(data);
            else if( this.isAdmin == 1 && this.doctor != ''){
                if(this.doctor.id == item.drId)
                    this.calendarOptions.events.push(data);
            }
        },
    },

    computed: {
        items() {
            return this.keyword
                ? this.appointments.filter((item) =>
                      item.doctor_id
                          .toLowerCase()
                          .includes(this.keyword.toLowerCase())
                  )
                : this.appointments;
        },
    },
};
</script>

<style>
.fc-content {
    color: white;
}

.background {
    background-color: red;
}
</style>
