<template>
  <app-layout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl leading-tight">
          Tracks
        </h2>
      </div>
    </template>

    <div class="justify-center flex flex-wrap px-5 m-2">
      <inertia-link
        
        :href="route('tracks.create')"
        class="
          px-4
          bg-blueGray-700
          py-2
          rounded-sm
          text-amber-300
          font-semibold
          hover:bg-blueGray-800
          my-4
        "
      >
        New
      </inertia-link>

      <table class="min-w-full table-auto">
        <thead class="justify-between">
          <tr class="bg-blueGray-800">
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
            v-for="track in tracks"
            :key="track.id"
            class="bg-white border-1 border-gray-200"
          >
            <td class="px-4 py-1">
              {{ track.id }}
            </td>
            <td>
              <audio controls>
			  <source :src="'/storage/tracks/' + track.file" type="audio/ogg">	
			</audio>
            
            </td>
            <td>
              <span class="text-center ml-2 font-semibold">{{
                track.name
              }}</span>
            </td>
            <td>
              <span class="text-center ml-2 font-semibold">{{
                track.author
              }}</span>
            </td>
          
            <td class="px-4 py-1">
              <span>{{ track.level }}</span>
            </td>
            <td class="px-4 py-1">
              <inertia-link
                :href="route('tracks.show', track.id)"
                v-if="can('locomotoras ver')"
              >
                <img
                  
                  class="
                    w-6
                    rounded
                    border-gray-400 border-1
                    bg-gray-100
                    p-1
                    mx-1
                    inline-block
                  "
                />
              </inertia-link>

              <inertia-link
                :href="route('tracks.edit', track.id)"
                
              >
                <img
                 
                  class="
                    w-6
                    rounded
                    border-gray-400 border-1
                    bg-gray-100
                    p-1
                    mx-1
                    inline-block
                  "
                />
              </inertia-link>

              <img
                @click="remove(track.id)"
               
               
                class="
                  w-6
                  rounded
                  border-gray-400 border-1
                  bg-amber-500
                  p-1
                  mx-1
                  inline-block
                "
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
    const state = reactive({});

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
            title: '<p class="text-xl text-blueGray-300">Hecho</p>',
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
    };
  },
};
</script>