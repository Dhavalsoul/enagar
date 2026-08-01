<script setup>
defineProps({
    label: {
        type: String,
        required: true,
    },
    labelGu: {
        type: String,
        default: '',
    },
    required: {
        type: Boolean,
        default: false,
    },
    // The portal hangs the asterisk beside the Gujarati line on the search
    // fields and beside the English line everywhere else.
    starOnGujarati: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="pl-3.5">
        <div class="text-[0.6875rem] leading-[1.35] text-[#6f6f6f]">
            <span class="relative block">
                <span
                    v-if="required && !starOnGujarati"
                    class="absolute -left-3.5 text-[#e53935]"
                    aria-hidden="true"
                    >*</span
                >
                {{ label }}
            </span>
            <span v-if="labelGu" class="relative block">
                <span
                    v-if="required && starOnGujarati"
                    class="absolute -left-3.5 text-[#e53935]"
                    aria-hidden="true"
                    >*</span
                >
                {{ labelGu }}
            </span>
        </div>

        <div class="mt-1.5">
            <slot />
        </div>
    </div>
</template>
