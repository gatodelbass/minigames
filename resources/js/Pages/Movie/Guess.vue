<template>
    <app-layout>
        <div
            class="w-full flex flex-wrap p-2 m-1 sticky top-0 z-40 bg-gray-800"
        >
            <input
                v-model="state.nickname"
                class="py-2 px-3 rounded-sm border-1 border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"
                type="text"
                placeholder=""
            />

            <button
                @click="addPlayer()"
                class="w-auto bg-teal-500 hover:bg-teal-600 rounded-sm shadow-xl font-medium text-gray-700 px-4 py-2 mx-2"
            >
                Add Player
            </button>
        </div>

        <h1>{{ state.hide }}</h1>

        <div
            class="w-full flex flex-wrap p-2 m-2 sticky top-0 z-40 bg-gray-800"
        >
            <div
                v-for="(player, index) in state.players"
                :key="player.id"
                class="bg-red-500 text-white px-3 mx-2 py-1 text-xl font-semibold rounded-sm"
            >
                {{ player.nickname }}
                <span
                    class="bg-gray-700 text-yellow-400 rounded px-2 py-1 mx-1 text-lg"
                    >{{ player.score }}</span
                >

                <button
                    @click="addPoint(index)"
                    class="text-lg font-bold rounded-full px-1 py-0 text-gray-100 border-2 border-gray-100"
                >
                    +
                </button>
            </div>
        </div>

        <div class="justify-center flex flex-wrap content-center">
            <div
                v-for="(movie, index) in state.movies"
                :key="movie.index"
                class="bg-teal-200 border-1 border-gray-200 m-1"
            >
                <div
                    @click="solve(index)"
                    class="text-center cursor-pointer bg-teal-400"
                >
                    solve
                </div>

                <div class="relative">
                    <img
                        class=" "
                        alt="img"
                        :src="'/storage/movies/' + movie.file"
                    />

                    <div
                        class="absolute w-20 h-20 top-0 left-0 hide"
                        hidden
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-20 left-20 hide"
                        hidden
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-40 left-40 hide"
                        hidden
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-60 left-60 hide"
                        hidden
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-80 left-80 hide"
                        hidden
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-100 left-100 hide"
                        hidden
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-120 left-100 hide"
                        hidden
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-140 left-100 hide"
                        hidden
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-160 left-100 hide"
                        hidden
                    ></div>

                    <div v-for="x in 9" :key="x" v-if="!movie.solve">
                        <div v-for="y in 6" :key="y">
                            <div
                                class="absolute z-10 w-20 h-20 top-0 text-4xl text-center border-2 border-gray-700 bg-yellow-500"
                                :class="`left-${y * 20 - 20} top-${
                                    x * 20 - 20
                                }`"
                                @click="removeTile(x, y, $event)"
                            >
                                {{ x }}{{ y }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, onMounted } from "vue";
import JetInput from "@/Jetstream/Input.vue";
import Swal from "sweetalert2";

import { Inertia } from "@inertiajs/inertia";

export default {
    name: "LocomotiveIndex",
    components: {
        AppLayout,
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
            movies: [],
            players: [],
            nickname: "",
        });

        onMounted(() => {
            props.movies.forEach((element) => {
                let movie = {
                    name: element.name,
                    file: element.file,
                    revealName: false,
                    solve: false,
                };

                state.movies.push(movie);
            });
        });

        function addPlayer() {
            let player = {
                nickname: state.nickname,
                score: 0,
            };
            state.players.push(player);

            state.nickname = "";
        }

        function addPoint(index) {
            let player = {
                nickname: state.nickname,
                score: 0,
            };
            state.players[index].score++;
        }

        function can(permission) {
            if (props.userPermissions.includes(permission)) {
                return true;
            } else {
                return false;
            }
        }

        function revealName(index) {
            state.movies[index].revealName = true;
        }

        function removeTile(x, y, event) {
            const el = event.currentTarget;
            el.style.visibility = "hidden";
        }

        function solve(index) {
            state.movies[index].solve = true;
        }

        return {
            state,

            can,
            revealName,
            addPlayer,
            addPoint,
            removeTile,
            solve,
        };
    },
};
</script>
