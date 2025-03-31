<script>
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
import CourseSelect from "@/Components/CourseSelect.vue";
import FileInput from "@/Components/FileInput.vue";

export default {
  components: {
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
    CourseSelect,
    FileInput,
  },
  props: {
    errors: Object,
    admissionForm: Object,
    courses: Array,
    branches: Array,
    admin: Boolean,
  },
  data() {
    const formFields = {
      form_uuid: this.admissionForm.uuid,
      response_data: {},
    };

    let formIsActive = this.admin ? true : this.admissionForm.active;

    let showForm = this.admin ? true : formIsActive;
    let showFormMessage = false;

    this.admissionForm.schema.forEach((schema) => {
      if (
        ["text", "number", "email", "file", "password", "radio"].includes(schema.type)
      ) {
        formFields.response_data[schema.field_name] = null;
      } else if (schema.type === "checkbox") {
        formFields.response_data[schema.field_name] = [];
      } else if (schema.type === "select") {
        formFields.response_data[schema.field_name] = "";
      } else if (schema.type === "select_course") {
        formFields.response_data.course_id = null;
      }
    });

    const form = useForm(formFields);

    return {
      form,
      showFormMessage,
      showForm,
      formIsActive,
    };
  },
  methods: {
    submit() {
      this.form.post(route("admin.form_responses.store"), {
        onSuccess: () => {
          toastr.success("Entry successfully submitted");
          this.resetForm();
          this.showMessage();
        },
        onError: () => {
          toastr.error("Something went wrong");
        },
      });
    },
    resetForm() {
      this.form.reset();
      this.form.clearErrors();
    },
    showMessage() {
      this.showFormMessage = true;
      this.showForm = false;
      this.formIsActive = true;
    },
  },
};
</script>

<template>
  <div class="py-12" v-if="showForm && formIsActive">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div v-if="admissionForm.image" class="shadow-sm w-full h-44 lg:h-72">
          <img
            :src="admissionForm.image"
            alt=""
            class="inset-0 w-full h-full object-cover"
          />
        </div>
        <div class="p-6">
          <div>
            <p class="text-2xl font-bold capitalize">{{ admissionForm.title }}</p>
            <p v-if="admissionForm.description" class="text-sm text-gray-600">
              {{ admissionForm.description }}
            </p>
          </div>

          <div class="mt-4">
            <form @submit.prevent="submit">
              <div class="space-y-5">
                <div v-for="(question, index) in admissionForm.schema" :key="index">
                  <div>
                    <InputLabel
                      v-if="question.type != 'select_course'"
                      :for="`field-${index}`"
                      :value="question.title"
                      :required="question.validators.required"
                    />
                    <TextInput
                      v-if="
                        ['text', 'number', 'email', 'password'].includes(question.type)
                      "
                      :id="`field-${index}`"
                      :type="question.type"
                      class="mt-1 w-full"
                      v-model="form.response_data[question.field_name]"
                      :placeholder="question.title"
                      :class="{
                        'border-red-600':
                          form.errors[`response_data.${question.field_name}`],
                      }"
                    />

                    <!-- File Input -->
                    <div v-else-if="question.type === 'file'">
                      <FileInput
                        class="mt-1"
                        @input="
                          form.response_data[question.field_name] = $event.target.files[0]
                        "
                        :maxSize="2 * 1024"
                        :accept="
                          question.options
                            ? question.options.split(',').map((type) => '.' + type.trim())
                            : []
                        "
                        :class="{
                          'file:bg-red-600 hover:file:bg-red-500 file:text-white':
                            form.errors[`response_data.${question.field_name}`],
                        }"
                      />
                    </div>

                    <!-- Select Input -->
                    <div v-else-if="question.type === 'select'">
                      <SelectInput
                        :id="question.field_name"
                        v-model="form.response_data[question.field_name]"
                        class="mt-1 w-full"
                        :class="{
                        'border-red-600':
                          form.errors[`response_data.${question.field_name}`],
                      }"
                      >
                        <option value="" disabled selected>-- Select an option --</option>
                        <option
                          v-for="option in question.options.split(',')"
                          :key="option.trim()"
                          :value="option.trim()"
                        >
                          {{ option.trim() }}
                        </option>
                      </SelectInput>
                    </div>

                    <!-- Select Location and Course  -->
                    <div v-else-if="question.type === 'select_course'">
                      <CourseSelect
                        :branches="this.branches"
                        :courses="this.courses"
                        :form="form"
                        :id="question.field_name"
                        :required="true"
                      ></CourseSelect>
                    </div>

                    <div
                      class="flex items-center space-x-4"
                      v-else-if="question.type === 'checkbox'"
                    >
                      <div
                        class="mt-1 flex items-center space-x-2"
                        v-for="(option, idx) in question.options.split(',')"
                        :key="idx"
                      >
                        <Checkbox
                          :id="`field-${index}-option-${idx}`"
                          v-model:checked="form.response_data[question.field_name]"
                          :value="option.trim()"
                        />
                        <InputLabel
                          :for="`field-${index}-option-${idx}`"
                          :value="option.trim()"
                        />
                      </div>
                    </div>

                    <div
                      class="flex items-center gap-4"
                      v-else-if="question.type == 'radio'"
                    >
                      <div
                        class="mt-1 flex items-center space-x-2"
                        v-for="(option, idx) in question.options.split(',')"
                        :key="idx"
                      >
                        <RadioInput
                          :id="`field-${index}-option-${idx}`"
                          v-model:checked="form.response_data[question.field_name]"
                          :value="option.trim()"
                        />
                        <InputLabel
                          :for="`field-${index}-option-${idx}`"
                          :value="option.trim()"
                        />
                      </div>
                    </div>
                    <InputError
                      :message="form.errors[`response_data.${question.field_name}`] || form.errors['response_data.course_id']"
                    />
                  </div>
                </div>

                <div>
                  <PrimaryButton
                    v-if="!admin"
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

  <div class="p-6" v-if="showFormMessage && formIsActive">
    <div>
      <p class="text-2xl font-bold capitalize">
        {{ admissionForm.message_after_registration }}
      </p>
    </div>
  </div>

  <div class="p-6" v-if="!formIsActive">
    <div>
      <p class="text-2xl font-bold capitalize">
        {{ admissionForm.message_when_inactive }}
      </p>
    </div>
  </div>
</template>
