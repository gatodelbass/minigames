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

        <div class="justify-center flex flex-wrap w-full">
            <div
                v-for="(car, index) in state.cars"
                :key="car.index"
                class="bg-teal-200  border-gray-200 my-2"
            >
                <div
                    @click="solve(index)"
                    class="text-center cursor-pointer bg-teal-400"
                >
                    solve
                </div>

                <div v-if="car.solve"
                   
                    class="text-center hide "
                >
                    {{ car.name }}
                </div>

                <div class="relative">
                    <img
                        class=" "
                        alt="img"
                        :src="'/storage/cars/' + car.file"
                    />


                    <!--
                    <div
                        class="absolute w-20 h-20 top-180 left-0 bg-red-300 border-1"
                        
                    >0</div>

                    <div
                        class="absolute w-20 h-20 top-180 left-20 bg-red-300 border-1"
                        
                    >20</div>
                    <div
                        class="absolute w-20 h-20 top-180 left-40 bg-red-300 border-1"
                        
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-180 left-60 bg-red-300 border-1"
                        
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-180 left-80 bg-red-300 border-1"
                        
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-180 left-100 bg-red-300 border-1"
                        
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-180 left-120 bg-red-300 border-1"
                        
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-180 left-140 bg-red-300 border-1"
                        
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-180 left-160 bg-red-300 border-1"
                        
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-180 left-180 bg-red-300 border-1"
                        
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-180 left-200 bg-red-300 border-1"
                        
                    ></div>
                    <div
                        class="absolute w-20 h-20 top-180 left-220 bg-red-300 border-1"
                        
                    ></div>
              -->
                   
                  



                    <div v-for="x in 9" :key="x" v-if="!car.solve">
                        <div v-for="y in 12" :key="y">
                            <div
                                class="absolute z-10 w-20 h-20 top-0 text-3xl text-center border-2 border-gray-700 bg-yellow-500"
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

export default {
    name: "LocomotiveIndex",
    components: {
        AppLayout,
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
            cars: [],
            players: [],
            nickname: "",
        });

        onMounted(() => {
            props.cars.forEach((element) => {
                let car = {
                    name: element.name,
                    file: element.file,
                    revealName: false,
                    solve: false,
                };

                state.cars.push(car);
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
            state.cars[index].revealName = true;
        }

        function removeTile(x, y, event) {
            const el = event.currentTarget;
            el.style.visibility = "hidden";
        }

        function solve(index) {
            state.cars[index].solve = true;
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
