<template>
    <div>
        <div
            v-if="errors"
            class="alert alert-danger"
        >
            <div
                v-for="error in errors"
                :key="error.message"
            >
                {{ error.message }}
            </div>
        </div>
        <div
            v-if="success"
            class="alert alert-success"
        >
            {{ $t('success') }}
        </div>
        <form
            name="password"
            method="post"
            @submit.prevent="submit"
        >
            <div class="row form-group justify-content-md-center">
                <div class="col-md-2">
                    <label
                        for="password"
                        class="required"
                    >
                        {{ $t('password') }}
                    </label>
                </div>
                <div class="col-md-2">
                    <input
                        id="password"
                        v-model="password"
                        type="text"
                        name="profile[password]"
                        required="required"
                        :placeholder="$t('password')"
                        class="form-control form-control-sm form-control"
                    >
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <button
                    class="btn btn-md btn-outline-primary mt-2"
                    type="submit"
                    name="profile[submit]"
                >
                    {{ $t('save') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Password',
    data() {
        return {
            password: '',
            errors: null,
            success: false,
        };
    },
    methods: {
        submit() {
            if (!this.$data.password) {
                return;
            }
            this.$data.errors = null;
            this.$data.success = false;
            axios
                .patch(
                    `/api/users/${window.userid}.jsonld`,
                    {
                        password: this.$data.password,
                    },
                    {
                        headers: {
                            'Content-Type': 'application/merge-patch+json',
                        },
                    },
                ).then(() => {
                    this.$data.success = true;
                })
                .catch((error) => {
                    this.$data.errors = error.response.data.violations
                        ? error.response.data.violations
                        : ['There was an error during the update. Please try again later.'];
                });
        },
    },
};
</script>

<style scoped>

</style>

<i18n lang="yaml">
en:
    success: Your password has been successfully updated.
    password: Password
    save: Save
fr:
    success: Votre compte a bien été mis à jour.
    password: Mot de passe
    save: Enregistrer
</i18n>
