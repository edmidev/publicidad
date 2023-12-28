<template>
    <div class="wrapper">
        <TopBar />
        <HorizontalNav />
        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">
                <router-view></router-view>
            </div>
            <!-- content -->
            <Footer />
        </div>
    </div>
</template>

<script setup>

import TopBar from './partials/top-bar.vue'
import HorizontalNav from './partials/horizontal-nav.vue'
import Footer from './partials/footer.vue'

</script>
