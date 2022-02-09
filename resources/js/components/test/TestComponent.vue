<template>

    <div class="p-5">
        
        <li v-for="item in test">
            {{item['name']}}
        </li>

    </div>
</template>

<script>
    export default {
        props:['data'],

        data() {
            return { 
                test: JSON.parse(this.data), 
            }
        },

    }
</script>