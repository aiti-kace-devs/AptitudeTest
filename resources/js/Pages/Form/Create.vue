<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import LinkButton from "@/Components/LinkButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { ref } from "vue";
import SelectInput from "@/Components/SelectInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import DangerButton from "@/Components/DangerButton.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import PreviewImage from "@/Components/PreviewImage.vue";
import FileInput from "@/Components/FileInput.vue";

export default {
  components: {
    AuthenticatedLayout,
    Head,
    LinkButton,
    PrimaryButton,
    TextInput,
    InputError,
    InputLabel,
    SelectInput,
    Checkbox,
    DangerButton,
    TextAreaInput,
    PreviewImage,
    FileInput
  },
  props: {
    errors: Object,
  },
  data() {
    const selections = ref([]);

    const imageConfig = {
      image: null,
      isDirty: true,
      preview: null,
    };

    const form = useForm({
      title: null,
      description: null,
      image: imageConfig.image,
      code: null,
      message_when_inactive: null,
      message_after_registration: null,
      active: 1,
      schema: [],
    });

    return {
      form,
      imageConfig,
      selections,
      editContent: false,
    };
  },
  watch: {
    selections: {
      handler(newSelections) {
        // Sync selections with form.schema
        this.form.schema = newSelections;
      },
      deep: true,
    },
  },
  methods: {
    addSelection() {
      // Add a new field with default values
      const newField = {
        id: `field_${this.selections.length + 1}`, // Unique ID
        label: `Field ${this.selections.length + 1}`, // Default label
        title: null,
        description: null,
        type: "text",
        placeholder: "Question", // Placeholder
        options: null,
        validators: {
          required: false,
          unique: false,
        },
      };

      this.selections.push(newField);

      this.form.clearErrors("schema");
    },
    removeSelection(index) {
      this.selections.splice(index, 1);
    },
    changeSelectionType(index) {
      this.form.clearErrors(`schema.${index}.options`);

      const selection = this.selections[index];

      if (!["select", "radio", "checkbox", "file"].includes(selection.type)) {
        selection.options = null;
      }
    },
    moveField(index, direction) {
      const swapIndex = direction === "up" ? index - 1 : index + 1;

      if (swapIndex < 0 || swapIndex >= this.selections.length) return;

      const temp = this.selections[index];
      this.selections[index] = this.selections[swapIndex];
      this.selections[swapIndex] = temp;
    },
    submit() {
      this.form.post(route("admin.form.store"), {
        data: {
          isDirty: this.imageConfig.isDirty,
        },
        onSuccess: () => {
          toastr.success("Form successfully saved");
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
      this.selections = [];
    },

    handleImageOnChange(event) {
      const file = event.target.files[0];

      if (file) {
        this.previewImage(file);
        this.imageConfig.image = file;
        this.imageConfig.isDirty = true;
      }
    },
    previewImage(file) {
      // Use FileReader to read the file and generate a data URL
      const reader = new FileReader();

      reader.onload = (e) => {
        this.imageConfig.preview = e.target.result;
      };

      reader.readAsDataURL(file);
    },
    toggleImageChange() {
      this.imageConfig.isDirty = !this.imageConfig.isDirty;

      if (this.imageConfig.isDirty) {
        this.form.image = null;
      } else {
        this.restoreImage();
      }
    },
    cancelImage() {
      this.imageConfig.preview = null;
      this.imageConfig.isDirty = false;
      this.form.reset("image");

      const fileInput = document.getElementById("image");
      if (fileInput) {
        fileInput.value = ""; // This clears the file input
      }
    },
    restoreImage() {
      this.imageConfig.preview = this.original_img;
    },
    clearImage() {
      this.imageConfig.preview = null;
      this.form.reset("image");

      const fileInput = document.getElementById("image");
      if (fileInput) {
        fileInput.value = ""; // This clears the file input
      }
    },
  },
};
</script>

<template>
  <Head title="Forms | Create Form" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center">
        Forms
        <span class="material-symbols-outlined text-gray-400">
          keyboard_arrow_right
        </span>
        Create Form
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-transparent overflow-hidden shadow-none sm:rounded-lg">
          <div class="p-6">
            <div>
              <form @submit.prevent="submit" class="space-y-5">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div class="p-6">
                    <div class="grid gap-5">
                      <div>
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

                      <div>
                        <InputLabel
                          for="description"
                          value="Description"
                          :required="false"
                        />
                        <TextAreaInput
                          v-model="form.description"
                          class="w-full"
                          :class="{ 'border-red-600': form.errors.description }"
                        />

                        <InputError :message="form.errors.description" />
                      </div>

                      <div>
                        <InputLabel for="image" value="image" :required="false" />
                        <div
                          v-if="imageConfig.preview != null"
                          class="mt-1 mb-4 flex items-start gap-3 shrink-0"
                        >
                          <div class="flex items-center gap-5">
                            <PreviewImage
                              :src="imageConfig.preview"
                              :alt="imageConfig.preview ? 'image' : 'No image'"
                            />

                            <div>
                              <DangerButton
                                type="button"
                                @click="clearImage"
                                class="h-9 w-9 flex items-center justify-center"
                              >
                                <span class="material-symbols-outlined"> delete </span>
                              </DangerButton>
                            </div>
                          </div>
                          <div v-if="editContent">
                            <div class="grid gap-2">
                              <button
                                type="button"
                                @click="toggleImageChange"
                                class="py-2 px-4 text-sm capitalize text-slate-600 font-semibold bg-gray-100 rounded-full hover:text-gray-700 hover:bg-gray-200"
                              >
                                <span v-if="!imageConfig.isDirty">Change image</span>
                                <span v-else>Cancel</span>
                              </button>
                            </div>
                          </div>
                        </div>

                        <div v-if="imageConfig.isDirty" class="mt-1">
                          <span class="sr-only">Choose image</span>

                          <FileInput
                            id="image"
                            accept="image/*"
                            :class="{
                              'file:bg-red-600 hover:file:bg-red-500 file:text-white':
                                form.errors.image,
                            }"
                            @change="handleImageOnChange"
                          />
                        </div>

                        <InputError :message="form.errors.image" />
                      </div>
                    </div>
                  </div>
                  </div>

                   <div>
                    <InputLabel for="code" value="Unique Code" :required="true" />
                    <TextInput
                      id="code"
                      type="text"
                      class="w-full"
                      v-model="form.code"
                      :placeholder="'Code'"
                      autocomplete="code"
                      :class="{ 'border-red-600': form.errors.title }"
                    />
                    <InputError :message="form.errors.code" />
                  </div>
                  <!-- message after registration -->
                    <div>
                    <InputLabel for="message_after_registration" value="Message After Registration" :required="true" />
                    <TextInput
                      id="message_after_registration"
                      type="text"
                      class="w-full h-15"
                      v-model="form.message_after_registration"
                      :placeholder="'Message After Registration'"
                      :class="{ 'border-red-600': form.errors.message_after_registration }"
                    />
                    <InputError :message="form.errors.code" />
                  </div>

                  <!-- message when inactive -->
                    <div>
                    <InputLabel for="message_when_inactive" value="Message When Inactive" :required="true" />
                    <TextInput
                      id="code"
                      type="text"
                      class="w-full"
                      v-model="form.message_when_inactive"
                      :placeholder="'Message When Inactive'"
                      :class="{ 'border-red-600': form.errors.message_when_inactive }"
                    />
                    <InputError :message="form.errors.code" />
                  </div>

                  <!-- status -->
                    <div>
                    <InputLabel for="active" value="Form Accepting Responses" :required="true" />

                        <SelectInput
                          :id="'active'"
                          v-model="form.active"
                          class="w-full"
                        >
                          <option value="1" selected>Yes</option>
                          <option value="0">No</option>
                        </SelectInput>
                    <InputError :message="form.errors.active" />

                    </div>

                <!-- Questions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div class="p-6">
                    <div class="grid gap-5">
                      <div
                        class="border border-gray-400 p-6 rounded-lg shadow-sm space-y-4"
                        v-for="(selection, row) in selections"
                        :key="row"
                      >
                        <div class="grid grid-cols-3 gap-4">
                          <div class="col-span-2">
                            <TextInput
                              :id="selection.id"
                              type="text"
                              class="w-full"
                              v-model="selection.title"
                              :placeholder="selection.placeholder"
                              :class="{
                                'border-red-600': form.errors[`schema.${row}.title`],
                              }"
                            />
                            <InputError :message="form.errors[`schema.${row}.title`]" />
                          </div>

                          <div>
                            <SelectInput
                              @change="changeSelectionType(row)"
                              :id="'input_type_' + row"
                              v-model="selection.type"
                              class="w-full"
                            >
                              <option value="text" selected>Text</option>
                              <option value="textarea">Textarea</option>
                              <option value="select">Select</option>
                              <option value="checkbox">Checkbox</option>
                              <option value="radio">Radio</option>
                              <option value="number">Number</option>
                              <option value="file">File</option>
                              <option value="select_course">Course Selection</option>
                        </SelectInput>
                          </div>

                          <div class="col-span-full">
                            <InputLabel
                              for="description"
                              value="Description"
                              :required="false"
                            />
                            <TextAreaInput
                              v-model="selection.description"
                              class="w-full"
                              :class="{
                                'border-red-600':
                                  form.errors[`schema.${row}.description`],
                              }"
                            />

                            <InputError
                              :message="form.errors[`schema.${row}.description`]"
                            />
                          </div>
                        </div>

                        <div>
                          <div
                            v-if="
                              ['select', 'radio', 'checkbox', 'file'].includes(
                                selection.type
                              )
                            "
                          >
                            <TextInput
                              :id="selection.id"
                              type="text"
                              class="w-full"
                              v-model="selection.options"
                              :placeholder="
                                (selection.type == 'file' ? 'File type' : 'Options') +
                                ' (comma-separated)'
                              "
                              :class="{
                                'border-red-600': form.errors[`schema.${row}.options`],
                              }"
                            />

                            <div class="mt-1" v-if="selection.type == 'file'">
                              <p class="text-sm text-gray-600">
                                Supported formats: jpg, jpeg, png, gif, docx, txt, pdf,
                                csv, xlsx and zip.
                              </p>
                            </div>

                            <InputError :message="form.errors[`schema.${row}.options`]" />
                          </div>
                        </div>

                        <div class="flex justify-between items-center">
                          <div class="flex items-center gap-4">
                            <div>
                              <label
                                class="inline-flex items-center cursor-pointer space-x-3 text-sm"
                              >
                                Required
                                <Checkbox
                                  v-model:checked="selection.validators.required"
                                  class="sr-only peer"
                                />
                                <div
                                  class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-700 peer-disabled:cursor-not-allowed"
                                ></div>
                              </label>
                            </div>

                            <div>
                              <label
                                class="inline-flex items-center cursor-pointer space-x-3 text-sm"
                              >
                                Unique
                                <Checkbox
                                  v-model:checked="selection.validators.unique"
                                  class="sr-only peer"
                                />
                                <div
                                  class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-700 peer-disabled:cursor-not-allowed"
                                ></div>
                              </label>
                            </div>
                          </div>

                          <div
                            class="flex flex-col items-center"
                            :class="{
                              'gap-y-2.5': row !== 0 && row !== selections.length - 1,
                            }"
                          >
                            <div>
                              <button
                                @click="moveField(row, 'up')"
                                v-if="row !== 0"
                                type="button"
                                class="w-11 h-8 flex items-center justify-center border border-transparent bg-gray-100 rounded-sm shadow-sm p-1 disabled:opacity-25 disabled:cursor-not-allowed"
                              >
                                <span
                                  class="material-symbols-outlined text-2xl font-bold text-gray-800"
                                >
                                  keyboard_arrow_up
                                </span>
                              </button>
                            </div>

                            <div>
                              <button
                                @click="moveField(row, 'down')"
                                v-if="row !== selections.length - 1"
                                type="button"
                                class="w-11 h-8 flex items-center justify-center border border-transparent bg-gray-100 rounded-sm shadow-sm p-1 disabled:opacity-25 disabled:cursor-not-allowed"
                              >
                                <span
                                  class="material-symbols-outlined text-2xl font-bold text-gray-800"
                                >
                                  keyboard_arrow_down
                                </span>
                              </button>
                            </div>
                          </div>

                          <div>
                            <DangerButton
                              type="button"
                              @click="removeSelection(row)"
                              class="h-8 w-9 flex items-center justify-center"
                            >
                              <span class="material-symbols-outlined"> delete </span>
                            </DangerButton>
                          </div>
                        </div>
                      </div>

                      <div
                        v-if="form.errors.schema"
                        class="bg-red-100 rounded-md p-4 border border-red-200 text-red-700 text-md"
                      >
                        <span class="font-bold">Oops!</span> {{ form.errors.schema }}
                      </div>

                      <div>
                        <PrimaryButton @click="addSelection" type="button">
                          <span class="material-symbols-outlined mr-2"> add </span>
                          add question
                        </PrimaryButton>
                      </div>

                      <div>
                        <PrimaryButton
                          type="submit"
                          :disabled="form.processing"
                          :class="{ 'opacity-25': form.processing }"
                          >save
                        </PrimaryButton>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
