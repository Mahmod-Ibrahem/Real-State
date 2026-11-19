<template>
    <div class="row g-4">
        <div class="col-md-4">
            <ul class="nav nav-pills nav-justified flex-column bg-white rounded-3 shadow p-3 mb-0 sticky-bar"
                id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link rounded-3 active" id="pre-approval-office" data-bs-toggle="pill"
                       href="#pills-pre-approval" role="tab" aria-controls="pills-pre-approval" aria-selected="false">
                        <div class="text-center pt-1 pb-1">
                            <h6 class="title fw-normal mb-0">Rental Application</h6>
                        </div>
                    </a>
                </li>

                <li class="nav-item mt-2">
                    <a class="nav-link rounded-3" id="schedule-office" data-bs-toggle="pill" href="#pills-schedule"
                       role="tab" aria-controls="pills-schedule" aria-selected="false">
                        <div class="text-center pt-1 pb-1">
                            <h6 class="title fw-normal mb-0">Schedule a Tour</h6>
                        </div>
                    </a>
                </li>

                <li class="nav-item mt-2">
                    <a class="nav-link rounded-3" id="offer-space" data-bs-toggle="pill" href="#pills-offer" role="tab"
                       aria-controls="pills-offer" aria-selected="false">
                        <div class="text-center pt-1 pb-1">
                            <h6 class="title fw-normal mb-0">Submit Rental Request</h6>
                        </div>
                    </a>
                </li>

                <li class="nav-item mt-2">
                    <a class="nav-link rounded-3" id="inspection-room" data-bs-toggle="pill" href="#pills-inspection"
                       role="tab" aria-controls="pills-inspection" aria-selected="false">
                        <div class="text-center pt-1 pb-1">
                            <h6 class="title fw-normal mb-0">Unit Inspection</h6>
                        </div>
                    </a>
                </li>

                <li class="nav-item mt-2">
                    <a class="nav-link rounded-3" id="appraisal-room" data-bs-toggle="pill" href="#pills-appraisal"
                       role="tab" aria-controls="pills-appraisal" aria-selected="false">
                        <div class="text-center pt-1 pb-1">
                            <h6 class="title fw-normal mb-0">Background & Credit Check</h6>
                        </div>
                    </a>
                </li>

                <li class="nav-item mt-2">
                    <a class="nav-link rounded-3" id="closing-room" data-bs-toggle="pill" href="#pills-closing"
                       role="tab" aria-controls="pills-closing" aria-selected="false">
                        <div class="text-center pt-1 pb-1">
                            <h6 class="title fw-normal mb-0">Lease Signing</h6>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        <div class="col-md-8 col-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade bg-white show active" id="pills-pre-approval" role="tabpanel"
                     aria-labelledby="pre-approval-office">
                    <img :src="images.agentMonochromatic" class="img-fluid" alt="">
                    <div class="mt-4">
                        <h5 class="">Rental Application</h5>
                        <p class="text-muted">Get started by completing a rental application. Our platform helps you submit all the needed documents in one place to speed up the approval process.</p>
                    </div>
                </div>

                <div class="tab-pane fade bg-white" id="pills-schedule" role="tabpanel"
                     aria-labelledby="schedule-office">
                    <img :src="images.presentationFL" class="img-fluid" alt="">
                    <div class="mt-4">
                        <h5 class="">Schedule a Tour</h5>
                        <p class="text-muted">Browse available units and instantly schedule a private showing at a time that works for you—no waiting or back-and-forth calls.</p>
                    </div>
                </div>

                <div class="tab-pane fade bg-white" id="pills-offer" role="tabpanel" aria-labelledby="offer-space">
                    <img :src="images.sessionFL" class="img-fluid" alt="">
                    <div class="mt-4">
                        <h5 class="">Submit Rental Request</h5>
                        <p class="text-muted">Found the perfect place? Submit a rental request with ease and track the process through our platform in real-time.</p>
                    </div>
                </div>

                <div class="tab-pane fade bg-white" id="pills-inspection" role="tabpanel"
                     aria-labelledby="inspection-room">
                    <img :src="images.startupFL" class="img-fluid" alt="">
                    <div class="mt-4">
                        <h5 class="">Unit Inspection</h5>
                        <p class="text-muted">Schedule a unit walk-through before moving in to ensure everything is in perfect condition. Document any concerns directly through the app.</p>
                    </div>
                </div>

                <div class="tab-pane fade bg-white" id="pills-appraisal" role="tabpanel"
                     aria-labelledby="appraisal-room">
                    <img :src="images.teamFL" class="img-fluid" alt="">
                    <div class="mt-4">
                        <h5 class="">Background & Credit Check</h5>
                        <p class="text-muted">Landlords require a credit and background check. Upload your documents securely and get results quickly to move things forward.</p>
                    </div>
                </div>

                <div class="tab-pane fade bg-white" id="pills-closing" role="tabpanel" aria-labelledby="closing-room">
                    <img :src="images.teamMeeting" class="img-fluid" alt="">
                    <div class="mt-4">
                        <h5 class="">Lease Signing</h5>
                        <p class="text-muted">Finalize your lease agreement digitally and prepare for your move-in day. Welcome to your new home!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";
import agentMonochromatic from '@/assets/images/svg/Agent_Monochromatic.svg'
import presentationFL from '@/assets/images/svg/presentation_Flatline.svg'
import sessionFL from '@/assets/images/svg/session_Flatline.svg'
import startupFL from '@/assets/images/svg/Startup_Flatline.svg'
import teamFL from '@/assets/images/svg/team_Flatline.svg'
import teamMeeting from '@/assets/images/svg/Team_meeting_Two.svg'

const images=ref({
        agentMonochromatic,
        presentationFL,
        sessionFL,
        startupFL,
        teamFL,
        teamMeeting
    }
)
</script>

<style lang="scss" scoped></style>
