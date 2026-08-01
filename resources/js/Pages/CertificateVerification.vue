<script setup>
import EnagarField from '@/Components/EnagarField.vue';
import EnagarIcon from '@/Components/EnagarIcon.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    ulbs: {
        type: Array,
        default: () => [],
    },
    applications: {
        type: Array,
        default: () => [],
    },
    certificate: {
        type: Object,
        default: null,
    },
});

const form = useForm({
    ulb_code: props.ulbs[2]?.value ?? '',
    application_name: props.applications[0]?.value ?? '',
    building_permission_no: '',
    captcha: '',
});

const captchaUrl = ref(`${route('verify-certificate.captcha')}?t=${Date.now()}`);

// Each submit consumes the code held in the session, so the image is always
// replaced once the request comes back.
function refreshCaptcha() {
    captchaUrl.value = `${route('verify-certificate.captcha')}?t=${Date.now()}`;
    form.captcha = '';
}

function submit() {
    form.post(route('verify-certificate.verify'), {
        preserveScroll: true,
        preserveState: true,
        onFinish: refreshCaptcha,
    });
}

const detailFields = [
    { key: 'application_status', label: 'Application Status', required: true },
    { key: 'application_number', label: 'Application Number', required: true },
    { key: 'application_date', label: 'Date', required: true },
    {
        key: 'architect_engineer_no',
        label: 'Architect/Engineer No.',
        required: true,
    },
    {
        key: 'architect_engineer_name',
        label: 'Architect/ Engineer Name',
        required: true,
    },
    {
        key: 'structure_engineer_no',
        label: 'Structure Engineer No.',
        required: true,
    },
    {
        key: 'structure_engineer_name',
        label: 'Structure Engineer Name',
        required: true,
    },
    { key: 'clerk_of_works_no', label: 'Clerk of Works No.', required: true },
    {
        key: 'clerk_of_works_name',
        label: 'Clerk of Works Name',
        required: true,
    },
    { key: 'developer_no', label: 'Developer No.', required: true },
    { key: 'developer_name', label: 'Developer Name', required: true },
    { key: 'owner_name', label: 'Owner Name', required: true },
    { key: 'owner_address', label: 'Owner Address', type: 'textarea' },
    { key: 'applicant_name', label: 'Applicant Name', required: true },
    { key: 'applicant_address', label: 'Applicant Address', type: 'textarea' },
    {
        key: 'administrative_zone',
        label: 'Administrative Zone',
        required: true,
    },
    {
        key: 'administrative_ward',
        label: 'Administrative Ward',
        required: true,
    },
    { key: 'district', label: 'District', required: true },
    { key: 'taluka', label: 'Taluka', required: true },
    { key: 'city_village', label: 'City/Village', required: true },
    {
        key: 'tp_scheme_number',
        label: 'TP Scheme/ Non TP Scheme Number',
        required: true,
    },
    {
        key: 'tp_scheme_name',
        label: 'TP Scheme/ Non TP Scheme Name',
        required: true,
    },
    { key: 'revenue_survey_no', label: 'Revenue Survey No.', required: true },
    {
        key: 'city_survey_no',
        label: 'City Survey No.',
        required: true,
        action: true,
    },
    { key: 'final_plot_no', label: 'Final Plot No.', required: true },
    { key: 'original_plot_no', label: 'Original Plot No.', required: true },
    { key: 'sub_plot_no', label: 'Sub Plot no.', required: true },
    { key: 'tikka_no', label: 'Tikka No. / Part No.', required: true },
    { key: 'site_address', label: 'Site Address', type: 'textarea' },
    { key: 'block_tenement_no', label: 'Block No/Tenement No', required: true },
    {
        key: 'max_height_of_building',
        label: 'Max Height Of Building',
        required: true,
    },
    { key: 'odps_application_no', label: 'ODPS Application No.', required: true },
    {
        key: 'certificate_issue_date',
        label: 'Certificate Issue Date',
        required: true,
    },
    {
        key: 'certificate_expiry_date',
        label: 'Certificate Expiry Date',
        required: true,
    },
];

const controlClass =
    'w-full rounded-none border-0 border-b border-[#e0e0e0] bg-transparent px-0 py-1 text-[0.8125rem] text-[#333333] placeholder:text-[#9e9e9e] focus:border-[#1976d2] focus:ring-0';

const buttonClass =
    'rounded-[2px] bg-[#1976d2] px-5 py-2 text-[0.75rem] font-medium uppercase tracking-wide text-white shadow-[0_1px_3px_rgba(0,0,0,0.3)] transition hover:bg-[#1565c0] disabled:opacity-60';
</script>

<template>
    <Head title="Certificate Verification" />

    <PublicLayout>
        <div class="px-4 py-8">
            <div class="relative mx-auto w-full max-w-[680px]">
                <!-- Close -->
                <Link
                    :href="route('welcome')"
                    class="absolute -top-2 right-2 z-10 grid h-7 w-7 place-items-center rounded-full border border-[#e53935] bg-white text-[#e53935] transition hover:bg-[#e53935] hover:text-white"
                    aria-label="Close"
                >
                    <EnagarIcon name="close" class="h-3.5 w-3.5" />
                </Link>

                <div
                    class="bg-white px-4 pb-10 pt-4 shadow-[0_1px_12px_rgba(0,0,0,0.18)] sm:px-6"
                >
                    <!-- Breadcrumb -->
                    <nav class="text-[0.6875rem] text-[#8a8a8a]">
                        <Link
                            :href="route('welcome')"
                            class="hover:text-[#1976d2]"
                            >Home</Link
                        >
                        <span class="px-1.5">/</span>
                        <span>Certificate Verification</span>
                    </nav>

                    <!-- Title -->
                    <div class="mt-5 text-center">
                        <h1 class="text-[1.0625rem] font-semibold text-[#333333]">
                            Certificate Verification
                        </h1>
                        <p class="mt-1 text-[0.9375rem] text-[#333333]">
                            પ્રમાણપત્ર ચકાસણી
                        </p>
                        <p class="mt-1 text-[0.6875rem] text-[#9e9e9e]">
                            All fields are required
                        </p>
                    </div>

                    <!-- Search form -->
                    <form
                        class="mt-6 grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2"
                        @submit.prevent="submit"
                    >
                        <EnagarField
                            label="ULB Name"
                            label-gu="ULB નું નામ"
                            required
                            star-on-gujarati
                        >
                            <div class="relative">
                                <select
                                    v-model="form.ulb_code"
                                    :class="[controlClass, 'appearance-none pr-6']"
                                >
                                    <option value="" disabled>Select</option>
                                    <option
                                        v-for="ulb in ulbs"
                                        :key="ulb.value"
                                        :value="ulb.value"
                                    >
                                        {{ ulb.label }}
                                    </option>
                                </select>
                                <EnagarIcon
                                    name="chevron"
                                    class="pointer-events-none absolute right-0 top-1.5 h-3.5 w-3.5 text-[#8a8a8a]"
                                />
                            </div>
                            <p
                                v-if="form.errors.ulb_code"
                                class="mt-1 text-[0.625rem] text-[#e53935]"
                            >
                                {{ form.errors.ulb_code }}
                            </p>
                        </EnagarField>

                        <EnagarField
                            label="Application Name"
                            label-gu="એપ્લિકેશનનું નામ"
                            required
                            star-on-gujarati
                        >
                            <div class="relative">
                                <select
                                    v-model="form.application_name"
                                    :class="[controlClass, 'appearance-none pr-6']"
                                >
                                    <option value="" disabled>Select</option>
                                    <option
                                        v-for="application in applications"
                                        :key="application.value"
                                        :value="application.value"
                                    >
                                        {{ application.label }}
                                    </option>
                                </select>
                                <EnagarIcon
                                    name="chevron"
                                    class="pointer-events-none absolute right-0 top-1.5 h-3.5 w-3.5 text-[#8a8a8a]"
                                />
                            </div>
                            <p
                                v-if="form.errors.application_name"
                                class="mt-1 text-[0.625rem] text-[#e53935]"
                            >
                                {{ form.errors.application_name }}
                            </p>
                        </EnagarField>

                        <EnagarField label="Building Permission No." required>
                            <input
                                v-model="form.building_permission_no"
                                type="text"
                                :class="controlClass"
                            />
                            <p
                                v-if="form.errors.building_permission_no"
                                class="mt-1 text-[0.625rem] text-[#e53935]"
                            >
                                {{ form.errors.building_permission_no }}
                            </p>
                        </EnagarField>

                        <div class="hidden sm:block"></div>

                        <EnagarField label="" required>
                            <input
                                v-model="form.captcha"
                                type="text"
                                placeholder="Captcha"
                                autocomplete="off"
                                :class="controlClass"
                            />
                            <p
                                v-if="form.errors.captcha"
                                class="mt-1 text-[0.625rem] text-[#e53935]"
                            >
                                {{ form.errors.captcha }}
                            </p>
                        </EnagarField>

                        <div class="flex items-center justify-between gap-3">
                            <img
                                :src="captchaUrl"
                                alt="Captcha"
                                class="h-10 select-none"
                            />
                            <button
                                type="button"
                                class="grid h-8 w-8 place-items-center rounded-[2px] bg-[#eeeeee] text-[#555555] transition hover:bg-[#e0e0e0]"
                                aria-label="Refresh captcha"
                                @click="refreshCaptcha"
                            >
                                <EnagarIcon name="refresh" class="h-4 w-4" />
                            </button>
                        </div>

                        <div class="flex justify-center sm:col-span-2">
                            <button
                                type="submit"
                                :class="buttonClass"
                                :disabled="form.processing"
                            >
                                Verify
                            </button>
                        </div>
                    </form>

                    <!-- Verified details -->
                    <template v-if="certificate">
                        <div
                            class="mt-8 grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2"
                        >
                            <EnagarField
                                v-for="field in detailFields"
                                :key="field.key"
                                :label="field.label"
                                :required="!!field.required"
                            >
                                <textarea
                                    v-if="field.type === 'textarea'"
                                    :value="certificate[field.key]"
                                    rows="2"
                                    readonly
                                    :class="[
                                        controlClass,
                                        'resize-y text-[#1f5486]',
                                    ]"
                                ></textarea>

                                <div
                                    v-else
                                    class="flex items-end justify-between gap-2 border-b border-[#e0e0e0] pb-1"
                                >
                                    <span
                                        class="text-[0.8125rem] text-[#1f5486]"
                                        >{{ certificate[field.key] }}</span
                                    >
                                    <button
                                        v-if="field.action"
                                        type="button"
                                        class="rounded-[2px] bg-[#eeeeee] px-1.5 text-[0.625rem] leading-4 text-[#555555] hover:bg-[#e0e0e0]"
                                        :title="field.label"
                                    >
                                        &hellip;
                                    </button>
                                </div>
                            </EnagarField>
                        </div>

                        <div
                            class="mt-8 grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2"
                        >
                            <EnagarField
                                label="Mobile Number"
                                label-gu="મોબાઈલ નંબર"
                                required
                            >
                                <input
                                    :value="certificate.mobile_number"
                                    type="text"
                                    placeholder="Mobile Number"
                                    :class="controlClass"
                                />
                            </EnagarField>

                            <EnagarField label="Email Id" label-gu="ઈમેઇલ">
                                <input
                                    :value="certificate.email_id"
                                    type="text"
                                    placeholder="Email Id"
                                    :class="controlClass"
                                />
                            </EnagarField>
                        </div>

                        <div class="mt-8 flex flex-wrap justify-center gap-3">
                            <a
                                v-if="certificate.has_certificate_pdf"
                                :href="
                                    route('verify-certificate.download', {
                                        certificate: certificate.id,
                                        type: 'certificate',
                                    })
                                "
                                :class="buttonClass"
                            >
                                Download PDF
                            </a>
                            <button
                                v-else
                                type="button"
                                disabled
                                :class="[buttonClass, 'cursor-not-allowed']"
                            >
                                Download PDF
                            </button>

                            <a
                                v-if="certificate.has_drawing_pdf"
                                :href="
                                    route('verify-certificate.download', {
                                        certificate: certificate.id,
                                        type: 'drawing',
                                    })
                                "
                                :class="buttonClass"
                            >
                                Download Drawing PDF
                            </a>
                            <button
                                v-else
                                type="button"
                                disabled
                                :class="[buttonClass, 'cursor-not-allowed']"
                            >
                                Download Drawing PDF
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
