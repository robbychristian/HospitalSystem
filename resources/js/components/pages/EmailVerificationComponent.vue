<template>

    <div class="email-verify">
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

        <div class="welcome-teledocs" v-if="verify == null">
            <h1>Welcome to teledocs!</h1>
            <h6> Please request a verification code before you proceed!</h6>
            <h6> The code will be send to your email</h6>
            <button class="btn" @click="requestCode()">Request Verification code</button>

            <form action="/offline" method="POST">
                <input type="hidden" name="_token" v-bind:value="token">
                <button class="btn btn-secondary bg-secondary btn-sm" type="submit">Log out</button>
            </form>
        </div>
        
        <div class="email-verify-content" v-else>
            
            <h2> Enter Validation Code</h2>
            <h3> Before you continue to Teledocs you need to verify your email first</h3>
            <h3> we send a code in to your email.</h3>
            <p> {{expiration}}</p>
            <div>
                
                <div class="code-box">
                    <h3>CODE:</h3>
                    <input ref="inpt1" v-model="inpt1" type="text" @paste="onPaste" @keyup="isAlphaNumeric(2, $event)" @keyup.left="prevInput(0)" @keyup.right="nextInput(2)" maxlength="1" autofocus>
                    <input ref="inpt2" v-model="inpt2" type="text" @paste="onPaste" @keyup="isAlphaNumeric(3, $event)" @keyup.left="prevInput(1)" @keyup.right="nextInput(3)" maxlength="1">
                    <input ref="inpt3" v-model="inpt3" type="text" @paste="onPaste" @keyup="isAlphaNumeric(4, $event)" @keyup.left="prevInput(2)" @keyup.right="nextInput(4)" maxlength="1">
                    <input ref="inpt4" v-model="inpt4" type="text" @paste="onPaste" @keyup="isAlphaNumeric(5, $event)" @keyup.left="prevInput(3)" @keyup.right="nextInput(5)" maxlength="1">
                    <input ref="inpt5" v-model="inpt5" type="text" @paste="onPaste" @keyup="isAlphaNumeric(6, $event)" @keyup.left="prevInput(4)" @keyup.right="nextInput(6)" maxlength="1">
                    <input ref="inpt6" v-model="inpt6" type="text" @paste="onPaste" @keyup="isAlphaNumeric(7, $event)" @keyup.left="prevInput(5)" @keyup.right="nextInput(7)" maxlength="1">
                </div>
                <button type="button" @click="verifyCode()" class="btn-verify" :disabled="isDisabled">Verify</button>

            </div>
            
            <div class="backout-box">

                <form action="/email/resend" method="POST">
                    <input type="hidden" name="_token" v-bind:value="token">
                    <button class="btn btn-link btn-sm" type="submit">Did not recieve the code?</button>
                </form>
                <!-- <a type="button" @click="newCode()"> Did not recieve the code?</a> -->
                
                <form action="/offline" method="POST">
                    <input type="hidden" name="_token" v-bind:value="token">
                    <button class="btn btn-link btn-sm" type="submit">Log out</button>
                </form>

                <b-button class="btn-popover" v-b-popover.hover.top="'Ask the teledocs admin to change your email!'" title="Wrong email?">
                    Current {{email}}
                </b-button>
            </div>
        </div>

    </div>

</template>

<script>
    import moment from 'moment'
    export default {
        props:['email', 'verifyData', 'token'],

        data() {
            return { 
                verify: JSON.parse(this.verifyData),

                alertBackground: '',
                error: '',
                dismissSecs: 5,
                dismissCountDown: 0,

                expiration: JSON.parse(this.verifyData) ? 'PROCESSING' : '',
                
                isDisabled: true,
                inpt1: '',
                inpt2: '',
                inpt3: '',
                inpt4: '',
                inpt5: '',
                inpt6: '',
            }
        },

        methods:{

            verifyCode(){

                var distance = moment(this.verify.expiration).diff(moment())
                
                if (distance < 0) {
                    this.error = "Code is expired please request a new code"
                    this.alertBackground = 'danger'
                    this.showAlert()
                }
                else{
                    let code = this.inpt1 + this.inpt2 + this.inpt3 + this.inpt4 + this.inpt5 + this.inpt6
                    console.log(code.toUpperCase()  + " " + this.verify.code)

                    if( code.toUpperCase() == this.verify.code.toUpperCase()){
                        let self = this

                        axios.post('/email/verify', {
                            id: this.verify.id,
                        })
                        .then( function (response){
                            if(response.data.error){
                                self.alertBackground = "danger"
                                self.error = response.data.message
                                self.showAlert()
                            }
                            else{
                                window.location.href = "/dashboard"
                            }
                            
                        })
                        .catch( function (error){
                            console.log(error);
                        });
                    }
                    else{
                        this.error = "Wrong Code!"
                        this.alertBackground = 'danger'
                        this.showAlert()
                    }
                }
            },

            newCode(){
                let self = this

                axios.post('/email/resend', {
                    id: this.verify.id,
                })
                .then( function (response){
                    if(response.data.error){
                        self.alertBackground = "danger"
                    }
                    else{
                        self.verify = JSON.parse(response.data.verify)
                        self.alertBackground = "success"
                        self.expiration = 'PROCESSING'
                    }
                    
                    self.error = response.data.message
                    self.showAlert()
                })
                .catch( function (error){
                    console.log(error);
                });

            },

            requestCode(){
                let self = this

                axios.post('/email/request')
                .then( function (response){
                    if(response.data.error){
                        self.alertBackground = "danger"
                    }
                    else{
                        self.verify = JSON.parse(response.data.verify)
                        self.alertBackground = "success"
                        self.expiration = 'PROCESSING'
                    }
                    
                    self.error = response.data.message
                    self.showAlert()
                })
                .catch( function (error){
                    console.log(error);
                });

            },

            isAlphaNumeric(next, event){
                if(next != 7){
                    var key = event.which
                    if(key == 8){
                        this.check()
                    }
                    else if( (key >= 48 && key <= 57) || (key >= 65 && key <= 90) ){
                        this.nextInput(next)
                    }
                }
                else{
                    this.check()
                }
            },  

            nextInput(next){
                if(next != 7){
                    this.$refs['inpt'+next].focus()
                    this.$refs['inpt'+next].select()
                }
                this.check()
            },

            prevInput(prev){
                if(prev != 0){
                    this.$refs['inpt'+prev].focus()
                    this.$refs['inpt'+prev].select()
                }
                this.check()
            },

            check(){
                    if(
                        this.inpt1 != '' && this.inpt2 != '' && this.inpt3 != '' &&
                        this.inpt4 != '' && this.inpt5 != '' && this.inpt6 != '' 
                    )
                        this.isDisabled = false
                    else{
                        this.isDisabled = true
                    }
            },   
            
            onPaste(event){
                let text = event.clipboardData.getData('text/plain')

                this.inpt1 = text[0] ? text[0] : '' 
                this.inpt2 = text[1] ? text[1] : '' 
                this.inpt3 = text[2] ? text[2] : '' 
                this.inpt4 = text[3] ? text[3] : '' 
                this.inpt5 = text[4] ? text[4] : '' 
                this.inpt6 = text[5] ? text[5] : '' 

                this.check()
            },

            countDownChanged(dismissCountDown) { this.dismissCountDown = dismissCountDown },
            showAlert() { this.dismissCountDown = this.dismissSecs },
        },

        watch:{

            expiration: {
                handler(value) {
                    
                    setTimeout(() => { 
                        var distance = moment(this.verify.expiration).diff(moment())
                        
                        this.expiration = moment(distance).format("mm:ss")
                        
                        if (distance < 0) {
                            this.expiration = "EXPIRED";
                        }

                    }, 1010)
                },

                immediate: true 
            },
        },
    }
</script>