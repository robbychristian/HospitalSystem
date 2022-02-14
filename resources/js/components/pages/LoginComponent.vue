<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div
                class="col-xl-12 d-flex align-items-center justify-content-center login-bg"
                style="background-color: #004f91; height: 94.2vh"
            >
                <b-card
                    img-src=""
                    img-alt="Image"
                    img-top
                    tag="article"
                    style="max-width: 30rem"
                    class="mb-2"
                >
                    <b-form @submit="onSubmit" v-if="show">
                        <b-form-group
                            id="input-group-1"
                            label="Email:"
                            label-for="input-1"
                            class="mb-2"
                        >
                            <b-form-input
                                id="input-1"
                                v-model="form.email"
                                type="email"
                                required
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group
                            id="input-group-2"
                            label="Password:"
                            label-for="input-2"
                        >
                            <b-form-input
                                id="input-2"
                                v-model="form.pass"
                                type="password"
                                required
                            ></b-form-input>
                        </b-form-group>

                        <div class="text-center mt-3">
                            <b-button
                                type="submit"
                                variant="primary"
                                style="width: 8rem"
                                class="mx-auto"
                                >Login
                            </b-button>
                        </div>
                    </b-form>
                </b-card>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["data"],
    data() {
        return {
            form: {
                email: "",
                pass: "",
            },
            show: true,
            img: "../../../public/images/logo1.png",
        };
    },
    methods: {
        onSubmit(event) {
<<<<<<< HEAD
            // event.preventDefault();
=======
            event.preventDefault();
>>>>>>> parent of 5434b79 (Polished Login)
            const email = this.form.email;
            const pass = this.form.pass;

            const arr = JSON.parse(this.data);

            let loggedIn = false;
            let self = this;

            arr.map((item, index) => {
                if (item.email == email && item.password == pass) {
                    loggedIn = true;
                }
            });

            if (loggedIn == false) {
                alert("Credentials does not match");
            } else {
                axios
                    .post("/sign-in/", {
                        params: {
                            email: this.form.email,
                            pass: this.form.pass,
                        },
                    })
                    .then((response) => {
                        console.log(response.data);
                    });
            }
        },
    },
};
</script>
