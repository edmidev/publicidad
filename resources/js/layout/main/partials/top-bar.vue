<!-- eslint-disable vue/no-unused-vars -->
<template>
    <div class="navbar-custom">
        <div class="topbar container-fluid">
            <div class="d-flex align-items-center gap-1">
                <!-- Topbar Brand Logo -->
                <div class="logo-topbar">
                    <!-- Logo light -->
                    <!-- Use unique logo for both -->
                    <router-link :to="{ name: 'dashboard' }">
                        <span class="logo-lg">
                            <img
                                src="@/assets/images/logo.png"
                                alt="logo"
                                style="width: 165px; height: auto"
                            />
                        </span>
                        <span class="logo-sm">
                            <img
                                src="@/assets/images/logo.png"
                                alt="small logo"
                            />
                        </span>
                    </router-link>
                </div>

                <!-- Sidebar Menu Toggle Button -->
                <button class="button-toggle-menu">
                    <i class="ri-menu-line"></i>
                </button>

                <!-- Horizontal Menu Toggle Button -->
                <button
                    class="navbar-toggle"
                    data-bs-toggle="collapse"
                    data-bs-target="#topnav-menu-content"
                >
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>

                <!-- Topbar Search Form -->
            </div>

            <ul class="topbar-menu d-flex align-items-center gap-3">
                <li class="dropdown d-lg-none">
                    <a
                        class="nav-link dropdown-toggle arrow-none"
                        data-bs-toggle="dropdown"
                        href="#"
                        role="button"
                        aria-haspopup="false"
                        aria-expanded="false"
                    >
                        <i class="ri-search-line fs-22"></i>
                    </a>
                    <div
                        class="dropdown-menu dropdown-menu-animated dropdown-lg p-0"
                    >
                        <form class="p-3">
                            <input
                                type="search"
                                class="form-control"
                                placeholder="Buscar ..."
                                aria-label="Recipient's username"
                            />
                        </form>
                    </div>
                </li>
                <li class="d-none d-sm-inline-block">
                    <div class="nav-link" id="light-dark-mode">
                        <i class="ri-moon-line fs-22"></i>
                    </div>
                </li>

                <li class="dropdown">
                    <a
                        class="nav-link dropdown-toggle arrow-none nav-user"
                        data-bs-toggle="dropdown"
                        href="#"
                        role="button"
                        aria-haspopup="false"
                        aria-expanded="false"
                    >
                        <span class="d-lg-block d-none">
                            <h5 class="my-0 fw-normal">
                                {{ userStore.user ? userStore.user.name : "" }}
                                <i
                                    class="ri-arrow-down-s-line d-none d-sm-inline-block align-middle"
                                ></i>
                            </h5>
                        </span>
                    </a>
                    <div
                        class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown"
                    >
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">
                                Bienvenido
                                {{ userStore.user ? userStore.user.name : "" }}
                            </h6>
                        </div>

                        <!-- item-->
                        <a class="dropdown-item pointer" @click="logout()">
                            <i
                                class="ri-logout-box-line fs-18 align-middle me-1"
                            ></i>
                            <span>Cerrar sesión</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { useUserStore } from "../../../stores";
import { useRouter } from "vue-router";
import { ref } from "vue";
import { useHelperStore } from "./../../../stores";

const userStore = useUserStore();
const router = useRouter();
const patientsList = ref([]);
const searchText = ref(null);
const selectedClient = ref(null);
const isSearch = ref(false);
const helperStore = useHelperStore();

const selectClient = (selectedOption) => {
    helperStore.setSelectedItem(selectedOption);
    helperStore.setItemType("search-client");
    router.push({ name: "patients" });
};

const logout = () => {
    userStore.logout();
    router.push({ name: "login" });
};
</script>
