<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import LinkButton from "@/Components/LinkButton.vue";
</script>

<template>
  <Head title="Setup | Admission Form" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center">
        Setup
        <span class="material-symbols-outlined text-gray-400">
          keyboard_arrow_right
        </span>
        Admission Form
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="flex justify-end">
              <LinkButton :href="route('admin.setup.admission_form.create')">
                <span class="material-symbols-outlined mr-2"> add </span>
                create form
              </LinkButton>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
