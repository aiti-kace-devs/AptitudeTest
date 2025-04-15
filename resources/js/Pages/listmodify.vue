<template>
    <div>
        <h1>Create List</h1>

        <form @submit.prevent="form.post(route('lists.store'))">
            <div>
                <label for="view_name">View Name:</label>
                <input type="text" v-model="form.view_name" id="view_name" />
                <div v-if="form.errors.view_name" class="text-red-500">{{ form.errors.view_name }}</div>
            </div>

            <div>
                <label for="table_name">Table Name:</label>
                <select v-model="form.table_name" id="table_name">
                    <option v-for="table in tables" :key="table" :value="table">
                        {{ table }}
                    </option>
                </select>
                <div v-if="form.errors.table_name" class="text-red-500">{{ form.errors.table_name }}</div>
            </div>

            <div>
                <label>Columns:</label>
                <select v-model="form.columns" multiple>
                    <option v-for="column in availableColumns" :key="column" :value="column">
                        {{ column }}
                    </option>
                </select>
                <div v-if="form.errors.columns" class="text-red-500">{{ form.errors.columns }}</div>
            </div>

            <div id="where-conditions-container">
                <label>Where Conditions:</label>
                <div v-for="(condition, index) in form.where_conditions" :key="'where-' + index" class="where-condition">
                    <input
                        type="text"
                        v-model="condition.column"
                        placeholder="Column"
                    />
                    <select v-model="condition.operator">
                        <option value="==">=</option>
                        <option value="!=">!=</option>
                        <option value="<">&lt;</option>
                        <option value=">">&gt;</option>
                        <option value="<=">&lt;=</option>
                        <option value=">=">&gt;=</option>
                        <option value="LIKE">LIKE</option>
                        <option value="NOT LIKE">NOT LIKE</option>
                        <option value="IN">IN</option>
                        <option value="NOT IN">NOT IN</option>
                    </select>
                    <input type="text" v-model="condition.value" placeholder="Value" />
                    <button type="button" @click="removeWhereCondition(index)">Remove</button>
                </div>
                <button type="button" @click="addWhereCondition">Add Condition</button>
            </div>

            <div>
                <label>Joins:</label>
                <div id="joins-container">
                    <div v-for="(join, index) in form.joins" :key="'join-' + index"  class="join-condition">
                        <input type="text" v-model="join.table" placeholder="Join Table" />
                        <select v-model="join.type">
                            <option value="inner">Inner Join</option>
                            <option value="left">Left Join</option>
                            <option value="right">Right Join</option>
                            <option value="cross">Cross Join</option>
                        </select>
                        <input type="text" v-model="join.first_column" placeholder="First Column" />
                        <select v-model="join.operator">
                            <option value="=">=</option>
                            <option value=">">></option>
                            <option value="<">
                                <</option>
                            <option value=">=">>=</option>
                            <option value="<=">
                                <=</option>
                        </select>
                        <input type="text" v-model="join.second_column" placeholder="Second Column" />
                        <button type="button" @click="removeJoin(index)">Remove</button>
                    </div>
                </div>
                <button type="button" @click="addJoin">Add Join</button>
            </div>

            <div>
                <label for="order_by_column">Order By Column:</label>
                <input v-model="form.order_by_column" id="order_by_column" />
            </div>

            <div>
                <label for="order_by_direction">Order By Direction:</label>
                <select v-model="form.order_by_direction" id="order_by_direction">
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
            </div>

            <div>
                <label for="limit">Limit:</label>
                <input type="number" v-model="form.limit" id="limit" />
            </div>

            <button type="submit">Create List</button>
        </form>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

export default {
    props: {
        tables: Array,
    },
    setup(props) {
        const form = useForm({
            view_name: '',
            table_name: '',
            columns: [],
            where_conditions: [{ column: '', operator: '==', value: '' }],
            order_by_column: '',
            order_by_direction: 'asc',
            limit: null,
            joins: [],
        });

        const availableColumns = ref([]);

        watch(() => form.table_name, (newTableName) => {
            // Fetch columns for the selected table.
            if (newTableName) {
                // In a real application, you'd fetch this from your backend.
                // For this example, we'll just use a hardcoded list.
                const tableColumns = {
                    users: ['id', 'name', 'email', 'created_at'],
                    products: ['id', 'name', 'price', 'category_id'],
                    orders: ['id', 'order_date', 'customer_id', 'total_amount'],
                };
                availableColumns.value = tableColumns[newTableName] || [];
            } else {
                availableColumns.value = [];
            }
        });

        const addWhereCondition = () => {
            form.where_conditions.push({ column: '', operator: '==', value: '' });
        };

        const removeWhereCondition = (index) => {
            form.where_conditions.splice(index, 1);
        };

        const addJoin = () => {
            form.joins.push({ table: '', type: 'inner', first_column: '', operator: '=', second_column: '' });
        };

        const removeJoin = (index) => {
            form.joins.splice(index, 1);
        };

        return { form, addWhereCondition, removeWhereCondition, addJoin, removeJoin, availableColumns };
    },
};
</script>
