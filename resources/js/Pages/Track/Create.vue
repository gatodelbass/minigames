<template>
  <div class="max-w-4xl mx-auto mt-10 bg-amber-100 border-1 p-2">
    <div class="py-3 mx-auto sm:px-6 lg:px-8">
      <div class="flex justify-center m-2">
        <div class="flex">
          <h1 class="text-gray-600 font-bold md:text-2xl text-xl">
            New product
          </h1>
        </div>
      </div>
      <div class="flex flex-wrap">
        <div class="w-full md:w-2/3 mb-4 px-2">
          <jet-label for="name">Product name: </jet-label>
          <jet-input
            id="other_position"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
          />
          <jet-input-error :message="form.errors.name" class="mt-2" />
        </div>

        <div class="w-full md:w-1/3 mb-4 px-2">
          <jet-label for="name">Condition: </jet-label>
          <select
            v-model="form.condition"
            class="p-3 border-gray-400 h-10 py-0 w-full mt-1"
          >
            <option
              v-for="condition in state.conditions"
              :value="condition"
              :key="condition"
            >
              {{ condition }}
            </option>
          </select>
          <jet-input-error :message="form.errors.status" class="mt-2" />
        </div>

        <div class="w-full md:w-1/3 mb-4 px-2">
          <jet-label for="name">Status: </jet-label>
          <select
            v-model="form.status"
            class="p-3 border-gray-400 h-10 py-0 w-full mt-1"
          >
            <option
              v-for="option in state.options"
              :value="option"
              :key="option"
            >
              {{ option }}
            </option>
          </select>
          <jet-input-error :message="form.errors.status" class="mt-2" />
        </div>

        <div class="w-full md:w-1/3 mb-4 px-2">
          <jet-label for="cost">Cost:</jet-label>
          <jet-input
            id="cost"
            type="number"
            class="mt-1 block w-full"
            v-model="form.cost"
          />
          <jet-input-error :message="form.errors.cost" class="mt-2" />
        </div>

        <div class="w-full md:w-1/3 mb-4 px-2">
          <jet-label for="discount">Discount:</jet-label>
          <jet-input
            id="discount"
            type="number"
            class="mt-1 block w-full"
            v-model="form.discount"
          />
          <jet-input-error :message="form.errors.discount" class="mt-2" />
        </div>

        <div class="w-full mb-4 px-2">
          <jet-label for="notes">Key 1:</jet-label>
          <jet-text-area
            id="notes"
            class="mt-1 block w-full"
            v-model="form.key1"
          >
          </jet-text-area>
          <jet-input-error :message="form.errors.key1" class="mt-2" />
        </div>

        <div class="w-full mb-4 px-2">
          <jet-label for="notes">Key 2:</jet-label>
          <jet-text-area
            id="notes"
            class="mt-1 block w-full"
            v-model="form.key2"
          >
          </jet-text-area>
          <jet-input-error :message="form.errors.key2" class="mt-2" />
        </div>

        <div class="w-full mb-4 px-2">
          <jet-label for="notes">Key 3:</jet-label>
          <jet-text-area
            id="notes"
            class="mt-1 block w-full"
            v-model="form.key3"
          >
          </jet-text-area>
          <jet-input-error :message="form.errors.key3" class="mt-2" />
        </div>
      </div>
    </div>

    <div class="flex items-center justify-center px-4 mb-4 md:gap-8 gap-4">
      <a
        :href="route('products.index')"
        class="w-auto bg-gray-600 hover:bg-gray-700 r font-medium text-amber-300 px-4 py-2"
        >Cancel</a
      >
      <button
        @click="save()"
        class="w-auto bg-teal-500 hover:bg-teal-600 font-medium text-gray-700 px-4 py-2"
      >
        Save
      </button>
    </div>
  </div>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";
import { reactive } from "vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetSelect from "@/Jetstream/Select.vue";
import JetTextArea from "@/Jetstream/Textarea.vue";
import Swal from "sweetalert2";

export default {
  name: "ProductCreate",
  components: {
    JetInput,
    JetInputError,
    JetLabel,
    JetSelect,
    JetTextArea,
  },
  props: {
    errors: Object,
    brands: {
      type: Object,
      default: {},
    },
    categories: {
      type: Object,
      default: {},
    },
  },

  setup(props, { emit }) {
    const state = reactive({
      selectedFile: null,
      options: { Active: "Active", Inactive: "Inactive" },
      conditions: { Nuevo: "Nuevo", Reacondicionado: "Reacondicionado" },
    });

    const form = useForm({
      name: null,
      cost: 0,
      discount: 0,
      key1: null,
      key2: null,
      key3: null,
      status: "Active",
      condition: "Nuevo",
    });

    function save() {
      form.post(route("products.store"), {
        onSuccess: () => {
          Swal.fire({
            toast: true,
            title: '<p class="text-xl text-blueGray-300">Hecho</p>',
            showClass: { popup: "" },
            position: "top-end",
            showConfirmButton: false,
            icon: "success",
            background: "#334155",
            timer: 1500,
          });
        },
        onError: () => {
          Swal.fire({
            toast: true,
            title: '<p class="text-xl">Error</p>',
            showClass: { popup: "" },
            position: "top-end",
            showConfirmButton: false,
            icon: "error",
            background: "#fddf7f",
            timer: 1500,
          });
        },
      });
    }

    return {
      save,
      form,
      state,
    };
  },
};
</script>
