<template>
    <div class="container pt-5">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div
                                class="col-lg-6 d-none d-lg-block"
                                style="margin-top: 80px"
                            >
                                <app-vecindapp-banner
                                    size="sm"
                                ></app-vecindapp-banner>
                            </div>
                            <div
                                class="col-lg-6 d-sm-none"
                                style="margin-top: 30px"
                            >
                                <app-vecindapp-banner
                                    size="sm"
                                ></app-vecindapp-banner>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-lg-5 p-2">
                                    <form class="user">
                                        <div class="form-group">
                                            <b-form-input
                                                type="email"
                                                class="form-control form-control-user"
                                                id="exampleInputEmail"
                                                aria-describedby="emailHelp"
                                                placeholder="Ingrese su usuario..."
                                                v-model="username"
                                                @input="$v.username.$touch()"
                                                :state="
                                                    !$v.username.$dirty
                                                        ? null
                                                        : !$v.username.$error
                                                "
                                            />
                                        </div>
                                        <div class="form-group">
                                            <b-form-input
                                                type="password"
                                                class="form-control form-control-user"
                                                id="exampleInputPassword"
                                                placeholder="Clave"
                                                v-model="password"
                                                @input="$v.password.$touch()"
                                                :state="
                                                    !$v.password.$dirty
                                                        ? null
                                                        : !$v.password.$error
                                                "
                                            />
                                        </div>

                                        <a
                                            href="index.html"
                                            class="btn btn-primary btn-user btn-block"
                                            @click.prevent="onLogin"
                                            >Login</a
                                        >
                                        <hr />
                                    </form>
                                    <hr />
                                    <div class="text-center">
                                        <b-btn
                                            variant="link"
                                            @click="goToPasswordReset"
                                            >Olvidé la contraseña</b-btn
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { required } from "vuelidate/lib/validators";
import { mapMutations, mapActions } from "vuex";
//import * as mutations from "@store/_mutations";
import * as mutations from "../../../store/_mutations.js";

export default {
    data: () => ({
        password: "",
        username: "",
    }),
    validations: {
        username: { required },
        password: { required },
    },
    methods: {
        ...mapMutations({
            setCurrentUser: mutations.SET_CURRENT_USER,
        }),
        async onLogin() {
            this.$v.$touch();
            if (this.$v.$invalid)
                return toastr.warning(
                    "Debe escribir el usuario y la contraseña"
                );

            const { data } = await this.$repo.post("login", {
                username: this.username,
                password: this.password,
            });

            if (data.status) {
                console.log(data);
                this.setCurrentUser(data.user);
            } else {
                toastr.warning("Usuario o contraseña incorrectos");
            }
        },
        validateAction() {
            const { p, a } = this.$route.query;
            if (!!a) {
                this.$router.push({ name: a, params: { payload: p } });
            }
        },
        goToPasswordReset() {
            this.$router.push({ name: "passwordReset" });
        },
    },
    mounted() {
        this.validateAction();
    },
    beforeCreate() {
        localStorage.clear();
    },
};
</script>
<style scoped>
.authorization-link {
    text-decoration: underline;
}
</style>
