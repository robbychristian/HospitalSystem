<template>
    <div class="calendar-page">
        <FullCalendar :options="calendarOptions" />
        <b-button v-b-modal="modalShow" hidden>Add Appointment</b-button>

        <b-modal
            id="addCalendar"
            centered
            title="Schedule an Appointment"
            @ok="submitForm"
            ref="addCalendar"
        >
            <form
                action="addappointment"
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
                    <b-col sm="2"><label>Start: </label></b-col>
                    <b-col sm="10">
                        <b-form-input
                            v-model="startTime"
                            name="startTime"
                            readonly
                        ></b-form-input>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col sm="2"><label>End: </label></b-col>
                    <b-col sm="10">
                        <b-form-input
                            v-model="endTime"
                            name="endTime"
                            readonly
                        ></b-form-input>
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
export default {
    props: ["csrf", "patients", "appointments"],

    mounted() {
        let data = JSON.parse(this.patients);
        data.forEach(this.toItems);
        console.log(JSON.parse(this.patients));

        let allAppointments = JSON.parse(this.appointments);
        allAppointments.forEach(this.toEvents);
        console.log(JSON.parse(this.appointments));

        console.log(this.calendarOptions.events);
    },

    components: {
        FullCalendar, // make the <FullCalendar> tag available
    },
    data() {
        return {
            //form
            text: "",
            startTime: "",
            endTime: "",
            description: "",
            selectedPatient: "",
            options: [],
            //Calendar
            modalShow: "addCalendar",
            calendarOptions: {
                plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin],
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek",
                },
                initialView: "timeGridWeek",
                aspectRatio: 2.3,
                dateClick: this.handleDateClick,
                expandRows: true,
                selectable: true,
                events: [],
            },
        };
    },
    methods: {
        submitForm() {
            this.$refs["form"].submit();
        },
        handleDateClick(arg) {
            this.$refs["addCalendar"].show();
            this.startTime = moment(arg.dateStr).format("ddd | LL, LT");
            this.endTime = moment(arg.dateStr)
                .add(30, "minutes")
                .format("ddd | LL, LT");
            console.log(arg);
            //let calendarApi = arg.view.calendar;
            //
            //calendarApi.unselect(); // clear date selection
            //
            //if (title) {
            //    calendarApi.addEvent({
            //        id: uuidv4(),
            //        title,
            //        start: arg.startStr,
            //        end: arg.endStr,
            //        allDay: arg.allDay,
            //    });
            //}
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
                title: item.title,
                start: moment(item.start).format("YYYY-MM-DD HH:mm"),
                end: moment(item.end).format("YYYY-MM-DD HH:mm"),
            };

            this.calendarOptions.events.push(data);
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
