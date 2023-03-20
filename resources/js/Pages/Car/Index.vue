<template>
    <app-layout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl leading-tight">Cars</h2>
            </div>
        </template>

        <div class="justify-center flex flex-wrap px-5 m-2">
            <inertia-link
                :href="route('cars.create')"
                class="px-4 bg-gray-700 py-2 rounded-sm text-amber-300 font-semibold hover:bg-gray-800 my-4"
            >
                New
            </inertia-link>

            <table class="min-w-full table-auto">
                <thead class="justify-between">
                    <tr class="bg-gray-800">
                        <th class="px-4 py-2">
                            <span class="text-gray-300">ID</span>
                        </th>
                        <th class="px-4 py-2">
                            <span class="text-gray-300">Image</span>
                        </th>
                        <th class="px-4 py-2">
                            <span class="text-gray-300">Name</span>
                        </th>
                        <th class="px-4 py-2">
                            <span class="text-gray-300">Option</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-200 text-center">
                    <tr
                        v-for="(car, index) in cars"
                        :key="index"
                        class="bg-white border-1 border-gray-200"
                    >
                        <td class="px-4 py-1">
                            {{ car.id }}
                        </td>
                        <td>
                            <img
                                class="w-20"
                                alt="img"
                                :src="'/storage/cars/' + car.file"
                            />
                        </td>
                        <td>
                            <jet-input
                                id="other_position"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="car.name"
                            />
                        </td>

                        <td class="px-4 py-1">
                            <img
                                @click="saveInfo(index, car.id, $event)"
                                :src="'https://cdns.iconmonstr.com/wp-content/releases/preview/2017/96/iconmonstr-save-14.png'"
                                class="w-8 rounded p-1 mx-1 inline-block"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive } from "vue";
import JetInput from "@/Jetstream/Input.vue";
import Swal from "sweetalert2";

export default {
    name: "LocomotiveIndex",
    components: {
        AppLayout,
        JetInput,
    },
    props: {
        cars: {
            type: Object,
            default: {},
        },
        userPermissions: {
            type: Array,
            default: [],
        },
    },

    setup(props, { emit }) {
        const state = reactive({
            info: {
                id: "",
                name: "",
            },
        });

        function can(permission) {
            if (props.userPermissions.includes(permission)) {
                return true;
            } else {
                return false;
            }
        }

        async function saveInfo(index, carId, event) {
            state.info.id = carId;
            state.info.name = props.cars[index].name;

            await axios
                .get(route("saveCarInfo", { params: state.info }))
                .then(function (response) {
                    Swal.fire({
                        toast: true,
                        title: '<p class="text-xl text-blueGray-300">Done!</p>',
                        showClass: { popup: "" },
                        position: "top-end",
                        showConfirmButton: false,
                        icon: "success",
                        background: "#334155",
                        timer: 1500,
                    });
                })
                .catch(function (error) {});
        }

        return {
            state,
            saveInfo,
            can,
        };
    },
};
</script>
