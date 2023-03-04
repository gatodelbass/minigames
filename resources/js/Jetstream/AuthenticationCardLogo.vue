<template>
    <img class="w-10 inline-block" :src="logo" />
</template>

<script>
import logo from "../../images/logo.jpg";

export default {
    name: "LocomotiveIndex",
    components: {},

    setup(props, { emit }) {
        return {
            logo,
        };
    },
};
</script>
