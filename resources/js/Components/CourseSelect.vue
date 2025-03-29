
/**
 * CourseSelect Component.
 *
 * This component provides a user interface for selecting a location and then a course
 * based on the selected location. It uses two select elements, one for locations and
 * another for courses.  The course selection is dynamically updated based on the
 * location selected.
 *
 * @prop {Array} locations - An array of location objects, each with an `id` and `name` property.
 * @data {String} selectedLocation - The ID of the currently selected location.
 * @data {String} selectedCourse - The ID of the currently selected course.
 * @data {Array} filteredCourses - An array of courses filtered based on the selected location.
 * @method updateCourses - Updates the `filteredCourses` array when the `selectedLocation` changes.
 */


<template>
    <div>
        <label for="location">Select Location:</label>
        <select id="location" v-model="selectedLocation" @change="updateCourses">
            <option value="" disabled>Select a location</option>
            <option v-for="location in locations" :key="location.id" :value="location.id">
                {{ location.name }}
            </option>
        </select>

        <label for="course">Select Course:</label>
        <select id="course" v-model="selectedCourse" :disabled="!selectedLocation">
            <option value="" disabled>Select a course</option>
            <option v-for="course in filteredCourses" :key="course.id" :value="course.id">
                {{ course.name }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    data() {
        return {
            selectedLocation: "",
            selectedCourse: "",
            locations: [
                { id: 1, name: "Location A" },
                { id: 2, name: "Location B" },
            ],
            courses: [
                { id: 1, name: "Course 1", locationId: 1 },
                { id: 2, name: "Course 2", locationId: 1 },
                { id: 3, name: "Course 3", locationId: 2 },
            ],
        };
    },
    computed: {
        filteredCourses() {
            return this.courses.filter(
                (course) => course.locationId === this.selectedLocation
            );
        },
    },
    methods: {
        updateCourses() {
            this.selectedCourse = ""; // Reset course selection when location changes
        },
    },
};
</script>
