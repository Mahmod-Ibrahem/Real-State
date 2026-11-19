<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="p-4 rounded-3 shadow">
                    <form @submit.prevent="submit">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Your Name <span
                                        class="text-danger">*</span></label>
                                    <input name="full_name" id="name" class="form-control"
                                           v-model="form.full_name"
                                           placeholder="Name :">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Your Email <span
                                        class="text-danger">*</span></label>
                                    <input name="email" id="email" class="form-control"
                                           v-model="form.email"
                                           placeholder="Email :">
                                </div>
                            </div><!--end col-->

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input name="phone" id="phone" class="form-control"
                                           v-model="form.phone"
                                           placeholder="Phone :">
                                </div>
                            </div><!--end col-->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Subject</label>
                                    <input name="subject" id="subject" class="form-control"
                                           v-model="form.subject"
                                           placeholder="Subject :">
                                </div>
                            </div><!--end col-->

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Message <span
                                        class="text-danger">*</span></label>
                                    <textarea name="comments" id="comments" rows="4" class="form-control"
                                              v-model="form.message"
                                              placeholder="Message :"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" id="submit" name="send" class="btn btn-primary">Send
                                        Message
                                    </button>
                                </div>
                            </div><!--end col-->
                        </div><!--end row-->
                    </form>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</template>
<script setup>

import {useForm} from "@inertiajs/vue3";

const form = useForm({
    full_name: '',
    email: '',
    phone: '',
    subject: '',
    message: ''
})

function submit() {
    form.post(route('contact.post'), {
        onSuccess: () => {
            form.reset();
        }
    });
}
</script>
