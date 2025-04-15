<template>
    <div>
        <h1>Available Lists (Views)</h1>
        <ul>
            <li v-for="view in views" :key="view.Name">
                <Link :href="route('lists.show', { viewName: view.Name })">{{ view.Name }}</Link>
                <form @submit.prevent="destroyView(view.Name)" style="display: inline-block;">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
            </li>
        </ul>
        <Link :href="route('lists.create')" class="btn btn-primary">Create New List</Link>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

export default {
    components: {
        Link,
    },
    props: {
        views: Array,
    },
    methods: {
         destroyView(viewName) {
            if (confirm('Are you sure you want to delete this view?')) {
                useForm({ viewName }).delete(route('lists.destroy', { viewName }));
            }
        },
    },
};
</script>
