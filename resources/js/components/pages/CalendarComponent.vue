<template>
    <div class="calendar-page">
        <div class="row">
            <div
                class="col d-flex mx-3 justify-content-start"
                v-if="this.isAdmin == 1"
            >
                <!-- <b-dropdown
                    split
                    split-variant="outline-primary"
                    variant="primary"
                    :text="doctorName"
                    class="m-2"
                >
                    <b-dropdown-item-button @click="showAlert"
                        >Action</b-dropdown-item-button
                    >
                    <b-dropdown-item href="#">Another action</b-dropdown-item>
                    <b-dropdown-item href="#"
                        >Something else here...</b-dropdown-item
                    >
                </b-dropdown> -->
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
                    <b-col sm="2"><label>Title: </label></b-col>
                    <b-col sm="10">
                        <b-form-input
                            v-model="text"
                            name="title"
                        ></b-form-input>
                    </b-col>
                </b-row>
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
    props: ["csrf", "patients", "appointments", "isAdmin"],

    mounted() {
        let data = JSON.parse(this.patients);
        data.forEach(this.toItems);
        console.log(JSON.parse(this.patients));

        let allAppointments = JSON.parse(this.appointments);
        allAppointments.forEach(this.toEvents);
        console.log(JSON.parse(this.appointments));

        console.log(this.calendarOptions.events);

        console.log("Admin Status: " + this.isAdmin);
    },

    components: {
        FullCalendar, // make the <FullCalendar> tag available
    },
    data() {
        return {
            //form
            text: "",
            date: moment(new Date()).format("YYYY-MM-DD"),
            startTime: moment(new Date()).format("HH:mm:ss"),
            endTime: moment(this.startTime)
                .add(30, "minutes")
                .format("HH:mm:ss"),
            description: "",
            selectedPatient: "",
            options: [],
            modalShow: "addCalendar",

            //filter
            doctorName: "Choose a Doctor",

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
        addCalendar() {
            this.$refs["addCalendar"].show();
            console.log(this.startTime);
        },
        submitForm() {
            if (
                this.text == "" ||
                this.date == "" ||
                this.description == "" ||
                this.selectedPatient == ""
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
            let data = {
                id: item.id,
                title: item.title,
                start: moment(item.start).format("YYYY-MM-DD HH:mm"),
                end: moment(item.end).format("YYYY-MM-DD HH:mm"),
            };

            this.calendarOptions.events.push(data);
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
