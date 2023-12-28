<template>
    <div class="topnav">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg">
                <div class="collapse navbar-collapse" id="topnav-menu-content">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <router-link
                                :to="{ name: 'ads' }"
                                class="nav-link dropdown-toggle arrow-none"
                                :class="(route.fullPath.includes('ads')) ? 'active' : ''"
                                role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="ri-shield-user-line"></i>Publicidades
                            </router-link>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</template>

<script setup>

import { useRoute } from 'vue-router'

const route = useRoute()

</script>
