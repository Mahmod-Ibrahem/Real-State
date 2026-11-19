<template>
    <div class="card shadow rounded-3 border-0 mt-4">
        <div class="card-body">
            <h5 class="card-title mb-0">Comments :</h5>

            <ul class="media-list list-unstyled mb-0">
                <li class="mt-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <a class="pe-3" href="#">
                                <img src="../../assets/images/client/01.jpg"
                                    class="img-fluid avatar avatar-md-sm rounded-circle shadow" alt="img">
                            </a>
                            <div class="commentor-detail">
                                <h6 class="mb-0"><a href="javascript:void(0)" class="text-dark media-heading">Lorenzo
                                        Peterson</a></h6>
                                <small class="text-muted">15th August, 2021 at 01:25 pm</small>
                            </div>
                        </div>
                        <a href="#" class="text-muted"><i class="mdi mdi-reply"></i> Reply</a>
                    </div>
                    <div class="mt-3">
                        <p class="text-muted fst-italic p-3 bg-light rounded-3">" There are many variations of passages
                            of Lorem Ipsum available, but the majority have suffered alteration in some form, by
                            injected humour "</p>
                    </div>
                </li>

                <li class="mt-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <a class="pe-3" href="#">
                                <img src="../../assets/images/client/02.jpg"
                                    class="img-fluid avatar avatar-md-sm rounded-circle shadow" alt="img">
                            </a>
                            <div class="commentor-detail">
                                <h6 class="mb-0"><a href="javascript:void(0)" class="media-heading text-dark">Tammy
                                        Camacho</a></h6>
                                <small class="text-muted">15th August, 2021 at 05:44 pm</small>
                            </div>
                        </div>
                        <a href="#" class="text-muted"><i class="mdi mdi-reply"></i> Reply</a>
                    </div>
                    <div class="mt-3">
                        <p class="text-muted fst-italic p-3 bg-light rounded-3">" There are many variations of passages
                            of Lorem Ipsum available, but the majority have suffered alteration in some form, by
                            injected humour "</p>
                    </div>
                </li>

                <li class="mt-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <a class="pe-3" href="#">
                                <img src="../../assets/images/client/03.jpg"
                                    class="img-fluid avatar avatar-md-sm rounded-circle shadow" alt="img">
                            </a>
                            <div class="commentor-detail">
                                <h6 class="mb-0"><a href="javascript:void(0)" class="media-heading text-dark">Tammy
                                        Camacho</a></h6>
                                <small class="text-muted">16th August, 2021 at 03:44 pm</small>
                            </div>
                        </div>
                        <a href="#" class="text-muted"><i class="mdi mdi-reply"></i> Reply</a>
                    </div>
                    <div class="mt-3">
                        <p class="text-muted fst-italic p-3 bg-light rounded-3">" There are many variations of passages
                            of Lorem Ipsum available, but the majority have suffered alteration in some form, by
                            injected humour "</p>
                    </div>

                    <ul class="list-unstyled ps-4 ps-md-5 sub-comment">
                        <li class="mt-4">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <a class="pe-3" href="#">
                                        <img src="../../assets/images/client/01.jpg"
                                            class="img-fluid avatar avatar-md-sm rounded-circle shadow" alt="img">
                                    </a>
                                    <div class="commentor-detail">
                                        <h6 class="mb-0"><a href="javascript:void(0)"
                                                class="text-dark media-heading">Lorenzo Peterson</a></h6>
                                        <small class="text-muted">17th August, 2021 at 01:25 pm</small>
                                    </div>
                                </div>
                                <a href="#" class="text-muted"><i class="mdi mdi-reply"></i> Reply</a>
                            </div>
                            <div class="mt-3">
                                <p class="text-muted fst-italic p-3 bg-light rounded-3">" There are many variations of
                                    passages of Lorem Ipsum available, but the majority have suffered alteration in some
                                    form, by injected humour "</p>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <div class="card shadow rounded-3 mt-4">
        <div class="card-body">
            <h5 class="card-title mb-0">Leave A Comment :</h5>

            <form class="mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Your Comment</label>
                            <textarea id="message" placeholder="Your Comment" rows="5" name="message"
                                class="form-control" required=""></textarea>
                        </div>
                    </div><!--end col-->

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input id="name" name="name" type="text" placeholder="Name" class="form-control"
                                required="">
                        </div>
                    </div><!--end col-->

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Your Email <span class="text-danger">*</span></label>
                            <input id="email" type="email" placeholder="Email" name="email" class="form-control"
                                required="">
                        </div>
                    </div><!--end col-->

                    <div class="col-md-12">
                        <div class="send d-grid">
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </form><!--end form-->
        </div>
    </div>

    <div class="card shadow rounded-3 mt-4">
        <div class="card-body">
            <h5 class="card-title mb-0">Related News :</h5>

            <div class="row g-4 mt-0">
                <div class="col-lg-6">
                    <div class="card blog blog-primary shadow rounded-3 overflow-hidden border-0">
                        <div class="card-img blog-image position-relative overflow-hidden rounded-0">
                            <div class="position-relative overflow-hidden">
                                <img src="../../assets/images/property/7.jpg" class="img-fluid" alt="">
                                <div class="card-overlay"></div>
                            </div>

                            <div class="blog-tag p-3">
                                <a href="" class="badge bg-primary">Towntor</a>
                            </div>
                        </div>

                        <div class="card-body content p-0">
                            <div class="p-4">
                                <router-link to="/blog-detail" class="title fw-medium fs-5 text-dark">110 Quick Tips
                                    About
                                    Real Estate</router-link>
                                <p class="text-muted mt-2">The most well-known dummy text is the 'Lorem Ipsum', in the
                                    16th century.</p>

                                <a href="" class="text-dark read-more">Read More <i
                                        class="mdi mdi-chevron-right align-middle"></i></a>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-6">
                    <div class="card blog blog-primary shadow rounded-3 overflow-hidden border-0">
                        <div class="card-img blog-image position-relative overflow-hidden rounded-0">
                            <div class="position-relative overflow-hidden">
                                <img src="../../assets/images/property/8.jpg" class="img-fluid" alt="">
                                <div class="card-overlay"></div>
                            </div>

                            <div class="blog-tag p-3">
                                <a href="" class="badge bg-primary">Towntor</a>
                            </div>
                        </div>

                        <div class="card-body content p-0">
                            <div class="p-4">
                                <router-link to="/blog-detail" class="title fw-medium fs-5 text-dark">15 Best Blogs To
                                    Follow About Real Estate</router-link>
                                <p class="text-muted mt-2">The most well-known dummy text is the 'Lorem Ipsum', in the
                                    16th century.</p>

                                <a href="" class="text-dark read-more">Read More <i
                                        class="mdi mdi-chevron-right align-middle"></i></a>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div>
    </div>
</template>

<script setup>

</script>

<style lang="scss" scoped></style>