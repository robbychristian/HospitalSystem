<template>
    <div class="inquiry-page">

        <div class="alert-top">
            <b-alert
                :show="dismissCountDown"
                variant="danger"
                @dismissed="dismissCountDown=0"
                @dismiss-count-down="countDownChanged"
                fade
                
            >
                {{ error }} 
            </b-alert>
        </div>

        <div class="inquiry-content">

            
            <div class="patient-box">
                <!-- <div class="option-box">
                    <label> Search patients: </label>
                    <input type="text" v-model="keyword">
                    <h6> Patient chosen: {{email.name}}</h6>
                </div>

                <div class="patient-list">
                    <div :class="[{ 'bg-dark-green': email.name == data.name ? true : false }, 'patient-info']"
                        v-for="data in items" :key="data.id" @click="email = data">

                        <input type="radio" v-model="email" :value="data">
                        <img :src="data.avatar" :alt="data.name">
                        <h6> {{data.name}}</h6>
                    </div>
                </div> -->

                
                <div class="form">
                    
                    <h6>Patients's Name: </h6>

                    <div class="form-input">
                        
                        <input :class="['mt-2']" type="text" placeholder="Patient Name">
                    </div>

                    <h6>Title: </h6>

                    <div class="form-input">
                        
                        <input :class="['mt-2']" type="text" placeholder="Patient Name">
                    </div>

                    <h6>Subject: </h6>

                    <div class="form-input">
                        
                        <input :class="['mt-2']" type="text" placeholder="Patient Name">
                    </div>
                    
                    <h6>Body: </h6>

                    <div class="form-input">
                        
                        <textarea :class="['mt-2']" type="text" placeholder="Patient Name"> </textarea>
                    </div>
                </div>
                    
            </div>
            


            <div class="divider"></div>

            <div class="file-box">
                    <div class="input-file"
                        @click="triggerInputFile"
                        @drop.prevent="dropFile"
                        @dragover.prevent
                        @dragenter="changeDesign(true)"
                        @dragleave="changeDesign(false)"
                        id="input-box"
                    >
                        <input type="file" id="file-input" @change="addFile" accept=".pdf, .doc, .docx," hidden>
                        Choose a file or drop it here
                    </div>

                <h6 v-show="file">
                    <span class="text-dark-green font-bold"> File uploaded: </span> {{file ? file.name : "" }} 
                </h6>
            </div>

            <button class="btn btn-success">Send!</button>

        </div>
    </div>
</template>

<script>
    export default {
        props:['patient'],

        data() {
            return { 
                file: null,
                dismissSecs: 5,
                dismissCountDown: 0,
                error: '',

                keyword: '',
                static: [],
                email: '',
            }
        },

        methods: {
            triggerInputFile(){ document.getElementById('file-input').click() },

            addFile(event){
                this.file = event.target.files[0]
            },

            changeDesign(enter){
                if(enter) document.getElementById('input-box').classList.add("bg-aiai")
                else document.getElementById('input-box').classList.remove("bg-aiai")
            },

            dropFile(event){
                let file = event.dataTransfer.files[0]
                let name = file.name
                let ext = name.substr(name.lastIndexOf('.'))

                if( ".doc .docx .pdf".includes(ext) ){
                    this.file = file
                }
                else{
                    this.error = "Error only .docx .doc and .pdf extensions are accepted";
                    this.showAlert()
                    document.getElementById('input-box').classList.remove("bg-aiai")
                }
            },
            
            toItems(item){

                let data = {
                    name: item.fname + " " + item.lname,
                    email: item.email,
                    avatar: item.avatar,
                    id: item.id,
                }

                this.static.push(data)
            },
            
            countDownChanged(dismissCountDown) { this.dismissCountDown = dismissCountDown },
            showAlert() { this.dismissCountDown = this.dismissSecs },
        },

        computed: {
            items () {
                return this.keyword
                    ? this.static.filter(
                        item => item.name.toLowerCase().includes(this.keyword.toLowerCase()) 
                    )
                    : this.static
            }
        },

        mounted(){
            let data = JSON.parse(this.patient)
            
            data.forEach(this.toItems)
            
        }

    }
</script>