<template>
    <div class="col-xxl-8 col-lg-10">
        <div class="card overflow-hidden">
            <div class="row g-0">
                <div class="col-lg-6 d-none d-lg-block p-2">
                    <img src="@/assets/images/auth-img.jpg" alt="" class="img-fluid rounded h-100">
                </div>
                <div class="col-lg-6">
                    <div class="d-flex flex-column h-100">
                        <div class="auth-brand p-4">
                            <!-- <a href="index.html" class="logo-light">
                                <img src="@/assets/images/logo.png" alt="logo" height="52">
                            </a> -->
                            <a href="index.html" class="logo-dark">
                                <img src="@/assets/images/dr_ozono.svg" alt="dark logo" height="52">
                            </a>
                        </div>
                        <div class="p-4">
                            <h4 class="fs-20">Recuperar contraseña</h4>
                            <p class="text-muted mb-3">
                                Ingrese su dirección de correo electrónico para iniciar el proceso de restablecimiento de contraseña.
                            </p>
                            <!-- form -->
                            <section>
                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Correo electrónico</label>
                                    <input class="form-control" type="email" id="emailaddress" required
                                        placeholder="Ingrese su dirección de correo electrónico">
                                </div>
                                <div class="mb-0 text-start">
                                    <button class="btn btn-soft-primary w-100" type="submit">
                                        <i class="ri-login-circle-fill me-1"></i>
                                        <span class="fw-bold">
                                            Recuperar contraseña
                                        </span>
                                    </button>
                                </div>
                            </section>
                            <!-- end form-->
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <p class="text-dark-emphasis">¿Tienes una cuenta?
                    <router-link :to="{ name: 'login' }"
                        class="text-dark fw-bold ms-1 link-offset-3 text-decoration-underline">
                        <b>Iniciar sesión</b>
                    </router-link>
                </p>
            </div> <!-- end col -->
        </div>
    </div>
    <!-- end row -->
</template>
<script setup>

</script>
