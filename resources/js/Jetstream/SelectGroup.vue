<template>
    <select class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" v-model="value">
        
        <optgroup  v-for="group, keyg in options" :key="keyg" :label="group.label" >
            <option
                v-for="option, key in group.options" 
                :value="key"
                :selected="key == value"
                :key="key"
                :disabled="option.disabled"
            >
            {{ option.value }}
            </option>
        </optgroup>

    </select>

</template>

<script>
    export default {
        props: {
            options: { type: Object, default: {} },
            value: { default: null, },
        },
    }
</script>



