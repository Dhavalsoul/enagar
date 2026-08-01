<script setup>
import EnagarIcon from '@/Components/EnagarIcon.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const page = usePage();

const openMenu = ref(null);
const mobileOpen = ref(false);
const fontScale = ref(1);

const navItems = computed(() => [
    { label: 'Home', icon: 'home', href: route('welcome') },
    {
        label: 'Quick Pay',
        icon: 'bolt',
        items: [
            'Property Tax',
            'Water & Drainage Tax',
            'Professional Tax',
            'Shop & Establishment',
        ],
    },
    {
        label: 'Certificate Verification',
        icon: 'check',
        href: route('verify-certificate'),
    },
    {
        label: 'Online Services',
        icon: 'globe',
        items: [
            'Birth Certificate',
            'Death Certificate',
            'Marriage Registration',
            'Building Permission',
            'Fire NOC',
        ],
    },
    {
        label: 'Downloads',
        icon: 'download',
        items: ['Forms', 'User Manuals', 'Circulars', 'Mobile App'],
    },
    {
        label: 'Municipalities',
        icon: 'building',
        items: [
            'Municipal Corporations',
            'Municipalities',
            'Development Authorities',
        ],
    },
    // {
    //     label: 'Login',
    //     icon: 'user',
    //     items: ['Citizen Login', 'ULB Login', 'Architect Login'],
    // },
    // { label: 'Register', icon: 'user-plus', href: route('register') },
]);

const currentPath = computed(() => new URL(page.url, 'http://x').pathname);

const isActive = (item) =>
    !!item.href && new URL(item.href, 'http://x').pathname === currentPath.value;

function toggleMenu(label) {
    openMenu.value = openMenu.value === label ? null : label;
}

function closeMenus() {
    openMenu.value = null;
}

/**
 * A- / A / A+ resize the whole page by moving the root font size, which the
 * rem based type scale below follows.
 */
function setFontScale(scale) {
    fontScale.value = scale;
    document.documentElement.style.fontSize = `${16 * scale}px`;
}

onMounted(() => document.addEventListener('click', closeMenus));

onBeforeUnmount(() => {
    document.removeEventListener('click', closeMenus);
    document.documentElement.style.fontSize = '';
});

const footerColumns = [
    {
        title: 'About Us',
        links: ['About Us', 'Privacy Policy', 'Accessibility Widget'],
    },
    { title: 'Contact Us', links: ['Contact Us', 'Feedback', 'Municipalities'] },
    { title: 'Help', links: ['Help', 'FAQ'] },
];

const footerLogos = [
    {
        short: 'UD&UHD',
        label: 'Urban Development And Urban Housing Department',
        class: 'bg-[#b7312c]',
    },
    {
        short: 'GSWAN',
        label: 'Gujarat State Wide Area Network',
        class: 'bg-[#8a6d1f]',
    },
    {
        short: 'GUDM',
        label: 'Gujarat Urban Development Mission',
        class: 'bg-[#e2801e] rounded-full',
    },
    {
        short: 'GIGW',
        label: 'GIGW (Guidelines for Indian Government Websites)',
        class: 'bg-[#2f2f2f]',
    },
];
</script>

<template>
    <div
        class="flex min-h-screen flex-col bg-[#e9eaec] font-enagar text-[#333333]"
    >
        <!-- Top navigation -->
        <header class="sticky top-0 z-40 bg-[#444444] shadow-[0_1px_5px_rgba(0,0,0,0.35)]">
            <div
                class="mx-auto flex h-14 max-w-[1350px] items-center gap-2 px-3"
            >
                <Link
                    :href="route('welcome')"
                    class="flex shrink-0 items-center gap-2"
                >
                    <span
                        class="grid h-9 w-9 place-items-center rounded-full bg-[#1a6bb5] text-xs font-bold text-white"
                    >
                        eN
                    </span>
                    <span class="leading-none">
                        <span class="block text-lg font-bold text-[#4da3e8]"
                            >Nagar</span
                        >
                        <span
                            class="block text-[0.625rem] font-semibold uppercase tracking-widest text-[#f37021]"
                            >Gujarat</span
                        >
                    </span>
                </Link>

                <nav
                    class="ml-4 hidden flex-1 items-center self-stretch xl:flex"
                    @click.stop
                >
                    <template v-for="item in navItems" :key="item.label">
                        <Link
                            v-if="item.href"
                            :href="item.href"
                            class="flex h-full items-center gap-1.5 border-b-[3px] px-3 text-[0.8125rem] transition hover:text-[#4da3e8]"
                            :class="
                                isActive(item)
                                    ? 'border-[#4da3e8] font-semibold text-white'
                                    : 'border-transparent text-[#e0e0e0]'
                            "
                        >
                            <EnagarIcon :name="item.icon" class="h-4 w-4" />
                            {{ item.label }}
                        </Link>

                        <div v-else class="relative h-full">
                            <button
                                type="button"
                                class="flex h-full items-center gap-1.5 border-b-[3px] border-transparent px-3 text-[0.8125rem] text-[#e0e0e0] transition hover:text-[#4da3e8]"
                                @click="toggleMenu(item.label)"
                            >
                                <EnagarIcon :name="item.icon" class="h-4 w-4" />
                                {{ item.label }}
                                <EnagarIcon
                                    name="chevron"
                                    class="h-3 w-3 transition"
                                    :class="
                                        openMenu === item.label
                                            ? 'rotate-180'
                                            : ''
                                    "
                                />
                            </button>

                            <div
                                v-show="openMenu === item.label"
                                class="absolute left-0 top-full z-50 w-60 border-t-2 border-[#4da3e8] bg-[#4f4f4f] py-1 shadow-lg"
                            >
                                <a
                                    v-for="link in item.items"
                                    :key="link"
                                    href="#"
                                    class="block px-4 py-2 text-[0.8125rem] text-[#e0e0e0] hover:bg-[#5a5a5a] hover:text-white"
                                >
                                    {{ link }}
                                </a>
                            </div>
                        </div>
                    </template>
                </nav>

                <div class="ml-auto flex items-center gap-2">
                    <span
                        class="hidden h-10 w-10 place-items-center rounded-full border-2 border-[#e2801e] text-[0.5rem] font-bold text-[#e2801e] sm:grid"
                    >
                        GUDM
                    </span>

                    <div class="flex items-center gap-px">
                        <button
                            v-for="option in [
                                { label: 'A-', scale: 0.875 },
                                { label: 'A', scale: 1 },
                                { label: 'A+', scale: 1.125 },
                            ]"
                            :key="option.label"
                            type="button"
                            class="h-6 w-6 border border-[#7a7a7a] text-[0.625rem] font-semibold text-white transition"
                            :class="
                                fontScale === option.scale
                                    ? 'bg-[#1a6bb5]'
                                    : 'bg-[#2f2f2f] hover:bg-[#1f1f1f]'
                            "
                            :title="`Font size ${option.label}`"
                            @click="setFontScale(option.scale)"
                        >
                            {{ option.label }}
                        </button>
                    </div>

                    <button
                        type="button"
                        class="ml-1 grid h-8 w-8 place-items-center rounded border border-[#7a7a7a] text-[#e0e0e0] xl:hidden"
                        aria-label="Toggle navigation"
                        @click.stop="mobileOpen = !mobileOpen"
                    >
                        <span class="text-lg leading-none">&#9776;</span>
                    </button>
                </div>
            </div>

            <!-- Mobile navigation -->
            <nav
                v-show="mobileOpen"
                class="border-t border-[#5a5a5a] bg-[#444444] xl:hidden"
                @click.stop
            >
                <template v-for="item in navItems" :key="item.label">
                    <Link
                        v-if="item.href"
                        :href="item.href"
                        class="flex items-center gap-2 border-b border-[#555555] px-4 py-2.5 text-[0.8125rem]"
                        :class="
                            isActive(item)
                                ? 'font-semibold text-[#4da3e8]'
                                : 'text-[#e0e0e0]'
                        "
                    >
                        <EnagarIcon :name="item.icon" class="h-4 w-4" />
                        {{ item.label }}
                    </Link>

                    <div v-else class="border-b border-[#555555]">
                        <button
                            type="button"
                            class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-[0.8125rem] text-[#e0e0e0]"
                            @click="toggleMenu(item.label)"
                        >
                            <EnagarIcon :name="item.icon" class="h-4 w-4" />
                            {{ item.label }}
                            <EnagarIcon
                                name="chevron"
                                class="ml-auto h-3 w-3"
                                :class="
                                    openMenu === item.label ? 'rotate-180' : ''
                                "
                            />
                        </button>
                        <a
                            v-for="link in item.items"
                            v-show="openMenu === item.label"
                            :key="link"
                            href="#"
                            class="block bg-[#4f4f4f] px-10 py-2 text-[0.8125rem] text-[#e0e0e0]"
                        >
                            {{ link }}
                        </a>
                    </div>
                </template>
            </nav>
        </header>

        <div class="h-3 bg-[#e0e1e3]"></div>

        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-[#444444] text-[#cfcfcf]">
            <div
                class="mx-auto grid max-w-[1350px] gap-x-8 gap-y-10 px-6 py-12 sm:grid-cols-2 lg:grid-cols-5"
            >
                <div v-for="column in footerColumns" :key="column.title">
                    <h2 class="mb-4 text-base uppercase text-white">
                        {{ column.title }}
                    </h2>
                    <ul class="space-y-2.5 text-[0.8125rem]">
                        <li v-for="link in column.links" :key="link">
                            <a href="#" class="hover:text-white">{{ link }}</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h2 class="mb-4 text-base uppercase text-white">
                        Navigate Site
                    </h2>
                    <ul class="space-y-2.5 text-[0.8125rem]">
                        <li>
                            <Link :href="route('welcome')" class="hover:text-white"
                                >Home</Link
                            >
                        </li>
                        <li><a href="#" class="hover:text-white">Search</a></li>
                        <li><a href="#" class="hover:text-white">Sitemap</a></li>
                    </ul>

                    <h3
                        class="mb-3 mt-6 text-[0.8125rem] font-semibold uppercase text-[#4da3e8]"
                    >
                        Download Mobile App
                    </h3>
                    <div class="flex gap-3">
                        <a
                            v-for="store in ['android', 'apple']"
                            :key="store"
                            href="#"
                            class="grid h-9 w-9 place-items-center rounded-full bg-[#1a6bb5] text-white hover:bg-[#4da3e8]"
                            :aria-label="`Download ${store} app`"
                        >
                            <EnagarIcon :name="store" class="h-5 w-5" />
                        </a>
                    </div>
                </div>

                <ul class="space-y-3">
                    <li
                        v-for="logo in footerLogos"
                        :key="logo.short"
                        class="flex items-center gap-3"
                    >
                        <span
                            class="grid h-11 w-11 shrink-0 place-items-center text-[0.5rem] font-bold text-white"
                            :class="logo.class"
                        >
                            {{ logo.short }}
                        </span>
                        <span class="text-[0.8125rem] leading-snug text-white">
                            {{ logo.label }}
                        </span>
                    </li>
                </ul>
            </div>

            <div class="mx-auto max-w-[1350px] px-6">
                <span
                    class="inline-block border border-[#8b929b] bg-[#e9eaec] px-3 py-1 text-xs text-[#333]"
                >
                    Visitor Count : 49006972
                </span>
            </div>

            <div
                class="mx-auto flex max-w-[1350px] flex-col gap-2 px-6 py-6 text-xs sm:flex-row sm:items-end sm:justify-between"
            >
                <div class="space-y-1.5">
                    <p>
                        This Website belongs to Urban development and Urban
                        Housing Development , Government of Gujarat
                    </p>
                    <p>
                        Best viewed in Google Chrome, Mozilla Firefox, Microsoft
                        Edge and Safari with screen resolution 1366 x 768 or
                        higher.
                    </p>
                </div>
                <p class="shrink-0">Last Updated On : 01/08/2026</p>
            </div>

            <div class="bg-[#3a3a3a] py-3 text-center text-xs text-[#b5b5b5]">
                2026 &copy; ENAGAR All rights Reserved.
            </div>
        </footer>
    </div>
</template>
