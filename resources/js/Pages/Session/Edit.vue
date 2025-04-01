<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

export default {
  components: {
    AuthenticatedLayout,
    Head,
    PrimaryButton,
    TextInput,
    InputError,
    InputLabel,
  },
  props: {
    errors: Object,
    session: Object,
  },
  data() {
    const form = useForm({
      title: this.session.title,
      starts_at: this.session.starts_at,
      ends_at: this.session.ends_at,
    });

    return {
      form,
      editContent: true,
    };
  },
  methods: {
    submit() {
      this.form.put(route("admin.session.update", { session: this.session.uuid }), {
        onSuccess: () => {
          toastr.success("Session successfully updated");
          this.resetForm();
        },
        onError: (errors) => {
          toastr.error("Something went wrong");
        },
      });
    },
    resetForm() {
      this.form.reset();
      this.form.clearErrors();
    },
  },
};
</script>

<template>
  <Head title="Sessions | Edit Session" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center">
        Sessions
        <span class="material-symbols-outlined text-gray-400">
          keyboard_arrow_right
        </span>
        Edit Session
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-none sm:rounded-lg">
          <div class="p-6">
            <form @submit.prevent="submit">
              <div class="grid grid-cols-12 gap-5">
                <div class="col-span-full">
                  <InputLabel for="title" value="Title" :required="true" />
                  <TextInput
                    id="title"
                    type="text"
                    class="w-full"
                    v-model="form.title"
                    :placeholder="'Title'"
                    autocomplete="title"
                    :class="{ 'border-red-600': form.errors.title }"
                  />
                  <InputError :message="form.errors.title" />
                </div>

                <div class="col-span-6">
                  <InputLabel for="starts_at" value="Starts at" :required="true" />
                  <TextInput
                    id="starts_at"
                    type="time"
                    class="w-full"
                    v-model="form.starts_at"
                    :placeholder="'Starts At'"
                    :class="{ 'border-red-600': form.errors.starts_at }"
                  />
                  <InputError :message="form.errors.starts_at" />
                </div>

                <div class="col-span-6">
                  <InputLabel for="ends_at" value="ends at" :required="true" />
                  <TextInput
                    id="ends_at"
                    type="time"
                    class="w-full"
                    v-model="form.ends_at"
                    :placeholder="'Ends At'"
                    :class="{ 'border-red-600': form.errors.ends_at }"
                  />
                  <InputError :message="form.errors.ends_at" />
                </div>

                <div class="col-span-full">
                  <PrimaryButton
                    type="submit"
                    :disabled="form.processing"
                    :class="{ 'opacity-25': form.processing }"
                    >update
                  </PrimaryButton>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
