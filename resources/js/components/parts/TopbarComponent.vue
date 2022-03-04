<template>
    <div class="topbar">
        <h2>{{pageName}}</h2>
        
        <div class="btn-holder">
            <button v-if="isOpen" @click="closeSidebar()" class="btn text-success"><i class="fa-solid fa-xmark"></i></button>
            <button v-else @click="openSidebar()" class="btn text-success"><i class="fa-solid fa-bars"></i></button>
        </div>
    </div>
</template>

<script>
    export default {
        props:['page-name'],

        data() {
            return { 
                isOpen: false,
            }
        },

        // mounted(){
        //     console.log(this.pageName)
        // },

        methods:{
            openSidebar(){
                document.getElementById('sidebar').style.setProperty( 'width', '100px');
                this.isOpen = true;
            },

            
            closeSidebar(){
                document.getElementById('sidebar').style.setProperty( 'width', '0px');
                this.isOpen = false;
            },
        },

    }
</script>