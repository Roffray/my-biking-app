<template>
    <div>
        <h1 class="h3 my-3 font-weight-normal d-flex justify-content-center">
            {{ $t('title') }}
        </h1>

        <ul
            class="nav nav-tabs justify-content-md-center"
            style="margin-bottom: 1em;"
        >
            <li class="nav-item">
                <a
                    id="profile-tab"
                    class="nav-link"
                    data-toggle="tab"
                    href="#profile"
                    role="tab"
                    aria-controls="profile"
                    aria-selected="true"
                >
                    {{ $t('profile') }}
                </a>
            </li>
            <li class="nav-item">
                <a
                    id="password-tab"
                    class="nav-link"
                    data-toggle="tab"
                    href="#password"
                    role="tab"
                    aria-controls="password"
                    aria-selected="false"
                >
                    {{ $t('password') }}
                </a>
            </li>
            <li class="nav-item">
                <a
                    id="routes-tab"
                    class="nav-link active"
                    data-toggle="tab"
                    href="#routes"
                    role="tab"
                    aria-controls="routes"
                    aria-selected="false"
                >
                    {{ $t('routes') }}
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div
                id="profile"
                class="tab-pane"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <Profile />
            </div>
            <div
                id="password"
                class="tab-pane"
                role="tabpanel"
                aria-labelledby="password-tab"
            >
                <Password />
            </div>
            <div
                id="routes"
                class="tab-pane show active"
                role="tabpanel"
                aria-labelledby="routes-tab"
            >
                <Routes />
            </div>
        </div>
    </div>
</template>

<script>
import Password from './Password';
import Profile from './Profile';
import Routes from './Routes';

export default {
    name: 'Account',
    components: {
        Password,
        Profile,
        Routes,
    },
};
</script>

<style scoped>

</style>

<i18n lang="yaml">
    en:
        title: My account
        profile: Profile
        password: Password
        routes: Routes
    fr:
        title: Mon compte
        profile: Profile
        password: Mot de
        routes: Parcours
</i18n>
