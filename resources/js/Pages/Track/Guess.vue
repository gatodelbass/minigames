<template>
  <app-layout>
    <div class="w-full flex flex-wrap p-2 m-1 sticky top-0 z-40 bg-gray-800">
      <input
        v-model="state.nickname"
        class="
          py-2
          px-3
          rounded-sm
          border-1 border-gray-300
          mt-1
          focus:outline-none
          focus:ring-2 focus:ring-amber-400
          focus:border-transparent
        "
        type="text"
        placeholder=""
      />

      <button
        @click="addPlayer()"
        class="
          w-auto
          bg-teal-500
          hover:bg-teal-600
          rounded-sm
          shadow-xl
          font-medium
          text-gray-700
          px-4
          py-2
          mx-2
        "
      >
        Add Player
      </button>
    </div>

    <div class="w-full flex flex-wrap p-2 m-2 sticky top-0 z-40 bg-gray-800">
      <div
        v-for="(player, index) in state.players"
        :key="player.id"
        class="
          bg-red-500
          text-white
          px-3
          mx-2
          py-1
          text-xl
          font-semibold
          rounded-sm
        "
      >
        {{ player.nickname }}
        <span
          class="bg-gray-700 text-yellow-400 rounded px-2 py-1 mx-1 text-lg"
          >{{ player.score }}</span
        >

        <button @click="addPoint(index)" class="text-lg font-bold  rounded-full px-1 py-0 text-gray-100 border-2 border-gray-100">+</button>
      </div>
    </div>

    <div class="justify-center flex flex-wrap px-5 m-2">
      <table class="min-w-full table-auto">
        <thead class="justify-between">
          <tr class="bg-gray-800">
            <th class="px-4 py-2">
              <span class="text-gray-300">#</span>
            </th>
            <th class="px-4 py-2">
              <span class="text-gray-300">Play</span>
            </th>
            <th class="px-4 py-2">
              <span class="text-gray-300">Level</span>
            </th>
            <th class="px-4 py-2">
              <span class="text-gray-300">Name</span>
            </th>
            <th class="px-4 py-2">
              <span class="text-gray-300">Author</span>
            </th>
          </tr>
        </thead>
        <tbody class="bg-gray-200 text-center">
          <tr
            v-for="(song, index) in state.songs"
            :key="song.index"
            class="bg-white border-1 border-gray-200"
          >
            <td class="px-1 py-1">
              {{ index }}
            </td>
            <td class="z-0">
              <audio controls>
                <source
                  :src="'/storage/tracks/' + song.file"
                  type="audio/ogg"
                />
              </audio>
            </td>

            <td class="px-1 py-1">
              <span
                v-if="song.level == 'Easy'"
                class="bg-teal-500 text-white font-bold px-2 py-1 rounded-sm"
                >😁😁{{ song.level }}😁😁</span
              >
              <span
                v-if="song.level == 'Medium'"
                class="bg-yellow-500 text-white font-bold px-2 py-1 rounded-sm"
                >😮😮{{ song.level }}😮😮</span
              >
              <span
                v-if="song.level == 'Hard'"
                class="bg-red-500 text-white font-bold px-2 py-1 rounded-sm"
                >☠️☠️{{ song.level }}☠️☠️</span
              >
            </td>

            <td class="px-1 py-1">
              <span v-if="song.revealName">{{ song.name }}</span>
              <span v-else
                ><button
                  @click="revealName(index)"
                  class="
                    w-auto
                    bg-teal-500
                    hover:bg-teal-600
                    rounded-sm
                    shadow-xl
                    font-medium
                    text-gray-700
                    px-4
                    py-2
                  "
                >
                  Reveal name!
                </button></span
              >
            </td>
            <td class="px-1 py-1">
              <span v-if="song.revealAuthor">{{ song.author }}</span>
              <span v-else
                ><button
                  @click="revealAuthor(index)"
                  class="
                    w-auto
                    bg-teal-500
                    hover:bg-teal-600
                    rounded-sm
                    shadow-xl
                    font-medium
                    text-gray-700
                    px-4
                    py-2
                  "
                >
                  Reveal author!
                </button></span
              >
            </td>
          </tr>
        </tbody>
      </table>
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
      songs: [],
      players: [],
      nickname: "",
    });

    onMounted(() => {
      props.tracks.forEach((element) => {
        let song = {
          name: element.name,
          author: element.author,
          level: element.level,
          file: element.file,
          revealName: false,
          revealAuthor: false,
        };

        state.songs.push(song);
      });
    });

    function revealName(index) {
      state.songs[index].revealName = true;
    }

    function revealAuthor(index) {
      state.songs[index].revealAuthor = true;
    }

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

    function remove(id) {
      Swal.fire({
        title: "Desea eliminar el registro?",
        showDenyButton: true,
        confirmButtonText: `Si`,
        denyButtonText: `No`,

        confirmButtonColor: "#22C55E",
        showClass: { popup: "" },

        customClass: {
          confirmButton: "order-2",
          denyButton: "order-3",
        },
      }).then((result) => {
        if (result.isConfirmed) {
          Inertia.delete(route("tracks.destroy", id));

          Swal.fire({
            toast: true,
            title: '<p class="text-xl text-gray-300">Hecho</p>',
            showClass: { popup: "" },
            position: "top-end",
            showConfirmButton: false,
            icon: "success",
            background: "#334155",
            timer: 1500,
          });
        }
      });
    }

    return {
      remove,
      state,
     
      can,
      revealName,
      revealAuthor,
      addPlayer,
      addPoint,
    };
  },
};
</script>