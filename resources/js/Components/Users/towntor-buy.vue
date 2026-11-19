<template>
    <div class="row g-4">
        <div class="col-md-4">
            <ul class="nav nav-pills nav-justified flex-column bg-white rounded-3 shadow p-3 mb-0 sticky-bar"
                id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link rounded-3 active" id="pre-approval-office" data-bs-toggle="pill"
                        href="#pills-pre-approval" role="tab" aria-controls="pills-pre-approval" aria-selected="false">
                        <div class="text-center pt-1 pb-1">
                            <h6 class="title fw-normal mb-0">Pre Approval Letter</h6>
                        </div>
                    </a><!--end nav link-->
                </li><!--end nav item-->

                <li class="nav-item mt-2">
                    <a class="nav-link rounded-3" id="schedule-office" data-bs-toggle="pill" href="#pills-schedule"
                        role="tab" aria-controls="pills-schedule" aria-selected="false">
                        <div class="text-center pt-1 pb-1">
                            <h6 class="title fw-normal mb-0">Schedule a Showing</h6>
                        </div>
                    </a><!--end nav link-->
                </li><!--end nav item-->

                <li class="nav-item mt-2">
                    <a class="nav-link rounded-3" id="offer-space" data-bs-toggle="pill" href="#pills-offer" role="tab"
                        aria-controls="pills-offer" aria-selected="false">
                        <div class="text-center pt-1 pb-1">
                            <h6 class="title fw-normal mb-0">Submit an Offer</h6>
                        </div>
                    </a><!--end nav link-->
                </li><!--end nav item-->

                <li class="nav-item mt-2">
                    <a class="nav-link rounded-3" id="inspection-room" data-bs-toggle="pill" href="#pills-inspection"
                        role="tab" aria-controls="pills-inspection" aria-selected="false">
                        <div class="text-center pt-1 pb-1">
                            <h6 class="title fw-normal mb-0">Property Inspection</h6>
                        </div>
                    </a><!--end nav link-->
                </li><!--end nav item-->

                <li class="nav-item mt-2">
                    <a class="nav-link rounded-3" id="appraisal-room" data-bs-toggle="pill" href="#pills-appraisal"
                        role="tab" aria-controls="pills-appraisal" aria-selected="false">
                        <div class="text-center pt-1 pb-1">
                            <h6 class="title fw-normal mb-0">Appraisal</h6>
                        </div>
                    </a><!--end nav link-->
                </li><!--end nav item-->

                <li class="nav-item mt-2">
                    <a class="nav-link rounded-3" id="closing-room" data-bs-toggle="pill" href="#pills-closing"
                        role="tab" aria-controls="pills-closing" aria-selected="false">
                        <div class="text-center pt-1 pb-1">
                            <h6 class="title fw-normal mb-0">Closing Deal</h6>
                        </div>
                    </a><!--end nav link-->
                </li><!--end nav item-->
            </ul><!--end nav pills-->
        </div><!--end col-->

        <div class="col-md-8 col-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade bg-white show active" id="pills-pre-approval" role="tabpanel"
                    aria-labelledby="pre-approval-office">
                    <img :src="images.agentMonochromatic" class="img-fluid" alt="">
                    <div class="mt-4">
                        <h5 class="">Pre Approval Letter</h5>
                        <p class="text-muted">Most buyers think the first step is finding their dream house, but the
                            truth is finding the funding is the first step. Towntor streamlines the Loan Pre-Approval
                            process with our ecosystem of Premier Partners or simply upload your own Pre-Approval
                            letter.</p>
<!--                        <a href="javascript:void(0)" class="text-primary">See More <i data-feather="arrow-right"-->
<!--                                class="fea icon-sm"></i></a>-->
                    </div>
                </div><!--end teb pane-->

                <div class="tab-pane fade bg-white" id="pills-schedule" role="tabpanel"
                    aria-labelledby="schedule-office">
                    <img :src="images.presentationFL" class="img-fluid" alt="">
                    <div class="mt-4">
                        <h5 class="">Schedule a Showing</h5>
                        <p class="text-muted">Towntor allows a buyer to schedule an instant showing and gain a private
                            viewing without the need for multiple parties to be involved. You pick the time that works
                            for you!</p>
<!--                        <a href="javascript:void(0)" class="text-primary">See More <i data-feather="arrow-right"-->
<!--                                class="fea icon-sm"></i></a>-->
                    </div>
                </div><!--end teb pane-->

                <div class="tab-pane fade bg-white" id="pills-offer" role="tabpanel" aria-labelledby="offer-space">
                    <img :src="images.sessionFL" class="img-fluid" alt="">
                    <div class="mt-4">
                        <h5 class="">Submit an Offer</h5>
                        <p class="text-muted">Towntor walks a buyer through the purchase agreement process making the
                            paperwork appear effortless. With our custom workflows and progress analytics, you will
                            always know where your purchase stands. No more phone tag and unreturned emails!</p>
<!--                        <a href="javascript:void(0)" class="text-primary">See More <i data-feather="arrow-right"-->
<!--                                class="fea icon-sm"></i></a>-->
                    </div>
                </div><!--end teb pane-->

                <div class="tab-pane fade bg-white" id="pills-inspection" role="tabpanel"
                    aria-labelledby="inspection-room">
                    <img :src="images.teamFL" class="img-fluid" alt="">
                    <div class="mt-4">
                        <h5 class="">Property Inspection</h5>
                        <p class="text-muted">No one wants to buy a lemon. Book an inspection with a licensed inspector,
                            then submit the repair requests all via the Towntor platform.</p>
<!--                        <a href="javascript:void(0)" class="text-primary">See More <i data-feather="arrow-right"-->
<!--                                class="fea icon-sm"></i></a>-->
                    </div>
                </div><!--end teb pane-->

                <div class="tab-pane fade bg-white" id="pills-appraisal" role="tabpanel"
                    aria-labelledby="appraisal-room">
                    <img :src="images.teamFL" class="img-fluid" alt="">
                    <div class="mt-4">
                        <h5 class="">Appraisal</h5>
                        <p class="text-muted">Towntor monitors the appraisal process ensuring the home you are
                            purchasing meets or exceeds the price you are paying.</p>
<!--                        <a href="javascript:void(0)" class="text-primary">See More <i data-feather="arrow-right"-->
<!--                                class="fea icon-sm"></i></a>-->
                    </div>
                </div><!--end teb pane-->

                <div class="tab-pane fade bg-white" id="pills-closing" role="tabpanel" aria-labelledby="closing-room">
                    <img :src="images.teamMeeting" class="img-fluid" alt="">
                    <div class="mt-4">
                        <h5 class="">Closing Deal</h5>
                        <p class="text-muted">Finally the closing packet is sent to the Title office, and the day has
                            come… You have Towntor the home of your dreams!</p>
<!--                        <a href="javascript:void(0)" class="text-primary">See More <i data-feather="arrow-right"-->
<!--                                class="fea icon-sm"></i></a>-->
                    </div>
                </div><!--end teb pane-->
            </div><!--end tab content-->
        </div><!--end col-->
    </div><!--end row-->
</template>

<script setup>
import {ref} from "vue";
import agentMonochromatic from "@/assets/images/svg/Agent_Monochromatic.svg";
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
