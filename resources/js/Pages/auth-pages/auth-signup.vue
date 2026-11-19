<template>
    <section class="bg-home zoom-image d-flex align-items-center">
        <div class="bg-overlay image-wrap" :style="{'background-image': 'url('+ require('../../assets/images/bg/03.jpg') +')', backgroundPosition: 'center'}">
        </div>
        <div class="bg-overlay bg-gradient-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="p-4 bg-white rounded-3 shadow-md mx-auto w-100" style="max-width: 400px;">
<!--                        <form>-->
<!--                            <router-link to="/"><img src="../../assets/images/logo-icon-80.png"-->
<!--                                    class="mb-4 d-block mx-auto" alt=""></router-link>-->
<!--                            <h5 class="mb-3">Register your account</h5>-->

<!--                            <div class="form-floating mb-2">-->
<!--                                <input type="text" class="form-control" id="floatingInput" placeholder="Harry">-->
<!--                                <label for="floatingInput">First Name</label>-->
<!--                            </div>-->

<!--                            <div class="form-floating mb-2">-->
<!--                                <input type="email" class="form-control" id="floatingEmail"-->
<!--                                    placeholder="name@example.com">-->
<!--                                <label for="floatingEmail">Email Address</label>-->
<!--                            </div>-->

<!--                            <div class="form-floating mb-3">-->
<!--                                <input type="password" class="form-control" id="floatingPassword"-->
<!--                                    placeholder="Password">-->
<!--                                <label for="floatingPassword">Password</label>-->
<!--                            </div>-->

<!--                            <div class="form-check mb-3">-->
<!--                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">-->
<!--                                <label class="form-check-label text-muted" for="flexCheckDefault">I Accept <a href="#"-->
<!--                                        class="text-primary">Terms And Condition</a></label>-->
<!--                            </div>-->

<!--                            <button class="btn btn-primary w-100" type="submit">Register</button>-->

<!--                            <div class="col-12 text-center mt-3">-->
<!--                                <span><span class="text-muted me-2">Already have an account ? </span> <router-link-->
<!--                                        to="/auth-login" class="text-dark fw-medium">Sign in</router-link></span>-->
<!--                            </div>&lt;!&ndash;end col&ndash;&gt;-->
<!--                        </form>-->
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- ENd Hero -->
</template>

<script setup>

</script>

<style lang="scss" scoped></style>
