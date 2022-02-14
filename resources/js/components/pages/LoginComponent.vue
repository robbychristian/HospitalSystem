<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div
                class="col-xl-12 d-flex align-items-center justify-content-center login-bg"
            >
                <b-card
                    img-src="../../../../public/img/logo1.png"
                    img-alt="Image"
                    img-top
                    tag="article"
                    style="max-width: 30rem"
                    class="mb-2"
                >
                    <b-alert :show="showAlert" variant="danger">{{
                        error
                    }}</b-alert>
                    <b-form
                        @submit="onSubmit"
                        v-if="show"
                        action="/sign-in"
                        method="POST"
                        v-bind:value="csrf"
                    >
                        <input
                            type="hidden"
                            name="_token"
                            v-bind:value="csrf"
                        />
                        <b-form-group
                            id="input-group-1"
                            label="Email:"
                            label-for="input-1"
                            class="mb-2"
                        >
                            <input
                                type="hidden"
                                name="email"
                                v-bind:value="this.form.email"
                            />
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
                            <input
                                type="hidden"
                                name="data"
                                v-bind:value="JSON.parse(this.data)"
                            />
                            <input
                                type="hidden"
                                name="pass"
                                v-bind:value="this.form.pass"
                            />
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
    props: ["data", "csrf", "error"],
    data() {
        if (this.error != "") {
            this.showAlert = true;
        } else {
            this.showAlert = false;
        }
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
            //event.preventDefault();
            const email = this.form.email;
            const pass = this.form.pass;

            const arr = JSON.parse(this.data);

            let loggedIn = false;
            arr.map((item, index) => {
                if (item.email == email && item.password == pass) {
                    loggedIn = true;
                }
            });

            if (loggedIn == false) {
                alert("Credentials does not match");
            } else {
                axios
                    .post("/sign-in", {
                        params: {
                            email: this.form.email,
                            pass: this.form.pass,
                        },
                    })
                    .then((response) => {
                        console.log(response);
                    });
            }
        },
    },
};
</script>
