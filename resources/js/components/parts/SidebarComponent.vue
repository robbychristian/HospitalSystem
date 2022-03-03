<style>
    .slide-fade-enter-active {
        transition: all 0.3s ease-out;
    }

    .slide-fade-leave-active {
        transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
    }

    .slide-fade-enter-from,
    .slide-fade-leave-to {
        transform: translateX(-20px);
        opacity: 0;
    }
</style>

<template>
    <div class="sidebar" id="sidebar">

        <div class="sidebar-content">
            <div class="sidebar-logo-holder" >
                <img class="sidebar-logo" alt="">
            </div>
            <div class="sidebar-menu-holder">
                
                <div class="sidebar-divider"></div>
            
                <a href="/dashboard" :class="[{ active: active == 'home' ? true : false }, 'sidebar-menu']">
                    <i class="fa-solid fa-house-chimney"></i>
                    <h6 class="mb-0">Home</h6>
                </a>

                <a href="/announcement" :class="[{ active: active == 'announcement' ? true : false }, 'sidebar-menu']">
                    <i class="fa-solid fa-bullhorn"></i>
                    <h6 class="mb-0" >Announcement</h6>
                </a>


                <a href="/patient" :class="[{ active: active == 'patient' ? true : false }, 'sidebar-menu']">
                    <i class="fa-solid fa-hospital-user"></i>
                    <h6 class="mb-0" >Patient</h6>
                </a>


                <a href="/test/calendar" :class="[{ active: active == 'calendar' ? true : false }, 'sidebar-menu']">
                    <i class="fa-solid fa-calendar-days"></i>
                    <h6 class="mb-0" >Calendar</h6>
                </a>

                <a href="/inquiry" :class="[{ active: active == 'inquiry' ? true : false }, 'sidebar-menu']">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <h6 class="mb-0">Lab request or Prescription</h6>
                </a>

                <div class="sidebar-divider"></div>
            </div>

            <button @click="openSettings()" class="collapse-button">
                <i class="fa-solid fa-gear"></i>
                <!-- <i class="fa-solid fa-circle-arrow-right"></i> -->
            </button>

            <Transition name="slide-fade">
                <div class="sidebar-setting" v-show="showSettings">
                    <div class="close-btn">
                        <button @click="closeSettings()" class="btn"><i class="fa-solid fa-xmark fa-lg"></i></button>
                    </div>

                    <div class="settings-content">
                        <div class="profile-img" :style="{ backgroundImage: 'url(' + profilePicture + ')' }"></div>

                        <h5 class="text-uppercase"> {{name == '' ? 'ADMIN' : name}}</h5>

                        <a href="#">Patient</a>
                        <a href="#">Calendar</a>
                        <a href="#">Inquiry</a>


                        <form action="/offline" method="POST">
                            <input type="hidden" name="_token" v-bind:value="token">
                            <button class="btn btn-link" type="submit">Log out</button>
                        </form>
                    </div>
                </div>
            </Transition>
        </div>


    </div>
</template>

<script>
    export default {
        props:['active', 'token', 'name', 'profilePicture'],

        data() {
            return { 
                showSettings: false,
            }
        },

        methods:{

            openSettings(){
                this.showSettings = true
            },

            closeSettings(){
                this.showSettings = false
            },

        },
        
    }
</script>