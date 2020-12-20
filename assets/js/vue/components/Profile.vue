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
            name="profile"
            method="post"
            @submit.prevent="submit"
        >
            <div class="row form-group justify-content-md-center">
                <div class="col-md-2">
                    <label
                        for="profile-email"
                        class="required"
                    >
                        {{ $t('email') }}
                    </label>
                </div>
                <div class="col-md-2">
                    <input
                        id="profile-email"
                        v-model="email"
                        type="email"
                        name="profile[email]"
                        required="required"
                        :placeholder="$t('email')"
                        maxlength="180"
                        class="form-control form-control-sm form-control"
                    >
                </div>
            </div>

            <div class="row form-group justify-content-md-center">
                <div class="col-md-2">
                    <label
                        for="profile-name"
                        class="required"
                    >
                        {{ $t('name') }}
                    </label>
                </div>
                <div class="col-md-2">
                    <input
                        id="profile-name"
                        v-model="username"
                        type="text"
                        name="profile[name]"
                        required="required"
                        :placeholder="$t('name')"
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
    name: 'Profile',
    data() {
        return {
            username: '',
            email: '',
            errors: null,
            success: false,
        };
    },
    async created() {
        const response = await axios.get(`/api/users/${window.userid}`);
        this.$data.email = response.data.email;
        this.$data.username = response.data.name;
    },
    methods: {
        submit() {
            this.$data.errors = null;
            this.$data.success = false;
            axios
                .patch(
                    `/api/users/${window.userid}.jsonld`,
                    {
                        email: this.$data.email,
                        name: this.$data.username,
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
    success: Your account has been successfully updated.
    email: Email
    name: Name
    save: Save
fr:
    success: Votre compte a bien été mis à jour.
    email: Email
    name: Nom
    save: Enregistrer
</i18n>
