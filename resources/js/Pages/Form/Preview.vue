<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import FormView from "./FormView.vue";
import { Head } from "@inertiajs/vue3";

export default {
  components: {
    AuthenticatedLayout,
   FormView,
   Head
  },
  props: {
    errors: Object,
    admissionForm: Object,
    courses: Array,
    branches: Array,
    withLayout: Boolean,
  },
};
</script>

<template>
  <Head title="Forms | Preview Form" v-if="withLayout"/>
  <AuthenticatedLayout v-if="withLayout">
    <template #header>
      <div class="flex items-center">
        Forms
        <span class="material-symbols-outlined text-gray-400">
          keyboard_arrow_right
        </span>
        Preview Form
      </div>
    </template>

   <FormView
      :admissionForm="admissionForm"
      :courses="courses"
      :branches="branches"
      :admin="true"
      :errors="errors"
      :form="form"/>

  </AuthenticatedLayout>

  <div v-if="!withLayout">
       <FormView
      :admissionForm="admissionForm"
      :admin="false"
      :courses="courses"
      :branches="branches"
      :errors="errors"/>
  </div>

</template>
