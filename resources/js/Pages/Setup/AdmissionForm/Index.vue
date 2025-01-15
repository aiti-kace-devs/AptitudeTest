<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import LinkButton from "@/Components/LinkButton.vue";
import MenuDropdown from "@/Components/MenuDropdown.vue";
import DangerButton from "@/Components/DangerButton.vue";

export default {
  components: {
    AuthenticatedLayout,
    Head,
    LinkButton,
    MenuDropdown,
    DangerButton,
  },
  mounted() {
    this.fetch();

    $(document).on("click", ".dropdown-toggle", (evt) => {
      const data = $(evt.target).attr("dropdown-log");

      if (this.$refs.menuDropdown && evt.target.classList.contains("dropdown-span")) {
        this.$refs.menuDropdown.toggleDropdown(data);
      }
    });

    $(document).on("click", "body", (evt) => {
      if (this.$refs.menuDropdown && !evt.target.classList.contains("dropdown-span")) {
        // Close all dropdowns when clicking outside
        this.$refs.menuDropdown.closeAllDropdowns();
      }
    });

    $(document).on("click", ".edit", (evt) => {
      const data = $(evt.currentTarget).data("id");

      router.get(route("admin.setup.admission_form.edit", data));
    });

    $(document).on("click", ".preview", (evt) => {
      const data = $(evt.currentTarget).data("id");

      router.get(route("admin.setup.admission_form.preview", data));
    });
  },
  methods: {
    fetch() {
      $("#data_table").DataTable({
        destroy: true,
        stateSave: true,
        processing: false,
        serverSide: true,
        ajax: {
          url: route("admin.setup.admission_form.fetch"),
        },
        columns: [
          { data: "title", name: "title" },
          { data: "date", name: "created at" },
          {
            data: "action",
            name: "action",
            orderable: false,
            searchable: false,
          },
        ],
        lengthMenu: [
          [10, 25, 50, 100, -1],
          ["10", "25", "50", "100", "All"],
        ],
        columnDefs: [
          { width: "5%", targets: -1 },
        ],
        createdRow: function (row, data, dataIndex) {
          // Find the dropdown element in the row and set its width
          var dropdownMenu = $(row).find(".dropdown-menu");
          if (dropdownMenu.length > 0) {
            dropdownMenu.width(160); // Set your desired width here
          }
        },
      });
    },
  },
};
</script>
<template>
  <Head title="Setup | Admission Form" />

  <AuthenticatedLayout>
    <MenuDropdown ref="menuDropdown" />
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

            <div class="flex flex-col mt-4">
              <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 inline-w-full sm:px-6 lg:px-8">
                  <div class="overflow-x-auto">
                    <table id="data_table" class="w-full text-sm table-striped">
                      <thead class="capitalize border-b bg-gray-100 font-medium">
                        <tr>
                          <th
                            scope="col"
                            class="text-gray-900 px-6 py-4 text-left"
                          >
                            Title
                          </th>
                          <th
                            scope="col"
                            class="text-gray-900 px-6 py-4 text-left"
                          >
                            created at
                          </th>
                          <th
                            scope="col"
                            class="text-gray-900 px-6 py-4 text-left"
                          >
                            action
                          </th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
