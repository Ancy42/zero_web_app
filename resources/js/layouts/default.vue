<template>
    <div class="min-h-screen flex flex-col">
        <NavbarTopContainer :themeName="master.themeName"/>
        <Navbar :themeName="master.themeName"/>

        <div class="flex-grow">
            <slot />
        </div>

        <div>
            <FooterContainer :varientName="master.themeName" />
            <BasketCard />
        </div>
    </div>

    <!-- <ScrollToTopBtn /> -->
</template>

<script setup>
import { onMounted, watch } from "vue";
import NavbarTopContainer from "../components/NavbarTopContainer.vue";
import Navbar from "../components/Navbar.vue";
import BasketCard from "../components/BasketCard.vue";

import { useMaster } from "../stores/MasterStore";
import ScrollToTopBtn from "../components/ScrollToTopBtn.vue";
import FooterContainer from "../components/FooterContainer.vue";

const master = useMaster();

onMounted(() => {
    master.fetchData();
    window.scrollTo(0, 0);
    setupThemeColors();
    setDirection();
    setupFontFamily();
});

watch(
    () => master.themeColors,
    () => {
        setupThemeColors();
    }
);

watch(
    () => master.langDirection,
    () => {
        setDirection();
    }
);

watch(
    () => master.themeName,
    () => {
        setupFontFamily();
    }
);


watch(
    () => master.selectedCurrency,
    () => {
        window.location.reload();


    }
);

const setDirection = () => {
    const html = document.querySelector("html");
    html.dir = master.langDirection || "ltr";
};

const areThemeColorsValid = () => {
    const colorProperties = [
        "primary",
        "primary50",
        "primary100",
        "primary200",
        "primary300",
        "primary400",
        "primary500",
        "primary600",
        "primary700",
        "primary800",
        "primary900",
        "primary950",
    ];

    for (const color of colorProperties) {
        if (!master.themeColors[color]) {
            return false;
        }
    }
    return true;
};

const setupThemeColors = () => {
    if (!areThemeColorsValid()) {
        return;
    }
    document.documentElement.style.setProperty(
        "--primary",
        master.themeColors.primary
    );
    document.documentElement.style.setProperty(
        "--primary-50",
        master.themeColors.primary50
    );
    document.documentElement.style.setProperty(
        "--primary-100",
        master.themeColors.primary100
    );
    document.documentElement.style.setProperty(
        "--primary-200",
        master.themeColors.primary200
    );
    document.documentElement.style.setProperty(
        "--primary-300",
        master.themeColors.primary300
    );
    document.documentElement.style.setProperty(
        "--primary-400",
        master.themeColors.primary400
    );
    document.documentElement.style.setProperty(
        "--primary-500",
        master.themeColors.primary500
    );
    document.documentElement.style.setProperty(
        "--primary-600",
        master.themeColors.primary600
    );
    document.documentElement.style.setProperty(
        "--primary-700",
        master.themeColors.primary700
    );
    document.documentElement.style.setProperty(
        "--primary-800",
        master.themeColors.primary800
    );
    document.documentElement.style.setProperty(
        "--primary-900",
        master.themeColors.primary900
    );
    document.documentElement.style.setProperty(
        "--primary-950",
        master.themeColors.primary950
    );
};

const setupFontFamily = () => {
    if (master.themeName == "NovaStore") {
        document.body.style.fontFamily = "Lato, sans-serif";
    } else if (master.themeName == "MegaMart") {
        document.body.style.fontFamily = "Lato, sans-serif";
    } else if (master.themeName == "NextMart") {
        document.body.style.fontFamily = "Lexend, sans-serif";
    } else if (master.themeName == "PrimeCart") {
        document.body.style.fontFamily = "Montserrat, sans-serif";
    }else if (master.themeName == "UltraMart") {
        document.body.style.fontFamily = "Inter, sans-serif";
    }
    // Setting the --font-family CSS custom property
};
</script>
