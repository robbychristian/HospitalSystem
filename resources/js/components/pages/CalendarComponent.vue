<template>
    <div class="calendar-page" style="margin-top: 1rem">
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
                    <b-dropdown-item-button
                        v-for="(doctor, ind) in doctors"
                        @click="chooseDoctor(ind)"
                        :key="'D' + ind"
                    >
                        {{ doctor.fname }} {{ doctor.lname }}
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

                <b-row v-if="isAdmin == 1">
                    <b-col sm="2"><label>Doctor: </label></b-col>
                    <b-col sm="10">
                        <b-form-select
                            v-model="selectedDoctor"
                            :options="optDoc"
                            name="doctor"
                            @input="updateUser()"
                        ></b-form-select>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col sm="2"><label>Date: </label></b-col>
                    <b-col sm="10">
                        <b-form-datepicker
                            id="example-datepicker"
                            v-model="date"
                            name="date"
                            :date-disabled-fn="dateDisabled"
                            :min="min"
                            @input="updateSlots()"
                            :disabled="user.length == 0"
                        ></b-form-datepicker>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col sm="2"><label>Slot: </label></b-col>
                    <b-col sm="10">
                        <b-form-select
                            v-model="timeSlot"
                            :options="allSlot"
                            class="w-100"
                            name="timeSlot"
                            :disabled="date == '' || user.length == 0"
                        >
                            <!-- This slot appears above the options from 'options' prop -->
                            <template #first>
                                <b-form-select-option :value="null" disabled
                                    >-- Please select an option
                                    --</b-form-select-option
                                >
                            </template>
                        </b-form-select>
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
                        <input
                            type="radio"
                            id="one"
                            value="Teleconsultation"
                            :disabled="
                                user.provideTeleService == 0 ? true : false
                            "
                            v-model="appointState"
                        />
                        <label for="one">Teleconsultation</label>

                        <br />

                        <input
                            type="radio"
                            id="two"
                            value="Hospital"
                            :disabled="hospital == 0 ? true : false"
                            v-model="appointState"
                        />
                        <label for="two">Hospital</label>

                        <input
                            type="hidden"
                            name="appointState"
                            :value="appointState"
                        />
                    </b-col>
                </b-row>
            </form>
        </b-modal>
    </div>
</template>
<script>
import { Calendar } from "@fullcalendar/core";
import timeGridPlugin from "@fullcalendar/timegrid";
import "@fullcalendar/core/vdom"; // solves problem with Vite
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import { v4 as uuidv4 } from "uuid";
import swal from "sweetalert";
import Moment from "moment";
import { extendMoment } from 'moment-range';

const moment = extendMoment(Moment)

export default {
    props: [
        "csrf",
        "patients",
        "appointments",
        "isAdmin",
        "doctorsData",
        "userData",
        "hospital",
        "drUserData",
    ],

    mounted() {
        let data = JSON.parse(this.patients);

        data.forEach(this.toItems);

        this.isPatient = false;
        data = this.doctors;
        data.forEach(this.toItems);

        let allAppointments = this.appointmentss;

        allAppointments.forEach(this.toEvents);
        
        this.populateWeekdays()

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
            //FOR DATE
            date: '',
            min: new Date(),
            //FOR TIME SLOT
            timeSlot: null,
            allSlot: [],

            description: "",
            selectedPatient: "",
            selectedDoctor: "",
            options: [],
            optDoc: [],
            modalShow: "addCalendar",
            appointState: "",

            //filter
            doctorName: "Choose a Doctor",
            doctor: '',

            // Sunday = 0, Saturday 6
            weekdays: {
                sun: "",
                mon: "",
                tue: "",
                wed: "",
                thurs: "",
                fri: "",
                sat: "",
            },
            isPatient: true,

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
                initialView: "dayGridMonth",
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
        chooseDoctor(ind) {
            this.doctor = this.doctors[ind];
            this.doctorName = this.doctor.fname + " " + this.doctor.lname;

            this.calendarOptions.events = [];

            let allAppointments = this.appointmentss;

            allAppointments.forEach(this.toEvents);
        },
        addCalendar() {
            this.$refs["addCalendar"].show();
        },
        submitForm() {
            if (
                this.date == "" ||
                this.description == "" ||
                this.selectedPatient == "" ||
                this.appointState == "" ||
                this.timeSlot == "" ||
                this.selectedDoctor == ""
            ) {
                swal({
                    title: "Error",
                    text: "Some input fields are empty!",
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
            let self = this;
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

                            let length = self.appointmentss.length;

                            for (let i = 0; i < length; i++) {
                                if (self.appointmentss[i].id == arg.event.id) {
                                    self.appointmentss.splice(i, 1);
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

            if (this.isPatient) this.options.push(data);
            else {
                if (item.isAdmin == 0) this.optDoc.push(data);
            }
        },

        toEvents(item) {
            let date = item.appointDate;
            let schedule = item.bookingSchedule;
            let start = schedule.substring(
                schedule.indexOf(" ") + 1,
                schedule.lastIndexOf("-") - 1
            );
            let end = schedule.substring(schedule.lastIndexOf(" ") + 1);
            let name = item.pfName + " " + item.plName;

            let data = {
                id: item.id,
                start: moment(date + " " + start).format("YYYY-MM-DD HH:mm"),
                end: moment(date + " " + end).format("YYYY-MM-DD HH:mm"),
                title: name,
            };

            if (this.isAdmin == 0) this.calendarOptions.events.push(data);
            else if (this.isAdmin == 1 && this.doctor != "") {
                if (this.doctor.id == item.drId)
                    this.calendarOptions.events.push(data);
            }
        },

        dateDisabled(ymd, date) {
            // Disable weekends (Sunday = `0`, Saturday = `6`) and
            // disable days that fall on the 13th of the month
            const weekday = date.getDay();
            const day = date.getDate();
            // Return `true` if the date should be disabled

            let length = this.weekdays.length;

            for (let i = 0; i < length; i++)
                this.weekdays[i] = weekday === this.weekdays[i];

            return (
                weekday === this.weekdays.sun ||
                weekday === this.weekdays.mon ||
                weekday === this.weekdays.tue ||
                weekday === this.weekdays.wed ||
                weekday === this.weekdays.thurs ||
                weekday === this.weekdays.fri ||
                weekday === this.weekdays.sat
            );
        },

        updateSlots(){
            let day = moment(this.date).days()
            let dayName = moment(this.date).format('ddd')

            this.allSlot = []
            let days = [
                { day: 'sun', field: 'teleSun' },
                { day: 'mon', field: 'teleMon' },
                { day: 'tue', field: 'teleTue' },
                { day: 'wed', field: 'teleWed' },
                { day: 'thurs', field: 'teleThurs' },
                { day: 'fri', field: 'teleFri' },
                { day: 'sat', field: 'teleSat'},
            ]

            let week = this.weekdays[ days[day].day ]
            let startTime = moment(this.date + " " + week[0])
            let endTime = moment(this.date + " " + week[1])

            let totalMin = ( endTime.hour() - startTime.hour()) *60 + ( endTime.minute() - startTime.minute())
            let slots = parseInt(totalMin/week[2]) 

            for (let i = 0; i < slots; i++) {

                let start = startTime.format("HH:mm");
                let startA = startTime.format("h:mmA");
                startTime.add(week[2], "m")
                let end = startTime.format("HH:mm");
                let endA = startTime.format("h:mmA");

                let slot = dayName + ": " + start + " - " + end

                if( !this.conflicts(slot) ){
                    this.allSlot.push({
                        value: slot,
                        text: startA + " - " + endA,
                    });
                }
            }

        },

        updateUser(){

            let length = this.doctors.length
            
            for(let i = 0; i < length; i++){
                if( this.doctors[i].id == this.selectedDoctor ) this.user = this.doctors[i]
            }

            console.log(this.user)
            
            this.populateWeekdays() 
        },

        conflicts( slot ){
            let length = this.appointmentss.length
            let appointDate = moment( this.date ).format('L')
            let id = this.user.id
            
            let sched = slot.substr( slot.indexOf(' ') + 1 )
            sched = sched.trim()

            let time1s = sched.substr( 0, sched.indexOf(' ') )
            let time1e = sched.substr( sched.lastIndexOf(' ') + 1 )
            var date1 = [moment(appointDate + " " + time1s), moment(appointDate + " " + time1e)];
            var range  = moment.range(date1);

            for(let i = 0; i < length; i++){
                
                console.log(appointDate + " " + this.appointmentss[i].appointDate)

                if( appointDate == this.appointmentss[i].appointDate ){
                    
                    if( id == this.appointmentss[i].drId ){
                        

                        sched = this.appointmentss[i].bookingSchedule.substr( this.appointmentss[i].bookingSchedule.indexOf(' ') + 1 )
                        sched = sched.trim()
                        time1s = sched.substr( 0, sched.indexOf(' ') )
                        time1e = sched.substr( sched.lastIndexOf(' ') + 1 )

                        var date2 = [moment(appointDate + " " + time1s), moment(appointDate + " " + time1e)];
                        var range2 = moment.range(date2);

                        if(range.overlaps(range2)){
                            return true
                        }
                    }
                }
            }
            return false

        },

        populateWeekdays(){
            
        //SETTING AVAILABLE DAYS
            let drData = this.user;
            this.selectedDoctor = this.user.id

            if(this.user.length != 0){
                
                let days = [
                    { day: 'sun', field: 'teleSun' },
                    { day: 'mon', field: 'teleMon' },
                    { day: 'tue', field: 'teleTue' },
                    { day: 'wed', field: 'teleWed' },
                    { day: 'thurs', field: 'teleThurs' },
                    { day: 'fri', field: 'teleFri' },
                    { day: 'sat', field: 'teleSat'},
                ]

                for(let i = 0; i < 7; i++){
                    let day = days[i]
                    if (drData[ day.field ] == null || drData[ day.field ] == '') {
                        this.weekdays[ day.day ] = i;
                    }
                    else if( drData[ day.field ][0] == '' ){
                        this.weekdays[ day.day ] = i;
                    }
                    else {
                        this.weekdays[ day.day ] = drData[ day.field ];
                    }
                }
            }
        }
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
