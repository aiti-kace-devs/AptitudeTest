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
import RadioInput from "@/Components/RadioInput.vue";

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
    RadioInput,
  },
  props: {
    errors: Object,
    admissionForm: Object,
  },
  data() {
    const selections = ref([]);
    const form = useForm({
      form_uuid: this.admissionForm.uuid,
    });

    return {
      form,
      selections,
    };
  },
  mounted() {
    // Initialize dynamic fields based on the schema provided
    this.admissionForm.schema.forEach((schema, index) => {
      const fieldName = schema.title.replace(/\s+/g, "_").toLowerCase();

      if (!this.form.hasOwnProperty(fieldName)) {
        if (
          ["text", "number", "email", "file", "password", "radio"].includes(schema.type)
        ) {
          this.form[fieldName] = null;
        } else if (["checkbox"].includes(schema.type)) {
          this.form[fieldName] = [];
        } else if (["select"].includes(schema.type)) {
          this.form[fieldName] = "";
        }
      }

      // Push the field details into selections
      const newField = {
        id: `field_${index + 1}`,
        label: schema.title,
        title: schema.title,
        type: schema.type,
        fieldName: fieldName,
        placeholder: schema.title,
        options: schema.options || [],
        is_required: schema.is_required,
      };

      this.selections.push(newField);
    });
  },
  methods: {
    submit() {
      this.form.post(route("admission.store"), {
        onSuccess: () => {
          toastr.success("Entry successfully submitted");
          this.resetForm();
        },
        onError: () => {
          toastr.error("Something went wrong");
        },
      });
    },
    resetForm() {
      this.form.reset();
      this.form.clearErrors();
      this.selections = [];
    },
  },
};
</script>

<template>
  <Head title="Forms | Preview Form" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center">
        Forms
        <span class="material-symbols-outlined text-gray-400">
          keyboard_arrow_right
        </span>
        Preview Form
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div>
              <p class="text-2xl font-bold capitalize">{{ admissionForm.title }}</p>
            </div>

            <div class="mt-4">
              <form @submit.prevent="submit">
                <div class="space-y-5">
                  <div v-for="(question, index) in selections" :key="index">
                    <div>
                      <InputLabel
                        :for="`field-${index}`"
                        :value="question.title"
                        :required="question.is_required"
                      />

                      <!-- Text Input -->
                      <TextInput
                        v-if="
                          ['text', 'number', 'email', 'password'].includes(question.type)
                        "
                        :id="`field-${index}`"
                        :type="question.type"
                        class="mt-1 w-full"
                        v-model="form[question.fieldName]"
                        :placeholder="question.placeholder"

                        :class="{
                          'block w-full mt-2 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100':
                            question.type == 'file',
                          'file:bg-red-600 hover:file:bg-red-500 file:text-white':
                            question.type == 'file',
                          'border-red-600': form.errors[question.fieldName],
                        }"
                      />

                      <!-- Select Input -->
                      <div v-else-if="question.type === 'select'">
                        <SelectInput
                          :id="question.fieldName"
                          v-model="form[question.fieldName]"
                          class="mt-1 w-full"
                        >
                          <option value="" disabled selected>
                            -- Select an option --
                          </option>
                          <option
                            v-for="option in question.options.split(',')"
                            :key="option.trim()"
                            :value="option.trim()"
                          >
                            {{ option.trim() }}
                          </option>
                        </SelectInput>
                      </div>

                      <!-- Checkbox Input -->
                      <div class="flex items-center space-x-4" v-else-if="question.type === 'checkbox'">
                        <div class="mt-1 flex items-center space-x-2"
                          v-for="(option, idx) in question.options.split(',')"
                          :key="idx"
                        >
                          <Checkbox
                            :id="`field-${index}-option-${idx}`"
                            v-model:checked="form[question.fieldName]"
                            :value="option.trim()"
                          />
                          <InputLabel
                            :for="`field-${index}-option-${idx}`"
                            :value="option.trim()"
                          />
                        </div>
                      </div>

                      <!-- Show any error message -->
                      <InputError :message="form.errors[question.fieldName]" />
                    </div>
                  </div>

                  <div>
                    <PrimaryButton
                      type="submit"
                      :disabled="form.processing"
                      :class="{ 'opacity-25': form.processing }"
                    >
                      Submit
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
