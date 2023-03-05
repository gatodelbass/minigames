<template>
    <app-layout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl leading-tight">Movies</h2>
            </div>
        </template>

        <div class="justify-center flex flex-wrap px-5 m-2">
            <inertia-link
                :href="route('movies.create')"
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
                            <span class="text-gray-300">Play</span>
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
                        v-for="(movie, index) in movies"
                        :key="index"
                        class="bg-white border-1 border-gray-200"
                    >
                        <td class="px-4 py-1">
                            {{ movie.id }}
                        </td>
                        <td>
                            <img
                                class="w-20"
                                alt="img"
                                :src="'/storage/movies/' + movie.file"
                            />
                        </td>
                        <td>
                            <jet-input
                                id="other_position"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="movie.name"
                            />
                        </td>

                        <td class="px-4 py-1">
                            <img
                                @click="saveInfo(index, movie.id, $event)"
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
        movies: {
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

        async function saveInfo(index, movieId, event) {
            state.info.id = movieId;
            state.info.name = props.movies[index].name;

            await axios
                .get(route("saveMovieInfo", { params: state.info }))
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
