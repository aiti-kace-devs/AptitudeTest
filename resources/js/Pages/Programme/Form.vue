<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import FileInput from "@/Components/FileInput.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";

export default {
  components: {
    AuthenticatedLayout,
    Head,
    PrimaryButton,
    TextInput,
    InputError,
    InputLabel,
    SelectInput,
    Checkbox,
    FileInput,
    TextAreaInput,
    MarkdownEditor,
  },
  props: {
    isCreateMethod: Boolean,
    errors: Object,
    programme: Object,
    categories: Object,
  },
  data() {
    const fileInput = ref(null);

    const imageConfig = ref({
      image: this.programme?.image ?? null,
      isDirty: this.isCreateMethod ?? false,
      preview: this.programme?.image ?? null,
      original: this.programme?.image ?? null,
    });

    const form = useForm({
      course_category_id: this.programme?.course_category_id ?? null,
      title: this.programme?.title ?? null,
      duration: this.programme?.duration ?? null,
      start_date: this.programme?.start_date ?? null,
      end_date: this.programme?.end_date ?? null,
      status: this.programme?.status ?? false,
      image: this.programme?.image ?? null,
      description: this.programme?.description ?? null,
      content: this.programme?.content ?? null,
    });

    return {
      form,
      imageConfig,
      fileInput,
    };
  },
  computed: {
    // Determine mode
    mode() {
      return this.isCreateMethod ? "Create" : "Edit";
    },
    submitMethod() {
      return this.isCreateMethod ? "post" : "put";
    },
    submitButtonText() {
      return this.isCreateMethod ? "save" : "update";
    },
    submitRoute() {
      return this.isCreateMethod
        ? route("admin.programme.store")
        : route("admin.programme.update", {
            programme: this.programme.id,
            isDirty: this.imageConfig.isDirty,
            _method: "put",
          });
    },
    successMessage() {
      return this.isCreateMethod
        ? "Programme successfully saved!"
        : "Programme successfully updated!";
    },
  },
  methods: {
    handleImageOnChange(event) {
      const file = event.target.files[0];
      if (!file) return;

      this.previewImage(file);
      this.imageConfig.image = file;
      this.imageConfig.isDirty = true;
      this.form.image = file;
    },
    previewImage(file) {
      // Use FileReader to read the file and generate a data URL
      const reader = new FileReader();

      reader.onload = (e) => {
        this.imageConfig.preview = e.target.result;
      };

      reader.readAsDataURL(file);
    },
    removeImage() {
      this.imageConfig.preview = null;
      this.imageConfig.image = null;
      this.imageConfig.isDirty = false;
      this.resetInput();
    },
    restoreImage() {
      this.imageConfig.preview = this.imageConfig.original;
      this.imageConfig.image = null;
      this.imageConfig.isDirty = false;
    },
    resetInput() {
      if (this.fileInput) {
        this.fileInput = ""; // Clear file input
      }
    },
    submit() {
      this.form.post(this.submitRoute, {
        onSuccess: () => {
          toastr.success(this.successMessage);
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
  <Head :title="'Programmes | ' + this.mode + ' Programme'" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center">
        Programmes
        <span class="material-symbols-outlined text-gray-400">
          keyboard_arrow_right
        </span>
        {{ this.mode }} Programme
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-none sm:rounded-lg">
          <div class="p-6">
            <form @submit.prevent="submit">
              <div class="flex flex-col lg:w-1/2 gap-5">
                <div>
                  <InputLabel for="category" value="category" :required="true" />
                  <SelectInput
                    id="category"
                    v-model="form.course_category_id"
                    class="mt-1 w-full"
                    :class="{
                      'border-red-600': form.errors.course_category_id
                    }"
                  >
                    <option value="" disabled selected>-- Select category --</option>
                    <option
                      v-for="category in this.categories"
                      :key="category"
                      :value="category.id"
                    >
                      {{ category.title }}
                    </option>
                  </SelectInput>

                  <InputError :message="form.errors.course_category_id" />
                </div>

                <div>
                  <InputLabel for="title" value="title" :required="true" />
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

                <div>
                  <InputLabel for="duration" value="duration" :required="true" />
                  <TextInput
                    id="duration"
                    type="text"
                    class="w-full"
                    v-model="form.duration"
                    :placeholder="'Duration'"
                    autocomplete="false"
                    :class="{ 'border-red-600': form.errors.duration }"
                  />
                  <InputError :message="form.errors.duration" />
                </div>

                <div>
                  <InputLabel for="start_date" value="Start date" :required="false" />
                  <TextInput
                    id="start_date"
                    type="date"
                    class="w-full"
                    v-model="form.start_date"
                    :placeholder="'Start Date'"
                    autocomplete="false"
                    :class="{ 'border-red-600': form.errors.start_date }"
                  />
                  <InputError :message="form.errors.start_date" />
                </div>

                <div>
                  <InputLabel for="end_date" value="end date" :required="false" />
                  <TextInput
                    id="end_date"
                    type="date"
                    class="w-full"
                    v-model="form.end_date"
                    :placeholder="'End Date'"
                    autocomplete="false"
                    :class="{ 'border-red-600': form.errors.end_date }"
                  />
                  <InputError :message="form.errors.end_date" />
                </div>

                <div>
                  <label
                    class="inline-flex items-center cursor-pointer space-x-3 font-medium text-sm text-gray-700"
                  >
                    Active
                    <Checkbox v-model:checked="form.status" class="sr-only peer" />
                    <div
                      class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-700 peer-disabled:cursor-not-allowed"
                    ></div>
                  </label>
                </div>

                <div>
                  <InputLabel for="image" value="image" :required="false" />

                  <div class="flex flex-col gap-6 mt-3">
                    <!-- preview section -->
                    <div
                      v-if="imageConfig.preview"
                      class="relative h-28 w-28 md:w-56 md:h-36"
                    >
                      <img
                        :src="imageConfig.preview"
                        alt="Preview"
                        class="w-full h-full object-cover rounded-lg shadow-md"
                      />

                      <button
                        @click="removeImage"
                        class="inline-flex absolute top-0 right-0 bg-red-600 text-white p-1 rounded-full shadow-lg hover:bg-red-700"
                      >
                        <span class="material-symbols-outlined"> close </span>
                      </button>
                    </div>

                    <!-- Upload Button -->
                    <div>
                      <FileInput
                        ref="fileInput"
                        id="image-upload"
                        class="hidden"
                        @change="handleImageOnChange"
                        accept="image/*"
                      />
                      <label
                        for="image-upload"
                        class="cursor-pointer text-sm py-2 px-4 bg-gray-100 text-gray-700 rounded-md shadow hover:bg-gray-200"
                      >
                        Choose Image
                      </label>
                    </div>

                    <!-- Restore Button -->
                    <div v-if="this.mode === 'Edit' && imageConfig.isDirty">
                      <button
                        @click="restoreImage"
                        class="py-2 px-4 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700"
                      >
                        Restore Original
                      </button>
                    </div>
                  </div>

                  <InputError class="mt-2" :message="form.errors.image" />
                </div>

                <div>
                  <InputLabel for="description" value="Description" :required="false" />
                  <TextAreaInput
                    v-model="form.description"
                    class="w-full"
                    :class="{ 'border-red-600': form.errors.description }"
                  />

                  <InputError :message="form.errors.description" />
                </div>

                <div>
                  <InputLabel for="content" value="Content" :required="false" />
                  <MarkdownEditor v-model="form.content" :error="form.errors.content" />

                  <InputError :message="form.errors.content" />
                </div>

                <div>
                  <PrimaryButton
                    type="submit"
                    :disabled="form.processing"
                    :class="{ 'opacity-25': form.processing }"
                    >{{ this.submitButtonText }}
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
