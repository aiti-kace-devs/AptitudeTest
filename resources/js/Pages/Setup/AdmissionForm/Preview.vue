<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import LinkButton from "@/Components/LinkButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
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
    const formFields = {
      form_uuid: this.admissionForm.uuid,
      response_data: {},
    };

    this.admissionForm.schema.forEach((schema) => {
      const fieldName = schema.field_name;

      if (
        ["text", "number", "email", "file", "password", "radio"].includes(schema.type)
      ) {
        formFields.response_data[fieldName] = null;
      } else if (schema.type === "checkbox") {
        formFields.response_data[fieldName] = [];
      } else if (schema.type === "select") {
        formFields.response_data[fieldName] = "";
      }
    });

    const form = useForm(formFields);

    return {
      form,
    };
  },
  methods: {
    submit() {
      this.form.post(route("form_responses.store"), {
        onSuccess: () => {
          toastr.success("Response successfully submitted");
          this.resetForm();
        },
        onError: (errors) => {
          toastr.error("Something went wrong");
        },
      });
    },
    resetForm() {
      // Clear form and selections after successful submission
      this.form.reset();
      this.form.clearErrors();
    },
  },
};
</script>

<template>
  <Head title="Forms | Preview" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center">
        Forms
        <span class="material-symbols-outlined text-gray-400">
          keyboard_arrow_right
        </span>
        Preview
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
                <div class="space-y-7">
                  <div v-for="(question, row) in admissionForm.schema" :key="row">
                    <div>
                      <p class="mb-2">
                        {{ question.title
                        }}<span class="text-red-600" v-if="question.is_required">*</span>
                      </p>
                      <TextInput
                        v-if="
                          ['text', 'number', 'email', 'file', 'password'].includes(
                            question.type
                          )
                        "
                        :id="`field-${row}`"
                        :type="question.type"
                        class="w-full"
                        v-model="form.response_data[question.field_name]"
                        :placeholder="question.title"
                        autocomplete="off"
                        :class="{
                          'block w-full mt-2 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100':
                            question.type == 'file',
                          'file:bg-red-600 hover:file:bg-red-500 file:text-white':
                            question.type == 'file',
                          'border-red-600':
                            form.errors[`response_data.${question.field_name}`],
                        }"
                      />

                      <div
                        class="flex items-center space-x-4"
                        v-else-if="question.type == 'checkbox'"
                      >
                        <div
                          class="flex items-center space-x-2"
                          v-for="(option, idx) in question.options.split(',')"
                          :key="idx"
                        >
                          <Checkbox
                            :id="`field-${row}-option-${idx}`"
                            v-model:checked="form.response_data[question.field_name]"
                            :value="option.trim()"
                          />
                          <InputLabel
                            :for="`field-${row}-option-${idx}`"
                            :value="option.trim()"
                          />
                        </div>
                      </div>

                      <div
                        class="flex items-center gap-4"
                        v-else-if="question.type == 'radio'"
                      >
                        <div
                          class="flex items-center space-x-2"
                          v-for="(option, idx) in question.options.split(',')"
                          :key="idx"
                        >
                          <RadioInput
                            :id="`field-${row}-option-${idx}`"
                            v-model:checked="form.response_data[question.field_name]"
                            :value="option.trim()"
                          />
                          <InputLabel
                            :for="`field-${row}-option-${idx}`"
                            :value="option.trim()"
                          />
                        </div>
                      </div>

                      <div v-else-if="question.type == 'select'">
                        <SelectInput
                          :id="question.title"
                          v-model="form.response_data[question.field_name]"
                          class="w-full"
                        >
                          <option value="" disabled selected>
                            -- Select an option --
                          </option>
                          <option
                            v-for="option in question.options.split(',')"
                            :value="option.trim()"
                          >
                            {{ option.trim() }}
                          </option>
                        </SelectInput>
                      </div>
                      <InputError
                        :message="form.errors[`response_data.${question.field_name}`]"
                      />
                    </div>
                  </div>

                  <div>
                    <PrimaryButton
                      type="submit"
                      :disabled="form.processing"
                      :class="{ 'opacity-25': form.processing }"
                      >submit
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
