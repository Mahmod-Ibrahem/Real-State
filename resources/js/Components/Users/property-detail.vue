<template>
    <div class="container mt-[50px]">
        <div class="row g-4">
            <div class="col-lg-8 col-md-7 col-12">
                <div class="section-title">
                    <h4 class="title mb-0 d-flex align-items-center flex-wrap gap-2">
                        {{ props.property.name }}
                        <span v-if="props.property.status"
                              class="badge rounded-pill fs-6 text-uppercase"
                              :class="props.property.status.value === 'sold' ? 'bg-danger' : 'bg-primary'">
                            {{ props.property.status.label }}
                        </span>
                    </h4>

                    <ul class="list-unstyled mb-0 py-3">
                        <li class="list-inline-item">
                            <span class="d-flex align-items-center me-4">
                                <i class="mdi mdi-arrow-expand-all fs-4 me-2 text-primary"></i>
                                <span class="text-muted fs-5">{{ props.property.sqft }} Sqf</span>
                            </span>
                        </li>
                        <li class="list-inline-item" v-if="props.property.floor">
                            <span class="d-flex align-items-center me-4">
                                <i class="mdi mdi-office-building fs-4 me-2 text-primary"></i>
                                <span class="text-muted fs-5">{{ props.property.floor }} Floor </span>
                            </span>
                        </li>

                        <li class="list-inline-item">
                            <span class="d-flex align-items-center me-4">
                                <i class="mdi mdi-bed fs-4 me-2 text-primary"></i>
                                <span class="text-muted fs-5">{{ props.property.bedrooms }} Beds</span>
                            </span>
                        </li>

                        <li class="list-inline-item">
                            <span class="d-flex align-items-center me-4">
                                <i class="mdi mdi-shower fs-4 me-2 text-primary"></i>
                                <span class="text-muted fs-5">{{ props.property.bathrooms }}Baths</span>
                            </span>
                        </li>

                        <li class="list-inline-item">
                            <span class="d-flex align-items-center me-4">
                                <i class="mdi mdi-currency-usd fs-4  text-primary"></i>
                                <span class="text-muted fs-5">EGP {{ props.property.price }}</span>
                            </span>
                        </li>

                        <li class="list-inline-item">
    <span class="flex items-center me-4 cursor-pointer" @click="showShare = true">
      <!-- SVG ICON -->
      <svg class="" width="25px" height="25px" viewBox="-0.5 0 25 25" fill="none"
           xmlns="http://www.w3.org/2000/svg">
        <path
            d="M13.47 4.13998C12.74 4.35998 12.28 5.96 12.09 7.91C6.77997 7.91 2 13.4802 2 20.0802C4.19 14.0802 8.99995 12.45 12.14 12.45C12.34 14.21 12.79 15.6202 13.47 15.8202C15.57 16.4302 22 12.4401 22 9.98006C22 7.52006 15.57 3.52998 13.47 4.13998Z"
            stroke="#38bdf8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </span>
                        </li>
                    </ul>
                    <!-- Overlay -->
                    <div v-if="showShare"
                         class="fixed inset-0 bg-transparent z-50 flex items-center justify-center"
                         @click.self="showShare = false"
                    >
                        <!-- Share box -->
                        <div class="bg-white p-6 rounded-xl shadow-lg w-96 relative">
                            <button @click="showShare = false"
                                    class="absolute top-2 right-2 text-gray-500 hover:text-red-500">&times;
                            </button>
                            <h2 class="!text-xl font-semibold mb-4">Share This Property</h2>
                            <div class="flex items-center justify-center gap-2">
                                <!-- Facebook -->
                                <a :href="shareLink.facebook"
                                   class="relative w-8 h-8 rounded-full transition-all duration-500 flex justify-center items-center bg-[#337FFF]  hover:bg-gray-900 ">
                                    <svg class="w-[1rem] h-[1rem] text-white" viewBox="0 0 8 14" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.04111 7.81204L7.41156 5.46043H5.1296V3.93188C5.1296 3.28886 5.44818 2.66054 6.46692 2.66054H7.51899V0.657999C6.90631 0.560385 6.28723 0.507577 5.66675 0.5C3.78857 0.5 2.56239 1.62804 2.56239 3.66733V5.46043H0.480469V7.81204H2.56239V13.5H5.1296V7.81204H7.04111Z"
                                            fill="currentColor"/>
                                    </svg>

                                </a>
                                <!-- Whatsapp -->
                                <a :href="shareLink.whatsapp" class="relative w-8 h-8 rounded-full transition-all duration-500 flex justify-center items-center
                        ">
                                    <svg width="24px" height="24px" viewBox="0 0 48 48" version="1.1"
                                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         fill="#000000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                           stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier"><title>Whatsapp-color</title>
                                            <desc>Created with Sketch.</desc>
                                            <defs></defs>
                                            <g id="Icons" stroke="none" stroke-width="1" fill="none"
                                               fill-rule="evenodd">
                                                <g id="Color-" transform="translate(-700.000000, -360.000000)"
                                                   fill="#67C15E">
                                                    <path
                                                        d="M723.993033,360 C710.762252,360 700,370.765287 700,383.999801 C700,389.248451 701.692661,394.116025 704.570026,398.066947 L701.579605,406.983798 L710.804449,404.035539 C714.598605,406.546975 719.126434,408 724.006967,408 C737.237748,408 748,397.234315 748,384.000199 C748,370.765685 737.237748,360.000398 724.006967,360.000398 L723.993033,360.000398 L723.993033,360 Z M717.29285,372.190836 C716.827488,371.07628 716.474784,371.034071 715.769774,371.005401 C715.529728,370.991464 715.262214,370.977527 714.96564,370.977527 C714.04845,370.977527 713.089462,371.245514 712.511043,371.838033 C711.806033,372.557577 710.056843,374.23638 710.056843,377.679202 C710.056843,381.122023 712.567571,384.451756 712.905944,384.917648 C713.258648,385.382743 717.800808,392.55031 724.853297,395.471492 C730.368379,397.757149 732.00491,397.545307 733.260074,397.27732 C735.093658,396.882308 737.393002,395.527239 737.971421,393.891043 C738.54984,392.25405 738.54984,390.857171 738.380255,390.560912 C738.211068,390.264652 737.745308,390.095816 737.040298,389.742615 C736.335288,389.389811 732.90737,387.696673 732.25849,387.470894 C731.623543,387.231179 731.017259,387.315995 730.537963,387.99333 C729.860819,388.938653 729.198006,389.89831 728.661785,390.476494 C728.238619,390.928051 727.547144,390.984595 726.969123,390.744481 C726.193254,390.420348 724.021298,389.657798 721.340985,387.273388 C719.267356,385.42535 717.856938,383.125756 717.448104,382.434484 C717.038871,381.729275 717.405907,381.319529 717.729948,380.938852 C718.082653,380.501232 718.421026,380.191036 718.77373,379.781688 C719.126434,379.372738 719.323884,379.160897 719.549599,378.681068 C719.789645,378.215575 719.62006,377.735746 719.450874,377.382942 C719.281687,377.030139 717.871269,373.587317 717.29285,372.190836 Z"
                                                        id="Whatsapp"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </a>                                <!-- Whatsapp -->
                                <a :href="shareLink.telegram" class="relative w-8 h-8 rounded-full transition-all duration-500 flex justify-center items-center
                        ">
                                    <svg width="24px" height="24px" viewBox="0 0 32 32" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                           stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <circle cx="16" cy="16" r="14" fill="url(#paint0_linear_87_7225)"></circle>
                                            <path
                                                d="M22.9866 10.2088C23.1112 9.40332 22.3454 8.76755 21.6292 9.082L7.36482 15.3448C6.85123 15.5703 6.8888 16.3483 7.42147 16.5179L10.3631 17.4547C10.9246 17.6335 11.5325 17.541 12.0228 17.2023L18.655 12.6203C18.855 12.4821 19.073 12.7665 18.9021 12.9426L14.1281 17.8646C13.665 18.3421 13.7569 19.1512 14.314 19.5005L19.659 22.8523C20.2585 23.2282 21.0297 22.8506 21.1418 22.1261L22.9866 10.2088Z"
                                                fill="white"></path>
                                            <defs>
                                                <linearGradient id="paint0_linear_87_7225" x1="16" y1="2" x2="16"
                                                                y2="30" gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="#37BBFE"></stop>
                                                    <stop offset="1" stop-color="#007DBB"></stop>
                                                </linearGradient>
                                            </defs>
                                        </g>
                                    </svg>
                                </a>
                                <!-- Twitter X -->
                                <a :href="shareLink.twitter" class="relative w-8 h-8 rounded-full transition-all duration-500 flex justify-center items-center
                        ">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100"
                                         viewBox="0 0 50 50">
                                        <path
                                            d="M 11 4 C 7.134 4 4 7.134 4 11 L 4 39 C 4 42.866 7.134 46 11 46 L 39 46 C 42.866 46 46 42.866 46 39 L 46 11 C 46 7.134 42.866 4 39 4 L 11 4 z M 13.085938 13 L 21.023438 13 L 26.660156 21.009766 L 33.5 13 L 36 13 L 27.789062 22.613281 L 37.914062 37 L 29.978516 37 L 23.4375 27.707031 L 15.5 37 L 13 37 L 22.308594 26.103516 L 13.085938 13 z M 16.914062 15 L 31.021484 35 L 34.085938 35 L 19.978516 15 L 16.914062 15 z"></path>
                                    </svg>

                                </a>
                                <!-- Linked In -->
                                <a :href="shareLink.linkedin" class="relative w-8 h-8 rounded-full transition-all duration-500 flex justify-center items-center
                        ">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100"
                                         viewBox="0 0 48 48">
                                        <path fill="#0288D1"
                                              d="M42,37c0,2.762-2.238,5-5,5H11c-2.761,0-5-2.238-5-5V11c0-2.762,2.239-5,5-5h26c2.762,0,5,2.238,5,5V37z"></path>
                                        <path fill="#FFF"
                                              d="M12 19H17V36H12zM14.485 17h-.028C12.965 17 12 15.888 12 14.499 12 13.08 12.995 12 14.514 12c1.521 0 2.458 1.08 2.486 2.499C17 15.887 16.035 17 14.485 17zM36 36h-5v-9.099c0-2.198-1.225-3.698-3.192-3.698-1.501 0-2.313 1.012-2.707 1.99C24.957 25.543 25 26.511 25 27v9h-5V19h5v2.616C25.721 20.5 26.85 19 29.738 19c3.578 0 6.261 2.25 6.261 7.274L36 36 36 36z"></path>
                                    </svg>

                                </a>
                                <button @click="copyLink()"
                                        class="bg-gray-900 font-medium text-sm text-white px-2 py-1 rounded">Copy Link
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card map border-0">
                        <div class="card-body p-0">
                            <iframe
                                :src="props.property.google_map"
                                class="rounded-3" style="border:0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <div class="mt-4 shadow-sm border rounded">
                    <div class="p-4">
                        <h4 class="border-bottom pb-2 mb-3">Description</h4>
                        <p>{{ props.property.description }}</p>
                    </div>
                </div>
                <div class="mt-4 shadow-sm border rounded">
                    <div class="p-4">
                        <h4 class="flex border-b flex-nowrap  pb-2 mb-3">Features</h4>
                        <div class="flex flex-col flex-wrap gap-3">
                            <div v-for="(features, key) in props.features"
                                 :key="key"
                                 class="flex flex-col me-3 mb-2"
                                 style="min-width: 200px;">
                                <h4 class="flex flex-nowrap pb-2 mb-3">{{ key }}</h4>

                                <!-- container for features - flex row to align horizontally -->
                                <div class="flex flex-row flex-wrap gap-2">
                                    <div v-for="feature in features" :key="feature.id" class="flex items-center gap-2">
                                        <i data-feather="check-circle" class="me-2"
                                           style="width: 20px; height: 20px;"></i>
                                        <p class="mb-0">{{ feature.name }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-lg-4 col-md-5 col-12">
                <div class="rounded-3 shadow bg-white sticky-bar p-4">
                    <h5 class="mb-3 items-center inline-flex justify-center w-full">More About This Property</h5>
                    <form @submit.prevent="onSubmit">
                        <div class="d-flex flex-column items-center justify-center gap-3">
                            <input class=" w-100 border border-slate-100 focus:ring-sky-400 me-2 p-2 rounded"
                                   placeholder="Full Name"
                                   v-model="form.name"/>
                            <input type="email"
                                   class=" w-100 border border-slate-100 focus:ring-sky-400 me-2 p-2 rounded"
                                   placeholder="Email"
                                   v-model="form.email"/>
                            <input type="tel"
                                   class=" w-100 border border-slate-100 focus:ring-sky-400 me-2 p-2 rounded"
                                   placeholder="Phone"
                                   v-model="form.phone"/>
                            <textarea class=" w-100 border border-slate-100 focus:ring-sky-400 me-2 p-2 rounded "
                                      placeholder="How Can We Help You?"
                                      v-model="form.message"/>
                        </div>
                        <div class="d-flex mt-3">
                            <button type="submit" :disabled="form.processing" class="btn btn-primary w-100 me-2">Send
                                Message
                            </button>
                        </div>
                    </form>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import feather from 'feather-icons';
import '../../../css/app.css';
import {ref, watch} from "vue";
import {useToast} from 'vue-toastification'

const toast = useToast()
const showShare = ref(false)
//To Prevent Scroll While Modal is Open
watch(
    () => showShare.value,
    (newVal) => {
        if (newVal) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'unset';
        }
    }
)
const props = defineProps({
    property: Object,
    features: Array
})
const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: '',
    propertyId: props.property.id
})

const shareLink = ref(
    {
        facebook: 'https://www.facebook.com/sharer/sharer.php?u=' + window.location.href,
        twitter: 'https://twitter.com/intent/tweet?url=' + window.location.href,
        linkedin: 'https://www.linkedin.com/shareArticle?url=' + window.location.href,
        whatsapp: 'https://wa.me/?text=' + window.location.href,
        telegram: 'https://t.me/share/url?url=' + window.location.href
    }
)

function copyLink() {
    navigator.clipboard.writeText(window.location.href)
        .then(() => {
            toast("Link Copied !", {
                position: "top-right",
                timeout: 4038,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: true,
                closeButton: "button",
                icon: true,
                rtl: false
            });
        });
}

function onSubmit() {
    form.post(route('client-inquiries.store'), {
        onSuccess: () => {
            form.reset();
        },
        onError: () => {
            console.log('error')
        }
    })
}

feather.replace();
</script>

<style lang="scss" scoped></style>
