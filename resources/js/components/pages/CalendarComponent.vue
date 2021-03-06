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

            <div
                class="col d-flex mx-3 justify-content-start"
                v-else-if="this.isAdmin == 0"
            >
                <b-button
                    @click="showAppointments"
                    class="btn"
                    style="background-color: #14679b"
                    ><i class="fas fa-list"></i> Hospital Appointments</b-button
                >
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

        <!-- MODAL FOR SHOW APPOINTMENT -->
        <b-modal
            id="modal-tall"
            centered
            title="Hospital Appointments"
            ref="showAppointments"
            size="xl"
            :fields="this.fields"
        >
            <b-table striped hover :items="appointmentModalData">
                <template #cell(Actions)="data">
                    <span v-html="data.value"></span>
                </template>
            </b-table>
        </b-modal>
        <!-- MODAL FOR SHOW APPOINTMENT -->

        <!-- MODAL FOR ADD APPOINTMENT -->
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
                    <b-col sm="4"><label>Appointment State: </label></b-col>
                    <b-col sm="8">
                        <input
                            type="radio"
                            id="one"
                            value="Teleconsultation"
                            :disabled="
                                user.provideTeleService == 0 ? true : false
                            "
                            @change="changeDate(false)"
                            v-model="appointState"
                        />
                        <label for="one">Teleconsultation</label>

                        <br />

                        <input
                            type="radio"
                            id="two"
                            value="Hospital"
                            :disabled="
                                user != null && user != ''
                                    ? user.hospital.length <= 0
                                    : true
                            "
                            v-model="appointState"
                            @change="changeDate(true)"
                        />
                        <label for="two">Hospital</label>

                        <input
                            type="hidden"
                            name="appointState"
                            :value="appointState"
                        />
                    </b-col>
                </b-row>

                <b-row v-if="appointState == 'Hospital'">
                    <b-col sm="2"><label>Hospital: </label></b-col>
                    <b-col sm="10">
                        <b-form-select
                            v-model="selectedHospital"
                            :options="optHos"
                            name="hospital"
                            @input="updateHospital()"
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
                            @input="updateSlotsOrQueue()"
                            :disabled="
                                user.length == 0 ||
                                appointState == '' ||
                                (appointState == 'Hospital' &&
                                    selectedHospital == '')
                            "
                        ></b-form-datepicker>
                    </b-col>
                </b-row>

                <b-row v-if="appointState == 'Teleconsultation'">
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

                <b-row v-else-if="appointState == 'Hospital'">
                    <b-col sm="2"><label>Queue: </label></b-col>
                    <b-col sm="10"
                        ><label> {{ queue }} </label></b-col
                    >
                    <input
                        type="hidden"
                        name="hospitalName"
                        :value="
                            selectedHospital == '' || selectedHospital == null
                                ? ''
                                : selectedHospital.hospitalName
                        "
                    />
                    <input
                        type="hidden"
                        name="hospitalAddress"
                        :value="
                            selectedHospital == '' || selectedHospital == null
                                ? ''
                                : selectedHospital.hospitalAddress
                        "
                    />
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
            </form>
        </b-modal>
        <!-- MODAL FOR ADD APPOINTMENT -->
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
import { extendMoment } from "moment-range";

const moment = extendMoment(Moment);

export default {
    props: [
        "csrf",
        "patients",
        "appointments",
        "isAdmin",
        "doctorsData",
        "userData",
        "drUserData",
    ],

    mounted() {
        console.log(this.user);
        let data = JSON.parse(this.patients);

        data.forEach(this.toItems);

        this.isPatient = false;
        data = this.doctors;
        data.forEach(this.toItems);

        let allAppointments = this.appointmentss;

        allAppointments.forEach(this.toEvents);
        const arr = [4, 3, 6, 8, 1, 2, 0];
        //FOR MODAL
        const appointmentModal = JSON.parse(this.appointments);
        for (let i = 0; i < appointmentModal.length; i++) {
            if (
                appointmentModal[i].appointDate == moment().format("MM/DD/y") &&
                appointmentModal[i].appointState == "Hospital"
            ) {
                console.log(moment(appointmentModal[i].bookingSchedule));
                console.log(moment().format("MM/DD/y"));
                const newObject = {
                    Queue: appointmentModal[i].prescribeNo,
                    Patient:
                        appointmentModal[i].pfName +
                        " " +
                        appointmentModal[i].plName,
                    Date: appointmentModal[i].appointDate,
                    Status: appointmentModal[i].status,
                    Actions: `<div class="d-flex justify-content-space"><div class="px-1"><a href="calendar/setongoing/${appointmentModal[i].id}" class="btn btn-primary"><i class="fas fa-history"></i></a></div><div class="px-1"><a href="calendar/setlate/${appointmentModal[i].id}" class="btn btn-warning"><i class="fa-solid fa-user-clock text-white"></i></a></div><div class="px-1"><a href="calendar/setearly/${appointmentModal[i].id}" class="btn btn-success mr-2"><i class="fas fa-user-check"></i></a></div><div class="px-1"><a href="calendar/cancel/${appointmentModal[i].id}" class="btn btn-danger mr-2">Cancel</a></div></div>`,
                };
                this.appointmentModalData.push(newObject);
                //SORTING DEPENDE SA QUEUE
                this.appointmentModalData = this.appointmentModalData.sort(
                    (a, b) => {
                        if (a.Queue < b.Queue) {
                            return -1;
                        }
                        if (a.Queue > b.Queue) {
                            return 1;
                        }
                        return 0;
                    }
                );
            } else {
                console.log("Nothing here");
            }
        }
        // this.populateWeekdays();
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
            date: "",
            min: new Date(),
            //FOR TIME SLOT
            timeSlot: null,
            allSlot: [],

            description: "",
            selectedPatient: "",
            selectedDoctor: "",
            selectedHospital: "",
            options: [],
            optDoc: [],
            optHos: [],
            modalShow: "addCalendar",
            appointState: "",

            //filter
            doctorName: "Choose a Doctor",
            doctor: "",

            //FOR SHOW APPOINTMENTS MODAL
            fields: ["Patient", "Date", "Time", "Status", "Action"],
            appointmentModalData: [],
            queue: "",

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
        updateSlotsOrQueue() {
            if (this.appointState == "Hospital") {
                this.updateQueue();
            } else {
                this.updateSlots();
            }
        },

        changeDate(isHospital) {
            this.timeSlot = null;
            this.selectedHospital = "";
            this.date = "";
            this.queue = "";

            if (isHospital) {
                this.pupulateOptHos();
            } else {
                this.populateWeekdays();
            }
        },

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
        showAppointments() {
            this.$refs["showAppointments"].show();
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
            } else if (
                this.appointState == "Hospital" &&
                this.selectedHospital == ""
            ) {
                swal({
                    title: "Error",
                    text: "Some input fields are empty!",
                    icon: "error",
                });
            } else if (
                this.appointState == "Teleconsultation" &&
                this.timeSlot == null
            ) {
                swal({
                    title: "Error",
                    text: "Some input fields are empty!",
                    icon: "error",
                });
            } else if (
                this.appointState == "Hospital" &&
                this.queue == "FULL"
            ) {
                swal({
                    title: "Error",
                    text: "Queue is Already Full!",
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
                text: "Are you sure you want to remove the appointment?",
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

        pupulateOptHos() {
            this.optHos = [];
            let length = this.user.hospital.length;

            for (let i = 0; i < length; i++) {
                let data = {
                    value: this.user.hospital[i],
                    text: this.user.hospital[i].hospitalName,
                };

                this.optHos.push(data);
            }
        },

        toEvents(item) {
            let date = item.appointDate;
            let schedule = item.bookingSchedule;
            let start =
                schedule == "" || schedule == null
                    ? ""
                    : schedule.substring(
                          schedule.indexOf(" ") + 1,
                          schedule.lastIndexOf("-") - 1
                      );
            let end =
                schedule == "" || schedule == null
                    ? ""
                    : schedule.substring(schedule.lastIndexOf(" ") + 1);
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

        updateSlots() {
            let day = moment(this.date).days();
            let dayName = moment(this.date).format("ddd");

            this.allSlot = [];
            let days = [
                { day: "sun", field: "teleSun" },
                { day: "mon", field: "teleMon" },
                { day: "tue", field: "teleTue" },
                { day: "wed", field: "teleWed" },
                { day: "thurs", field: "teleThurs" },
                { day: "fri", field: "teleFri" },
                { day: "sat", field: "teleSat" },
            ];

            let week = this.weekdays[days[day].day];
            let startTime = moment(this.date + " " + week[0]);
            let endTime = moment(this.date + " " + week[1]);

            let totalMin =
                (endTime.hour() - startTime.hour()) * 60 +
                (endTime.minute() - startTime.minute());
            let slots = parseInt(totalMin / week[2]);

            for (let i = 0; i < slots; i++) {
                let start = startTime.format("HH:mm");
                let startA = startTime.format("h:mmA");
                startTime.add(week[2], "m");
                let end = startTime.format("HH:mm");
                let endA = startTime.format("h:mmA");

                let slot = dayName + ": " + start + " - " + end;

                if (!this.conflicts(slot)) {
                    this.allSlot.push({
                        value: slot,
                        text: startA + " - " + endA,
                    });
                }
            }
        },

        updateHospital() {
            this.date = "";
            this.populateWeekdays();
        },

        updateUser() {
            let length = this.doctors.length;

            for (let i = 0; i < length; i++) {
                if (this.doctors[i].id == this.selectedDoctor)
                    this.user = this.doctors[i];
            }

            // console.log(this.user);
            this.appointState = "";

            // this.populateWeekdays();
        },

        updateQueue() {
            let appointDate = moment(this.date).format("L");
            let length = this.appointmentss.length;
            let day = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];
            let id = this.user.id;

            let maxQueue = parseInt(
                this.selectedHospital[day[moment(this.date).day()]][2]
            );
            let count = 0;

            for (let i = 0; i < length; i++) {
                if (appointDate == this.appointmentss[i].appointDate) {
                    if (id == this.appointmentss[i].drId) {
                        if ("Hospital" == this.appointmentss[i].appointState)
                            count++;
                    }
                }
            }

            if (count < maxQueue) this.queue = "NOT FULL";
            else this.queue = "FULL";
        },

        conflicts(slot) {
            let length = this.appointmentss.length;
            let appointDate = moment(this.date).format("L");
            let id = this.user.id;

            let sched = slot.substr(slot.indexOf(" ") + 1);
            sched = sched.trim();

            let time1s = sched.substr(0, sched.indexOf(" "));
            let time1e = sched.substr(sched.lastIndexOf(" ") + 1);
            var date1 = [
                moment(appointDate + " " + time1s),
                moment(appointDate + " " + time1e),
            ];
            var range = moment.range(date1);

            for (let i = 0; i < length; i++) {
                if (appointDate == this.appointmentss[i].appointDate) {
                    if (id == this.appointmentss[i].drId) {
                        if ("Hospital" != this.appointmentss[i].appointState) {
                            sched = this.appointmentss[
                                i
                            ].bookingSchedule.substr(
                                this.appointmentss[i].bookingSchedule.indexOf(
                                    " "
                                ) + 1
                            );
                            sched = sched.trim();
                            time1s = sched.substr(0, sched.indexOf(" "));
                            time1e = sched.substr(sched.lastIndexOf(" ") + 1);

                            var date2 = [
                                moment(appointDate + " " + time1s),
                                moment(appointDate + " " + time1e),
                            ];
                            var range2 = moment.range(date2);

                            if (range.overlaps(range2)) {
                                return true;
                            }
                        }
                    }
                }
            }
            return false;
        },

        populateWeekdays() {
            //SETTING AVAILABLE DAYS
            let drData = this.user;
            this.selectedDoctor = this.user.id;

            if (this.user.length != 0) {
                let days = [
                    { day: "sun", field: "teleSun" },
                    { day: "mon", field: "teleMon" },
                    { day: "tue", field: "teleTue" },
                    { day: "wed", field: "teleWed" },
                    { day: "thurs", field: "teleThurs" },
                    { day: "fri", field: "teleFri" },
                    { day: "sat", field: "teleSat" },
                ];
                if (this.appointState == "Hospital") {
                    drData = this.selectedHospital;

                    days = [
                        { day: "sun", field: "sun" },
                        { day: "mon", field: "mon" },
                        { day: "tue", field: "tue" },
                        { day: "wed", field: "wed" },
                        { day: "thurs", field: "thu" },
                        { day: "fri", field: "fri" },
                        { day: "sat", field: "sat" },
                    ];
                }

                for (let i = 0; i < 7; i++) {
                    let day = days[i];
                    if (drData[day.field] == null || drData[day.field] == "") {
                        this.weekdays[day.day] = i;
                    } else if (drData[day.field][0] == "") {
                        this.weekdays[day.day] = i;
                    } else {
                        this.weekdays[day.day] = drData[day.field];
                    }
                }
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
