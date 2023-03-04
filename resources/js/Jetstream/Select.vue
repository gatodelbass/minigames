<template>

    {{ valuex }}

  <select class="p-3 border-gray-400 h-10 py-0" v-model="modelValue">
    <option
      v-for="(option, key) in options"
      :value="key"
      :selected="key === valuex"
      :key="key"
    >
      {{ option }} - {{ key }}
    </option>
  </select>
</template>

<script>
export default {
  props: {
    options: {
      type: Array,
      default: [],
    },
    valuex: {
      type: String,
      default: null,
    },
  },
};
</script>
