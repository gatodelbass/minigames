<template>
    <app-layout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl leading-tight">Tracks</h2>
            </div>
        </template>

        <div class="justify-center flex flex-wrap px-5 m-2">
            <inertia-link
                :href="route('tracks.create')"
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
                            <span class="text-gray-300">Author</span>
                        </th>
                        <th class="px-4 py-2">
                            <span class="text-gray-300">Level</span>
                        </th>
                        <th class="px-4 py-2">
                            <span class="text-gray-300">Option</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-200 text-center">
                    <tr
                        v-for="(track, index) in tracks"
                        :key="index"
                        class="bg-white border-1 border-gray-200"
                    >
                        <td class="px-4 py-1">
                            {{ track.id }}
                        </td>
                        <td>
                            <audio controls>
                                <source
                                    :src="'/storage/tracks/' + track.file"
                                    type="audio/ogg"
                                />
                            </audio>
                        </td>
                        <td>
                            <jet-input
                                id="other_position"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="track.name"
                            />
                        </td>
                        <td>
                            <jet-input
                                type="text"
                                class="mt-1 block w-full"
                                v-model="track.author"
                            />
                        </td>

                        <td class="px-4 py-1">
                            <span
                                v-bind:class="{
                                    'bg-green-400': track.level == 'Easy',
                                }"
                                @click="setLevel(index, 'Easy')"
                                class="border-green-500 border-2 p-1 m-1 rounded"
                                >Easy</span
                            >

                            <span
                                v-bind:class="{
                                    'bg-yellow-400': track.level == 'Medium',
                                }"
                                @click="setLevel(index, 'Medium')"
                                class="border-yellow-500 border-2 p-1 m-1 rounded"
                                >Medium</span
                            >

                            <span
                                v-bind:class="{
                                    'bg-red-400': track.level == 'Hard',
                                }"
                                @click="setLevel(index, 'Hard')"
                                class="border-red-500 border-2 p-1 m-1 rounded"
                                >Hard</span
                            >
                        </td>
                        <td class="px-4 py-1">
                            <img
                                @click="saveInfo(index, track.id, $event)"
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
        tracks: {
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
                author: "",
                level: "Easy",
            },
        });

        function can(permission) {
            if (props.userPermissions.includes(permission)) {
                return true;
            } else {
                return false;
            }
        }

        function setLevel(index, level) {
            state.info.level = level;
            props.tracks[index].level = level;
        }

        async function saveInfo(index, trackId, event) {
            state.info.id = trackId;
            state.info.name = props.tracks[index].name;
            state.info.author = props.tracks[index].author;

            await axios
                .get(route("saveTrackInfo", { params: state.info }))
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
            setLevel,
        };
    },
};
</script>
