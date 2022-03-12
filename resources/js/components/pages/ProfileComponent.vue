<template>
    <div class="container">asdasda</div>
</template>

<script>
export default {
    props: ["profileData", "csrf", "isAdmin"],

    mounted() {
        console.log(this.isAdmin);
        console.log(JSON.parse(this.profileData));
    },

    data() {
        return {};
    },
};
</script>
