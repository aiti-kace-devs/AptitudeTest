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
  },
  props: {
    errors: Object,
    admissionForm: Object,
  },
  data() {
    const selections = ref([]);

    const form = useForm({
      title: this.admissionForm.title,
      schema: this.admissionForm.schema,
    });

    return {
      form,
      selections,
    };
  },
  mounted() {
    this.admissionForm.schema.forEach((schema) => {
      const newField = {
        id: `field_${this.selections.length + 1}`, // Unique ID
        label: `Field ${this.selections.length + 1}`, // Default label
        title: schema.title,
        type: schema.type, // Default type
        placeholder: "Question", // Placeholder
        options: schema.options, // Options for dropdown/select fields
        is_required: schema.is_required, // Default required status
      };

      this.selections.push(newField);
    });
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
        id: `field_${Date.now()}`, // Unique ID
        label: `Field ${this.selections.length + 1}`, // Default label
        title: null,
        type: "text", // Default type
        placeholder: "Question", // Placeholder
        options: null, // Options for dropdown/select fields
        is_required: false, // Default required status
      };

      this.selections.push(newField);
    },
    removeSelection(index) {
      // Remove the field at the specified index
      this.selections.splice(index, 1);
    },
    changeSelectionType(index) {
      // Reset specific field properties when type changes
      const selection = this.selections[index];
      if (selection.type !== "dropdown") {
        selection.options = null; // Remove options for non-dropdown fields
      }
    },
    submit() {
      // Submit the form with schema as JSON
      this.form.put(
        route("admin.setup.admission_form.update", { form: this.admissionForm.uuid }),
        {
          onSuccess: () => {
            toastr.success("Form successfully updated");
            this.resetForm();
          },
          onError: (errors) => {
            toastr.error("Something went wrong");
          },
        }
      );
    },
    resetForm() {
      // Clear form and selections after successful submission
      this.form.reset();
      this.form.clearErrors();
      this.selections = [];
    },
  },
};
</script>

<template>
  <Head title="Setup | Admission Form | Edit Form" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center">
        Setup
        <span class="material-symbols-outlined text-gray-400">
          keyboard_arrow_right
        </span>
        Admission Form
        <span class="material-symbols-outlined text-gray-400">
          keyboard_arrow_right
        </span>
        Edit Form
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div>
              <form @submit.prevent="submit">
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

                  <!-- Questions -->
                  <div
                    class="border border-gray-400 p-6 rounded-lg shadow-sm space-y-4"
                    v-for="(selection, row) in selections"
                    :key="row"
                  >
                    <div class="grid grid-cols-3 gap-x-4">
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
                        </SelectInput>
                      </div>
                    </div>

                    <div>
                      <div>
                        <TextInput
                          v-if="['select', 'radio', 'checkbox'].includes(selection.type)"
                          :id="selection.id"
                          type="text"
                          class="w-full"
                          v-model="selection.options"
                          :placeholder="'Options (comma-separated)'"
                          :class="{
                            'border-red-600': form.errors[`schema.${row}.options`],
                          }"
                        />
                        <InputError :message="form.errors[`schema.${row}.options`]" />
                      </div>
                    </div>

                    <div class="flex justify-between items-center">
                      <div>
                        <label class="inline-flex items-center cursor-pointer space-x-3">
                          Required
                          <Checkbox
                            v-model:checked="selection.is_required"
                            class="sr-only peer"
                          />
                          <div
                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-700 peer-disabled:cursor-not-allowed"
                          ></div>
                        </label>
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
                      >update
                    </PrimaryButton>
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
